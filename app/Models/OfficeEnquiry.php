<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeEnquiry extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

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
