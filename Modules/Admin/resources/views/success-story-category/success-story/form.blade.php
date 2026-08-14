@extends('admin::layouts.app')
@section('title', isset($successStory) ? 'Edit Success Story' : 'Create Success Story')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('success-story-categories.index') }}">
            Success Story Categories
        </a>
    </li>
    <li class="breadcrumb-item">
        <a
            href="{{ route('success-story-categories.success-stories.index', ['success_story_category' => base64_encode($successStoryCategory->id)]) }}">
            Success Stories
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($successStory) ? route('success-story-categories.success-stories.update', ['success_story_category' => base64_encode($successStoryCategory->id), 'success_story' => base64_encode($successStory->id)]) : route('success-story-categories.success-stories.store', base64_encode($successStoryCategory->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($successStory)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('success-story-categories.success-stories.index', [
                'success_story_category' => base64_encode($successStoryCategory->id),
            ])" save-label="{{ isset($successStory) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $successStory->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $successStory->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($successStory) && $successStory->status == 1)>
                                            Enabled
                                        </option>
                                        <option value="0" @selected(isset($successStory) && $successStory->status == 0)>
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
@include('admin::partials.filepond-setup', ['mediaSource' => $successStory ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('success-story-categories.success-stories.index', ['success_story_category' => base64_encode($successStoryCategory->id)]) }}"
            });
        });
    </script>
@endpush
