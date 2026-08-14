@extends('admin::layouts.app')
@section('title', isset($faq) ? 'Edit PCL Models' : 'Create PCL Models')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('product-custom-landings.index') }}">
            Product Custom Landings
        </a>
    </li>
    <li class="breadcrumb-item">
        <a
            href="{{ route('product-custom-landings.faqs.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}">
            PCL Faqs
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($faq) ? route('product-custom-landings.faqs.update', ['product_custom_landing' => base64_encode($productCustomLanding->id), 'faq' => base64_encode($faq->id)]) : route('product-custom-landings.faqs.store', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($faq)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('product-custom-landings.faqs.index', [
                'product_custom_landing' => base64_encode($productCustomLanding->id),
            ])" save-label="{{ isset($faq) ? 'Update' : 'Create' }}" />
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Main Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="question" class="form-label">Question*</label>
                                    <input type="text" name="question" class="form-control" id="question"
                                        value="{{ $faq->question ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="answer" class="form-label">Answer*</label>
                                    <textarea name="answer" id="answer" class="form-control tinymce" data-rule-maxlength="5000" required>{{ $faq->answer ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $faq->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($faq) && $faq->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($faq) && $faq->status == 0)>Disabled</option>
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
                successRoute: "{{ route('product-custom-landings.faqs.index', ['product_custom_landing' => base64_encode($productCustomLanding->id)]) }}"
            });
        });
    </script>
@endpush
