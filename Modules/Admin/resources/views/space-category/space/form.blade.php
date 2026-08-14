@extends('admin::layouts.app')
@section('title', isset($space) ? 'Edit Space' : 'Create Space')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('space-categories.index') }}">
            Space Categories
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('space-categories.spaces.index', ['space_category' => base64_encode($spaceCategory->id)]) }}">
            Spaces
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($space) ? route('space-categories.spaces.update', ['space_category' => base64_encode($spaceCategory->id), 'space' => base64_encode($space->id)]) : route('space-categories.spaces.store', base64_encode($spaceCategory->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($space)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('space-categories.spaces.index', ['space_category' => base64_encode($spaceCategory->id)])" save-label="{{ isset($space) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $space->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $space->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Show on Home Page</label>
                                    <select class="form-select" name="is_home">
                                        <option value="1" @selected(isset($space) && $space->is_home == 1)>
                                            Yes
                                        </option>
                                        <option value="0" @selected(isset($space) && $space->is_home == 0)>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($space) && $space->status == 1)>
                                            Enabled
                                        </option>
                                        <option value="0" @selected(isset($space) && $space->status == 0)>
                                            Disabled
                                        </option>
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
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="image"
                                        id="image" data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: </div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="" data-rule-maxlength="191">
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
@include('admin::partials.filepond-setup', ['mediaSource' => $space ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('space-categories.spaces.index', ['space_category' => base64_encode($spaceCategory->id)]) }}"
            });
        });
    </script>
@endpush
