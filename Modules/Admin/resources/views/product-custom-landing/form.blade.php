@extends('admin::layouts.app')
@section('title', isset($productCustomLanding) ? 'Edit Product Custom Landing' : 'Create Product Custom Landing')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('product-custom-landings.index') }}">
            Product Custom Landings
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($productCustomLanding) ? route('product-custom-landings.update', base64_encode($productCustomLanding->id)) : route('product-custom-landings.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($productCustomLanding)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('product-custom-landings.index')"
                save-label="{{ isset($productCustomLanding) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
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
                                        value="{{ $productCustomLanding->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug*</label>
                                    <input type="text" class="form-control slug-output" id="slug" name="slug"
                                        value="{{ $productCustomLanding->slug ?? '' }}" data-model="ProductCustomLanding"
                                        data-id="{{ isset($productCustomLanding) ? base64_encode($productCustomLanding->id) : '' }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Product<span data-bs-toggle="tooltip"
                                            title="Only product which are variable and has main attribute values will be shown here.">
                                            <i class="fas fa-info-circle text-muted fs-6"></i>
                                        </span></label>
                                    <select class="form-select" name="product_id" id="product_id"
                                        data-placeholder="Choose Product" required>
                                        <option value=""></option>
                                        @isset($productCustomLanding)
                                            <option value="{{ $productCustomLanding->product_id }}" selected>
                                                {{ $productCustomLanding->product->name ?? '' }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($productCustomLanding) && $productCustomLanding->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($productCustomLanding) && $productCustomLanding->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Banner Content Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="banner_super_title" class="form-label">Banner Super Title</label>
                                    <input type="text" class="form-control" id="banner_super_title"
                                        name="banner_super_title"
                                        value="{{ $productCustomLanding->banner_super_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="banner_title" class="form-label">Banner Title</label>
                                    <input type="text" class="form-control" id="banner_title" name="banner_title"
                                        value="{{ $productCustomLanding->banner_title ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="banner_btn_text" class="form-label">Banner Btn Text</label>
                                    <input type="text" class="form-control" id="banner_btn_text" name="banner_btn_text"
                                        value="{{ $productCustomLanding->banner_btn_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Banner Button</label>
                                    <select class="form-select" name="banner_btn_show">
                                        <option value="1" @selected(isset($productCustomLanding) && $productCustomLanding->banner_btn_show == 1)>Enabled</option>
                                        <option value="0" @selected(isset($productCustomLanding) && $productCustomLanding->banner_btn_show == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="banner_bulk_order_btn_text" class="form-label">Banner Bulk Order Btn
                                        Text</label>
                                    <input type="text" class="form-control" id="banner_bulk_order_btn_text"
                                        name="banner_bulk_order_btn_text"
                                        value="{{ $productCustomLanding->banner_bulk_order_btn_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="banner_type" class="form-label">Banner Media Type*</label>
                                    <select class="form-select" id="banner_type" name="banner_type" required>
                                        <option value="image" @selected(isset($productCustomLanding) && $productCustomLanding->banner_type === 'image')>Image</option>
                                        <option value="video" @selected(isset($productCustomLanding) && $productCustomLanding->banner_type === 'video')>Video</option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="image-fields">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="banner_image" class="form-label">Image*</label>
                                            <input type="file" class="form-control filepond-input-crop"
                                                name="banner_image" id="banner_image"
                                                data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                            <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="banner_mob_image" class="form-label">Mobile Image*</label>
                                            <input type="file" class="form-control filepond-input-crop"
                                                name="banner_mob_image" id="banner_mob_image"
                                                data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                            <div class="text-muted">Dimensions: </div>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="banner_image_alt_text" class="form-label">Image Alt Text</label>
                                            <input type="text" class="form-control" id="banner_image_alt_text"
                                                name="banner_image_alt_text"
                                                value="{{ $productCustomLanding->banner_image_alt_text ?? '' }}"
                                                data-rule-maxlength="191">
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="video-fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="video_thumbnail_image" class="form-label">Thumbnail Image*</label>
                                            <input type="file" class="form-control filepond-input-crop"
                                                name="video_thumbnail_image" id="video_thumbnail_image"
                                                data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                            <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="banner_video" class="form-label">Video*</label>
                                            <input type="file" class="form-control filepond-input" name="banner_video"
                                                id="banner_video" data-accept="video/mp4, video/avi, video/mov"
                                                data-size="10MB" required>
                                            <div class="text-muted">Maximum file size: 10MB</div>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="video_thumbnail_image_mobile" class="form-label">
                                                Thumbnail Image Mobile*
                                            </label>
                                            <input type="file" class="form-control filepond-input-crop"
                                                name="video_thumbnail_image_mobile" id="video_thumbnail_image_mobile"
                                                data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                            <div class="text-muted">Dimensions: </div>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="video_mobile" class="form-label">Video Mobile*</label>
                                            <input type="file" class="form-control filepond-input" name="video_mobile"
                                                id="video_mobile" data-accept="video/mp4, video/avi, video/mov"
                                                data-size="10MB" required>
                                            <div class="text-muted">Maximum file size: 10MB</div>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Overview Content Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="overview_description" class="form-label">Overview Description</label>
                                    <textarea name="overview_description" id="overview_description" class="form-control" rows="3"
                                        data-rule-maxlength="5000">{{ $productCustomLanding->overview_description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="overview_quote_text" class="form-label">Overview Quote</label>
                                    <textarea name="overview_quote_text" id="overview_quote_text" class="form-control" rows="3"
                                        data-rule-maxlength="250">{{ $productCustomLanding->overview_quote_text ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="overview_quote_description" class="form-label text-capitalize">overview
                                        Quote description</label>
                                    <input type="text" class="form-control" id="overview_quote_description"
                                        name="overview_quote_description"
                                        value="{{ $productCustomLanding->overview_quote_description ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="overview_image" class="form-label">Overview Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="overview_image"
                                        id="overview_image" data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Productivity Content Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="productivity_super_title" class="form-label">Productivity Super
                                        Title</label>
                                    <input type="text" class="form-control" id="productivity_super_title"
                                        name="productivity_super_title"
                                        value="{{ $productCustomLanding->productivity_super_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="productivity_title" class="form-label">Productivity
                                        Title</label>
                                    <input type="text" class="form-control" id="productivity_title"
                                        name="productivity_title"
                                        value="{{ $productCustomLanding->productivity_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="productivity_btn_text" class="form-label">Productivity
                                        Button Text</label>
                                    <input type="text" class="form-control" id="productivity_btn_text"
                                        name="productivity_btn_text"
                                        value="{{ $productCustomLanding->productivity_btn_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="productivity_btn_text" class="form-label">Productivity
                                        Button Url</label>
                                    <input type="url" class="form-control" id="productivity_btn_url"
                                        name="productivity_btn_url"
                                        value="{{ $productCustomLanding->productivity_btn_url ?? '' }}"
                                        data-rule-maxlength="500">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="mindful_engineering_title" class="form-label">Mindful Engineering
                                        Title</label>
                                    <input type="text" class="form-control" id="mindful_engineering_title"
                                        name="mindful_engineering_title"
                                        value="{{ $productCustomLanding->mindful_engineering_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="find_the_right_product_title" class="form-label">Find the Right Product
                                        Title</label>
                                    <input type="text" class="form-control" id="find_the_right_product_title"
                                        name="find_the_right_product_title"
                                        value="{{ $productCustomLanding->find_the_right_product_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Height Calculator Content Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="height_calculator_title" class="form-label">Height Calculator
                                        Title</label>
                                    <input type="text" class="form-control" id="height_calculator_title"
                                        name="height_calculator_title"
                                        value="{{ $productCustomLanding->height_calculator_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="height_calculator_description" class="form-label">Height Calculator
                                        Description</label>
                                    <textarea name="height_calculator_description" id="height_calculator_description" class="form-control"
                                        rows="3" data-rule-maxlength="5000">{{ $productCustomLanding->height_calculator_description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="show_height_calculator" class="form-label">Show Height Calculator*</label>
                                    <select class="form-select" id="show_height_calculator" name="show_height_calculator"
                                        required>
                                        <option value="1" @selected(isset($productCustomLanding) && $productCustomLanding->show_height_calculator === 1)>Enabled</option>
                                        <option value="0" @selected(isset($productCustomLanding) && $productCustomLanding->show_height_calculator === 0)>Disabled</option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sitting_desk_image" class="form-label">Sitting Desk Image</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="sitting_desk_image" id="sitting_desk_image"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 500 x 401 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="standing_desk_image" class="form-label">Standing Desk Image</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="standing_desk_image" id="standing_desk_image"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 500 x 401 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Assembly Content Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="assembly_super_title" class="form-label">Assembly Super
                                        Title</label>
                                    <input type="text" class="form-control" id="assembly_super_title"
                                        name="assembly_super_title"
                                        value="{{ $productCustomLanding->assembly_super_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="assembly_title" class="form-label">Assembly
                                        Title</label>
                                    <input type="text" class="form-control" id="assembly_title" name="assembly_title"
                                        value="{{ $productCustomLanding->assembly_title ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="assembly_support_text" class="form-label">Assembly
                                        Support Text</label>
                                    <input type="text" class="form-control" id="assembly_support_text"
                                        name="assembly_support_text"
                                        value="{{ $productCustomLanding->assembly_support_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="assembly_help_text" class="form-label">Assembly
                                        Help Text</label>
                                    <input type="text" class="form-control" id="assembly_help_text"
                                        name="assembly_help_text"
                                        value="{{ $productCustomLanding->assembly_help_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="show_assembly_section" class="form-label">Show Assembly Section*</label>
                                    <select class="form-select" id="show_assembly_section" name="show_assembly_section"
                                        required>
                                        <option value="1" @selected(isset($productCustomLanding) && $productCustomLanding->show_assembly_section === 1)>Enabled</option>
                                        <option value="0" @selected(isset($productCustomLanding) && $productCustomLanding->show_assembly_section === 0)>Disabled</option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="assembly_image" class="form-label">Assembly Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="assembly_image"
                                        id="assembly_image" data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 1369 x 1159 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-admin::meta-tags :meta-data="$productCustomLanding ?? null" />
            </div>
        </div>
    </form>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.choices-setup')
@include('admin::partials.select2-setup')
@include('admin::partials.slug-setup')
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $productCustomLanding ?? null])
@push('js')
    <script>
        $(document).ready(function() {
            $('#product_id').select2({
                ajax: {
                    url: "{{ route('get-products') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            type: 'variable_with_main_attributes'
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
            });

            const toggleMediaFieldsVisibility = () => {
                const selectedMediaType = $('#banner_type').val();

                $('#image-fields, #video-fields').hide();

                ['#image', '#image_mobile', '#image_alt_text', '#video_thumbnail_image',
                    '#video_thumbnail_image_mobile', '#video', '#video_mobile'
                ]
                .forEach(field => {
                    $(field).siblings('.error-block').html('');
                });

                if (selectedMediaType === 'image') {
                    $('#image-fields').show();
                } else if (selectedMediaType === 'video') {
                    $('#video-fields').show();
                }
            };

            $('#banner_type').change(toggleMediaFieldsVisibility);
            toggleMediaFieldsVisibility();

            $('form').customValidate({
                rules: {
                    banner_image: {
                        required: function() {
                            return $('#banner_type').val() === 'image';
                        }
                    },
                    banner_mob_image: {
                        required: function() {
                            return $('#banner_type').val() === 'image';
                        }
                    },
                    video_thumbnail_image: {
                        required: function() {
                            return $('#banner_type').val() === 'video';
                        }
                    },
                    video_thumbnail_image_mobile: {
                        required: function() {
                            return $('#banner_type').val() === 'video';
                        }
                    },
                    banner_video: {
                        required: function() {
                            return $('#banner_type').val() === 'video';
                        }
                    },
                    video_mobile: {
                        required: function() {
                            return $('#banner_type').val() === 'video';
                        }
                    },
                },
                successRoute: "{{ route('product-custom-landings.index') }}"
            });
        });
    </script>
@endpush
