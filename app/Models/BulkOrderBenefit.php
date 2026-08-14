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

class BulkOrderBenefit extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("icon")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('icon')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function iconValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('icon') ? $this->getFirstMediaUrl('icon', 'converted') : null
        );
    }

    public function iconAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->icon_alt_text ?? $this->title
        );
    }
}
