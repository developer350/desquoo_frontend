@extends('admin::layouts.app')
@section('title', 'Edit Office Cms')
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ route('office-cms.update', base64_encode($officeCms->id)) }}">
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
                                    <div class="text-muted">Dimensions: 40 x 40 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="want_to_chat_link" class="form-label">Want to Chat Link</label>
                                    <input type="url" class="form-control" id="want_to_chat_link"
                                        name="want_to_chat_link" value="{{ $officeCms->want_to_chat_link ?? '' }}"
                                        data-rule-maxlength="500">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="want_to_chat_text" class="form-label">Want to Chat Text</label>
                                    <input type="text" class="form-control" id="want_to_chat_text"
                                        name="want_to_chat_text" value="{{ $officeCms->want_to_chat_text ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="show_want_to_chat" class="form-label">Show Want to Chat?</label>
                                    <select name="show_want_to_chat" id="show_want_to_chat" class="form-control">
                                        <option value="1" {{ $officeCms->show_want_to_chat == 1 ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ $officeCms->show_want_to_chat == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="want_to_talk_number" class="form-label">Want to Talk Number</label>
                                    <input type="text" class="form-control" id="want_to_talk_number"
                                        name="want_to_talk_number" value="{{ $officeCms->want_to_talk_number ?? '' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="show_want_to_talk" class="form-label">Show Want to Talk?</label>
                                    <select name="show_want_to_talk" id="show_want_to_talk" class="form-control">
                                        <option value="1" {{ $officeCms->show_want_to_talk == 1 ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ $officeCms->show_want_to_talk == 0 ? 'selected' : '' }}>No
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
                                        name="section_one_title" value="{{ $officeCms->section_one_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_one_description" class="form-label">
                                        Section One Description*
                                    </label>
                                    <textarea name="section_one_description" id="section_one_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $officeCms->section_one_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="drive_us_title" class="form-label">Drive Us Title*</label>
                                    <input type="text" class="form-control" id="drive_us_title"
                                        name="drive_us_title" value="{{ $officeCms->drive_us_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="drive_us_description" class="form-label">
                                        Drive Us Description*
                                    </label>
                                    <textarea name="drive_us_description" id="drive_us_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $officeCms->drive_us_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_two_title" class="form-label">Section Two Title*</label>
                                    <input type="text" class="form-control" id="section_two_title"
                                        name="section_two_title" value="{{ $officeCms->section_two_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_two_description" class="form-label">
                                        Section Two Description*
                                    </label>
                                    <textarea name="section_two_description" id="section_two_description" class="form-control tinymce"
                                        data-rule-maxlength="5000" required>{{ $officeCms->section_two_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_three_title" class="form-label">Section Three Title*</label>
                                    <input type="text" class="form-control" id="section_three_title"
                                        name="section_three_title" value="{{ $officeCms->section_three_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_four_title" class="form-label">Section Four Title*</label>
                                    <input type="text" class="form-control" id="section_four_title"
                                        name="section_four_title" value="{{ $officeCms->section_four_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_five_title" class="form-label">Section Five Title*</label>
                                    <input type="text" class="form-control" id="section_five_title"
                                        name="section_five_title" value="{{ $officeCms->section_five_title }}"
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
                                        data-rule-maxlength="5000" required>{{ $officeCms->section_five_description }}</textarea>
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
                                            name="banner_super_title" value="{{ $officeCms->banner_super_title ?? '' }}"
                                            data-rule-maxlength="191">
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner_title" class="form-label">Title*</label>
                                        <input type="text" class="form-control" id="banner_title" name="banner_title"
                                            value="{{ $officeCms->banner_title ?? '' }}" data-rule-maxlength="191"
                                            required>
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="banner_alt_text" class="form-label">Banner Alt Text</label>
                                        <input type="text" class="form-control" id="banner_alt_text"
                                            name="banner_alt_text" value="{{ $officeCms->banner_alt_text ?? '' }}"
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
                                        <div class="text-muted">Dimensions: 1920 x 1080 px</div>
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
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $officeCms ?? null])
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('office-cms.edit') }}"
            });
        });
    </script>
@endpush
