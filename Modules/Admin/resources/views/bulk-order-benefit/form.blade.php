@extends('admin::layouts.app')
@section('title', isset($bulkOrderBenefit) ? 'Edit Bulk Order Benefit' : 'Create Bulk Order Benefit')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('bulk-order-benefits.index') }}">
            Bulk Order Benefits
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ isset($bulkOrderBenefit) ? route('bulk-order-benefits.update', base64_encode($bulkOrderBenefit->id)) : route('bulk-order-benefits.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($bulkOrderBenefit)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('bulk-order-benefits.index')" save-label="{{ isset($bulkOrderBenefit) ? 'Update' : 'Create' }}" />
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
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $bulkOrderBenefit->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="icon_alt_text" class="form-label">Icon Alt Text</label>
                                    <input type="text" class="form-control" id="icon_alt_text" name="icon_alt_text"
                                        value="{{ $bulkOrderBenefit->icon_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description*</label>
                                    <textarea name="description" id="description" class="form-control" data-rule-maxlength="5000" required>{{ $bulkOrderBenefit->description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label">Icon*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="icon"
                                        id="icon" data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml" required>
                                    <div class="text-muted">Dimensions: 40 x 40 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $bulkOrderBenefit->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($bulkOrderBenefit) && $bulkOrderBenefit->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($bulkOrderBenefit) && $bulkOrderBenefit->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $bulkOrderBenefit ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('bulk-order-benefits.index') }}"
            });
        });
    </script>
@endpush
