<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Client extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    protected $hidden  = ['media'];

    protected $appends = [
        'logo_value',
        'logo_alt_text_value'
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('clients');
        });

        static::deleted(function () {
            Cache::forget('clients');
        });
    }

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
            fn() => $this->logo_alt_text ?? app('appSettings')->get('app.name')->value
        );
    }
}
