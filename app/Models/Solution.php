<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solution extends Model
{
    use SoftDeletes, QueryScope, AdminTrackable;

    protected $guarded = ['id'];
}
