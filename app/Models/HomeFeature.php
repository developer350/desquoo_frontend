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

class HomeFeature extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    protected $hidden  = ['media'];

    protected $appends = [
        'image_value',
        'image_mobile_value',
        'image_alt_text_value'
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('home.features');
        });

        static::deleted(function () {
            Cache::forget('home.features');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("image")->singleFile();
        $this->addMediaCollection("image_mobile")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image', 'image_mobile')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function imageMobileValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('image_mobile') ? $this->getFirstMediaUrl('image_mobile', 'converted') : null
        );
    }

    public function imageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->image_alt_text ?? $this->title
        );
    }
}
