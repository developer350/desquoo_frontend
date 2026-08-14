<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductGallery extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("image")->singleFile();
        $this->addMediaCollection("video_thumbnail_image")->singleFile();
        $this->addMediaCollection("video")->singleFile();
        $this->addMediaCollection("video_url_thumbnail_image")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image','video_thumbnail_image', 'video_url_thumbnail_image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
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
            'image' => ['image'],
            'video' => ['video_thumbnail_image', 'video'],
            'video_url' => ['video_url_thumbnail_image'],
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

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function videoThumbnailImageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video_thumbnail_image') ? $this->getFirstMediaUrl('video_thumbnail_image', 'converted') : null
        );
    }

    public function videoUrlThumbnailImageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video_url_thumbnail_image') ? $this->getFirstMediaUrl('video_url_thumbnail_image', 'converted') : null
        );
    }

    public function videoValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('video') ? $this->getFirstMediaUrl('video') : null
        );
    }
}
