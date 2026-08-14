<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function formattedPhoneNumber(): Attribute
    {
        return Attribute::get(
            fn() => str_replace([' ', '-', '(', ')'], '', $this->phone_number)
        );
    }
}
