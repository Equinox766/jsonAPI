<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait hasUuid
{
    public function getIncrementing()
    {
        return false;
    }

    protected static function bootHasUuid()
    {
        static::creating(function($model){
            $model->id = Str::uuid()->toString();
        });
    }
}
