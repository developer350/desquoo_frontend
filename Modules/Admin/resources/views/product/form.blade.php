@extends('admin::layouts.app')
@section('title', isset($product) ? 'Edit Product' : 'Create Product')
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
        action="{{ isset($product) ? route('products.update', base64_encode($product->id)) : route('products.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($product)
            @method('PUT')
        @endisset
        <div class="row">
            <x-admin::action-buttons :cancel-url="route('products.index')" save-label="{{ isset($product) ? 'Update' : 'Create' }}" />
            <div class="col-xl-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#general" role="tab">
                            <span class="d-none d-sm-block">General</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#advanced" role="tab">
                            <span class="d-none d-sm-block">Advanced</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#seo" role="tab">
                            <span class="d-none d-sm-block">SEO</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="general" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
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
                                                        value="{{ $product->name ?? '' }}" data-rule-maxlength="191"
                                                        required>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="sku" class="form-label">
                                                        SKU
                                                        <span data-bs-toggle="tooltip" title="Leave blank to auto-generate">
                                                            <i class="fas fa-info-circle text-muted fs-6"></i>
                                                        </span>
                                                    </label>
                                                    <input type="text" class="form-control" id="sku" name="sku"
                                                        value="{{ $product->sku ?? '' }}" data-rule-maxlength="191">
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="short_description" class="form-label">
                                                        Short Description*
                                                    </label>
                                                    <textarea name="short_description" id="short_description" class="form-control" data-rule-maxlength="2000" required>{{ $product->short_description ?? '' }}</textarea>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="features" class="form-label">Features*</label>
                                                    <textarea name="features" id="features" class="form-control tinymce" data-rule-maxlength="10000" required>{{ $product->features ?? '' }}</textarea>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="dimensions" class="form-label">
                                                        Measurement and Dimension*
                                                    </label>
                                                    <textarea name="dimensions" id="dimensions" class="form-control tinymce" data-rule-maxlength="10000" required>{{ $product->dimensions ?? '' }}</textarea>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="warranty_shipping" class="form-label">
                                                        Warranty and Shipping*
                                                    </label>
                                                    <textarea name="warranty_shipping" id="warranty_shipping" class="form-control tinymce" data-rule-maxlength="10000"
                                                        required>{{ $product->warranty_shipping ?? '' }}</textarea>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="materials_certifications" class="form-label">
                                                        Material and Certfication*
                                                    </label>
                                                    <textarea name="materials_certifications" id="materials_certifications" class="form-control tinymce"
                                                        data-rule-maxlength="10000" required>{{ $product->materials_certifications ?? '' }}</textarea>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="productFeatures" class="form-label">Features</label>
                                                    <select class="form-control" id="productFeatures"
                                                        name="productFeatures[]" data-placeholder="Select Features"
                                                        multiple>
                                                        <option value=""></option>
                                                        @isset($product)
                                                            @foreach ($product->productFeatures as $feature)
                                                                <option value="{{ $feature->id }}" selected>
                                                                    {{ $feature->title }}
                                                                </option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="sort_order" class="form-label">Sort Order</label>
                                                    <input type="number" class="form-control numeric-input"
                                                        id="sort_order" name="sort_order"
                                                        value="{{ $product->sort_order ?? $sort_order }}">
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Best Seller</label>
                                                    <select class="form-select" name="is_best_seller">
                                                        <option value="1" @selected(isset($product) && $product->is_best_seller == 1)>
                                                            Enabled
                                                        </option>
                                                        <option value="0" @selected(isset($product) && $product->is_best_seller == 0)>
                                                            Disabled
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Favourite <span data-bs-toggle="tooltip"
                                                            title="You can show maximum 10 prodcuts in homepage">
                                                            <i class="fas fa-info-circle text-muted fs-6"></i>
                                                        </span></label>
                                                    <select class="form-select" name="is_favourite">
                                                        <option value="1" @selected(isset($product) && $product->is_favourite == 1)>
                                                            Enabled
                                                        </option>
                                                        <option value="0" @selected(isset($product) && $product->is_favourite == 0)>
                                                            Disabled
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="1" @selected(isset($product) && $product->status == 1)>
                                                            Enabled
                                                        </option>
                                                        <option value="0" @selected(isset($product) && $product->status == 0)>
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
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Product Category</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="category_id" class="form-label">Category*</label>
                                                    <select class="form-control" id="category_id" name="category_id"
                                                        data-placeholder="Select Category" required>
                                                        <option></option>
                                                        @isset($product)
                                                            <option value="{{ $product->category_id }}" selected>
                                                                {{ $product->category->name }}
                                                            </option>
                                                        @endisset
                                                    </select>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Associated Products</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="related_products" class="form-label">
                                                        Related Products
                                                    </label>
                                                    <input type="hidden" name="related_products" value="">
                                                    <select class="form-control" id="related_products"
                                                        {{ isset($product) && $product->is_addon ? 'disabled' : '' }}
                                                        name="related_products[]" data-placeholder="Select Products"
                                                        data-exclude-id="{{ $product->id ?? '' }}" multiple>
                                                        <option></option>
                                                        @isset($product)
                                                            @foreach ($relatedProducts as $relatedProduct)
                                                                <option value="{{ $relatedProduct->id }}" selected>
                                                                    {{ $relatedProduct->name }}
                                                                </option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="addons" class="form-label">
                                                        Addons
                                                    </label>
                                                    <select class="form-control" id="addons" name="addons[]"
                                                        data-placeholder="Select Addons"
                                                        {{ isset($product) && $product->is_addon ? 'disabled' : '' }}
                                                        data-exclude-id="{{ $product->id ?? '' }}" multiple>
                                                        <option value=""></option>
                                                        @isset($product)
                                                            @foreach ($product->addons as $addonProduct)
                                                                <option value="{{ $addonProduct->id }}" selected>
                                                                    {{ $addonProduct->name }}
                                                                </option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Media Upload</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="image" class="form-label">Image*</label>
                                                    <input type="file" class="form-control filepond-input-crop"
                                                        name="image" id="image"
                                                        data-accept="image/jpeg, image/png, image/jpg, image/webp"
                                                        required>
                                                    <div class="text-muted">Dimensions: 580 x 580 px</div>
                                                    <span class="error-block"></span>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="image_alt_text" class="form-label">Image Alt
                                                        Text</label>
                                                    <input type="text" class="form-control" id="image_alt_text"
                                                        name="image_alt_text"
                                                        value="{{ $product->image_alt_text ?? '' }}"
                                                        data-rule-maxlength="191">
                                                    <span class="error-block"></span>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="desc_image" class="form-label">Description Image</label>
                                                    <input type="file" class="form-control filepond-input-crop"
                                                        name="desc_image" id="desc_image"
                                                        data-accept="image/jpeg, image/png, image/jpg, image/webp">
                                                    <div class="text-muted">Dimensions: 668 x 1000 px</div>
                                                    <span class="error-block"></span>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="3d" class="form-label">3D File</label>
                                                    <input type="file" class="form-control filepond-input"
                                                        name="3d" id="3d" data-size="10MB"
                                                        data-accept="model/gltf-binary">
                                                    <div class="text-muted">File Type: GLB, Max Size: 10MB </div>
                                                    <span class="error-block"></span>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="qr" class="form-label">AR QR Image</label>
                                                    <input type="file" class="form-control filepond-input"
                                                        name="qr" id="qr" data-size="300KB">
                                                    <div class="text-muted">File Type: GLB, Max Size: 300KB </div>
                                                    <span class="error-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="advanced" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Product Type & Variations</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label d-block">Product Type</label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="type"
                                                            id="typeSingle" value="single" @checked(!isset($product) || (isset($product) && $product->type == 'single'))>
                                                        <label class="form-check-label" for="typeSingle">Single
                                                            Product</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="type"
                                                            id="typeVariable" value="variable"
                                                            @checked(isset($product) && $product->type == 'variable')>
                                                        <label class="form-check-label" for="typeVariable">Variable
                                                            Product</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" id="is_manage_stock"
                                                        value="1" name="is_manage_stock"
                                                        {{ isset($product) ? ($product->is_manage_stock == 1 ? 'checked' : '') : 'checked' }}>
                                                    <label class="form-check-label" for="is_manage_stock">Manage
                                                        Stock</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" id="is_addon"
                                                        value="1" name="is_addon"
                                                        {{ isset($product) ? ($product->is_addon == 1 ? 'checked' : '') : '' }}>
                                                    <label class="form-check-label" for="is_addon">Is Addon ?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-end">
                                            <div class="col-md-12 mb-3 d-flex justify-content-end">
                                                <button type="button" id="add-attribute"
                                                    class="btn btn-primary waves-effect btn-label waves-light {{ isset($product) ? ($product->type == 'variable' ? '' : 'd-none') : 'd-none' }}">
                                                    <i class="bx bx-plus label-icon"></i>
                                                    Add Attribute & Options
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive" id="attribute-selection">
                                                @isset($product)
                                                    @if ($product->type == 'variable')
                                                        @include(
                                                            'admin::product.partials.variable',
                                                            compact('selectedAttributes') + [
                                                                'variations' => $product->variants,
                                                                'product' => $product,
                                                            ]
                                                        )
                                                    @elseif ($product->type == 'single')
                                                        @include(
                                                            'admin::product.partials.single',
                                                            compact('product'))
                                                    @endif
                                                @else
                                                    @include('admin::product.partials.single', [
                                                        'product' => null,
                                                    ])
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="seo" role="tabpanel">
                        <x-admin::meta-tags :meta-data="$product ?? null" keywords-col="col-md-12" description-col="col-md-12"
                            other-col="col-md-12" />
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
@include('admin::partials.filepond-setup', ['mediaSource' => $product ?? null])
@include('admin::partials.choices-setup')
@include('admin::partials.sweet-alert-setup')
@include('admin::partials.jquery-validate-setup')
@push('js')
    <script>
        let productType = 'single';
        let attributeCount = $('#attribute-values-body tr').length;
        $(document).ready(function() {
            $('form').customValidate({
                successRoute: "{{ route('products.index') }}",
                beforeFormSubmit: function() {
                    const type = $('input[name="type"]:checked').val();

                    if (type === 'variable' && !$('#variation-value-table').length) {
                        const advTabId = $('input[name="type"]:checked')
                            .closest('.tab-pane').attr('id');
                        const activeTabId = $('.tab-pane.active').attr('id');

                        if (activeTabId === advTabId) {
                            Swal.fire({
                                title: "Action Required",
                                html: "Please generate variations before submitting.",
                                icon: "error",
                                confirmButtonText: "Got it",
                            });
                        } else {
                            const advTabText = $(`a[href="#${advTabId}"]`).text().trim();
                            Swal.fire({
                                title: "Action Required",
                                html: `Please check the <strong>${advTabText}</strong> tab to generate variations before submitting.`,
                                icon: "error",
                                confirmButtonText: "Got it",
                            }).then(() => {
                                $(`a[href="#${advTabId}"]`).tab('show');
                            });
                        }
                        return false; // block submission
                    }
                    return true;
                },
                invalidHandler: function(form, validator) {
                    if (!validator.numberOfInvalids()) return;

                    let activeTabId = $('.tab-pane.active').attr('id');
                    let errorTabs = [...new Set(
                        validator.errorList.map(err =>
                            $(err.element).closest('.tab-pane').attr('id')
                        )
                    )]; // unique tab ids with errors

                    if (errorTabs.includes(activeTabId)) return; // current tab has errors → no popup

                    // get tab names
                    let tabNames = errorTabs.map(id => $(`a[href="#${id}"]`).text().trim());

                    // prepare message
                    let message = (tabNames.length === 1) ?
                        `Please check the <strong>${tabNames[0]}</strong> tab for errors.` :
                        `Please check the following tabs for errors:<br><strong>${tabNames.join(', ')}</strong>`;

                    Swal.fire({
                        html: message,
                        icon: "error",
                        confirmButtonText: "Got it",
                    }).then(() => {
                        $(`a[href="#${errorTabs[0]}"]`).tab('show');
                    });
                }
            });

            @isset($product)
                @if ($product->type == 'variable')
                    selectInitAttribute();
                    selectInitAttributeValue();
                    addValidationOnVariable();
                @endif
            @endisset

            $('#category_id').select2({
                placeholder: $('#category_id').data('placeholder'),
                ajax: {
                    url: "{{ route('get-product-categories') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.name
                            })),
                            pagination: {
                                more: data.next_page_url !== null
                            }
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                    }
                }
            });

            $('#related_products').select2({
                placeholder: $('#related_products').data('placeholder'),
                maximumSelectionLength: 10,
                ajax: {
                    url: "{{ route('get-products') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            exclude_id: $('#related_products').data('exclude-id'),
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.name
                            })),
                            pagination: {
                                more: data.next_page_url !== null
                            }
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                    }
                }
            });

            $('#addons').select2({
                placeholder: 'addons',
                maximumSelectionLength: 10,
                ajax: {
                    url: "{{ route('get-addons') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.name
                            })),
                            pagination: {
                                more: data.next_page_url !== null
                            }
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                    }
                }
            });

            $('#productFeatures').select2({
                placeholder: 'Features',
                maximumSelectionLength: 10,
                ajax: {
                    url: "{{ route('get-features') }}",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.title
                            })),
                            pagination: {
                                more: data.next_page_url !== null
                            }
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                    }
                }
            });

            $(document).on('change', 'input[name="type"]', function() {
                productType = $(this).val();
                $.ajax({
                    type: "get",
                    url: "{{ route('get-variant-template') }}",
                    data: {
                        type: productType,
                        product_id: "{{ $product->id ?? null }}"
                    },
                    dataType: "html",
                    success: function(response) {
                        if (response) {
                            $('#attribute-selection').html(response);
                            if (productType == 'variable') {
                                attributeCount = 0;
                                $('#add-attribute').removeClass('d-none');
                                selectInitAttribute();
                                selectInitAttributeValue();
                            } else {
                                $('#add-attribute').addClass('d-none');
                            }
                            $('#is_manage_stock').trigger('change');
                        }
                    }
                });
            });

            $(document).on('click', '#add-attribute', function() {
                attributeCount++;
                $.ajax({
                    type: "get",
                    url: "{{ route('get-attribute-row') }}",
                    data: {
                        attribute_count: attributeCount
                    },
                    dataType: "html",
                    success: function(response) {
                        if (response) {
                            $('#attribute-values-body').append(response);
                            selectInitAttribute();
                            selectInitAttributeValue();
                        }
                    }
                });
            });

            $(document).on('click', '#generate-variations', function() {
                const $btn = $(this);

                // Ensure all attribute values are selected
                const isValid = $('select.attribute-values-select').toArray()
                    .every(el => $(el).val()?.length);

                if (!isValid) {
                    return Swal.fire({
                        icon: 'error',
                        text: 'Please choose each attribute and its option.',
                        confirmButtonText: 'Got it',
                        showClass: {
                            popup: 'animate__animated animate__shakeX animate__faster'
                        }
                    });
                }

                // Set processing state
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html(
                    '<i class="bx bx-refresh bx-spin"></i> Generate Variations');

                const attributes = $('select.attribute-id').map(function() {
                    const index = $(this).data('index');
                    return {
                        attribute_id: $(this).val(),
                        values: $(`select[name="attribute_values[${index}][]"]`).val() || []
                    };
                }).get();

                $.ajax({
                    type: "GET",
                    url: "{{ route('generate-variations') }}",
                    data: {
                        attributes_array: attributes,
                        product_id: "{{ $product->id ?? null }}"
                    },
                    dataType: "html",
                    success: function(response) {
                        if (!response) return;
                        $('#variation-value-table').remove();
                        $('#attribute-selection').append(response);
                        addValidationOnVariable();
                        $('#is_manage_stock').trigger('change');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            $(document).on('click', '.remove-attribute-row', function() {
                // Prevent deleting the last remaining attribute row
                if ($('.remove-attribute-row').length === 1) {
                    Swal.fire({
                        text: "At least one attribute is required.",
                        icon: "error",
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    });
                    return;
                }

                Swal.fire({
                    title: "Delete this attribute?",
                    text: "This will remove all related variations.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#2ab57d',
                    cancelButtonColor: '#fd625e',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).closest('tr').remove();
                        $('#generate-variations').trigger('click');
                    }
                });
            });

            $(document).on('click', '.remove-variation-value-row', function() {
                const $row = $(this).closest('tr');
                const $tableBody = $row.closest('tbody');
                const $table = $tableBody.closest('table');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will remove the row.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2ab57d',
                    cancelButtonColor: '#fd625e',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $tableBody.children('tr').length === 1 ? $table.remove() : $row.remove();

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The row has been removed.',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                });
            });

            function selectInitAttribute() {
                $('.attribute-id').select2({
                    placeholder: $(this).data('placeholder'),
                    ajax: {
                        url: "{{ route('get-attributes') }}",
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.data.map(item => ({
                                    id: item.id,
                                    text: item.name
                                })),
                                pagination: {
                                    more: data.next_page_url !== null
                                }
                            };
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                        }
                    }
                }).on('change', function() {
                    const $row = $(this).closest('tr');
                    const $valuesSelect = $row.find('.attribute-values-select');
                    const selectedVal = $(this).val();

                    $valuesSelect.empty(); // Clear attribute values and re-init
                    selectInitAttributeValue($valuesSelect); // re-init only this one
                    if (!selectedVal) return;

                    // Check for duplicate selection in other rows
                    const isDuplicate = $('select.attribute-id')
                        .not(this)
                        .toArray()
                        .some(el => $(el).val() === selectedVal);

                    if (isDuplicate) {
                        $(this).val('').trigger('change');
                        Swal.fire({
                            icon: 'error',
                            title: 'Duplicate Attribute',
                            text: 'This attribute is already selected in another row.',
                            confirmButtonText: 'Got it',
                            showClass: {
                                popup: 'animate__animated animate__shakeX animate__faster'
                            }
                        });
                    } else {
                        // Automatically open dropdown
                        $valuesSelect.select2('open');
                    }
                });
            }

            function selectInitAttributeValue() {
                $('.attribute-values-select').select2({
                    allowClear: true,
                    multiple: true,
                    placeholder: $(this).data('placeholder'),
                    ajax: {
                        url: "{{ route('get-attribute-values') }}",
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                attribute_id: $(this).closest('tr').find('.attribute-id').val()
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.data.map(item => ({
                                    id: item.id,
                                    text: item.value
                                })),
                                pagination: {
                                    more: data.next_page_url !== null
                                }
                            };
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error, xhr.status, xhr.responseText);
                        }
                    }
                });
            }

            function addValidationOnVariable() {
                $('#variation-value-table tr').each((i) => {
                    const $offerInput = $(`[name="offer_price[${i}]"]`);
                    const $priceInput = "#price_" + i;

                    if ($offerInput.length) {
                        $offerInput.rules('add', {
                            lessThanCompare: $priceInput
                        });
                    }
                });
            }
        });

        $('#is_manage_stock').on('change', function() {
            if ($(this).is(':checked')) {
                $('.stockValue').attr('readonly', false);
            } else {
                $('.stockValue').attr('readonly', true);
                $('.stockValue').val(0);
            }
        });

        $('#is_addon').on('change', function() {
            if ($(this).is(':checked')) {
                $('#addons').prop('disabled', true).val(null).trigger('change');
                $('#related_products').prop('disabled', true).val(null).trigger('change');

                //check if type is not single, then change it to single
                if ($('#typeVariable').is(':checked')) {
                    $('#typeSingle').prop('checked', true).trigger('change');
                }
            } else {
                $('#addons').prop('disabled', false).val(null).trigger('change');
                $('#related_products').prop('disabled', false).val(null).trigger('change');
            }
        });
    </script>
@endpush
