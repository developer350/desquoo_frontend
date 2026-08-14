<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class NotifyMe extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class)->withTrashed();
    }

    public function dateFormatted(): Attribute
    {
        return Attribute::get(
            fn () => Carbon::parse($this->created_at)->format('d M Y')
        );
    }

    public function formattedPhoneNumber(): Attribute
    {
        return Attribute::get(
            fn () => str_replace([' ', '-', '(', ')'], '', $this->phone_number)
        );
    }
}
