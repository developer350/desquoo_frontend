@extends('admin::layouts.app')
@section('title', isset($spaceCategory) ? 'Edit Space Category' : 'Create Space Category')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('space-categories.index') }}">
            Space Categories
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($spaceCategory) ? route('space-categories.update', base64_encode($spaceCategory->id)) : route('space-categories.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($spaceCategory)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('space-categories.index')" save-label="{{ isset($spaceCategory) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $spaceCategory->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="state_id" class="form-label">State*</label>
                                    <select class="form-control select2" id="state_id" name="state_id"
                                        data-placeholder="Select State" required>
                                        <option></option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" @selected(isset($spaceCategory) && $spaceCategory->state_id == $state->id)>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city_id" class="form-label">City*</label>
                                    <select class="form-control" id="city_id" name="city_id"
                                        data-placeholder="Select City" required>
                                        <option></option>
                                        @isset($cities)
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" @selected(isset($spaceCategory) && $spaceCategory->city_id == $city->id)>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $spaceCategory->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($spaceCategory) && $spaceCategory->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($spaceCategory) && $spaceCategory->status == 0)>Disabled</option>
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
                                        id="image" data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $spaceCategory->image_alt_text ?? '' }}" data-rule-maxlength="191">
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
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $spaceCategory ?? null])
@include('admin::partials.select2-setup')
@include('admin::partials.location-dropdown')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('space-categories.index') }}"
            });
        });
    </script>
@endpush
