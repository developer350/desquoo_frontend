@extends('admin::layouts.app')
@section('title', isset($attribute) ? 'Edit Attribute' : 'Create Attribute')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('attributes.index') }}">
            Attributes
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($attribute) ? route('attributes.update', base64_encode($attribute->id)) : route('attributes.store') }}">
        @csrf
        @isset($attribute)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('attributes.index')" save-label="{{ isset($attribute) ? 'Update' : 'Create' }}" />
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Main Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name*</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $attribute->name ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            @unless (isset($attribute))
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="values" class="form-label">Values*</label>
                                        <input id="values" name="values[]" class="form-control" value="" required>
                                        <span class="error-block"></span>
                                    </div>
                                </div>
                            @endunless
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control numeric-input" id="sort_order"
                                        name="sort_order" value="{{ $attribute->sort_order ?? $sort_order }}">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($attribute) && $attribute->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($attribute) && $attribute->status == 0)>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" name="default_listing_attribute" class="form-check-input"
                                            id="default_listing_attribute" {{ isset($attribute) && $attribute->default_listing_attribute ? 'checked' : '' }}>
                                        <label class="form-check-label" for="default_listing_attribute">Default
                                            Attribute(Lisiting)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" name="is_main_attribute" class="form-check-input"
                                            id="is_main_attribute" {{ isset($attribute) && $attribute->is_main_attribute ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_main_attribute">Is Main Attribute ?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@unless (isset($attribute))
    @include('admin::partials.choices-setup')
@endunless
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        @unless (isset($attribute))
            document.addEventListener("DOMContentLoaded", function() {
                initializeChoices("#values", {
                    delimiter: ',',
                    editItems: true,
                    maxItemCount: 10,
                    removeItemButton: true,
                    duplicateItemsAllowed: false,
                    placeholder: true,
                    placeholderValue: 'Add Values',
                    addItemFilter: (value) => {
                        const text = value.trim();
                        const allowedPattern = /^[A-Za-z0-9\s]+$/;
                        return allowedPattern.test(text) && text.length <= 191;
                    },
                    customAddItemText: (value) => {
                        const text = value.trim();
                        if (!/^[A-Za-z0-9\s]+$/.test(text)) {
                            return 'Only letters, numbers, and spaces are allowed.';
                        }
                        if (text.length > 191) {
                            return 'Please enter no more than 191 characters.';
                        }
                    },
                });
            });
        @endunless
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('attributes.index') }}"
            });
        });
    </script>
@endpush
