<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Casts\DelimitedCast;

class Pincode extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'pincodes' => DelimitedCast::class,
    ];
}
