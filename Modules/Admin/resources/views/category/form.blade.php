@extends('admin::layouts.app')
@section('title', isset($category) ? 'Edit Category' : 'Create Category')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('categories.index') }}">
            Categories
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($category) ? route('categories.update', base64_encode($category->id)) : route('categories.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($category)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('categories.index')" save-label="{{ isset($category) ? 'Update' : 'Create' }}" />
            <x-admin::banner :banner-data="$category ?? null" />
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
                                        value="{{ $category->name ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parent_id" class="form-label">Parent</label>
                                    <select class="form-control" id="parent_id" name="parent_id"
                                        data-placeholder="Select Parent Category"
                                        data-exclude-id="{{ $category->id ?? '' }}">
                                        <option></option>
                                        @if (isset($category?->parent))
                                            <option value="{{ $category->parent_id }}" selected>
                                                {{ $category->parent->name }}
                                            </option>
                                        @endif
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $category->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($category) && $category->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($category) && $category->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Is New ?</label>
                                    <select class="form-select" name="is_new" id="is_new"
                                        {{ isset($category) && $category->parent_id ? '' : 'disabled' }}>
                                        <option value="1" @selected(isset($category) && $category->is_new == 1)>Enabled</option>
                                        <option value="0" @selected(isset($category) && $category->is_new == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Show In Menu <span data-bs-toggle="tooltip"
                                            title="You can show maximum 3 categories in menu">
                                            <i class="fas fa-info-circle text-muted fs-6"></i>
                                        </span></label>
                                    <select class="form-select" name="show_in_menu" id="show_in_menu"
                                        {{ isset($category) && $category->parent_id ? 'disabled' : '' }}>
                                        <option value="1" @selected(isset($category) && $category->show_in_menu == 1)>Enabled</option>
                                        <option value="0" @selected(isset($category) && $category->show_in_menu == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Show In Homepage <span data-bs-toggle="tooltip"
                                            title="You can show maximum 3 categories in homepage">
                                            <i class="fas fa-info-circle text-muted fs-6"></i>
                                        </span> </label>
                                    <select class="form-select" name="show_in_homepage" id="show_in_homepage"
                                        {{ isset($category) && $category->parent_id ? 'disabled' : '' }}>
                                        <option value="1" @selected(isset($category) && $category->show_in_homepage == 1)>Enabled</option>
                                        <option value="0" @selected(isset($category) && $category->show_in_homepage == 0)>Disabled</option>
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
                                    <div class="text-muted">Dimensions: 272 x 199 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $category->image_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Home Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="home_image"
                                        id="home_image"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                                    <div class="text-muted">Dimensions: 720 x 720 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-admin::meta-tags :meta-data="$category ?? null" />
        </div>
    </form>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.select2-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $category ?? null])
@include('admin::partials.choices-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('#parent_id').select2({
                allowClear: true,
                placeholder: $('#parent_id').data('placeholder'),
                ajax: {
                    url: "{{ route('get-categories') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            exclude_id: $('#parent_id').data('exclude-id'),
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.name
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
            }).on('change', function(e) {
                let value = $(this).val();

                if (value == null || value === '') {
                    $('#show_in_menu').attr('disabled', false);
                    $('#show_in_homepage').attr('disabled', false);
                    $('#is_new').val('0').attr('disabled', true);
                } else {
                    $('#show_in_menu').val('0').attr('disabled', true);
                    $('#show_in_homepage').val('0').attr('disabled', true);
                    $('#is_new').attr('disabled', false);
                }
            });

            $('form').customValidate({
                rules: {
                    home_image: {
                        required: function() {
                            return $('#show_in_homepage').val() === '1';
                        },
                    }
                },
                successRoute: "{{ route('categories.index') }}"
            });
        });
    </script>
@endpush
