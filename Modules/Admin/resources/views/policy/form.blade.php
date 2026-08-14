@extends('admin::layouts.app')
@section('title', isset($policy) ? 'Edit Policy' : 'Create Policy')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('policies.index') }}">
            Policies
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($policy) ? route('policies.update', base64_encode($policy->id)) : route('policies.store') }}">
        @csrf
        @isset($policy)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('policies.index')" save-label="{{ isset($policy) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $policy->title ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">Content*</label>
                                    <textarea name="content" id="content" class="form-control tinymce" data-height="500px" data-rule-maxlength="10000"
                                        required>{{ $policy->content ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-admin::meta-tags :meta-data="$policy ?? null" />
        </div>
    </form>
@endsection
@include('admin::partials.tinymce-setup')
@include('admin::partials.choices-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('policies.index') }}"
            });
        });
    </script>
@endpush
