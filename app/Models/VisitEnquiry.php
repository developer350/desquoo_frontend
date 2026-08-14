<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class VisitEnquiry extends Model
{
    protected $guarded = ['id'];

    public function formattedPhoneNumber(): Attribute
    {
        return Attribute::get(
            fn () => str_replace([' ', '-', '(', ')'], '', $this->phone_number)
        );
    }

    public function dateFormatted(): Attribute
    {
        return Attribute::get(
            fn () => $this->created_at->format('d M Y')
        );
    }
}
