<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\FileNumber;
use App\Models\TripRequest;
use App\Models\TripRequestDetail;
use App\Models\User;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function fileReport(Request $request)
    {
        $fileCode = $request->input('file_code');
        $agentId = $request->input('agent_id');

        $files = FileNumber::all();
        $agents = User::where('role', 'agent')->get();

        $report = [];

        $tripRequestsQuery = TripRequest::with(['details', 'agent', 'fileNumber']);

        // تحديد حالة التصفية
        if ($fileCode && $agentId) {
            // حالة تحديد رقم ملف + مندوب
            $tripRequestsQuery->where('booking_number', $fileCode)
                ->where('agent_id', $agentId);
        } elseif ($fileCode) {
            // حالة تحديد رقم ملف فقط
            $tripRequestsQuery->where('booking_number', $fileCode);
        } elseif ($agentId) {
            // حالة تحديد مندوب فقط
            $tripRequestsQuery->where('agent_id', $agentId);
        }

        $tripRequests = $tripRequestsQuery->get();

        foreach ($tripRequests as $request) {
            $agentKey = $request->agent_id;
            $agentName = $request->agent->name ?? 'غير معروف';

            $fileCode = $request->fileNumber->file_code ?? 'غير محدد';

            $key = $agentKey . '-' . $fileCode;

            if (!isset($report[$key])) {
                $report[$key] = [
                    'agent_id' => $agentKey,
                    'agent_name' => $agentName,
                    'file_code' => $fileCode,
                    'adult_count' => 0,
                    'child_count' => 0,
                    'adult_limit' => 0,
                    'child_limit' => 0,
                ];
            }

            foreach ($request->details as $detail) {
                $report[$key]['adult_count'] += $detail->adult_count;
                $report[$key]['child_count'] += $detail->children_count;
            }

            // جلب حدود العدد من الملف
            if ($request->fileNumber) {
                $report[$key]['adult_limit'] = $request->fileNumber->adult_limit;
                $report[$key]['child_limit'] = $request->fileNumber->child_limit;
            }
        }

        // حساب النسب والتقييم
        foreach ($report as &$data) {
            $adultLimit = $data['adult_limit'] ?: 1;
            $childLimit = $data['child_limit'] ?: 1;

            $data['adult_percent'] = round(($data['adult_count'] / $adultLimit) * 100, 1);
            $data['child_percent'] = round(($data['child_count'] / $childLimit) * 100, 1);

            $avg = ($data['adult_percent'] + $data['child_percent']) / 2;

            $data['rating'] = match (true) {
                $avg >= 90 => 'ممتاز',
                $avg >= 75 => 'جيد جدًا',
                $avg >= 50 => 'جيد',
                default => 'ضعيف',
            };
        }

        return view('Pages.Reports.reports_file', compact('report', 'files', 'agents', 'fileCode', 'agentId'));
    }

    public function RepotsFinance(Request $request)
    {
        // فلترة المندوبين
        $agents = User::where('role', 'agent')->orderBy('name')->get();
        $files = FileNumber::orderBy('file_code')->get();

        // بناء الاستعلام الأساسي
        $query = TripRequestDetail::with([
            'tripRequest.agent',
            'provider',
            'tripType',
            'subTripType'
        ])
            ->where('status', 'confirmed');

        // تطبيق الفلاتر
        $this->applyFilters($query, $request);

        // جلب البيانات مع الحسابات
        $tripDetails = $query->get();
        $reportData = $tripDetails->map(function ($detail) {
            return $this->prepareReportRow($detail);
        });

        // إضافة الإجماليات
        $totals = $this->calculateTotals($reportData);

        return view('Pages.Reports.reports_finance', compact('reportData', 'agents', 'files', 'totals'));
    }

    private function applyFilters($query, $request)
    {
        // فلتر المندوب
        if ($request->filled('agent_id')) {
            $query->whereHas('tripRequest', fn($q) => $q->where('agent_id', $request->agent_id));
        }

        // فلتر التاريخ
        if ($request->filled('from_date')) {
            $query->where('booking_datetime', '>=', $request->from_date . ' 00:00:00');
        }
        if ($request->filled('to_date')) {
            $query->where('booking_datetime', '<=', $request->to_date . ' 23:59:59');
        }

        // فلتر رقم الملف
        if ($request->filled('file_code')) {
            $query->whereHas('tripRequest', fn($q) => $q->where('booking_number', $request->file_code));
        }
    }

    private function prepareReportRow($detail)
    {
        $requestData = $detail->tripRequest;
        $currency = $requestData->currency;
        $discount = $requestData->discount ?? 0;

        // حساب الخصم بالجنيه
        $discountInEGP = match ($currency) {
            'usd' => CurrencyConverter::convertToEGP($discount, 'USD'),
            'eur' => CurrencyConverter::convertToEGP($discount, 'EUR'),
            default => $discount,
        };

        // حساب الربح
        $profit = $detail->converted_total_price_egp
            - ($detail->total_price + $detail->commission_value + $discountInEGP);

        return [
            'agent_name' => $requestData->agent->name,
            'sale_price_egp' => $detail->total_price_egp,
            'sale_price_usd' => $detail->total_price_usd,
            'sale_price_eur' => $detail->total_price_eur,
            'converted_total_price_egp' => $detail->converted_total_price_egp,
            'cost_price' => $detail->total_price,
            'commission' => $detail->commission_value,
            'discount' => $discount,
            'currency' => $currency,
            'discountInEGP' => $discountInEGP,
            'provider' => $detail->provider?->name ?? 'غير متاح',
            'trip_type' => $detail->tripType?->type ?? 'غير متاح',
            'sub_trip_type' => $detail->subTripType?->type,
            'profit' => round($profit, 2),
            'booking_date' => \Carbon\Carbon::parse($detail->booking_datetime)->format('Y-m-d'),
        ];
    }

    private function calculateTotals($reportData)
    {
        return [
            'total_sale_egp' => $reportData->sum('sale_price_egp'),
            'total_sale_usd' => $reportData->sum('sale_price_usd'),
            'total_sale_eur' => $reportData->sum('sale_price_eur'),
            'total_converted' => $reportData->sum('converted_total_price_egp'),
            'total_cost' => $reportData->sum('cost_price'),
            'total_commission' => $reportData->sum('commission'),
            'total_discount_egp' => $reportData->sum('discountInEGP'),
            'total_profit' => $reportData->sum('profit'),
        ];
    }
}
