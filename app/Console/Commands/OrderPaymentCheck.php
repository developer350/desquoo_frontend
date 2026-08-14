<?php

namespace App\Console\Commands;

use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderTrack;
use App\Services\RazorpayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class OrderPaymentCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:order-payment-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it will process all orders to cancel if payment not completed within 15 minutes';

    /**
     * Execute the console command.
     */
    public function handle(RazorpayService $razorpayService)
    {
        $orders = Order::where('status', 'pending')->where('payment_status', 'pending')->whereNotNull('razorpay_order_id')->where('created_at', '<', now()->subMinutes(15))->get();
        foreach ($orders as $order) {
            $payments = $razorpayService->checkOrderPayment($order);

            $isFailed = false;

            foreach ($payments['items'] as $payment) {
                if ($payment['status'] == 'failed') {
                    $isFailed = true;
                    break;
                }
            }

            if ($isFailed) {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
                $order->save();

                $order->orderItems->each(function ($item) {
                    if ($item->product->is_manage_stock) {
                        $item->productVariant->increment('stock', $item->quantity);
                    }
                });
            } else {
                $order->payment_status = 'paid';
                $order->status = 'confirmed';
                $order->save();
            }

            OrderTrack::create([
                'order_id' => $order->id,
                'status' => $order->status,
            ]);

            if ($order->user_id != null) {
                Mail::to($order->user->email)->send(new OrderStatusMail($order));
            }
        }
    }
}
