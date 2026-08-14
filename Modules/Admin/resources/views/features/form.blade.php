@extends('admin::layouts.app')
@section('title', isset($feature) ? 'Edit Features' : 'Create Features')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('features.index') }}">Features</a>
    </li>
@endsection
@section('content')
    <form method="POST"
        action="{{ isset($feature) ? route('features.update', base64_encode($feature->id)) : route('features.store') }}">
        @csrf
        @isset($feature)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('features.index')" save-label="{{ isset($feature) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title*</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                        value="{{ $feature->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label">Icon*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="icon"
                                        id="icon" data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml"
                                        required>
                                    <div class="text-muted">Dimensions: 24 x 24 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="icon_alt_text" class="form-label">Icon Alt Text</label>
                                    <input type="text" name="icon_alt_text" class="form-control" id="icon_alt_text"
                                        value="{{ $feature->icon_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $feature->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($feature) && $feature->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($feature) && $feature->status == 0)>Disabled</option>
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
@include('admin::partials.tinymce-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $feature ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('features.index') }}"
            });
        });
    </script>
@endpush
