<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Innovator extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("logo")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('logo')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function logoValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('logo') ? $this->getFirstMediaUrl('logo', 'converted') : null
        );
    }

    public function logoAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->logo_alt_text ?? $this->title
        );
    }
}
