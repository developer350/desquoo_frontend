<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class)->withTrashed();
    }

    public function dateFormatted(): Attribute
    {
        return Attribute::get(
            fn() => Carbon::parse($this->created_at)->format('d M Y')
        );
    }

    public function comment(): Attribute
    {
        return Attribute::set(fn($value) => ['comment' => strip_tags($value)]);
    }
}
