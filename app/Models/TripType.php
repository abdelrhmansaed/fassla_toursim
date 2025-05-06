<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripType extends Model
{
    protected $fillable = ['type', 'adult_price', 'child_price', 'user_id'];

    // علاقة الأنواع الفرعية
    public function Provider_Trip()
    {
        return $this->belongsTo(User::class); // مزود الخدمة
    }
    public function subTripTypes()
    {
        return $this->hasMany(SubTripType::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // العلاقة مع الطلبات
    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class, 'trip_type_id');
    }
}
