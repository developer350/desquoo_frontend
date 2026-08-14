@extends('admin::layouts.app')
@section('title', isset($slider) ? 'Edit Slider' : 'Create Slider')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('sliders.index') }}">
            Sliders
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($slider) ? route('sliders.update', base64_encode($slider->id)) : route('sliders.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($slider)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('sliders.index')" save-label="{{ isset($slider) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Main Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title*</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $slider->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $slider->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($slider) && $slider->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($slider) && $slider->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Action Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="action_type" class="form-label">Action Type*</label>
                                    <select class="form-select" id="action_type" name="action_type" required>
                                        <option value="none" @selected(isset($slider) && $slider->action_type === 'none')>None</option>
                                        <option value="url" @selected(isset($slider) && $slider->action_type === 'url')>URL</option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 action-fields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="action_title" class="form-label">Action Title*</label>
                                    <input type="text" class="form-control" id="action_title" name="action_title"
                                        value="{{ $slider->action_title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4 action-fields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="action_url" class="form-label">Action URL*</label>
                                    <input type="text" class="form-control" id="action_url" name="action_url"
                                        value="{{ $slider->action_url ?? '' }}" data-rule-maxlength="500" required>
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
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="media_type" class="form-label">Media Type*</label>
                                    <select class="form-select" id="media_type" name="media_type" required>
                                        <option value="image" @selected(isset($slider) && $slider->media_type === 'image')>Image</option>
                                        <option value="video" @selected(isset($slider) && $slider->media_type === 'video')>Video</option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12" id="image-fields">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="image" class="form-label">Image*</label>
                                            <input type="file" class="form-control filepond-input-crop" name="image"
                                                id="image" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                                required>
                                            <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="image_mobile" class="form-label">Mobile Image*</label>
                                            <input type="file" class="form-control filepond-input-crop"
                                                name="image_mobile" id="image_mobile"
                                                data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                            <div class="text-muted">Dimensions: 390 x 694 px</div>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                            <input type="text" class="form-control" id="image_alt_text"
                                                name="image_alt_text" value="{{ $slider->image_alt_text ?? '' }}"
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
                                            <label for="video" class="form-label">Video*</label>
                                            <input type="file" class="form-control filepond-input" name="video"
                                                id="video" data-accept="video/mp4, video/avi, video/mov"
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
                                            <div class="text-muted">Dimensions: 390 x 694 px</div>
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
            </div>
        </div>
    </form>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $slider ?? null])
@push('js')
    <script>
        $(document).ready(function() {

            const toggleActionFieldsVisibility = () => {
                const selectedActionType = $('#action_type').val();

                if (selectedActionType === 'url') {
                    $('.action-fields').show();
                } else {
                    $('.action-fields').hide();
                    $('#action_title, #action_url').siblings('.error-block').html('');
                }
            };

            $('#action_type').change(toggleActionFieldsVisibility);
            toggleActionFieldsVisibility();

            const toggleMediaFieldsVisibility = () => {
                const selectedMediaType = $('#media_type').val();

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

            $('#media_type').change(toggleMediaFieldsVisibility);
            toggleMediaFieldsVisibility();

            $('form').customValidate({
                rules: {
                    action_title: {
                        required: function() {
                            return $('#action_type').val() === 'url';
                        },
                        maxlength: 191
                    },
                    action_url: {
                        required: function() {
                            return $('#action_type').val() === 'url';
                        },
                        url: true,
                        maxlength: 500
                    },
                    image: {
                        required: function() {
                            return $('#media_type').val() === 'image';
                        }
                    },
                    image_mobile: {
                        required: function() {
                            return $('#media_type').val() === 'image';
                        }
                    },
                    video_thumbnail_image: {
                        required: function() {
                            return $('#media_type').val() === 'video';
                        }
                    },
                    video_thumbnail_image_mobile: {
                        required: function() {
                            return $('#media_type').val() === 'video';
                        }
                    },
                    video: {
                        required: function() {
                            return $('#media_type').val() === 'video';
                        }
                    },
                    video_mobile: {
                        required: function() {
                            return $('#media_type').val() === 'video';
                        }
                    },
                },
                successRoute: "{{ route('sliders.index') }}"
            });
        });
    </script>
@endpush
