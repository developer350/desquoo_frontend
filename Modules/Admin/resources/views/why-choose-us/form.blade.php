@extends('admin::layouts.app')
@section('title', isset($whyChooseUs) ? 'Edit Why Choose Us' : 'Create Why Choose Us')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('why-choose-us.index') }}">
            Why Choose Us
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($whyChooseUs) ? route('why-choose-us.update', base64_encode($whyChooseUs->id)) : route('why-choose-us.store') }}">
        @csrf
        @isset($whyChooseUs)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('why-choose-us.index')" save-label="{{ isset($whyChooseUs) ? 'Update' : 'Create' }}" />
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
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $whyChooseUs->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description*</label>
                                    <textarea name="description" id="description" class="form-control tinymce" data-rule-maxlength="5000" required>{{ $whyChooseUs->description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $whyChooseUs->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($whyChooseUs) && $whyChooseUs->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($whyChooseUs) && $whyChooseUs->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@include('admin::partials.tinymce-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('why-choose-us.index') }}"
            });
        });
    </script>
@endpush
