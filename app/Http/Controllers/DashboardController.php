<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function myAccount()
    {
        $orders = Order::with('orderItems', 'orderItems.product:id,name,sku,image_alt_text', 'orderItems.productVariant:id,sku,image_alt_text', 'orderItems.productVariant.attributeValues')->where('user_id', Auth::id())->latest()->get();
        $addresses = UserAddress::where('user_id', Auth::id())->get();
        return view('dash.my-account', compact('orders', 'addresses'));
    }

    public function orderInvoice($uuid)
    {
        $order = Order::with(['orderItems' => function ($q) {
            $q->with('productVariant:id,sku', 'productVariant.attributeValues.attribute:id,name', 'product:id,name,slug');
        }, 'user:id,name,email', 'shippingAddress', 'billingAddress'])->where('uuid', $uuid)->firstOrFail();

        $logoBase64 = base64_encode(file_get_contents(public_path('frontend/images/mail-logo.png')));

        return Pdf::loadView('mails.invoice', compact('order', 'logoBase64'))
            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true])
            ->download('invoice_' . $order->uuid . '.pdf');
    }
}
