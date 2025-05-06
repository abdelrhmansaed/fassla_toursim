<?php

namespace App\Traits;

use App\Models\Transaction;

trait HasSequence
{
    public static function bootHasSequence()
    {
        static::creating(function ($model) {
            $lastSequence = Transaction::max('sequence') ?? 0;
            $model->sequence = $lastSequence + 1;
        });
    }
}

