<?php

namespace App\Models;

use App\Models\Attribute as ModelsAttribute;
use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttributeValue extends Model implements HasMedia
{
    use SoftDeletes, AdminTrackable, InteractsWithMedia,MediaUpload;

    protected $guarded = ['id'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ModelsAttribute::class)->withTrashed();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')->singleFile();
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
}
