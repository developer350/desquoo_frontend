@extends('admin::layouts.app')
@section('title', isset($productGallery) ? 'Edit Product Gallery' : 'Create Product Gallery')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('products.galleries.index', base64_encode($product->id)) }}">
            Product Gallery
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($productGallery) ? route('products.galleries.update', ['product' => base64_encode($product->id), 'gallery' => base64_encode($productGallery->id)]) : route('products.galleries.store', base64_encode($product->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($productGallery)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('products.galleries.index', base64_encode($product->id))" save-label="{{ isset($productGallery) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Media Upload</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="media_type" class="form-label">Media Type*</label>
                                    <select class="form-select" id="media_type" name="media_type" required>
                                        <option value="image" @selected(isset($productGallery) && $productGallery->media_type === 'image')>Image</option>
                                        <option value="video" @selected(isset($productGallery) && $productGallery->media_type === 'video')>Video</option>
                                        <option value="video_url" @selected(isset($productGallery) && $productGallery->media_type === 'video_url')>Video URL</option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 image-fields">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="image"
                                        id="image" data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 1080 x 1080 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 image-fields">
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $productGallery->image_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 video-fields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="video_thumbnail_image" class="form-label">Thumbnail Image*</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="video_thumbnail_image" id="video_thumbnail_image"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 1080 x 1080 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 video-fields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="video" class="form-label">Video*</label>
                                    <input type="file" class="form-control filepond-input" name="video" id="video"
                                        data-accept="video/mp4, video/avi, video/mov" data-size="10MB" required>
                                    <div class="text-muted">Maximum file size: 10MB</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 video-url-field" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="video_url_thumbnail_image" class="form-label">Thumbnail Image*</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="video_url_thumbnail_image" id="video_url_thumbnail_image"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 1080 x 1080 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 video-url-field" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="video_url" class="form-label">Video URL*</label>
                                    <input type="text" class="form-control" id="video_url" name="video_url"
                                        value="{{ $productGallery->video_url ?? '' }}" data-rule-maxlength="500" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $productGallery->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($productGallery) && $productGallery->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($productGallery) && $productGallery->status == 0)>Disabled</option>
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
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $productGallery ?? null])
@push('js')
    <script>
        $(document).ready(function() {
            const toggleMediaFieldsVisibility = () => {
                const selectedMediaType = $('#media_type').val();

                $('.image-fields, .video-fields, .video-url-field').hide();

                ['#image', '#image_alt_text', '#video_thumbnail_image', '#video',
                    '#video_url_thumbnail_image',
                    '#video_url'
                ].forEach(field => {
                    $(field).siblings('.error-block').html('');
                });

                if (selectedMediaType === 'image') {
                    $('.image-fields').show();
                } else if (selectedMediaType === 'video') {
                    $('.video-fields').show();
                } else if (selectedMediaType === 'video_url') {
                    $('.video-url-field').show();
                }
            };

            $('#media_type').change(toggleMediaFieldsVisibility);
            toggleMediaFieldsVisibility();

            $('form').customValidate({
                rules: {
                    image: {
                        required: function() {
                            return $('#media_type').val() === 'image';
                        }
                    },
                    video_thumbnail_image: {
                        required: function() {
                            return $('#media_type').val() === 'video';
                        }
                    },
                    video: {
                        required: function() {
                            return $('#media_type').val() === 'video';
                        }
                    },
                    video_url_thumbnail_image: {
                        required: function() {
                            return $('#media_type').val() === 'video_url';
                        }
                    },
                    video_url: {
                        required: function() {
                            return $('#media_type').val() === 'video_url';
                        },
                        url: true,
                        maxlength: 500
                    }
                },
                successRoute: "{{ route('products.galleries.index', base64_encode($product->id)) }}"
            });
        });
    </script>
@endpush
