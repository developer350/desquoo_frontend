<?php

namespace App\Http\Controllers;

use App\Helpers\FrontendHelpers;
use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderAdminMail;
use App\Mail\OrderMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderTrack;
use App\Models\Pincode;
use App\Models\UserAddress;
use App\Services\RazorpayService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class CheckoutController extends Controller
{
    public $userId;

    public $sessionId;

    public $razorpayService;

    public function __construct()
    {
        // check if login or not
        if (! Auth::check()) {
            $this->userId = null;
        } else {
            $this->userId = Auth::id();
        }

        $this->sessionId = session()->getId();

        $this->razorpayService = new RazorpayService;
    }

    public function index()
    {
        $addresses = UserAddress::where('user_id', $this->userId)->latest()->get();

        $cartProducts = app('cart');

        return view('checkout.index', compact('addresses', 'cartProducts'));
    }

    public function userAddress(Request $request)
    {
        $addresses = UserAddress::where('user_id', $this->userId)->latest('updated_at')->get();

        $shippingAddress = View::make('checkout.partials.shipping_addresses', compact('addresses'))->render();
        $billingAddress = View::make('checkout.partials.billing_addresses', compact('addresses'))->render();

        return response()->json(['status' => true, 'shippingAddress' => $shippingAddress, 'billingAddress' => $billingAddress, 'count' => $addresses->count()]);
    }

    public function checkout(CheckoutRequest $request)
    {
        DB::beginTransaction();
        try {

            $cartProducts = Cart::with('product:id,name,status,is_manage_stock', 'product.bulkOrders', 'productVariant.attributeValues:id,attribute_id,value', 'productVariant.attributeValues.attribute:id,name')
                ->where(function ($query) {
                    $query->whereHas('product', function ($q) {
                        return $q->active();
                    });
                })->when($this->userId, function ($q) {
                    return $q->where('user_id', $this->userId);
                })->when(! $this->userId, function ($q) {
                    return $q->where('session_id', $this->sessionId);
                })
                ->whereHas('product', function ($query) {
                    $query->where('status', 1);
                })
                ->whereHas('productVariant', function ($query) {
                    $query->where('status', 1);
                })->get();

            if (count($cartProducts) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart is empty',
                ], 200);
            }

            // check if user is logged
            if (! $this->userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please login to checkout',
                    'url' => route('login'),
                ], 200);
            }

            $subtotal = $this->getSubTotal($cartProducts);
            $discountAmount = $this->getDiscountAmount($cartProducts);
            // tax already added in product price
            $taxAmount = $this->getTaxAmount($cartProducts, $subtotal, $discountAmount);
            $grandTotal = $subtotal - $discountAmount;

            // one lakh limit in razorpay
            if ($grandTotal > 100000) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order total exceeds the maximum limit of ₹1,00,000 for online payments. Please contact support for assistance or lower your order total.',
                ], 200);
            }

            $order = Order::create([
                'user_id' => $this->userId,
                'session_id' => $this->userId != null ? null : $this->sessionId,
                'uuid' => uniqid(),
                'date' => date('Y-m-d'),
                'item_count' => count($cartProducts),
                'sub_total' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'payment_method' => 'Razorpay',
                'payment_status' => 'pending',
                'status' => 'pending',
                'note' => $request->note,
                'same_bill_address' => $request->same_bill_address,
            ]);

            foreach ($cartProducts as $key => $cartProduct) {
                $attributes = json_encode($cartProduct->productVariant->attributeValues);
                $subtotal = $cartProduct->productVariant->last_price * $cartProduct->quantity;
                $discountAmount = $this->getProductDiscountAmount($cartProduct);
                $order->orderItems()->create([
                    'order_id' => $order->id,
                    'product_id' => $cartProduct->product_id,
                    'product_variant_id' => $cartProduct->product_variant_id,
                    'name' => $cartProduct->product->name,
                    'sku' => $cartProduct->productVariant->sku ?? $cartProduct->product->sku,
                    'quantity' => $cartProduct->quantity,
                    'price' => $cartProduct->productVariant->last_price,
                    'sub_total' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total' => $subtotal - $discountAmount,
                    'attribute_values' => $attributes,
                ]);

                if ($cartProduct->product->is_manage_stock) {
                    // Add lockForUpdate() to safely update stock
                    $variant = DB::table('product_variants')
                        ->where('id', $cartProduct->productVariant->id)
                        ->lockForUpdate()
                        ->first();

                    if ($variant->stock < $cartProduct->quantity) {
                        $message = 'Stock not available for '.$cartProduct->product->name.' SKU '.$cartProduct->productVariant->sku ?? $cartProduct->product->sku;
                        $message .= '<br>'.$variant->stock.' in stock';
                        throw new Exception($message);
                    }

                    // decrease stock quantity
                    $cartProduct->productVariant->decrement('stock', $cartProduct->quantity);
                }
            }

            $this->addressManage($request, $order);

            OrderTrack::create([
                'order_id' => $order->id,
                'status' => 'pending',
            ]);

            DB::commit();

            $razorpayOrder = $this->razorpayService->createOrder($order);

            $order->update([
                'razorpay_order_id' => $razorpayOrder->id,
            ]);

            $data = [
                'order_uuid' => $order->uuid,
                'order_id' => $razorpayOrder->id,
                'key' => config('services.razorpay.key'),
                'amount' => $razorpayOrder->amount,
                'name' => $order->user ? $order->user->name : '',
                'email' => $order->user ? $order->user->email : '',
            ];

            return response()->json(['status' => true, 'message' => 'Order Placed Successfully', 'data' => $data], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong', 'url' => route('order-failed')], 200);
        }
    }

    public function addressManage($request, $order)
    {
        if ($order->same_bill_address) {
            OrderAddress::create($this->getData($request->shippingAddressId, $order, 'both'));
        } else {
            OrderAddress::create($this->getData($request->shippingAddressId, $order, 'shipping'));
            OrderAddress::create($this->getData($request->billingAddressId, $order, 'billing'));
        }
    }

    public function getData($addressId, $order, $addressType)
    {
        $address = UserAddress::findOrFail($addressId);

        return [
            'order_id' => $order->id,
            'address_type' => $addressType,
            'name' => $address->name,
            'email' => $address->email,
            'phone_number' => $address->phone_number,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'landmark' => $address->landmark,
            'gst_number' => $address->gst_number,
        ];
    }

    public function getSubTotal($cartProducts)
    {
        $subTotal = 0;
        foreach ($cartProducts as $cartProduct) {
            $subTotal += $cartProduct->productVariant->last_price * $cartProduct->quantity;
        }

        return $subTotal;
    }

    // only bulk order discount
    public function getDiscountAmount($cartProducts)
    {
        $discountAmount = 0;
        foreach ($cartProducts as $cartProduct) {
            if ($cartProduct->product->bulkOrders->count() > 0) {
                // check if the quantity is between in bulk order
                foreach ($cartProduct->product->bulkOrders as $bulkOrder) {
                    if ($cartProduct->quantity >= $bulkOrder->min_quantity && $cartProduct->quantity <= $bulkOrder->max_quantity) {
                        if ($bulkOrder->discount_percentage > 0) {
                            $discountAmount += $bulkOrder->discount_percentage / 100 * $cartProduct->productVariant->last_price * $cartProduct->quantity;
                        }
                        break;
                    }
                }
            }
        }

        return $discountAmount;
    }

    public function getTaxAmount($cartProducts, $subtotal, $discountAmount)
    {
        $taxPercentage = app('appSettings')->get('tax.percentage')->value ?? 0;
        if ($taxPercentage == 0) {
            return 0;
        }

        // tax calculation (reciprocal)

        $total = $subtotal - $discountAmount;

        $taxableAmount = ($total / (100 + $taxPercentage)) * 100;

        return round($taxableAmount * $taxPercentage / 100, 2);
    }

    public function getProductDiscountAmount($cartProduct)
    {
        $discountAmount = 0;
        if ($cartProduct->product->bulkOrders->count() > 0) {
            // check if the quantity is between in bulk order
            foreach ($cartProduct->product->bulkOrders as $bulkOrder) {
                if ($cartProduct->quantity >= $bulkOrder->min_quantity && $cartProduct->quantity <= $bulkOrder->max_quantity) {
                    if ($bulkOrder->discount_percentage > 0) {
                        $discountAmount += $bulkOrder->discount_percentage / 100 * $cartProduct->productVariant->last_price * $cartProduct->quantity;
                    }
                    break;
                }
            }
        }

        return $discountAmount;
    }

    public function orderConfirmation($uuid)
    {
        $order = Order::with([
            'orderItems' => function ($q) {
                $q->with('productVariant:id,sku,image_alt_text', 'productVariant.attributeValues.media', 'product:id,name,slug,is_addon,image_alt_text', 'product.media');
            },
            'shippingAddress',
        ])->where('uuid', $uuid)->firstOrFail();

        $socialLinks = app('socialLinks');

        $meta = FrontendHelpers::getPageDetails('order-confirmation');

        $pincode = $order->shippingAddress?->postal_code;
        if ($pincode != null) {
            $pincodeDeliveryDate = Pincode::where('pincodes', 'LIKE', '%'.$pincode.',%')->first();
            $estimatedDeliveryDate = Carbon::now()->addDays($pincodeDeliveryDate->delivery_days ?? 5)->format('d M Y');
        } else {
            $estimatedDeliveryDate = Carbon::now()->addDays(5)->format('d M Y');
        }

        return view('dash.order-confirmation', compact('order', 'socialLinks', 'meta', 'estimatedDeliveryDate'));
    }

    /**
     * Display the dash page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function orderFailed()
    {
        $meta = FrontendHelpers::getPageDetails('order-failed');

        return view('dash.order-failed', compact('meta'));
    }

    public function checkPincode(Request $request)
    {
        $request->validate([
            'pincode' => 'required|string',
        ]);

        $pincode = $request->input('pincode');

        $isAvailable = Pincode::where(function ($query) use ($pincode) {
            $query->where('pincodes', 'LIKE', $pincode.',%')  // At start
                ->orWhere('pincodes', 'LIKE', '%,'.$pincode.',%')  // In middle
                ->orWhere('pincodes', 'LIKE', '%,'.$pincode)  // At end
                ->orWhere('pincodes', '=', $pincode);  // Only value
        })->where('status', 1)->first();

        if ($isAvailable) {
            return response()->json([
                'status' => true,
                'message' => 'Delivery is available to this pincode.',
                'showSuccess' => false,
                'data' => $isAvailable,
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Sorry, delivery is not available to this pincode.',
                'data' => null,
                'showSuccess' => false,
            ], 200);
        }
    }

    public function razorpayPayment(Request $request)
    {
        $input = $request->all();
        // Check if Razorpay payment ID is provided
        if (empty($input['razorpay_payment_id'])) {
            return response()->json([
                'status' => false,
                'message' => 'Payment details are incomplete. Please try again.',
            ]);
        }

        try {
            $payment = $this->razorpayService->fetchPayment($input['razorpay_payment_id']);

            // Capture payment if authorized
            if ($payment && $payment->status === 'authorized') {
                $captureResponse = $this->razorpayService->capturePayment($input['razorpay_payment_id'], $payment['amount']);

                // Save the capture response in the database
                $this->updateEnquiryPaymentStatus($payment->notes['order_uuid'], 'paid', $captureResponse);

                return response()->json([
                    'status' => true,
                    'message' => 'Payment successful.',
                    'url' => route('order-confirmation', $payment->notes['order_uuid']),
                ]);
            } elseif ($payment && $payment->status === 'captured') {
                // If payment is already captured, handle the case
                $this->updateEnquiryPaymentStatus($payment->notes['order_uuid'], 'paid', $payment);

                return response()->json([
                    'status' => true,
                    'message' => 'Payment successful.',
                    'url' => route('order-confirmation', $payment->notes['order_uuid']),
                ]);
            } elseif ($payment && $payment->status === 'pending') {
                // If payment is still pending, handle this case
                return response()->json([
                    'status' => false,
                    'message' => 'Payment is still pending.',
                ]);
            } else {
                // If the payment is not authorized or captured
                $this->updateEnquiryPaymentStatus($payment->notes['order_uuid'], 'failed', $payment);

                return response()->json([
                    'status' => false,
                    'message' => 'Payment is not authorized or already captured.',
                    'url' => route('order-failed'),
                ]);
            }
        } catch (Exception $e) {
            // Catch exceptions and handle errors
            $errorMessage = $e->getMessage();

            // Log failed payment and update status to 'failed'
            if (isset($payment->notes['order_uuid'])) {
                $this->updateEnquiryPaymentStatus($payment->notes['order_uuid'], 'failed', [
                    'error_message' => $errorMessage,
                    'exception' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'We couldn\'t process your payment. Please contact support.',
                'error' => $errorMessage,
                'url' => route('order-failed'),
            ]);
        }
    }

    private function updateEnquiryPaymentStatus(?string $uuid, string $status, $response): void
    {
        if ($uuid) {
            $order = Order::where('uuid', $uuid)->first();

            if ($order) {
                $order->status = $status == 'paid' ? 'confirmed' : 'cancelled';
                $order->payment_status = $status;
                $order->payment_details = json_encode($response);
                $order->save();

                // ORDER TRACK
                OrderTrack::create([
                    'order_id' => $order->id,
                    'status' => $order->status,
                ]);

                if ($order->payment_status == 'paid') {
                    Cart::where(function ($query) {
                        $query->whereHas('product', function ($q) {
                            return $q->active();
                        });
                    })->when($this->userId, function ($q) {
                        return $q->where('user_id', $this->userId);
                    })->when(! $this->userId, function ($q) {
                        return $q->where('session_id', $this->sessionId);
                    })->delete();

                    session()->forget('cart');

                    defer(function () use ($order) {
                        if ($order->user_id != null) {
                            Mail::to($order->user->email)->send(new OrderMail($order));
                        }
                        Mail::to(config('mail.to.admin'))
                            ->cc(config('mail.to.cc'))
                            ->send(new OrderAdminMail($order));
                    })->always();
                } else {
                    $order->orderItems->each(function ($item) {
                        if ($item->product->is_manage_stock) {
                            $item->productVariant->increment('stock', $item->quantity);
                        }
                    });
                }
            }
        }
    }
}
