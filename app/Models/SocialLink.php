<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SocialLink extends Model implements HasMedia
{
    use AdminTrackable, InteractsWithMedia, MediaUpload, QueryScope, SoftDeletes;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')->singleFile();
    }

    public function iconValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('icon') ? $this->getFirstMediaUrl('icon') : null
        );
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            cache()->forget('socialLinks');
        });
        static::deleted(function ($model) {
            cache()->forget('socialLinks');

        });
    }
}
