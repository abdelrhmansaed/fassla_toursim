<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripRequestDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_request_id',
        'trip_type_id',
        'sub_trip_type_id',
        'total_people',
        'adult_count',
        'children_count',
        'adult_price',
        'children_price',
        'total_price',
        'total_price_egp',
        'total_price_usd',
        'total_price_eur',
        'booking_datetime',
        'image',
        'status',
        'provider_id',
        'rejection_reason',
        'converted_total_price_egp',
        'currency',
        'discount',
        'commission_value_egp',
        'commission_value_usd',
        'commission_value_eur',

    ];

    public function tripRequest()
    {
        return $this->belongsTo(TripRequest::class, 'trip_request_id');
    }
    public function tripRequestDetail()
    {
        return $this->belongsTo(TripRequestDetail::class, 'trip_request_detail_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'trip_request_detail_id');
    }
    // العلاقة مع النوع الرئيسي

    public function tripType()
    {
        return $this->belongsTo(TripType::class);
    }
    // العلاقة مع النوع الفرعي
    public function subTripType()
    {
        return $this->belongsTo(SubTripType::class);
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function updateCommission($tripRequestDetail)
    {
        if ($tripRequestDetail->status === 'waiting_payment') {
            $agent = $tripRequestDetail->tripRequest->agent;

            if ($agent && $agent->commission_percent) {
                // العمولة لكل عملة
                $commissionEgp = 0;
                $commissionUsd = 0;
                $commissionEur = 0;

                // حساب العمولة لكل عملة بناءً على سعر البيع
                if ($tripRequestDetail->total_price_egp > 0) {
                    $commissionEgp = ($tripRequestDetail->total_price_egp * $agent->commission_percent) / 100;
                }

                if ($tripRequestDetail->total_price_usd > 0) {
                    $commissionUsd = ($tripRequestDetail->total_price_usd * $agent->commission_percent) / 100;
                }

                if ($tripRequestDetail->total_price_eur > 0) {
                    $commissionEur = ($tripRequestDetail->total_price_eur * $agent->commission_percent) / 100;
                }

                // يمكنك تخزين العمولة بالعملة الأصلية أو تحويلها جميعًا إلى EGP
                // هنا مثال لتخزينها بالعملات الأصلية
                $tripRequestDetail->update([
                    'commission_value_egp' => $commissionEgp,
                    'commission_value_usd' => $commissionUsd,
                    'commission_value_eur' => $commissionEur,
                ]);
            }
        }
    }
}
