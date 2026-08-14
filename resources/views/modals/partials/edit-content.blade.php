<div class="headWrp">
    <h1 class="title">{{ $product->name }} </h1>
    <button class="btnClose" data-bs-dismiss="modal" aria-label="Close">
        <div class="icon">
            <svg viewBox="0 0 24 24">
                <path d="M18 6L6 18M6 6L18 18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
</div>

<div class="editList">
    <div class="imgItm">
        <img src="{{ $product->firstVariant->image_value ?? $product->image_value }}"
            alt="{{ $product->firstVariant->image_alt_text ?? $product->image_alt_text }}" width="500"
            height="500" id="editimageSection">
    </div>
    <div class="selectInputBx colorStripWrap">
        <div class="tle">Customise your {{ $product->name }}</div>
        @include('modals.partials.attributes')
    </div>
    <div class="aDons">
        <div class="tle">Add ons</div>
        @foreach ($product->addons as $addon)
            <div class="addOnBx">
                <div class="imgWrap">
                    <img src="{{ $addon->image_value }}" width="208" height="153" loading="lazy"
                        alt="{{ $addon->image_alt_text }}">
                </div>
                <div class="cntWrap">
                    <div class="tle">{{ $addon->name }}</div>
                    <div class="txt">
                        <span>{{ $addon->category->name }}</span>
                    </div>
                    <div class="txt price">
                        ₹{{ $addon->firstVariant->last_price }}
                    </div>
                    <div class="btnwrp">
                        {{-- @if (is_added($addon->firstVariant->id))
                                                                        <a href="javascript:void(0)"
                                                                            class="outStockBtn">Added</a>
                                                                    @else --}}
                        <a href="javascript:void(0)" data-product-id="{{ $addon->id }}"
                            data-type="{{ $addon->type }}" data-variant-id="{{ $addon->firstVariant->id }}"
                            class="btn add addAddonFromEditToCart">Add to Cart</a>
                        {{-- @endif --}}
                        •
                        +₹{{ $addon->firstVariant->last_price }}
                    </div>
                </div>
                <div class="addCartBtn">+</div>
            </div>
        @endforeach
    </div>
</div>
<div class="btmBx">
    <button type="button" class="hoveranim cmnBtn" id="editChangeVariant" data-cart-id="{{ $cart->id }}">
        <span>Confirm</span>
    </button>
</div>
