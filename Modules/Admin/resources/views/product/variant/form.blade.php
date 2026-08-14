@extends('admin::layouts.app')
@section('title', isset($productVariant) ? 'Edit Product Variant' : 'Create Product Variant')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('products.variants.index', ['product' => base64_encode($product->id)]) }}">
            Product Variants
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ isset($productVariant) ? route('products.variants.update', ['product' => base64_encode($product->id), 'variant' => base64_encode($productVariant->id)]) : route('products.variants.store', base64_encode($product->id)) }}"
        enctype="multipart/form-data">
        @csrf
        @isset($productVariant)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('products.variants.index', ['product' => base64_encode($product->id)])" save-label="{{ isset($productVariant) ? 'Update' : 'Create' }}" />
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Main Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @isset($productVariant)
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Product Variant</label>
                                        <div class="fw-normal text-muted">
                                            {!! $productVariant->combinations !!}
                                        </div>
                                    </div>
                                </div>
                            @endisset
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sku" class="form-label">
                                        SKU
                                        <span data-bs-toggle="tooltip" title="Leave blank to auto-generate">
                                            <i class="fas fa-info-circle text-muted fs-6"></i>
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        value="{{ $productVariant->sku ?? '' }}" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="short_description" class="form-label">
                                        Short Description
                                    </label>
                                    <textarea name="short_description" id="short_description" class="form-control" data-rule-maxlength="2000">{{ $productVariant->short_description ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <textarea name="features" id="features" class="form-control tinymce" data-rule-maxlength="10000">{{ $productVariant->features ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="dimensions" class="form-label">
                                        Measurement and Dimension
                                    </label>
                                    <textarea name="dimensions" id="dimensions" class="form-control tinymce" data-rule-maxlength="10000">{{ $productVariant->dimensions ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="warranty_shipping" class="form-label">
                                        Warranty and Shipping
                                    </label>
                                    <textarea name="warranty_shipping" id="warranty_shipping" class="form-control tinymce" data-rule-maxlength="10000">{{ $productVariant->warranty_shipping ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="materials_certifications" class="form-label">
                                        Material and Certfication
                                    </label>
                                    <textarea name="materials_certifications" id="materials_certifications" class="form-control tinymce"
                                        data-rule-maxlength="10000">{{ $productVariant->materials_certifications ?? '' }}</textarea>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">Price*</label>
                                    <input type="number" name="price" step="any" min="0.01"
                                        value="{{ $productVariant->price ?? '' }}" class="form-control decimal-input"
                                        placeholder="0.00" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="offer_price" class="form-label">Offer Price</label>
                                    <input type="number" name="offer_price" step="any" min="0"
                                        value="{{ $productVariant->offer_price ?? '' }}"
                                        class="form-control decimal-input" placeholder="0.00">
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stock" class="form-label">Stock*</label>
                                    <input type="number" name="stock" step="1" min="0"
                                        value="{{ $productVariant->stock ?? '' }}" class="form-control numeric-input"
                                        placeholder="0" required>
                                    <span class="error-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" @selected(isset($productVariant) && $productVariant->status == 1)>
                                            Enabled
                                        </option>
                                        <option value="0" @selected(isset($productVariant) && $productVariant->status == 0)>
                                            Disabled
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @unless (isset($productVariant) || $product->attributes->isEmpty())
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Add Variant</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($product->attributes as $attribute)
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="attribute-values-{{ $attribute->id }}" class="form-label">
                                                {{ $attribute->name }}
                                            </label>
                                            <select class="form-control select2"
                                                name="attribute_values[{{ $attribute->id }}]"
                                                id="attribute-values-{{ $attribute->id }}" data-placeholder="Select Option"
                                                required>
                                                <option></option>
                                                @foreach ($attribute->values as $value)
                                                    <option value="{{ $value->id }}">
                                                        {{ $value->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error-block"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endunless
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Media Upload</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="image"
                                        id="image" data-width="580" data-height="580"
                                        data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 580 x 580 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                        value="" data-rule-maxlength="191">
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="desc_image" class="form-label">Description Image</label>
                                    <input type="file" class="form-control filepond-input-crop" name="desc_image"
                                        id="desc_image" data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                    <div class="text-muted">Dimensions: 668 x 1000 px</div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="3d" class="form-label">3D File</label>
                                    <input type="file" class="form-control filepond-input" name="3d"
                                        id="3d" data-size="10MB" data-accept="model/gltf-binary">
                                    <div class="text-muted">File Type: GLB, Max Size: 10MB </div>
                                    <span class="error-block"></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="qr" class="form-label">AR QR Image</label>
                                    <input type="file" class="form-control filepond-input" name="qr"
                                        id="qr" data-size="300KB">
                                    <div class="text-muted">File Type: GLB, Max Size: 300KB </div>
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
@include('admin::partials.select2-setup')
@include('admin::partials.tinymce-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => $productVariant ?? null])
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('products.variants.index', ['product' => base64_encode($product->id)]) }}"
            });
        });
    </script>
@endpush
