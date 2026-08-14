<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\MediaUpload;
use App\Traits\QueryScope;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Casts\DelimitedCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model implements HasMedia
{
    use AdminTrackable, HasRecursiveRelationships, HasSlug, InteractsWithMedia, MediaUpload, QueryScope, SoftCascadeTrait, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => DelimitedCast::class,
    ];

    protected $softCascade = ['children', 'products'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('home_image')->singleFile();
        $this->addMediaCollection('banner')->singleFile();
        $this->addMediaCollection('banner_mobile')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($this->canConvertToWebp($media)) {
            $this->addMediaConversion('converted')
                ->format('webp')
                ->performOnCollections('image', 'banner', 'banner_mobile', 'home_image')
                ->nonOptimized()
                ->nonQueued();
        }
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function imageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('image') ? $this->getFirstMediaUrl('image', 'converted') : null
        );
    }

    public function homeImageValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('home_image') ? $this->getFirstMediaUrl('home_image', 'converted') : null
        );
    }

    public function imageAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->image_alt_text ?? $this->name
        );
    }

    public function bannerValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner') ? $this->getFirstMediaUrl('banner', 'converted') : null
        );
    }

    public function bannerMobileValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->hasMedia('banner_mobile') ? $this->getFirstMediaUrl('banner_mobile', 'converted') : null
        );
    }

    public function bannerAltTextValue(): Attribute
    {
        return Attribute::get(
            fn () => $this->banner_alt_text ?? $this->banner_title
        );
    }

    public function descendantCategories()
    {
        return $this->belongsToManyOfDescendants(Category::class);
    }

    public function wouldCreateCycle($newParentId)
    {
        if (! $newParentId) {
            return false;
        }

        // If this is the same as the category itself
        if ($this->id == $newParentId) {
            return true;
        }

        $current = Category::find($newParentId);
        $depth = 0;
        $maxDepth = 2;

        while ($current) {
            if ($current->id == $this->id) {
                return true; // Cycle detected
            }

            $current = $current->parent; // Move up the hierarchy
            $depth++;

            if ($depth > $maxDepth) {
                return true; // Prevent exceeding max depth
            }
        }

        return false;
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            cache()->forget('parentCategories');
            cache()->forget('homeCategories');
            cache()->forget('categories');
        });

        static::deleted(function ($model) {
            cache()->forget('parentCategories');
            cache()->forget('homeCategories');
            cache()->forget('categories');
        });
    }
}
