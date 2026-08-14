@extends('admin::layouts.app')
@section('title', isset($pincode) ? 'Edit Pincode' : 'Create Pincode')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pincodes.index') }}">
            Pincodes
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($pincode) ? route('pincodes.update', base64_encode($pincode->id)) : route('pincodes.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($pincode)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('pincodes.index')" save-label="{{ isset($pincode) ? 'Update' : 'Create' }}" />
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
                                        value="{{ $pincode->name ?? '' }}" data-rule-maxlength="191" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="pincodes" class="form-label">Pincodes*</label>
                                    <input type="text" class="form-control" id="pincodes" name="pincodes[]"
                                        value="{{ isset($pincode) ? $pincode->pincodes : '' }}" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="delivery_days" class="form-label">Delivery Days*</label>
                                    <input type="number" class="form-control" id="delivery_days" name="delivery_days"
                                        value="{{ $pincode->delivery_days ?? '' }}" required min="0" max="365">
                                    <span class="error-block"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($pincode) && $pincode->status == 1)>Enabled</option>
                                        <option value="0" @selected(isset($pincode) && $pincode->status == 0)>Disabled</option>
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
@include('admin::partials.choices-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            initializeChoices('#pincodes', {
                placeholderValue: 'Enter pincodes',
            });

            $('form').customValidate({
                successRoute: "{{ route('pincodes.index') }}"
            });
        });
    </script>
@endpush
