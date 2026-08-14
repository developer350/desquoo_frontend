<?php

namespace App\Models;

use App\Helpers\BackendHelpers;
use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteSettings extends Model implements HasMedia
{
    use AdminTrackable, InteractsWithMedia, MediaUpload;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('header_logo')->singleFile();
        $this->addMediaCollection('header_mobile_logo')->singleFile();
        $this->addMediaCollection('footer_logo')->singleFile();
        $this->addMediaCollection('auth_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('header_logo', 'footer_logo', 'header_mobile_logo', 'auth_logo')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function headerLogoValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('header_logo') ? $this->getFirstMediaUrl('header_logo', 'converted') : null
        );
    }

    public function headerLogoAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->header_logo_alt_text ?? BackendHelpers::getValueByKey('website-name')
        );
    }

    public function headerMobileLogoValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('header_mobile_logo') ? $this->getFirstMediaUrl('header_mobile_logo', 'converted') : null
        );
    }

    public function headerMobileLogoAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->header_mobile_logo_alt_text ?? BackendHelpers::getValueByKey('website-name')
        );
    }

    public function footerLogoValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('footer_logo') ? $this->getFirstMediaUrl('footer_logo', 'converted') : null
        );
    }

    public function footerLogoAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->footer_logo_alt_text ?? BackendHelpers::getValueByKey('website-name')
        );
    }

    public function authImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('auth_image') ? $this->getFirstMediaUrl('auth_image', 'converted') : null
        );
    }

    public function authImageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->auth_image_alt_text ?? BackendHelpers::getValueByKey('website-name')
        );
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            cache()->forget('siteSettings');
        });
    }

    public function getFormattedPhoneNumberAttribute()
    {
        return str_replace([' ', '-', '(', ')'], '', $this->phone_number);
    }

    public function getFormattedWhatsappNumberAttribute()
    {
        return str_replace([' ', '-', '(', ')'], '', $this->whatsapp_number);
    }
}
