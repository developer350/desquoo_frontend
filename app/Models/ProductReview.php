<?php

namespace App\Models;

use App\Traits\MediaUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductReview extends Model implements HasMedia
{
    use InteractsWithMedia,MediaUpload;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('review_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('review_image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function reviewImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('review_image') ? $this->getFirstMediaUrl('review_image', 'converted') : null
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dateFormatted(): Attribute
    {
        return Attribute::get(
            fn() => Carbon::parse($this->created_at)->format('d M Y')
        );
    }
}
