<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GotAQuestion extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function formattedPhoneNumber(): Attribute
    {
        return Attribute::get(
            fn() => str_replace([' ', '-', '(', ')'], '', $this->phone_number)
        );
    }

    public function dateFormatted(): Attribute
    {
        return Attribute::get(
            fn() => Carbon::parse($this->created_at)->format('d M Y')
        );
    }
}
