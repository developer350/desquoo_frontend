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
                    @foreach ($variation['values'] as $key => $value)
                        <div style="font-size: 0.9rem;">
                            <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                        </div>
                    @endforeach
                    <input type="hidden" name="combination_name[{{ $loop->iteration }}]"
                        value="{{ $variation['combination_name'] }}">
                    <input type="hidden" name="combination_id[{{ $loop->iteration }}]"
                        value="{{ $variation['product_variant_id'] ? $variation['product_variant_id'] : null }}">
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" name="product_variant_sku[{{ $loop->iteration }}]" class="form-control"
                            value="{{ $variation['sku'] ? $variation['sku'] : '' }}">
                        <div class="error-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="price[{{ $loop->iteration }}]" id="price_{{ $loop->iteration }}"
                            step="any" min="0.01" value="{{ $variation['price'] ? $variation['price'] : '' }}"
                            class="form-control decimal-input" placeholder="0.00" required>
                        <div class="error-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="offer_price[{{ $loop->iteration }}]"
                            id="offer_price_{{ $loop->iteration }}" step="any" min="0"
                            value="{{ $variation['offer_price'] ? $variation['offer_price'] : '' }}"
                            class="form-control decimal-input" placeholder="0.00">
                        <div class="error-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="stock[{{ $loop->iteration }}]" step="1" min="0"
                            value="{{ $variation['stock'] ? $variation['stock'] : '' }}"
                            class="form-control numeric-input stockValue" placeholder="0" required>
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
