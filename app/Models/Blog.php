<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Casts\DelimitedCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    protected $casts = [
        'search_keywords' => DelimitedCast::class,
        'meta_keywords' => DelimitedCast::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("image")->singleFile();
        $this->addMediaCollection("banner")->singleFile();
        $this->addMediaCollection('banner_mobile')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image', 'banner', 'banner_mobile')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function blogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    protected function relatedBlogs(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value !== null ? explode(',', $value) : null,
            set: fn($value) => is_array($value) ? implode(',', $value) : $value,
        );
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function imageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->image_alt_text ?? $this->title
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

    public function publishedOnValue(): Attribute
    {
        return Attribute::get(
            fn() => Carbon::parse($this->published_on)->format('d M Y')
        );
    }
}
