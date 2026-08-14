<?php

namespace App\Models;

use App\Models\Attribute as ModelsAttribute;
use App\Traits\MediaUpload;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductAttributeValueMedia extends Model implements HasMedia
{
    use InteractsWithMedia, MediaUpload;

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class)->withTrashed();
    }

    public function attribute()
    {
        return $this->hasOneThrough(
            ModelsAttribute::class,
            AttributeValue::class,
            'id',
            'id',
            'attribute_value_id',
            'attribute_id'
        )->withTrashed();
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }
}
