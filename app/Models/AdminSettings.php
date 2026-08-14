<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

class AdminSettings extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload, AdminTrackable;

    protected $fillable = ['value'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('value')->singleFile();
    }

    public function keyValue(): Attribute
    {
        return Attribute::get(
            fn() => str_replace('-', ' ', Str::title($this->key))
        );
    }

    public function formattedValue(): Attribute
    {
        return Attribute::get(function () {
            if ($this->type == 2) {
                return $this->hasMedia('value')
                    ? $this->getFirstMediaUrl('value')
                    : ($this->value && file_exists($this->value) ? asset($this->value) : null);
            }

            return $this->type == 1 ? $this->value : null;
        });
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            cache()->forget('adminSettings');
        });
    }
}
