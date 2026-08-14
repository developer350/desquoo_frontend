<input type="hidden" name="product_id" value="{{ $product->id }}">
<input type="hidden" name="variant_id" value="{{ $product->firstVariant->id }}">
<input type="hidden" name="is_added" value="{{ is_added($product->firstVariant->id) }}">
<input type="hidden" name="out_of_stock" value="{{ $product->is_manage_stock && $product->firstVariant->stock == 0 }}">
@if ($product->is_manage_stock && $product->firstVariant->stock == 0)
    <div class="orderBtnFlx">
        <div class="item outStockWrap">
            <button class="baseBtn_1 outStockBtn hoveranim" aria-label="Out of Stock" disabled>
                <span>Out of Stock</span>
            </button>
        </div>
        <div class="item notifyWrap">
            <button type="button" class="baseBtn_1 hoveranim" data-variant-id="{{ $product->firstVariant->id }}" data-product-id="{{ $product->id }}" id="notifyProduct">
                <span class="icon">
                    <img src="{{ asset('frontend/images/icon-notify.svg') }}" width="20" height="20"
                        alt="icon-notify">
                </span>
                <span>Notify Me</span>
            </button>
        </div>
    </div>
@else
    <div class="orderBtnFlx MinimumOrderWrap">
        {{-- @if (is_added($product->firstVariant->id))
            <div class="item">
                <a href="javascript:void(0)" class="baseBtn_1 outStockBtn hoveranim" aria-label="added to cart"
                    aria-disabled="true">
                    <span>Added</span>
                </a>
            </div>
        @else --}}
            <div class="item">
                <a href="javascript:void(0)" class="baseBtn_1 hoveranim addToCart" aria-label="add to cart"
                    data-product-id="{{ $product->id }}" data-type="{{ $product->type }}"
                    data-variant-id="{{ $product->firstVariant->id }}">
                    <span>Add To Cart</span>
                </a>
            </div>
        {{-- @endif --}}
    </div>
@endif
