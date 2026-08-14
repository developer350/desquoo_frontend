@extends('admin::layouts.app')
@section('title', isset($review) ? 'Edit Google Reviews' : 'Create Google Reviews')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('google-reviews.index') }}">Google Reviews</a>
    </li>
@endsection
@section('content')
    <form method="POST"
        action="{{ isset($review) ? route('google-reviews.update', base64_encode($review->id)) : route('google-reviews.store') }}">
        @csrf
        @isset($review)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('google-reviews.index')" save-label="{{ isset($review) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="{{ $review->name ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="profession" class="form-label">Profession</label>
                                    <input type="text" name="profession" class="form-control" id="profession"
                                        value="{{ $review->profession ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="review" class="form-label">Review</label>
                                    <textarea name="review" id="review" class="form-control" data-rule-maxlength="5000">{{ $review->review ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="rating" class="form-label">Rating*</label>
                                    <input type="number" min="1" max="5" step="1" name="rating"
                                        class="form-control" id="rating" required value="{{ $review->rating ?? '' }}"
                                        data-rule-maxlength="1">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="avatar" class="form-label">Avatar</label>
                                    <input type="file" class="form-control filepond-input-crop" name="avatar"
                                        id="avatar"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                                    <div class="text-muted">Dimensions: 64 x 64 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="avatar_alt_text" class="form-label">Avatar Alt Text</label>
                                    <input type="text" name="avatar_alt_text" class="form-control" id="avatar_alt_text"
                                        value="{{ $review->avatar_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Show in Bulk Order</label>
                                    <select class="form-select" name="show_in_bulk_order">
                                        <option value="1" @selected(isset($review) && $review->show_in_bulk_order == 1)>Show</option>
                                        <option value="0" @selected(isset($review) && $review->show_in_bulk_order == 0)>Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $review->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($review) && $review->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($review) && $review->status == 0)>Disabled</option>
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
@include('admin::partials.filepond-setup', ['mediaSource' => $review ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('google-reviews.index') }}"
            });
        });
    </script>
@endpush
