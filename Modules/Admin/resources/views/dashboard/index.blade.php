@extends('admin::layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <a href="{{ route('orders.index') }}" class="col-xl-3 col-md-4">
            <div class="card card-h-100 align-items-center">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <h4 class="mb-3">
                                <span class="counter-value text-primary" data-target="{{ $totalOrders }}">0</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class=" text-muted font-size-13 ">Total Orders</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('orders.index') }}" class="col-xl-3 col-md-4">
            <div class="card card-h-100 align-items-center">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <h4 class="mb-3 text-primary">
                                ₹<span class="counter-value text-primary" data-target="{{ $delivered->total }}">0</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class=" text-muted font-size-13 ">Revenue</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('orders.index') }}?status=delivered" class="col-xl-3 col-md-4">
            <div class="card card-h-100 align-items-center">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <h4 class="mb-3">
                                <span class="counter-value text-primary" data-target="{{ $delivered->count }}">0</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class=" text-muted font-size-13 ">Delivered</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('users.index') }}" class="col-xl-3 col-md-4">
            <div class="card card-h-100 align-items-center">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <h4 class="mb-3">
                                <span class="counter-value text-primary" data-target="{{ $totalUsers }}">0</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class=" text-muted font-size-13 ">Users</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Latest Orders</h4>
                    @if ($latestOrders->isNotEmpty())
                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm">
                            View All
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>UUID</th>
                                    <th>Order Date</th>
                                    <th>User</th>
                                    <th>Item Count</th>
                                    <th>Grand Total</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestOrders as $latestOrder)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $latestOrder->uuid }}</td>
                                        <td>{{ $latestOrder->order_date_formatted }}</td>
                                        <td>
                                            @if ($latestOrder->user_id != null)
                                                {{ $latestOrder->user->name }}
                                            @else
                                                <span class="badge bg-secondary">Guest</span>
                                            @endif
                                        </td>
                                        <td>{{ $latestOrder->item_count }}</td>
                                        <td>{{ $latestOrder->grand_total }}</td>
                                        <td>{{ $latestOrder->payment_method }}</td>
                                        <td>
                                            @if ($latestOrder->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger">Unpaid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($latestOrder->status == 'confirmed')
                                                <span class="badge bg-primary">Confirmed</span>
                                            @elseif ($latestOrder->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @elseif ($latestOrder->status == 'processing')
                                                <span class="badge bg-secondary">Processing</span>
                                            @elseif ($latestOrder->status == 'shipped')
                                                <span class="badge bg-info changeStatus">Shipped</span>
                                            @elseif ($latestOrder->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', base64_encode($latestOrder->id)) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
