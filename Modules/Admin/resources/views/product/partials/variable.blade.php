<table class="table dt-responsive" style="width:100%">
    <thead>
        <tr>
            <th>Variant Attribute</th>
            <th>Variant Options</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="attribute-values-body">
        @forelse ($selectedAttributes as $selectedAttribute)
            <tr id="attribute-row-{{ $loop->iteration }}">
                <td>
                    <select class="form-control attribute-id" name="attribute_id[{{ $loop->iteration }}]"
                        data-index="{{ $loop->iteration }}" data-placeholder="Choose Attribute">
                        <option></option>
                        <option value="{{ $selectedAttribute['attribute']->id }}" selected>
                            {{ $selectedAttribute['attribute']->name }}
                        </option>
                    </select>
                </td>
                <td>
                    <select class="form-control attribute-values-select" name="attribute_values[{{ $loop->iteration }}][]"
                        data-placeholder="Select Options" multiple>
                        <option></option>
                        @foreach ($selectedAttribute['values'] as $value)
                            <option value="{{ $value['id'] }}" selected>
                                {{ $value['value'] }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-attribute-row" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Remove">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr id="attribute-row-0">
                <td>
                    <select class="form-control attribute-id" name="attribute_id[0]" data-index="0"
                        data-placeholder="Choose Attribute">
                        <option></option>
                    </select>
                </td>
                <td>
                    <select class="form-control attribute-values-select" name="attribute_values[0][]"
                        data-placeholder="Select Options" multiple>
                        <option></option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-attribute-row" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Remove">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-end">
                <button type="button" id="generate-variations"
                    class="btn btn-warning btn-md d-inline-flex align-items-center gap-1">
                    <i class="bx bx-refresh bx-rotate-90"></i>
                    Generate Variations
                </button>
            </td>
        </tr>
    </tfoot>
</table>
@if ($selectedAttributes->isNotEmpty() && $variations->isNotEmpty())
    <table class="table table-border dt-responsive" id="variation-value-table">
        <thead>
            <th>Variant</th>
            <th>
                SKU
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Leave blank to auto-generate">
                    <i class="fas fa-info-circle text-muted fs-6"></i>
                </span>
            </th>
            <th>Price*</th>
            <th>Offer Price</th>
            <th>Stock*</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach ($variations as $variation)
                <tr id="variation-value-row-{{ $loop->iteration }}">
                    <td class="p-2" style="min-width:180px;">
                        @foreach ($variation->variantAttributes as $variantAttribute)
                            <div style="font-size: 0.9rem;">
                                <strong>{{ ucfirst($variantAttribute->attribute->name) }}:</strong>
                                {{ $variantAttribute->attributeValue->value }}
                            </div>
                        @endforeach
                        <input type="hidden" name="combination_name[{{ $loop->iteration }}]"
                            value="{{ $variation['variant_name'] }}">
                        <input type="hidden" name="combination_id[{{ $loop->iteration }}]"
                            value="{{ $variation['id'] }}">
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" name="product_variant_sku[{{ $loop->iteration }}]"
                                class="form-control" value="{{ $variation['sku'] }}">
                            <div class="error-block"></div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="price[{{ $loop->iteration }}]"
                                id="price_{{ $loop->iteration }}" step="any" min="0.01"
                                value="{{ $variation['price'] }}"
                                class="form-control decimal-input" placeholder="0.00" required>
                            <div class="error-block"></div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="offer_price[{{ $loop->iteration }}]"
                                id="offer_price_{{ $loop->iteration }}" step="any" min="0"
                                value="{{ $variation['offer_price'] }}"
                                class="form-control decimal-input" placeholder="0.00">
                            <div class="error-block"></div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="stock[{{ $loop->iteration }}]" step="1" min="0"
                                value="{{ $variation['stock'] }}" required
                                class="form-control numeric-input stockValue" placeholder="0"
                                {{ isset($product) && $product->is_manage_stock ? '' : 'readonly' }}>
                            <div class="error-block"></div>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variation-value-row"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Remove">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
