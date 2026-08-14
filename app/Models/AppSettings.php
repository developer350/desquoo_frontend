<?php

namespace App\Models;

use App\Traits\AdminTrackable;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use AdminTrackable;

    protected $fillable = ['value'];

    public static function getValue(string $key, $default = null)
    {
        $record = self::where('key', $key)->first();

        return match ($record?->type ?? 'string') {
            null     => $default,
            'boolean' => filter_var($record->value, FILTER_VALIDATE_BOOLEAN),
            'decimal' => is_numeric($record->value) ? (float) $record->value : $default,
            'integer' => is_numeric($record->value) ? (int) $record->value : $default,
            'json'    => json_decode($record->value, true) ?? $default,
            default   => $record->value,
        };
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            cache()->forget('appSettings');
        });
    }
}
