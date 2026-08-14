@extends('admin::layouts.app')
@section('title', 'Edit Home Cms')
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ route('home-cms.update', base64_encode($homeCms->id)) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <x-admin::action-buttons save-label="Update" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_one_title" class="form-label">Section One Title*</label>
                                    <input type="text" class="form-control" id="section_one_title"
                                        name="section_one_title" value="{{ $homeCms->section_one_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_one_image_alt_text" class="form-label">
                                        Section One Image Alt text
                                    </label>
                                    <input type="text" class="form-control" id="section_one_image_alt_text"
                                        name="section_one_image_alt_text" value="{{ $homeCms->section_one_image_alt_text }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_two_title" class="form-label">Section Two Title*</label>
                                    <input type="text" class="form-control" id="section_two_title"
                                        name="section_two_title" value="{{ $homeCms->section_two_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_three_title" class="form-label">Section Three Title*</label>
                                    <input type="text" class="form-control" id="section_three_title"
                                        name="section_three_title" value="{{ $homeCms->section_three_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_one_image" class="form-label">Section One Image*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="section_one_image"
                                        id="section_one_image" data-width="1920" data-height="1102"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 1920 x 1102</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_three_description" class="form-label">
                                        Section Three Description*
                                    </label>
                                    <textarea name="section_three_description" id="section_three_description" class="form-control"
                                        data-rule-maxlength="5000" required>{{ $homeCms->section_three_description }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_four_title" class="form-label">Section Four Title*</label>
                                    <input type="text" class="form-control" id="section_four_title"
                                        name="section_four_title" value="{{ $homeCms->section_four_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_five_title" class="form-label">Section Five Title*</label>
                                    <input type="text" class="form-control" id="section_five_title"
                                        name="section_five_title" value="{{ $homeCms->section_five_title }}"
                                        data-rule-maxlength="191" required>
                                    <div class="text-muted">Basic HTML supported (span, br).</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_four_description" class="form-label">
                                        Section Four Description*
                                    </label>
                                    <textarea name="section_four_description" id="section_four_description" class="form-control"
                                        data-rule-maxlength="5000" rows="5" required>{{ $homeCms->section_four_description }}</textarea>
                                    <div class="text-muted">Basic HTML supported (span, br).</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_six_title" class="form-label">Section Six Title*</label>
                                    <input type="text" class="form-control" id="section_six_title"
                                        name="section_six_title" value="{{ $homeCms->section_six_title }}"
                                        data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_six_image_alt_text" class="form-label">Section Six Image Alt
                                        Text</label>
                                    <input type="text" class="form-control" id="section_six_image_alt_text"
                                        name="section_six_image_alt_text"
                                        value="{{ $homeCms->section_six_image_alt_text }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="section_six_image" class="form-label">Section Six Image*</label>
                                    <input type="file" class="form-control filepond-input-crop"
                                        name="section_six_image" id="section_six_image" data-width="1840"
                                        data-height="1226" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                        required>
                                    <div class="text-muted">Dimensions: 1840 x 1226</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_six_description" class="form-label">
                                        Section Six Description*
                                    </label>
                                    <textarea name="section_six_description" id="section_six_description" class="form-control tinymce"
                                        data-rule-maxlength="10000" data-height="450px" required>{{ $homeCms->section_six_description }}</textarea>
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
@include('admin::partials.filepond-setup', ['mediaSource' => $homeCms ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('home-cms.edit') }}"
            });
        });
    </script>
@endpush
