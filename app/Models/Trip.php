<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'name',
        'type',
        'adult_price',
        'child_price',
    ];

    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class);
    }

}
