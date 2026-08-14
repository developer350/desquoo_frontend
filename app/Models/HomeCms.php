<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomeCms extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, AdminTrackable;

    protected $guarded = ['id'];

    protected $hidden  = ['media'];

    protected $appends = [
        'section_one_image_value',
        'section_one_image_alt_text_value',
        'section_six_image_value',
        'section_six_image_alt_text_value',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('home.cms');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("section_one_image")->singleFile();
        $this->addMediaCollection("section_six_image")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('section_one_image', 'section_six_image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function sectionOneImageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('section_one_image') ? $this->getFirstMediaUrl('section_one_image', 'converted') : null
        );
    }

    public function sectionOneImageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->section_one_image_alt_text ?? $this->section_one_title
        );
    }

    public function sectionSixImageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('section_six_image') ? $this->getFirstMediaUrl('section_six_image', 'converted') : null
        );
    }

    public function sectionSixImageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->section_six_image_alt_text ?? $this->section_six_title
        );
    }
}
