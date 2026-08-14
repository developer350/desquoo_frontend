@extends('admin::layouts.app')
@section('title', isset($bulkOrder) ? 'Edit Product Bulk Order' : 'Create Product Bulk Order')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('products.bulk-orders.index', ['product' => base64_encode($product->id)]) }}">
            Product Bulk Orders
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($bulkOrder) ? route('products.bulk-orders.update', ['product' => base64_encode($product->id), 'bulk_order' => base64_encode($bulkOrder->id)]) : route('products.bulk-orders.store', base64_encode($product->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($bulkOrder)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('products.bulk-orders.index', ['product' => base64_encode($product->id)])" save-label="{{ isset($bulkOrder) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Main Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title*</label>
                                    <input type="text" name="title" id="title"
                                        value="{{ $bulkOrder->title ?? '' }}" class="form-control" placeholder="Title"
                                        required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="min_quantity" class="form-label">Min Quantity*</label>
                                    <input type="number" name="min_quantity" step="1" min="1" max="999999"
                                        id="min_quantity" value="{{ $bulkOrder->min_quantity ?? '' }}"
                                        class="form-control numeric-input" placeholder="1" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_quantity" class="form-label">Max Quantity*</label>
                                    <input type="number" name="max_quantity" step="1" min="1" max="999999"
                                        id="max_quantity" value="{{ $bulkOrder->max_quantity ?? '' }}"
                                        class="form-control numeric-input" placeholder="10" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                    <input type="number" name="discount_percentage" step="0.01" min="0"
                                        max="100" value="{{ $bulkOrder->discount_percentage ?? '' }}"
                                        class="form-control decimal-input" placeholder="0.00" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order*</label>
                                    <input type="number" name="sort_order" step="1" min="0"
                                        value="{{ $bulkOrder->sort_order ?? $sort_order }}"
                                        class="form-control numeric-input" placeholder="0" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($bulkOrder) && $bulkOrder->status == 1)>
                                            Enabled
                                        </option>
                                        <option value="0" @selected(isset($bulkOrder) && $bulkOrder->status == 0)>
                                            Disabled
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@include('admin::partials.select2-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                rules: {
                    min_quantity: {
                        required: true,
                        min: 1,
                        max: 999999,
                        digits: true
                    },
                    max_quantity: {
                        required: true,
                        min: 1,
                        max: 999999,
                        digits: true,
                        gtOrEq: '#min_quantity'
                    },
                    discount_percentage: {
                        required: true,
                        min: 0,
                        max: 100,
                        number: true
                    },
                    sort_order: {
                        required: true,
                        min: 0,
                        digits: true
                    }
                },
                successRoute: "{{ route('products.bulk-orders.index', ['product' => base64_encode($product->id)]) }}"
            });
        });
    </script>
@endpush
