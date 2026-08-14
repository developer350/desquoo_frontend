@extends('admin::layouts.app')
@section('title', isset($socialLink) ? 'Edit Social Link' : 'Create Social Link')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('social-links.index') }}">
            Social Links
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($socialLink) ? route('social-links.update', base64_encode($socialLink->id)) : route('social-links.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($socialLink)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('social-links.index')" save-label="{{ isset($socialLink) ? 'Update' : 'Create' }}" />
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Main Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name*</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $socialLink->name ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="url" class="form-label">URL*</label>
                                    <input type="url" class="form-control" id="url" name="url"
                                        value="{{ $socialLink->url ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $socialLink->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($socialLink) && $socialLink->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($socialLink) && $socialLink->status == 0)>Disabled</option>
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
                                    <label for="icon" class="form-label">Icon*</label>
                                    <input type="file" class="form-control filepond-input" name="icon" id="icon"
                                        data-accept="image/svg+xml" required>
                                    <div class="text-muted">Dimensions: 24 x 24 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@include('admin::partials.filepond-setup', ['mediaSource' => $socialLink ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('social-links.index') }}"
            });
        });
    </script>
@endpush
