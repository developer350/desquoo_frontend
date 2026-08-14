@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="dashPage">
        <section id="OrderSection">
            <div class="container">

                <div class="item">
                    <div class="orderStatusBx">
                        <div class="imgWrap">
                            <img src="{{ asset('frontend/images/order-confirm.svg') }}" width="100" height="100"
                                loading="lazy" alt="order-confirm">
                        </div>
                        <div class="cntWrap">
                            <div class="tle">Order Confirmed!</div>
                            <div class="txt">We've received your order and will send you a confirmation email shortly.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="orderInfoBx">
                        <div class="infoFlx">
                            <div class="txt1">Order Summary</div>
                        </div>
                        <div class="infoFlx">
                            <div class="txt">
                                <span>Order Number</span><br>
                                <b>#{{ $order->uuid }}</b>
                            </div>
                            <div class="txt">
                                <span>Order Date</span><br>
                                <b>{{ $order->order_date_formatted }}</b>
                            </div>
                            <div class="txt">
                                <span>Estimated Delivery</span><br>
                                <b>{{ $estimatedDeliveryDate }}</b>
                            </div>
                        </div>
                        <hr />
                        <div class="infoFlx">
                            <div class="txt1">Items ordered</div>
                        </div>
                        @foreach ($order->orderItems as $orderItem)
                            <div class="orderProBx">
                                <div class="imgWrap">
                                    <img src="{{ $orderItem->productVariant->image_value ?? $orderItem->product->image_value }}"
                                        width="126" height="126" loading="lazy"
                                        alt="{{ $orderItem->productVariant->image_alt_text ?? ($orderItem->product->image_alt_text ?? $orderItem->product->name) }}">
                                </div>
                                <div class="cntWrap">
                                    <div>
                                        <div class="txt"><b>{{ $orderItem->product->name }}</b> </div>
                                        @foreach ($orderItem->productVariant->attributeValues as $attributeValue)
                                            <div class="txt">
                                                @if ($attributeValue->icon_value != null)
                                                    <img src="{{ $attributeValue->icon_value }}" width="16"
                                                        height="16" alt="{{ $attributeValue->value }}">
                                                @else
                                                    <div class="rounded-circle" style="width:16px;height:16px;">
                                                        <span class="text-black">
                                                            {{ strtoupper(substr($attributeValue->value, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <span>{{ $attributeValue->value }}</span>
                                            </div>
                                        @endforeach
                                        <div class="txt">Qty: {{ $orderItem->quantity }}</div>
                                    </div>
                                    <div>
                                        <div class="txt price"><b> ₹ {{ $orderItem->total }}</b> </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="orderSummaryWrap">
                            <hr />
                            <div class="infoFlx">
                                <div class="txt">Subtotal (Incl. Tax):</div>
                                <div class="txt">₹ {{ $order->sub_total }}</div>
                            </div>
                            <div class="infoFlx">
                                <div class="txt">Discount:</div>
                                <div class="txt">₹ -{{ $order->discount_amount }}</div>
                            </div>
                            <div class="infoFlx">
                                <div class="txt">Shipping Cost:</div>
                                <div class="txt">₹ 0.00</div>
                            </div>
                            <div class="infoFlx">
                                <div class="txt">Tax:</div>
                                <div class="txt">₹ {{ $order->tax_amount }}</div>
                            </div>
                            <div class="infoFlx">
                                <div class="txt"><b> Grand Total:</b></div>
                                <div class="txt"><b>₹ {{ $order->grand_total }}</b></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="orderInfoBx">
                        <div class="infoFlx">
                            <div class="txt1">Shipping Information</div>
                        </div>
                        <div class="infoFlx">
                            @if ($order->shippingAddress != null)
                                <div class="txt">
                                    <span>Shipping Address</span><br>
                                    <b>{{ $order->shippingAddress->address_line_1 }} <br>
                                        {{ $order->shippingAddress->address_line_2 }} <br>
                                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} -
                                        {{ $order->shippingAddress->postal_code }}</b>
                                </div>
                            @endif
                            <div class="txt">
                                <span>Payment Method</span><br>
                                <b>Razorpay (Online)</b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="choosingBx">
                        <div class="cntWrap">
                            <div class="tle">Thank You for Choosing Desqoo!</div>
                            <div class="txt">We're committed to delivering exceptional products and experiences. Your
                                satisfaction is our priority, and we're here to help every step of the way.</div>
                            @if ($socialLinks->isNotEmpty())
                                <ul class="socialUl">
                                    @foreach ($socialLinks as $socialLink)
                                        <li>
                                            <a href="{{ $socialLink->url }}" target="_blank">
                                                <img src="{{ $socialLink->icon_value }}" width="24" height="24"
                                                    loading="lazy" alt="twitter">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
@push('js')
    <script></script>
@endpush
