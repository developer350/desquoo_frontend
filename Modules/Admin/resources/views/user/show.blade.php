@extends('admin::layouts.app')
@section('title', 'User Details')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            Users
        </a>
    </li>
@endsection
@section('buttons')
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('backend/css/customer-view.css') }}">
@endpush
@section('content')
    <div class="dashboard-theme">
        <!-- Profile Header -->
        <div class="profile-header fade-in">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <img src="{{ asset('backend/images/avatar.webp') }}" alt="Profile Picture" class="avatar">
                </div>
                <div class="col-md-8">
                    <h1 class="mb-2">{{ $user->name }}</h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-envelope me-2"></i>
                        {{ $user->email }}
                    </p>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Member since: {{ $user->created_at->format('F j, Y') }}
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0">
                        @if ($user->status)
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-cancelled">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row fade-in">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon revenue-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3 class="mb-1">₹ {{ number_format($totalRevenue, 2) }}</h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon orders-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="mb-1">{{ $user->orders->count() }}</h3>
                    <p class="text-muted mb-0">Total Orders</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon subscriptions-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="mb-1">{{ $user->orders->where('status', 'delivered')->count() }}</h3>
                    <p class="text-muted mb-0">Total Orders Delivered</p>
                </div>
            </div>
        </div>

        <!-- Subscriptions Section -->
        <div class="content-card mb-4 fade-in">
            <div class="p-4">
                <h2 class="section-title">Orders</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>UUID</th>
                            <th>Order Date</th>
                            <th>Item Count</th>
                            <th>Grand Total</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user->orders as $latestOrder)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $latestOrder->uuid }}</td>
                                <td>{{ $latestOrder->order_date_formatted }}</td>
                                <td>{{ $latestOrder->item_count }}</td>
                                <td>₹ {{ $latestOrder->grand_total }}</td>
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

@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.fade-in');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
@endpush
