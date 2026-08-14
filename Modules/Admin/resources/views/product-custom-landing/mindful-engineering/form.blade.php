@extends('admin::layouts.app')
@section('title', isset($mindfulEngineering) ? 'Edit PCL Mindful Engineering' : 'Create PCL Mindful Engineering')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('product-custom-landings.index') }}">
            Product Custom Landings
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('product-custom-landings.mindful-engineering.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}">
            PCL Mindful Engineering
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($mindfulEngineering) ? route('product-custom-landings.mindful-engineering.update', ['product_custom_landing' => base64_encode($productCustomLanding->id), 'mindful_engineering' => base64_encode($mindfulEngineering->id)]) : route('product-custom-landings.mindful-engineering.store',['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($mindfulEngineering)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('product-custom-landings.mindful-engineering.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)])" save-label="{{ isset($mindfulEngineering) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $mindfulEngineering->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description*</label>
                                    <textarea name="description" id="description" class="form-control" data-rule-maxlength="500" required>{{ $mindfulEngineering->description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $mindfulEngineering->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($mindfulEngineering) && $mindfulEngineering->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($mindfulEngineering) && $mindfulEngineering->status == 0)>Disabled</option>
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
                                        id="image" data-width="512" data-height="512"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 512 x 512 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $mindfulEngineering->image_alt_text ?? '' }}" data-rule-maxlength="191">
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
@include('admin::partials.filepond-setup', ['mediaSource' => $mindfulEngineering ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('product-custom-landings.mindful-engineering.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
            });
        });
    </script>
@endpush
