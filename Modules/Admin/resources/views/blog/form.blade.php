@extends('admin::layouts.app')
@section('title', isset($blog) ? 'Edit Blog' : 'Create Blog')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('blogs.index') }}">
            Blogs
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ isset($blog) ? route('blogs.update', base64_encode($blog->id)) : route('blogs.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($blog)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('blogs.index')" save-label="{{ isset($blog) ? 'Update' : 'Create' }}" />
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
                                    <input type="text" class="form-control source-input" id="title" name="title"
                                        value="{{ $blog->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug*</label>
                                    <input type="text" class="form-control slug-output" id="slug" name="slug"
                                        value="{{ $blog->slug ?? '' }}" data-model="Blog"
                                        data-id="{{ isset($blog) ? base64_encode($blog->id) : '' }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sub_title" class="form-label">Sub Title</label>
                                    <input type="text" class="form-control" id="sub_title" name="sub_title"
                                        value="{{ $blog->sub_title ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="category" name="category"
                                        value="{{ $blog->category ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="short_content" class="form-label">Short Content*</label>
                                    <textarea name="short_content" id="short_content" class="form-control" data-rule-maxlength="5000" required>{{ $blog->short_content ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">Content*</label>
                                    <textarea name="content" id="content" class="form-control tinymce" data-rule-maxlength="10000" required>{{ $blog->content ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" class="form-control" id="author" name="author"
                                        data-rule-maxlength="191" value="{{ $blog->author ?? '' }}">
                                    <span class="error-block"></span>
                                </div>
                            </div> --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="published_on" class="form-label">Published On*</label>
                                    <input type="text" class="form-control" id="published_on" name="published_on"
                                        value="{{ $blog->published_on ?? '' }}" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-check-label" for="featured">Featured Article</label>
                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" name="featured" class="form-check-input" id="featured"
                                            {{ isset($blog) && $blog->featured ? 'checked' : '' }}>
                                    </div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($blog) && $blog->status == 1)>Published</option>
                                        <option value="0" @selected(isset($blog) && $blog->status == 0)>Draft</option>
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
                        <h3 class="card-title">Search Keywords</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="search_keywords" class="form-label">Search Keywords</label>
                                    <input id="search_keywords" name="search_keywords[]" class="form-control"
                                        value="{{ $blog->search_keywords ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Associated Blogs</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="related_blogs" class="form-label">
                                        Related Blogs
                                    </label>
                                    <input type="hidden" name="related_blogs" value="">
                                    <select class="form-control" id="related_blogs" name="related_blogs[]"
                                        data-placeholder="Select Blogs" data-exclude-id="{{ $blog->id ?? '' }}" multiple>
                                        <option></option>
                                        @isset($blog)
                                            @foreach ($relatedBlogs as $relatedBlog)
                                                <option value="{{ $relatedBlog->id }}" selected>
                                                    {{ $relatedBlog->title }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        id="image" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                        data-width="100" data-height="100" required>
                                    <div class="text-muted">Dimensions: 430 x 250 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $blog->image_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-admin::meta-tags :meta-data="$blog ?? null" />
        </div>
    </form>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.slug-setup')
@include('admin::partials.select2-setup')
@include('admin::partials.tinymce-setup')
@include('admin::partials.datepicker-setup')
@include('admin::partials.choices-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $blog ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initializeFlatpickr("#published_on", {
                altInput: true,
                altFormat: "d M Y",
                dateFormat: "Y-m-d",
                defaultDate: document.querySelector('#published_on').value || 'today',
                maxDate: "today"
            });
            initializeChoices("#search_keywords", {
                delimiter: ',',
                editItems: true,
                maxItemCount: 10,
                removeItemButton: true,
                duplicateItemsAllowed: false,
                placeholder: true,
                placeholderValue: 'Add keywords',
            });
        });
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('blogs.index') }}"
            });

            $('#related_blogs').select2({
                placeholder: $('#related_blogs').data('placeholder'),
                maximumSelectionLength: 10,
                ajax: {
                    url: "{{ route('get-blogs') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            exclude_id: $('#related_blogs').data('exclude-id'),
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.title
                            })),
                            pagination: {
                                more: data.next_page_url !== null
                            }
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                    }
                }
            });
        });
    </script>
@endpush
