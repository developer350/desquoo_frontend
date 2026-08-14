<table class="table dt-responsive" style="width:100%">
    <thead>
        <tr>
            <th>Price*</th>
            <th>Offer Price</th>
            <th>Stock*</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="form-group">
                    <input type="number" name="price" id="single_price" step="any" min="0.01"
                        value="{{ isset($product) && $product->singleVariant ? $product->singleVariant->price : '' }}"
                        class="form-control decimal-input" placeholder="0.00" required>
                    <div class="error-block"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="offer_price" id="single_offer_price" step="any" min="0"
                        value="{{ isset($product) && $product->singleVariant ? $product->singleVariant->offer_price : '' }}"
                        class="form-control decimal-input" placeholder="0.00" data-rule-lessThanCompare="#single_price">
                    <div class="error-block"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="stock" step="1" min="0"
                        value="{{ isset($product) && $product->singleVariant ? $product->singleVariant->stock : '' }}" required
                        class="form-control numeric-input stockValue" placeholder="0" {{ isset($product) && $product->is_manage_stock ? '' : 'readonly' }}>
                    <div class="error-block"></div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
