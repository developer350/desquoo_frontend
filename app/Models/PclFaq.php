<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PclFaq extends Model
{
    use QueryScope;

    protected $guarded = ['id'];

    public function productCustomLanding(): BelongsTo
    {
        return $this->belongsTo(ProductCustomLanding::class, 'product_custom_landing_id');
    }
}
