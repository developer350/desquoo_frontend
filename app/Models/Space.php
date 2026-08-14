<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Space extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("image")->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function spaceCategory(): BelongsTo
    {
        return $this->belongsTo(SpaceCategory::class);
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
}
