<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\TripRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use PDF;

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $code = null;
        $agent = null;
        $transactions = collect();
        $stats = [];
        $runningBalance = 0;
        $currencyStats = []; // إحصائيات العملات

        if ($request->has('agent_code') && preg_match('/^1\/(\d+)$/', $request->agent_code, $matches)) {
            $code = $matches[1];
            $agent = User::where('role', 'agent')->where('code', $code)->first();

            if ($agent) {
                // فلترة حسب العملة إذا تم اختيارها
                $currency = $request->get('currency', 'all');

                $query = Transaction::with(['tripRequestDetail.tripRequest.agent', 'tripRequestDetail.tripRequest.provider', 'tripRequestDetail.tripRequest'])
                    ->where('agent_id', $agent->id)
                    ->whereIn('type', ['invoice','discount','commission', 'payment']);

                if ($currency !== 'all') {
                    $query->where('currency', $currency);
                }

                $transactions = $query->orderBy('sequence', 'asc')->paginate(20);

                // حساب إحصائيات العملات
                $currencyStats = [
                    'egp' => $this->calculateCurrencyStats($agent->id, 'egp'),
                    'usd' => $this->calculateCurrencyStats($agent->id, 'usd'),
                    'eur' => $this->calculateCurrencyStats($agent->id, 'eur')
                ];

                // باقي الإحصائيات الحالية (كما هي)
                $tripDetails = \App\Models\TripRequestDetail::whereHas('tripRequest', function ($q) use ($agent) {
                    $q->where('agent_id', $agent->id);
                });

                $statusCounts = (clone $tripDetails)
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status');

                $transactionSums = Transaction::where('agent_id', $agent->id)
                    ->selectRaw('SUM(credit) as total_credit, SUM(debit) as total_debit, SUM(total_balance) as total_balance')
                    ->first();

                $stats = [
                    'total_requests' => (clone $tripDetails)->count(),
                    'confirmed' => $statusCounts['confirmed'] ?? 0,
                    'pending' => $statusCounts['pending'] ?? 0,
                    'canceled' => $statusCounts['canceled'] ?? 0,
                    'total_sales' => (clone $tripDetails)->sum('total_price'),
                    'total_credit' => $transactionSums->total_credit ?? 0,
                    'total_debit' => $transactionSums->total_debit ?? 0,
                    'total_balance' => $transactionSums->total_balance ?? 0,
                    'balance' => Transaction::where('agent_id', $agent->id)->latest()->first()?->total_balance ?? 0,
                ];
            }
        }

        return view('Pages.Reports.transactions', compact('transactions', 'agent', 'stats', 'currencyStats'));
    }

    private function calculateCurrencyStats($agentId, $currency)
    {
        // استخراج آخر معاملة حسب الـ sequence
        $lastTransaction = Transaction::where('agent_id', $agentId)
            ->orderByDesc('sequence')
            ->first();

        $current_balance = 0;

        if ($lastTransaction) {
            switch ($currency) {
                case 'usd':
                    $current_balance = $lastTransaction->total_balance_usd ?? 0;
                    break;
                case 'eur':
                    $current_balance = $lastTransaction->total_balance_eur ?? 0;
                    break;
                case 'egp':
                    $current_balance = $lastTransaction->total_balance_egp ?? 0;
                    break;
                default:
                    // كل العملات، تحوّل وتجمع بالجنيه
                    $current_balance = Transaction::where('agent_id', $agentId)->sum('credit_egp')
                        - Transaction::where('agent_id', $agentId)->sum('debit_egp');
            }
        }

        return [
            'total_sales' => \App\Models\TripRequestDetail::whereHas('tripRequest', function($q) use ($agentId) {
                $q->where('agent_id', $agentId);
            })->sum("total_price_{$currency}"),

            'total_payments' => Transaction::where('agent_id', $agentId)
                ->where('currency', $currency)
                ->sum('credit'),

            'current_balance' => $current_balance
        ];
    }

    public function report()
    {
        $transactions = Transaction::with('agent')->orderBy('created_at', 'desc')->get();
        return view('admin.transactions.report', compact('transactions'));
    }
    public function createPayForm(Request $request)
    {
        $currency = $request->get('currency', 'all'); // العملة المحددة أو all
        $agentId = Auth::id();

        // جلب آخر معاملة بناءً على sequence
        $lastTransaction = Transaction::where('agent_id', $agentId)
            ->orderByDesc('sequence')
            ->first();

        if ($lastTransaction) {
            switch ($currency) {
                case 'egp':
                    $totalBalance = $lastTransaction->total_balance_egp;
                    break;
                case 'usd':
                    $totalBalance = $lastTransaction->total_balance_usd;
                    break;
                case 'eur':
                    $totalBalance = $lastTransaction->total_balance_eur;
                    break;
                default:
                    $totalBalance = $lastTransaction->credit_egp - $lastTransaction->debit_egp;
            }
        } else {
            $totalBalance = 0;
        }

        return view('Pages.payments.pay', compact('totalBalance', 'currency'));
    }
    public function pay(Request $request, TripRequest $tripRequest)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:egp,usd,eur',
            'payment_date' => 'required|date',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        $agentId = Auth::user()->id;
        $currency = $request->currency;
        $paymentAmount = $request->amount;

        // جلب آخر معاملة للمندوب
        $lastTransaction = Transaction::where('agent_id', $agentId)
            ->orderByDesc('sequence')
            ->first();

        $lastBalanceEgp = $lastTransaction->total_balance_egp ?? 0;
        $lastBalanceUsd = $lastTransaction->total_balance_usd ?? 0;
        $lastBalanceEur = $lastTransaction->total_balance_eur ?? 0;

        $lastSequence = $lastTransaction->sequence ?? 0;

        // إعداد الرصيد الجديد حسب العملة
        $newBalanceEgp = $lastBalanceEgp;
        $newBalanceUsd = $lastBalanceUsd;
        $newBalanceEur = $lastBalanceEur;

        $creditEgp = null;
        $creditUsd = null;
        $creditEur = null;

        if ($currency === 'egp') {
            $newBalanceEgp -= $paymentAmount;
            $creditEgp = $paymentAmount;
        } elseif ($currency === 'usd') {
            $newBalanceUsd -= $paymentAmount;
            $creditUsd = $paymentAmount;
        } elseif ($currency === 'eur') {
            $newBalanceEur -= $paymentAmount;
            $creditEur = $paymentAmount;
        }

        // رفع صورة الإثبات إن وجدت
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // إنشاء المعاملة
        Transaction::create([
            'agent_id' => $agentId,
            'credit' => $paymentAmount,
            'debit' => 0,
            'total_balance' => 0, // لم تعد تُستخدم كمؤشر رئيسي، يمكن حذفها لاحقاً

            'credit_egp' => $creditEgp,
            'debit_egp' => 0,
            'total_balance_egp' => $newBalanceEgp,

            'credit_usd' => $creditUsd,
            'debit_usd' => 0,
            'total_balance_usd' => $newBalanceUsd,

            'credit_eur' => $creditEur,
            'debit_eur' => 0,
            'total_balance_eur' => $newBalanceEur,

            'sequence' => Transaction::getNextSequence(),
            'payment_date' => $request->payment_date,
            'note' => $request->payment_notes,
            'currency' => $currency,
            'image' => $proofPath,
            'type' => 'payment',
        ]);

        return redirect()->route('transactions.pay.form', $tripRequest->id)->with('success', 'تم الدفع بنجاح.');
    }

}
