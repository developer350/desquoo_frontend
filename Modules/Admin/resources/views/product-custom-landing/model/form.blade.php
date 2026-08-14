@extends('admin::layouts.app')
@section('title', isset($model) ? 'Edit PCL Models' : 'Create PCL Models')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('product-custom-landings.index') }}">
            Product Custom Landings
        </a>
    </li>
    <li class="breadcrumb-item">
        <a
            href="{{ route('product-custom-landings.model.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}">
            PCL Models
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($model) ? route('product-custom-landings.model.update', ['product_custom_landing' => base64_encode($productCustomLanding->id), 'model' => base64_encode($model->id)]) : route('product-custom-landings.model.store', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($model)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('product-custom-landings.model.index', [
                'product_custom_landing' => base64_encode($productCustomLanding->id),
            ])" save-label="{{ isset($model) ? 'Update' : 'Create' }}" />
            <div class="col-md-8">
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
                                        value="{{ $model->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" data-rule-maxlength="5000">{{ $model->description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="recommended_text" class="form-label">Recommended Text</label>
                                    <input type="text" class="form-control" id="recommended_text" name="recommended_text"
                                        value="{{ $model->recommended_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $model->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($model) && $model->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($model) && $model->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Media Upload</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="image"
                                        id="image" data-width="1040" data-height="693"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 1040 x 693</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $model->image_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
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
@include('admin::partials.tinymce-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $model ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('product-custom-landings.model.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
            });
        });
    </script>
@endpush
