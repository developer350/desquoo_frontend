<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderTracks()
    {
        return $this->hasMany(OrderTrack::class);
    }

    public function orderDateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->date)->format('d M Y'),
        );
    }

    public function checkStatusIsAfter($status)
    {
        return $this->statusUpdated($status);
    }

    public function statusUpdated($status)
    {
        $current = $this->status;

        $statuses = [
            'pending',
            'confirmed',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
        ];
        return array_search($current, $statuses) >= array_search($status, $statuses);
    }

    public function paymentMethodTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getPaymentMethodTitle(),
        );
    }

    private function getPaymentMethodTitle()
    {
        $methods = [
            'cod' => 'Cash on Delivery',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
        ];

        return $methods[$this->payment_method] ?? 'Unknown';
    }

    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class)->whereIn('address_type', ['shipping', 'both']);
    }

    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class)->whereIn('address_type', ['billing', 'both']);
    }
}
