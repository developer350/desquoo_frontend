@extends('admin::layouts.app')
@section('title', 'Order Details')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('orders.index') }}">
            Orders
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
@endsection
@push('css')
    <style>
        .order-header {
            background: white;
            color: black;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .info-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .table-responsive {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .summary-row {
            font-weight: 500;
        }

        .grand-total-row {
            background-color: #f8f9fa;
            font-weight: 700;
            font-size: 1.1rem;
        }
    </style>
@endpush
@section('content')
    <!-- Order Header -->
    <div class="order-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i class="fas fa-shopping-bag me-2"></i>Order Details</h2>
                    <p class="mb-0">Order ID: <strong>#{{ $order->uuid }}</strong></p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if ($order->status == 'pending')
                        <span class="status-badge status-pending">
                            <i class="fas fa-clock me-1"></i>{{ Str::title($order->status) }}
                        </span>
                    @elseif ($order->status == 'confirmed')
                        <span class="status-badge status-confirmed">
                            <i class="fas fa-check me-1"></i>{{ Str::title($order->status) }}
                        </span>
                    @elseif ($order->status == 'cancelled')
                        <span class="status-badge status-cancelled">
                            <i class="fas fa-times me-1"></i>{{ Str::title($order->status) }}
                        </span>
                    @else
                        <span class="status-badge status-shipped">
                            <i class="fas fa-truck me-1"></i>{{ Str::title($order->status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            @if ($order->user_id != null)
                <!-- Customer Information Card -->
                <div class="card info-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Customer Name</small>
                                <p class="mb-0 fw-semibold">{{ $order->user->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Email Address</small>
                                <p class="mb-0 fw-semibold">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Summary Card -->
            <div class="card info-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($order->user_id == null)
                            <div class="col-md-6">
                                <small class="text-muted">Order User</small>
                                <p class="mb-0 fw-semibold"><span class="text-danger">Guest</span></p>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Order Date</small>
                            <p class="mb-0 fw-semibold">{{ $order->order_date_formatted }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Order UUID</small>
                            <p class="mb-0 fw-semibold">{{ $order->uuid }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Payment Method</small>
                            <p class="mb-0 fw-semibold">{{ $order->payment_method }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Payment Status</small>
                            @if ($order->payment_status == 'pending')
                                <p class="mb-0"><span class="badge bg-warning text-dark"></span> Pending</p>
                            @elseif ($order->payment_status == 'paid')
                                <p class="mb-0"><span class="badge bg-success text-white"></span> Paid</p>
                            @elseif ($order->payment_status == 'failed')
                                <p class="mb-0"><span class="badge bg-danger text-white"></span> Failed</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Total Items</small>
                            <p class="mb-0 fw-semibold">{{ $order->item_count }} items</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($order->shippingAddress != null)
                {{-- Order Shipping Info --}}
                <div class="card info-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Shipping Address</small>
                                <p class="mb-0 fw-semibold">
                                    {{ $order->shippingAddress->name }}<br>
                                    {{ $order->shippingAddress->phone_number }}<br>
                                    {{ $order->shippingAddress->email }}<br>
                                    {{ $order->shippingAddress->address_line_1 }}<br>
                                    {{ $order->shippingAddress->address_line_2 }},{{ $order->shippingAddress->landmark }}<br>
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
                                    {{ $order->shippingAddress->postal_code }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Billing Address</small>
                                <p class="mb-0 fw-semibold">{{ $order->billingAddress->name }}<br>
                                    {{ $order->billingAddress->phone_number }}<br>
                                    {{ $order->billingAddress->email }}<br>
                                    {{ $order->billingAddress->address_line_1 }}<br>
                                    {{ $order->billingAddress->address_line_2 }},{{ $order->billingAddress->landmark }}<br>
                                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}<br>
                                    {{ $order->billingAddress->postal_code }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Items -->
            <div class="card info-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Attributes</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $orderItem->productVariant->image_value ?? $orderItem->product->image_value }}"
                                                    alt="Product" class="product-img me-3">
                                                <div>
                                                    <strong>{{ $orderItem->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <small class="text-muted">{{ $orderItem->sku }}</small>
                                        </td>
                                        <td class="align-middle">
                                            @if ($orderItem->attribute_values != null)
                                                <small>
                                                    @foreach ($orderItem->productVariant->attributeValues as $attributeValue)
                                                        <span
                                                            class="badge bg-secondary {{ $loop->last ? '' : 'me-1' }}">{{ $attributeValue->attribute->name }}:
                                                            {{ $attributeValue->value }}</span>
                                                    @endforeach
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ $orderItem->quantity }}</td>
                                        <td class="text-end align-middle">₹{{ $orderItem->sub_total }}</td>
                                        <td class="text-end align-middle text-success">-{{ $orderItem->discount_amount }}
                                        </td>
                                        <td class="text-end align-middle"><strong>₹{{ $orderItem->total }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Tracking -->
            <div class="card info-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Order Tracking History</h5>
                </div>
                <div class="card-body">
                    <!-- Timeline -->
                    <div class="position-relative">
                        @php
                            $topHeight = 50;
                        @endphp
                        @foreach ($order->orderTracks as $orderTrack)
                            <!-- Timeline Item 1 -->
                            <div class="d-flex mb-4 position-relative">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $orderTrack->status_bg }} d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-{{ $orderTrack->status_icon }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Order {{ Str::title($orderTrack->status) }}</h6>
                                            <small class="text-muted d-block mb-1">{{ $orderTrack->created_at_formatted }}
                                                at {{ $orderTrack->created_at_time }}</small>
                                            @if ($orderTrack->admin_id != null || $orderTrack->user_id != null)
                                                <small class="text-muted">
                                                    <i class="fas fa-user-shield me-1"></i>Updated by:
                                                    <strong>{{ $orderTrack->admin_id != null ? $orderTrack->admin->name : $orderTrack->user->name }}</strong>
                                                </small>
                                            @endif
                                        </div>
                                        <span
                                            class="badge bg-{{ $orderTrack->status_bg }}">{{ Str::title($orderTrack->status) }}</span>
                                    </div>
                                </div>
                            </div>

                            @if (!$loop->last)
                                <!-- Timeline Line -->
                                <div class="position-absolute bg-secondary"
                                    style="left: 24px; top: {{ $topHeight }}px; width: 2px; height: 60px;"></div>
                                @php
                                    $topHeight += 75;
                                @endphp
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card info-card sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 summary-row">
                        <span>Subtotal:</span>
                        <span>₹{{ $order->sub_total }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 summary-row text-success">
                        <span>Discount:</span>
                        <span>-₹{{ $order->discount_amount }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 summary-row">
                        <span>Tax:</span>
                        <span>₹{{ $order->tax_amount }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between grand-total-row p-2 rounded">
                        <span>Grand Total:</span>
                        <span class="text-primary">₹{{ $order->grand_total }}</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="order-note">
                        <small class="text-muted">Order Note:</small>
                        <p class="mb-0 fw-semibold">{{ $order->note ?? 'N/A' }}</p>
                    </div>
                    <a href="{{ route('order-invoice', ['uuid' => $order->uuid]) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-print me-2"></i>Download Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
