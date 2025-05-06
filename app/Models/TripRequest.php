<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripRequest extends Model
{
    protected $fillable = [
        'agent_id',
        'booking_number',
        'receipt_number',
        'total_price',
        'total_price_egp',
        'total_price_usd',
        'total_price_eur',
        'hotel_name',
        'payment_status',
        ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function details()
    {
        return $this->hasMany(TripRequestDetail::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function fileNumber()
    {
        return $this->belongsTo(FileNumber::class, 'booking_number', 'file_code');
    }

}
