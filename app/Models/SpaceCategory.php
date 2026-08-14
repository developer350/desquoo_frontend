<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpaceCategory extends Model implements HasMedia
{
    use AdminTrackable, InteractsWithMedia, MediaUpload, QueryScope, SoftCascadeTrait, SoftDeletes;

    protected $guarded = ['id'];

    protected $softCascade = ['spaces'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
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

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function imageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->image_alt_text ?? $this->title
        );
    }

    public function location(): Attribute
    {
        return Attribute::get(
            fn () => implode(', ', [
                optional($this->city)->name,
                optional($this->state)->name,
            ])
        );
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($category) {
            cache()->forget('spaceCategories');
        });
        static::deleted(function ($category) {
            cache()->forget('spaceCategories');
        });
    }
}
