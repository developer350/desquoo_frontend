<?php

namespace App\Models;

use App\Models\Attribute as ModelsAttribute;
use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Admin\Casts\DelimitedCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements HasMedia
{
    use AdminTrackable, HasSlug, InteractsWithMedia, MediaUpload, QueryScope, SoftCascadeTrait, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => DelimitedCast::class,
    ];

    protected $softCascade = ['variants', 'singleVariant', 'attributeValues', 'gallery'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->whereNotNull('variant_name');
    }

    public function singleVariant(): HasOne
    {
        return $this->hasOne(ProductVariant::class)->whereNull('variant_name');
    }

    public function firstVariant(): HasOne
    {
        return $this->hasOne(ProductVariant::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(ModelsAttribute::class, 'product_variant_attributes', 'product_id', 'attribute_id')->distinct();
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductVariantAttribute::class, 'product_id', 'id');
    }

    public function attributeValuesByAttribute(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attributes', 'product_id', 'attribute_value_id')->distinct();
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(ProductGallery::class, 'product_id', 'id')->whereNull('product_variant_id');
    }

    public function typeBadge(): Attribute
    {
        return Attribute::make(
            fn() => blank($this->type) ? null : (function ($type) {
                $class = match ($type) {
                    'single' => 'bg-primary',
                    'variable' => 'bg-secondary',
                    default => 'bg-dark',
                };

                return '<span class="badge ' . $class . '">' . Str::of($type)->headline() . '</span>';
            })($this->type)
        );
    }

    protected function relatedProducts(): Attribute
    {
        return Attribute::make(
            fn($value) => $value !== null ? explode(',', $value) : null,
            fn($value) => is_array($value) ? implode(',', $value) : $value,
        );
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function descImageValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('desc_image') ? $this->getFirstMediaUrl('desc_image', 'converted') : null
        );
    }

    public function qrValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('qr') ? $this->getFirstMediaUrl('qr', 'converted') : null
        );
    }

    public function threeDValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->hasMedia('3d') ? $this->getFirstMediaUrl('3d') : null
        );
    }

    public function imageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn() => $this->image_alt_text ?? $this->name
        );
    }

    public static function computeEffectivePrice(?ProductVariant $v): ?float
    {
        if (! $v) {
            return null;
        }

        // Priority: offer_price > discount_amount > discount_percentage > price
        if (! is_null($v->offer_price)) {
            return (float) $v->offer_price;
        }

        if (! is_null($v->discount_amount)) {
            return (float) max(0, $v->price - $v->discount_amount);
        }

        if (! is_null($v->discount_percentage)) {
            return (float) max(0, $v->price * (1 - ($v->discount_percentage / 100)));
        }

        return (float) $v->price;
    }

    public function priceDisplayValue(): Attribute
    {
        return Attribute::get(function () {
            $fmt = fn($n) => app('appSettings')->get('currency.symbol')->value . number_format($n, 2);

            if ($this->type === 'variable') {
                $prices = $this->variants()
                    ->active()
                    ->get()
                    ->map(fn($v) => self::computeEffectivePrice($v))
                    ->filter(fn($p) => ! is_null($p));

                if ($prices->isEmpty()) {
                    return '—';
                }

                $min = $prices->min();
                $max = $prices->max();

                return ($min === $max) ? $fmt($min) : $fmt($min) . ' - ' . $fmt($max);
            }

            // single
            $p = self::computeEffectivePrice($this->singleVariant);

            return is_null($p) ? '—' : $fmt($p);
        });
    }

    public function productDefaultAttributeValues()
    {
        return $this->hasManyThrough(AttributeValue::class, ProductVariantAttribute::class, 'product_id', 'id', 'id', 'attribute_value_id')
            ->whereIn('attribute_values.attribute_id', function ($query) {
                $query->select('id')
                    ->from('attributes')
                    ->where('default_listing_attribute', 1);
            })->with('media')->distinct();
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_addons',
            'product_id',
            'addon_product_id'
        );
    }

    // Inverse relationship - products where this product is used as an addon
    public function addonOf(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_addons', // same pivot table
            'addon_product_id',      // reversed
            'product_id'     // reversed
        )->withTimestamps();
    }

    public function bulkOrders(): HasMany
    {
        return $this->hasMany(ProductBulkOrder::class);
    }

    public function productFeatures()
    {
        return $this->belongsToMany(Feature::class, 'product_features', 'product_id', 'feature_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getStarRatingPercentagesAttribute()
    {
        $breakdown = $this->reviews()
            ->where('status', 1)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $total = $breakdown->sum();

        if ($total == 0) {
            return [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        }

        $percentages = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $breakdown->get($i, 0);
            $percentages[$i] = round(($count / $total) * 100, 1);
        }

        return $percentages;
    }

    public function productAttributeValueMedia()
    {
        return $this->hasOne(ProductAttributeValueMedia::class, 'product_id', 'id');
    }

    public function productCustomLandings()
    {
        return $this->hasMany(ProductCustomLanding::class);
    }

    public function highlightReviews()
    {
        return $this->hasMany(ProductReview::class)->where('highlight', 1);
    }

    public function scopeActiveCategory($query)
    {
        return $query->whereHas('category', fn ($q) => $q->active());
    }
}
