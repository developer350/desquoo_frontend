@extends('admin::layouts.app')
@section('title', 'Edit Support Secrtion Cms')
@section('content')
    <!-- form start -->
    <form method="POST" action="{{ route('support-section-cms.store') }}">
        @csrf
        <div class="row">
            <x-admin::action-buttons save-label="Update" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title*</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $cms->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">
                                        Description*
                                    </label>
                                    <textarea name="description" id="description" class="form-control" data-rule-maxlength="5000" required>{{ $cms->description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image*</label>
                                    <input type="file" class="form-control filepond-input-crop" name="image"
                                        id="image" data-accept="image/jpeg, image/png, image/jpg, image/webp" required>
                                    <div class="text-muted">Dimensions: 100 x 100 px</div>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="{{ $cms->image_alt_text ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="visit_store_btn_text" class="form-label">Visit Store Button Text</label>
                                    <input type="text" class="form-control" id="visit_store_btn_text"
                                        name="visit_store_btn_text"
                                        value="{{ $cms->visit_store_btn_text ?? 'visit our store' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="visit_store_btn_show" class="form-label">Visit Store Button Show?</label>
                                    <select name="visit_store_btn_show" id="visit_store_btn_show" class="form-select">
                                        <option value="1"
                                            {{ isset($cms) && $cms->visit_store_btn_show == 1 ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="0"
                                            {{ isset($cms) && $cms->visit_store_btn_show == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="get_a_virtual_demo_btn_text" class="form-label">Get A Virtual Demo Button
                                        Text</label>
                                    <input type="text" class="form-control" id="get_a_virtual_demo_btn_text"
                                        name="get_a_virtual_demo_btn_text"
                                        value="{{ $cms->get_a_virtual_demo_btn_text ?? 'Get A Virtual Demo' }}"
                                        data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="get_a_virtual_demo" class="form-label">Get A Virtual Demo Show ?</label>
                                    <select name="get_a_virtual_demo" id="get_a_virtual_demo" class="form-select">
                                        <option value="1"
                                            {{ isset($cms) && $cms->get_a_virtual_demo == 1 ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="0"
                                            {{ isset($cms) && $cms->get_a_virtual_demo == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="calendly_meeting_link" class="form-label">Calendly Meeting Link</label>
                                    <input type="text" class="form-control" id="calendly_meeting_link"
                                        name="calendly_meeting_link" value="{{ $cms->calendly_meeting_link ?? '' }}"
                                        data-rule-maxlength="500">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="form_title" class="form-label">Visit Form Title*</label>
                                    <input type="text" class="form-control" id="form_title" name="form_title"
                                        value="{{ $cms->form_title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="form_description" class="form-label">
                                        Visit Form Description
                                    </label>
                                    <textarea name="form_description" id="form_description" class="form-control" data-rule-maxlength="5000">{{ $cms->form_description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Section Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ isset($cms) && $cms->status == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                        <option value="0" {{ isset($cms) && $cms->status == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
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
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $cms ?? null])
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                rules: {
                    calendly_meeting_link: {
                        url: true,
                        required: function() {
                            return $('#get_a_virtual_demo').val() == 1;
                        }
                    }
                },
                successRoute: "{{ route('support-section-cms.index') }}"
            });
        });
    </script>
@endpush
