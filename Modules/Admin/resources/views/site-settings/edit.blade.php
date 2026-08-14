@extends('admin::layouts.app')
@section('title', 'Edit Site Settings')
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ route('site-settings.update', base64_encode($siteSettings->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <x-admin::action-buttons save-label="Update" />
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Contact Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="address" class="form-label">Address*</label>
                                            <textarea name="address" id="address" class="form-control" data-rule-maxlength="2000" required>{{ $siteSettings->address }}</textarea>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">Email*</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $siteSettings->email }}" data-rule-maxlength="191"
                                                data-rule-emailOnly="true" required>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone_number" class="form-label">Phone Number*</label>
                                            <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                                value="{{ $siteSettings->phone_number }}" data-rule-maxlength="191"
                                                required>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                            <input type="tel" class="form-control" id="whatsapp_number"
                                                name="whatsapp_number" data-rule-maxlength="191"
                                                value="{{ $siteSettings->whatsapp_number }}">
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="map_link" class="form-label">Map Link</label>
                                            <input type="url" class="form-control" id="map_link" name="map_link"
                                                value="{{ $siteSettings->map_link }}" data-rule-maxlength="191">
                                            <div class="text-muted">
                                                Note: Use the Google Maps "Share" link (e.g., https://goo.gl/maps/...).
                                            </div>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="map_iframe" class="form-label">Map Iframe</label>
                                            <textarea name="map_iframe" id="map_iframe" class="form-control" data-rule-maxlength="2000">{{ $siteSettings->map_iframe }}</textarea>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="working_hours" class="form-label">Working Hours*</label>
                                            <input type="text" class="form-control" id="working_hours"
                                                name="working_hours" data-rule-maxlength="191"
                                                value="{{ $siteSettings->working_hours }}" required>
                                            <span class="error-block"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="customer_care_info" class="form-label">Customer Care Info</label>
                                            <input type="text" class="form-control" id="customer_care_info"
                                                name="customer_care_info" data-rule-maxlength="500"
                                                value="{{ $siteSettings->customer_care_info }}">
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Media Upload</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="header_logo" class="form-label">Header Logo</label>
                                    <input type="file" class="form-control filepond-input-crop" name="header_logo"
                                        id="header_logo"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                                    <div class="text-muted">Dimensions: 112 x 24 px </div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="header_logo_alt_text" class="form-label">Header Logo Alt Text</label>
                                    <input type="text" class="form-control" id="header_logo_alt_text"
                                        name="header_logo_alt_text"
                                        value="{{ $siteSettings->header_logo_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="header_mobile_logo" class="form-label">Header Mobile Logo</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="header_mobile_logo" id="header_mobile_logo"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                                    <div class="text-muted">Dimensions: 74 x 16 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="header_mobile_logo_alt_text" class="form-label">Header Mobile Logo Alt
                                        Text</label>
                                    <input type="text" class="form-control" id="header_mobile_logo_alt_text"
                                        name="header_mobile_logo_alt_text"
                                        value="{{ $siteSettings->header_mobile_logo_alt_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="footer_logo" class="form-label">Footer Logo</label>
                                    <input type="file" class="form-control filepond-input-crop" name="footer_logo"
                                        id="footer_logo"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                                    <div class="text-muted">Dimensions: 427 x 93 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="footer_logo_alt_text" class="form-label">Footer Logo Alt Text</label>
                                    <input type="text" class="form-control" id="footer_logo_alt_text"
                                        name="footer_logo_alt_text"
                                        value="{{ $siteSettings->footer_logo_alt_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="auth_image" class="form-label">Auth Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="auth_image"
                                        id="auth_image" data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="auth_image_alt_text" class="form-label">Auth Image Alt Text</label>
                                    <input type="text" class="form-control" id="auth_image_alt_text"
                                        name="auth_image_alt_text" value="{{ $siteSettings->auth_image_alt_text ?? '' }}"
                                        data-rule-maxlength="191">
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
@include('admin::partials.tinymce-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $siteSettings ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                rules: {
                    map_iframe: {
                        iframeElement: true
                    }
                },
                successRoute: "{{ route('site-settings.edit') }}"
            });
        });
    </script>
@endpush
