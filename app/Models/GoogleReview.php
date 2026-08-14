<?php

namespace App\Models;

use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GoogleReview extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, QueryScope;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('avatar')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function avatarValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('avatar') ? $this->getFirstMediaUrl('avatar', 'converted') : 'https://avatar.iran.liara.run/username?username='.urlencode($this->name)
        );
    }

    public function avatarAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->avatar_alt_text ? $this->avatar_alt_text : $this->name
        );
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            cache()->forget('googleReviews');
        });
        static::deleted(function ($model) {
            cache()->forget('googleReviews');
        });
    }
}
