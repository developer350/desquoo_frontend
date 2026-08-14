<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    protected $hidden  = ['media'];

    protected $appends = [
        'image_value',
        'image_mobile_value',
        'image_alt_text_value',
        'video_thumbnail_image_value',
        'video_thumbnail_image_mobile_value',
        'video_value',
        'video_mobile_value',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('sliders');
        });

        static::deleted(function () {
            Cache::forget('sliders');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("image")->singleFile();
        $this->addMediaCollection("image_mobile")->singleFile();
        $this->addMediaCollection("video_thumbnail_image")->singleFile();
        $this->addMediaCollection("video_thumbnail_image_mobile")->singleFile();
        $this->addMediaCollection("video")->singleFile();
        $this->addMediaCollection("video_mobile")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image', 'image_mobile', 'video_thumbnail_image', 'video_thumbnail_image_mobile')
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

    public function videoThumbnailImageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video_thumbnail_image') ? $this->getFirstMediaUrl('video_thumbnail_image', 'converted') : null
        );
    }

    public function videoThumbnailImageMobileValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video_thumbnail_image_mobile') ? $this->getFirstMediaUrl('video_thumbnail_image_mobile', 'converted') : null
        );
    }

    public function videoValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video') ? $this->getFirstMediaUrl('video') : null
        );
    }

    public function videoMobileValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video_mobile') ? $this->getFirstMediaUrl('video_mobile') : null
        );
    }

    public function actionTitle(): Attribute
    {
        return Attribute::set(
            fn($value) => ['action_title' => $this->action_type === 'none' ? null : $value]
        );
    }

    public function actionUrl(): Attribute
    {
        return Attribute::set(
            fn($value) => ['action_url' => $this->action_type === 'none' ? null : $value]
        );
    }

    public function handleMediaChange($request)
    {
        if ($this->isMediaTypeUpdated($request)) {
            $this->removeOldMediaFiles($this->media_type);
        }
    }

    private function isMediaTypeUpdated($request)
    {
        return $request->input('media_type') !== $this->media_type;
    }

    private function removeOldMediaFiles($mediaType)
    {
        $mediaCollections = match ($mediaType) {
            'image' => ['image', 'image_mobile'],
            'video' => ['video_thumbnail_image', 'video_thumbnail_image_mobile', 'video', 'video_mobile'],
            default => [],
        };

        foreach ($mediaCollections as $collection) {
            if ($this->hasMedia($collection)) {
                $this->clearMediaCollection($collection);
            }
        }
    }

    public function mediaTypeValue(): Attribute
    {
        return Attribute::get(
            fn() => str_replace('_', ' ', Str::title($this->media_type))
        );
    }

    public function actionTypeValue(): Attribute
    {
        return Attribute::get(
            fn() => str_replace('_', ' ', Str::title($this->action_type))
        );
    }
}
