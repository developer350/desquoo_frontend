<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope('indiaStatesOnly', function (Builder $builder) {
            $builder->whereHas('state', function ($q) {
                $q->where('country_id', 101);
            });
        });
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
