<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_code',
        'adult_limit',
        'child_limit',
    ];

    /**
     * العلاقة مع المندوبين (agents)
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'agent_file_number');
    }

    /**
     * لو عندك علاقة مع trip_requests
     */
    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class);
    }
}
