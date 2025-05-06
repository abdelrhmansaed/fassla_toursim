<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubTripType extends Model
{
    protected $fillable = ['type', 'adult_price', 'child_price', 'trip_type_id'];

    // علاقة النوع الرئيسي
    public function tripType()
    {
        return $this->belongsTo(TripType::class);
    }

}
