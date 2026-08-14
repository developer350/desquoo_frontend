<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function statusBg(): Attribute
    {
        return Attribute::get(
            fn () => $this->getStatusColor($this->status)
        );
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'warning',
            'confirmed' => 'primary',
            'processing' => 'secondary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'warning',
        };
    }

    public function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->created_at)->format('d M, Y'),
        );
    }

    public function createdAtTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->created_at)->format('h:i A'),
        );
    }

    public function statusIcon(): Attribute
    {
        return Attribute::get(
            fn () => $this->getStatusIcon($this->status)
        );
    }

    public function getStatusIcon($status)
    {
        return match ($status) {
            'pending' => 'clock',
            'confirmed' => 'check',
            'processing' => 'sync',
            'shipped' => 'truck',
            'delivered' => 'truck',
            'cancelled' => 'times',
            default => 'clock',
        };
    }
}
