<?php

namespace App\Models;

use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Casts\DelimitedCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductCustomLanding extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, QueryScope, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => DelimitedCast::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')->singleFile();
        $this->addMediaCollection('banner_mob_image')->singleFile();
        $this->addMediaCollection('banner_video')->singleFile();
        $this->addMediaCollection('video_thumbnail_image')->singleFile();
        $this->addMediaCollection('video_mobile')->singleFile();
        $this->addMediaCollection('video_thumbnail_image_mobile')->singleFile();
        $this->addMediaCollection('overview_image')->singleFile();
        $this->addMediaCollection('sitting_desk_image')->singleFile();
        $this->addMediaCollection('standing_desk_image')->singleFile();
        $this->addMediaCollection('assembly_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('banner_image', 'banner_mob_image', 'video_thumbnail_image', 'video_thumbnail_image_mobile', 'overview_image', 'sitting_desk_image', 'standing_desk_image', 'assembly_image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function bannerImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner_image') ? $this->getFirstMediaUrl('banner_image', 'converted') : null
        );
    }

    public function bannerMobImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner_mob_image') ? $this->getFirstMediaUrl('banner_mob_image', 'converted') : null
        );
    }

    public function bannerVideoValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner_video') ? $this->getFirstMediaUrl('banner_video') : null
        );
    }

    public function videoThumbnailImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('video_thumbnail_image') ? $this->getFirstMediaUrl('video_thumbnail_image', 'converted') : null
        );
    }

    public function videoThumbnailImageMobileValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('video_thumbnail_image_mobile') ? $this->getFirstMediaUrl('video_thumbnail_image_mobile', 'converted') : null
        );
    }

    public function videoMobileValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('video_mobile') ? $this->getFirstMediaUrl('video_mobile') : null
        );
    }

    public function overviewImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('overview_image') ? $this->getFirstMediaUrl('overview_image', 'converted') : null
        );
    }

    public function sittingDeskImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('sitting_desk_image') ? $this->getFirstMediaUrl('sitting_desk_image', 'converted') : null
        );
    }

    public function standingDeskImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('standing_desk_image') ? $this->getFirstMediaUrl('standing_desk_image', 'converted') : null
        );
    }

    public function assemblyImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('assembly_image') ? $this->getFirstMediaUrl('assembly_image', 'converted') : null
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function boot()
    {
        parent::boot();
        self::saved(function (self $productCustomLanding) {
            cache()->forget('smartProducts');
        });
        self::deleted(function (self $productCustomLanding) {
            cache()->forget('smartProducts');
        });
    }
}
