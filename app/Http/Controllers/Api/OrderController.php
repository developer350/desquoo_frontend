<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $orders = Order::with('orderItems.productVariant:id,sku', 'orderItems.productVariant.attributeValues.attribute:id,name', 'user', 'billingAddress', 'shippingAddress')->paginate($perPage, ['*'], 'page', $page);

        // Logic to retrieve and return orders
        return response()->json([
            'status' => true,
            'message' => 'Orders retrieved successfully',
            'data' => [
                'orders' => $this->orderMapper($orders),
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $orders->total(),
                'total_pages' => $orders->lastPage(),
            ],
        ]);
    }

    private function orderMapper($orders)
    {
        return $orders->getCollection()->transform(function ($order) {
            $taxPercent = $order->tax_amount > 0 && $order->sub_total > 0 ? ($order->tax_amount / $order->sub_total) * 100 : 0;

            return [
                'id' => $order->id,
                'number' => (string) $order->id,
                'order_key' => $order->uuid,
                'created_via' => 'rest-api',
                'status' => $order->status,
                'currency' => 'INR',
                'date_created' => $order->created_at->toIso8601String(),
                'date_created_gmt' => $order->created_at->toIso8601String(),
                'date_modified' => $order->updated_at->toIso8601String(),
                'date_modified_gmt' => $order->updated_at->toIso8601String(),
                'discount_total' => $order->discount_amount,
                'discount_tax' => (string) ($order->discount_amount > 0 ? ($order->discount_amount * $taxPercent / 100) : 0.00),
                'shipping_total' => '00.00',
                'shipping_tax' => '0.00',
                'cart_tax' => $order->tax_amount,
                'total' => $order->grand_total,
                'total_tax' => $order->tax_amount,
                'prices_include_tax' => false,
                'customer_id' => $order->user_id ?? 0,
                'customer_ip_address' => '',
                'customer_user_agent' => '',
                'customer_note' => '',
                'billing' => [
                    'first_name' => $order->billingAddress->name ?? '',
                    'last_name' => '',
                    'company' => '',
                    'address_1' => $order->billingAddress->address_line_1 ?? '',
                    'address_2' => $order->billingAddress->address_line_2 ?? '',
                    'city' => $order->billingAddress->city ?? '',
                    'state' => $order->billingAddress->state ?? '',
                    'postcode' => $order->billingAddress->postal_code ?? '',
                    'country' => 'India',
                    'email' => $order->billingAddress->email ?? '',
                    'phone' => $order->billingAddress->phone_number ?? '',
                    'gstnumber' => $order->billingAddress->gst_number ?? '',
                ],
                'shipping' => [
                    'first_name' => $order->shippingAddress->name ?? '',
                    'last_name' => '',
                    'company' => '',
                    'address_1' => $order->shippingAddress->address_line_1 ?? '',
                    'address_2' => $order->shippingAddress->address_line_2 ?? '',
                    'city' => $order->shippingAddress->city ?? '',
                    'state' => $order->shippingAddress->state ?? '',
                    'postcode' => $order->shippingAddress->postal_code ?? '',
                    'country' => 'India',
                    'email' => $order->shippingAddress->email ?? '',
                    'phone' => $order->shippingAddress->phone_number ?? '',
                    'gstnumber' => $order->billingAddress->gst_number ?? '',
                ],
                'payment_method' => $order->payment_method,
                'payment_method_title' => $order->payment_method_title,
                'transaction_id' => '',
                'date_paid' => '',
                'date_paid_gmt' => '',
                'date_completed' => null,
                'date_completed_gmt' => null,
                'cart_hash' => '',
                'line_items' => $order->orderItems->map(function ($item) use ($taxPercent) {
                    $tax = $taxPercent > 0 ? number_format($item->sub_total * $taxPercent / 100, 2, '.', '') : 0.00;

                    return [
                        'id' => $item->id,
                        'name' => $item->name.' '.$item->productVariant->attributeValues->map(function ($attributeValue) {
                            return $attributeValue->attribute->name.': '.$attributeValue->value;
                        })->implode(', '),
                        'product_id' => $item->product_id,
                        'variation_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                        'tax_class' => '',
                        'subtotal' => number_format($item->sub_total, 2, '.', ''),
                        'subtotal_tax' => $tax,
                        'total' => $item->sub_total,
                        'total_tax' => $tax,
                        'taxes' => [],
                        'meta_data' => $item->productVariant->attributeValues->map(function ($attributeValue) {
                            return [
                                'id' => $attributeValue->id,
                                'key' => $attributeValue->attribute->name,
                                'value' => $attributeValue->value,
                            ];
                        })->toArray(),
                        'sku' => $item->sku,
                        'price' => $item->price,
                    ];
                })->toArray(),
                'tax_lines' => [],
                'shipping_lines' => [],
            ];
        });
    }
}
