<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BulkOrderCms extends Model implements HasMedia
{
    use AdminTrackable, InteractsWithMedia, MediaUpload;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('section_five_image')->singleFile();
        $this->addMediaCollection('banner')->singleFile();
        $this->addMediaCollection('banner_mobile')->singleFile();
        $this->addMediaCollection('expert_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('section_five_image', 'banner', 'banner_mobile', 'expert_image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function sectionFiveImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('section_five_image') ? $this->getFirstMediaUrl('section_five_image', 'converted') : null
        );
    }

    public function sectionFiveImageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->section_five_image_alt_text ?? $this->section_five_title
        );
    }

    public function bannerValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner') ? $this->getFirstMediaUrl('banner', 'converted') : null
        );
    }

    public function bannerMobileValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner_mobile') ? $this->getFirstMediaUrl('banner_mobile', 'converted') : null
        );
    }

    public function bannerAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->banner_alt_text ?? $this->banner_title
        );
    }

    public function expertImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('expert_image') ? $this->getFirstMediaUrl('expert_image', 'converted') : null
        );
    }

    public function getFormattedPhoneNumberAttribute()
    {
        return str_replace([' ', '-', '(', ')'], '', $this->want_to_talk_number);
    }
}
