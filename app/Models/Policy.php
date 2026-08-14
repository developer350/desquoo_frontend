<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Casts\DelimitedCast;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Policy extends Model
{
    use SoftDeletes, HasSlug, AdminTrackable;

    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => DelimitedCast::class,
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('page')
            ->saveSlugsTo('slug');
    }
}
