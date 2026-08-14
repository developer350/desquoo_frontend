<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Admin\Casts\DelimitedCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BannerAndMetaTag extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, AdminTrackable;

    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => DelimitedCast::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner')->singleFile();
        $this->addMediaCollection('banner_mobile')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('banner', 'banner_mobile')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function pageValue(): Attribute
    {
        return Attribute::get(
            fn() => str_replace('-', ' ', Str::title($this->page))
        );
    }

    public function bannerValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('banner') ? $this->getFirstMediaUrl('banner', 'converted') : null
        );
    }

    public function bannerMobileValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('banner_mobile') ? $this->getFirstMediaUrl('banner_mobile', 'converted') : null
        );
    }

    public function bannerAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->banner_alt_text ?? $this->banner_title
        );
    }
}
