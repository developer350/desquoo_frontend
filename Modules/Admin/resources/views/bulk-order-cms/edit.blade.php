@extends('admin::layouts.app')
@section('title', 'Edit Bulk Order Cms')
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ route('bulk-order-cms.update', base64_encode($bulkOrderCms->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <x-admin::action-buttons save-label="Update" />
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Banner Section</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="expert_image" class="form-label">Expert Avatar*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="expert_image"
                                        id="expert_image" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                        data-width="40" data-height="40" required>
                                    <div class="text-muted">Dimensions: 40x40 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="want_to_chat_link" class="form-label">Want to Chat Link</label>
                                    <input type="url" class="form-control" id="want_to_chat_link"
                                        name="want_to_chat_link" value="{{ $bulkOrderCms->want_to_chat_link ?? '' }}"
                                        data-rule-maxlength="500">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="want_to_chat_text" class="form-label">Want to Chat Text</label>
                                    <input type="text" class="form-control" id="want_to_chat_text"
                                        name="want_to_chat_text" value="{{ $bulkOrderCms->want_to_chat_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="show_want_to_chat" class="form-label">Show Want to Chat?</label>
                                    <select name="show_want_to_chat" id="show_want_to_chat" class="form-control">
                                        <option value="1" {{ $bulkOrderCms->show_want_to_chat == 1 ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ $bulkOrderCms->show_want_to_chat == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="want_to_talk_number" class="form-label">Want to Talk Number</label>
                                    <input type="text" class="form-control" id="want_to_talk_number"
                                        name="want_to_talk_number" value="{{ $bulkOrderCms->want_to_talk_number ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="show_want_to_talk" class="form-label">Show Want to Talk?</label>
                                    <select name="show_want_to_talk" id="show_want_to_talk" class="form-control">
                                        <option value="1" {{ $bulkOrderCms->show_want_to_talk == 1 ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ $bulkOrderCms->show_want_to_talk == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_one_title" class="form-label">Section One Title*</label>
                                    <input type="text" class="form-control" id="section_one_title"
                                        name="section_one_title" value="{{ $bulkOrderCms->section_one_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_two_title" class="form-label">Section Two Title*</label>
                                    <input type="text" class="form-control" id="section_two_title"
                                        name="section_two_title" value="{{ $bulkOrderCms->section_two_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_three_title" class="form-label">Section Three Title*</label>
                                    <input type="text" class="form-control" id="section_three_title"
                                        name="section_three_title" value="{{ $bulkOrderCms->section_three_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_three_description" class="form-label">
                                        Section Three Description*
                                    </label>
                                    <textarea name="section_three_description" id="section_three_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $bulkOrderCms->section_three_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_four_title" class="form-label">Section Four Title*</label>
                                    <input type="text" class="form-control" id="section_four_title"
                                        name="section_four_title" value="{{ $bulkOrderCms->section_four_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_four_description" class="form-label">
                                        Section Four Description*
                                    </label>
                                    <textarea name="section_four_description" id="section_four_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $bulkOrderCms->section_four_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_five_title" class="form-label">Section Five Title*</label>
                                    <input type="text" class="form-control" id="section_five_title"
                                        name="section_five_title" value="{{ $bulkOrderCms->section_five_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_five_description" class="form-label">
                                        Section Five Description*
                                    </label>
                                    <textarea name="section_five_description" id="section_five_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $bulkOrderCms->section_five_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_five_button_title" class="form-label">Section Five Buton
                                        Title*</label>
                                    <input type="text" class="form-control" id="section_five_button_title"
                                        name="section_five_button_title"
                                        value="{{ $bulkOrderCms->section_five_button_title }}" data-rule-maxlength="191"
                                        required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_five_image_alt_text" class="form-label">Section Five Image Alt
                                        Text</label>
                                    <input type="text" class="form-control" id="section_five_image_alt_text"
                                        name="section_five_image_alt_text"
                                        value="{{ $bulkOrderCms->section_five_image_alt_text }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_five_image" class="form-label">Section Five Image*</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="section_five_image" id="section_five_image" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                        required>
                                    <div class="text-muted">Dimensions: 1790 x 560 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_six_title" class="form-label">Section Six Title*</label>
                                    <input type="text" class="form-control" id="section_six_title"
                                        name="section_six_title" value="{{ $bulkOrderCms->section_six_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_six_description" class="form-label">
                                        Section Six Description*
                                    </label>
                                    <textarea name="section_six_description" id="section_six_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $bulkOrderCms->section_six_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Banner</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner_super_title" class="form-label">Super Title</label>
                                        <input type="text" class="form-control" id="banner_super_title"
                                            name="banner_super_title" value="{{ $bulkOrderCms->banner_super_title ?? '' }}"
                                            data-rule-maxlength="191">
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner_title" class="form-label">Title*</label>
                                        <input type="text" class="form-control" id="banner_title" name="banner_title"
                                            value="{{ $bulkOrderCms->banner_title ?? '' }}" data-rule-maxlength="191"
                                            required>
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner_alt_text" class="form-label">Banner Alt Text</label>
                                        <input type="text" class="form-control" id="banner_alt_text"
                                            name="banner_alt_text" value="{{ $bulkOrderCms->banner_alt_text ?? '' }}"
                                            data-rule-maxlength="191">
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner" class="form-label">Banner*</label>
                                        <input type="file" class="form-control filepond-input-crop" name="banner"
                                            id="banner" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                            data-width="1920" data-height="1080" required>
                                        <div class="text-muted">Dimensions: 1920 x 1080</div>
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner_mobile" class="form-label">Banner Mobile*</label>
                                        <input type="file" class="form-control filepond-input-crop"
                                            name="banner_mobile" id="banner_mobile"
                                            data-accept="image/jpeg, image/png, image/jpg, image/webp" data-width="768"
                                            data-height="1024" required>
                                        <div class="text-muted">Dimensions: </div>
                                        <span class="error-block"></span>
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
@include('admin::partials.tinymce-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $bulkOrderCms ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('bulk-order-cms.edit') }}"
            });
        });
    </script>
@endpush
