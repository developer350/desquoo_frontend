<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\QueryScope;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Attribute extends Model
{
    use SoftDeletes, SoftCascadeTrait, HasSlug, QueryScope, AdminTrackable;

    protected $guarded = ['id'];

    protected $softCascade = ['values'];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected static function booted()
    {
        static::created(function (self $attribute) {
            $values = request()->input('values');

            if (!empty($values[0])) {
                $valuesArray = collect(explode(',', $values[0]))
                    ->map(fn($value) => ['value' => trim($value)])
                    ->toArray();

                $attribute->values()->createMany($valuesArray);
            }
        });

        static::saved(function (self $attribute) {
            cache()->forget('defaultAttribute');
        });

        static::deleted(function (self $attribute) {
            cache()->forget('defaultAttribute');
        });
    }
}
