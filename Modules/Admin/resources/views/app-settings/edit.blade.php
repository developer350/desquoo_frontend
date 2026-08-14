@extends('admin::layouts.app')
@section('title', 'Edit App Settings')
@section('content')
    <form method="POST" action="{{ route('app-settings.update') }}">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <x-admin::action-buttons save-label="Update" />
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-cog me-2"></i>
                        <span>General</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">App Name*</label>
                            <input type="text" class="form-control" name="app[name]"
                                value="{{ $appSettings['app.name']->value }}" maxlength="191" required>
                            <div class="text-muted">
                                {{ $appSettings['app.name']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-boxes me-2"></i>
                        <span>Catalog & Orders</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">SKU Prefix*</label>
                            <input type="text" class="form-control" name="catalog[sku_prefix]"
                                value="{{ $appSettings['catalog.sku_prefix']->value }}" maxlength="20" required>
                            <div class="text-muted">
                                {{ $appSettings['catalog.sku_prefix']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Order Prefix*</label>
                            <input type="text" class="form-control" name="order[prefix]"
                                value="{{ $appSettings['order.prefix']->value }}" maxlength="20" required>
                            <div class="text-muted">
                                {{ $appSettings['order.prefix']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-envelope me-2"></i>
                        <span>Contact</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Support Email</label>
                            <input type="email" class="form-control" name="contact[support_email]"
                                value="{{ $appSettings['contact.support_email']->value }}" maxlength="191">
                            <div class="text-muted">
                                {{ $appSettings['contact.support_email']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-dollar-sign me-2"></i>
                        <span>Currency</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Currency Code*</label>
                            <input type="text" class="form-control" name="currency[code]"
                                value="{{ $appSettings['currency.code']->value }}" maxlength="10" required>
                            <div class="text-muted">
                                {{ $appSettings['currency.code']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Currency Symbol*</label>
                            <input type="text" class="form-control" name="currency[symbol]"
                                value="{{ $appSettings['currency.symbol']->value }}" maxlength="5" required>
                            <div class="text-muted">
                                {{ $appSettings['currency.symbol']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-receipt me-2"></i>
                        <span>Tax & Shipping</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Tax %*</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control"
                                name="tax[percentage]" value="{{ $appSettings['tax.percentage']->value }}" required>
                            <div class="text-muted">
                                {{ $appSettings['tax.percentage']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Shipping Flat Rate*</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                name="shipping[flat_rate]" value="{{ $appSettings['shipping.flat_rate']->value }}"
                                required>
                            <div class="text-muted">
                                {{ $appSettings['shipping.flat_rate']->description }}
                            </div>
                            <span class="error-block"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(function() {
            $('form').customValidate({
                successRoute: "{{ route('app-settings.edit') }}"
            });
        });
    </script>
@endpush
