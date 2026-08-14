<?php

namespace App\Services;

use App\Mail\OrderAdminMail;
use App\Mail\OrderMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

class RazorpayService
{
    public $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function createOrder($order)
    {
        $order = $this->api->order->create([
            'receipt' => $order->uuid,
            'amount' => $order->grand_total * 100, // Amount in paise
            // 'amount' => 1 * 100,
            'currency' => 'INR',
            'notes' => [
                'order_id' => $order->id,
                'order_uuid' => $order->uuid,
            ],
        ]);

        Log::info('Razorpay order created', ['order' => $order]);

        return $order;
    }

    public function fetchPayment($paymentId)
    {
        return $this->api->payment->fetch($paymentId);
    }

    public function capturePayment($paymentId, $amount)
    {
        return $this->api->payment->fetch($paymentId)->capture(['amount' => $amount]);
    }

    public function checkOrderPayment($order)
    {
        return $this->api->order->fetch($order->razorpay_order_id)->payments();
    }
}
