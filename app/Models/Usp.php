<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Usp extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    protected $hidden  = ['media'];

    protected $appends = [
        'icon_value',
        'icon_alt_text_value'
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('usps');
        });

        static::deleted(function () {
            Cache::forget('usps');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("icon")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('icon')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function iconValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('icon') ? $this->getFirstMediaUrl('icon', 'converted') : null
        );
    }

    public function iconAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->icon_alt_text ?? $this->title
        );
    }
}
