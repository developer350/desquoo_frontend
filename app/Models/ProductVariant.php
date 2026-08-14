<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductVariant extends Model implements HasMedia
{
    use AdminTrackable, InteractsWithMedia, MediaUpload, QueryScope, SoftCascadeTrait, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['last_price'];

    protected $softCascade = ['variantAttributes', 'gallery'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('desc_image')->singleFile();
        $this->addMediaCollection('3d')->singleFile();
        $this->addMediaCollection('qr')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image', 'desc_image', 'qr')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attributes',
            'product_variant_id',
            'attribute_value_id'
        )
            ->withPivot(['product_id', 'attribute_id'])
            ->withTimestamps()
            ->withTrashed();
    }

    public function variantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function gallery()
    {
        return $this->hasMany(ProductGallery::class, 'product_variant_id');
    }

    public function combinations(): Attribute
    {
        return Attribute::get(
            fn () => $this->variantAttributes
                ->map(fn ($attr) => '<strong>'.ucfirst($attr->attribute->name).':</strong> '.$attr->attributeValue->value)
                ->implode(', ') ?? '—'
        );
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function descImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('desc_image') ? $this->getFirstMediaUrl('desc_image', 'converted') : null
        );
    }

    public function qrValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('qr') ? $this->getFirstMediaUrl('qr', 'converted') : null
        );
    }

    public function threeDValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('3d') ? $this->getFirstMediaUrl('3d') : null
        );
    }

    public function priceFormatted(): Attribute
    {
        return Attribute::get(function () {
            $currency = app('appSettings')->get('currency.symbol')->value;

            return $currency.number_format($this->price, 2);
        });
    }

    public function offerPriceFormatted(): Attribute
    {
        return Attribute::get(function () {
            $currency = app('appSettings')->get('currency.symbol')->value;

            return ! is_null($this->offer_price)
                ? $currency.number_format($this->offer_price, 2)
                : '—';
        });
    }

    public function lastPrice(): Attribute
    {
        return Attribute::get(function () {
            if (is_null($this->offer_price)) {
                return $this->price;
            }

            return $this->offer_price;
        });
    }
}
