<?php

namespace App\Models;

use App\Traits\HasSequence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasSequence;

    use HasFactory;

    protected $fillable = ['agent_id','trip_request_detail_id', 'credit', 'debit', 'total_balance'
        ,  'sequence',
            'note' ,
            'payment_date' ,
            'image' ,
            'currency',
        'type',
        'credit_egp','debit_egp','total_balance_egp',
        'credit_usd','debit_usd','total_balance_usd',
        'credit_eur','debit_eur','total_balance_eur',
    ]; // تأكد من إضافة fillable
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (empty($transaction->sequence)) {
                $transaction->sequence = static::getNextSequence();
            }
        });
    }

    public static function getNextSequence()
    {
        return (static::max('sequence') ?? 0) + 1;
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function tripRequestDetail()
    {
        return $this->belongsTo(TripRequestDetail::class, 'trip_request_detail_id');
    }

    public static function recalculateAgentBalance($agentId)
    {
        $transactions = Transaction::where('agent_id', $agentId)

            ->orderBy('sequence', 'asc')
            ->get();

        $balances = ['egp' => 0, 'usd' => 0, 'eur' => 0];

        DB::beginTransaction();
        try {
            foreach ($transactions as $transaction) {
                $detail = $transaction->tripRequestDetail;

                foreach (['egp', 'usd', 'eur'] as $currency) {
                    $balances[$currency] += ($transaction->{"debit_$currency"} ?? 0) - ($transaction->{"credit_$currency"} ?? 0);

                    if ($transaction->type == 'discount') {
                        $balances[$currency] -= $detail->{"discount_$currency"} ?? 0;
                    }

                    if ($transaction->type == 'commission') {
                        $balances[$currency] -= $detail->{"commission_value_$currency"} ?? 0;
                    }

                    $transaction->{"total_balance_$currency"} = $balances[$currency];
                }

                $transaction->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
