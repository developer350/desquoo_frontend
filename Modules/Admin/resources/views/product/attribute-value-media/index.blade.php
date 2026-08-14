@extends('admin::layouts.app')
@section('title', 'Product Custom Landing Attribute Value Settings')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}">
            Products
        </a>
    </li>
@endsection
@section('content')
    <!-- form start -->
    <form method="POST"
        action="{{ route('products.attribute-value-medias.store', ['product' => base64_encode($product->id)]) }}"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('products.index')" save-label="Update" />
            <div class="col-md-12">
                @foreach ($attributes as $attribute)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ $attribute['name'] }} - Attribute Values</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($attribute['values'] as $attributeValue)
                                    @php
                                        $attributeValueMedia = $productAttributeValueMedias
                                            ->where('attribute_value_id', $attributeValue->id)
                                            ->first();
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">{{ $attributeValue->value }}</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <input type="hidden" name="attribute_value_id[]"
                                                            value="{{ $attributeValue->id }}">
                                                        <label for="title_{{ $attributeValue->id }}"
                                                            class="form-label">Title*</label>
                                                        <input type="text" class="form-control"
                                                            id="title_{{ $attributeValue->id }}"
                                                            name="title[{{ $attributeValue->id }}]"
                                                            value="{{ isset($attributeValueMedia) && $attributeValueMedia->title != null ? $attributeValueMedia->title : $attributeValue->value }}"
                                                            data-rule-maxlength="191" required>
                                                        <span class="error-block"></span>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="description_{{ $attributeValue->id }}"
                                                            class="form-label">Description</label>
                                                        <textarea name="description[{{ $attributeValue->id }}]" id="description_{{ $attributeValue->id }}"
                                                            class="form-control" data-rule-maxlength="500">{{ isset($attributeValueMedia) ? $attributeValueMedia->description : '' }}</textarea>
                                                        <span class="error-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="price_{{ $attributeValue->id }}"
                                                            class="form-label">Price</label>
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control" id="price_{{ $attributeValue->id }}"
                                                            name="price[{{ $attributeValue->id }}]"
                                                            value="{{ isset($attributeValueMedia) ? $attributeValueMedia->price : 0 }}"
                                                            data-rule-max="9999999">
                                                        <span class="error-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="sort_order_{{ $attributeValue->id }}"
                                                            class="form-label">Sort Order</label>
                                                        <input type="number" step="1" min="0" max="99999999"
                                                            class="form-control" id="sort_order_{{ $attributeValue->id }}"
                                                            name="sort_order[{{ $attributeValue->id }}]"
                                                            value="{{ isset($attributeValueMedia) ? $attributeValueMedia->sort_order : $loop->iteration }}"
                                                            required>
                                                        <span class="error-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check form-switch mb-3">
                                                        <input type="radio" class="form-check-input"
                                                            id="is_default_{{ $attributeValue->id }}"
                                                            name="is_default[{{ $attribute['id'] }}]"
                                                            value="{{ $attributeValue->id }}"
                                                            {{ isset($attributeValueMedia) && $attributeValueMedia->is_default == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="is_default_{{ $attributeValue->id }}">Default
                                                            Attribute?</label>
                                                        <span class="error-block"></span>
                                                    </div>
                                                </div>
                                                @if ($attribute['is_main_attribute'])
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="width_{{ $attributeValue->id }}"
                                                                class="form-label">Width</label>
                                                            <input type="number" step="0.01" min="0"
                                                                class="form-control" id="width_{{ $attributeValue->id }}"
                                                                name="width[{{ $attributeValue->id }}]"
                                                                value="{{ isset($attributeValueMedia) ? $attributeValueMedia->width : '' }}"
                                                                data-rule-maxlength="191" required>
                                                            <span class="error-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="depth_{{ $attributeValue->id }}"
                                                                class="form-label">Depth</label>
                                                            <input type="number" step="0.01" min="0"
                                                                class="form-control" id="depth_{{ $attributeValue->id }}"
                                                                name="depth[{{ $attributeValue->id }}]"
                                                                value="{{ isset($attributeValueMedia) ? $attributeValueMedia->depth : '' }}"
                                                                data-rule-maxlength="191" required>
                                                            <span class="error-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="height_{{ $attributeValue->id }}"
                                                                class="form-label">Height</label>
                                                            <input type="number" step="0.01" min="0"
                                                                class="form-control" id="height_{{ $attributeValue->id }}"
                                                                name="height[{{ $attributeValue->id }}]"
                                                                value="{{ isset($attributeValueMedia) ? $attributeValueMedia->height : '' }}"
                                                                data-rule-maxlength="191" required>
                                                            <span class="error-block"></span>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="image[{{ $attributeValue->id }}]"
                                                            class="form-label">Image*</label>
                                                        <input type="file" class="form-control filepond-input-crop"
                                                            name="image[{{ $attributeValue->id }}]"
                                                            id="image[{{ $attributeValue->id }}]"
                                                            data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                                            required
                                                            data-src="{{ isset($attributeValueMedia) ? $attributeValueMedia->image_value : '' }}">
                                                        <div class="text-muted">Dimensions: 108 x 80 px</div>
                                                        <span class="error-block"></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
    @include('admin::partials.crop-modal')
@endsection
@include('admin::partials.select2-setup')
@include('admin::partials.jquery-validate-setup')
@include('admin::partials.crop-filepond-setup')
@include('admin::partials.filepond-setup', ['mediaSource' => null])
@push('js')
    <script>
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('products.attribute-value-medias.index', ['product' => base64_encode($product->id)]) }}"
            });
        });
    </script>
@endpush
