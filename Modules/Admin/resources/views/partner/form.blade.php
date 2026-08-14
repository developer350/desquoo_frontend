@extends('admin::layouts.app')
@section('title', isset($partner) ? 'Edit Partner' : 'Create Partner')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('partners.index') }}">
            Partners
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($partner) ? route('partners.update', base64_encode($partner->id)) : route('partners.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($partner)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('partners.index')" save-label="{{ isset($partner) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $partner->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="logo_alt_text" class="form-label">Logo Alt Text</label>
                                    <input type="text" class="form-control" id="logo_alt_text" name="logo_alt_text"
                                        value="{{ $partner->logo_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="logo" class="form-label">Logo*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="logo"
                                        id="logo" data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml" required>
                                    <div class="text-muted">Dimensions: 106 x 36 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $partner->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($partner) && $partner->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($partner) && $partner->status == 0)>Disabled</option>
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
@include('admin::partials.filepond-setup', ['mediaSource' => $partner ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('partners.index') }}"
            });
        });
    </script>
@endpush
