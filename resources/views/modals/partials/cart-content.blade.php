@if ($carts->isEmpty() && $addonCarts->isEmpty())
    <div class="emptyCart">
        <div class="emptyBx">
            <div class="wrpabx">
                <div class="imgBx">
                    <img src="{{ asset('frontend/images/cartEmpty.png') }}" width="300" height="270" alt="emptyCart">
                </div>
                <div class="titles">Looks like your cart is empty</div>
                <div class="txt">But your next great workspace could be just a click away!</div>
            </div>
        </div>
        <a href="{{ route('product-listing') }}" class="checkoutBtn hoveranim" aria-label="checkout_Btn">
            <span>Explore Products</span>
        </a>
    </div>
@else
    <div class="headWrp">
        <h1 class="title">Your Cart <span id="totalItems">{{ $totalItems }}</span></h1>
        <button class="btnClose" data-bs-dismiss="modal" aria-label="Close">
            <div class="icon">
                <svg viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6L18 18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </button>
    </div>

    <div class="cartList">
        <div class="cartBox">
            <div class="ListItem">
                <div class="item">

                    {{-- table --}}
                    @foreach ($carts as $cart)
                        <div class="cartItemBx">
                            <div class="leftbx">
                                <div class="imgBx">
                                    <img src="{{ $cart->productVariant->image_value ?? $cart->product->image_value }}"
                                        width="154" height="154"
                                        alt="{{ $cart->productVariant->image_alt_text ?? $cart->product->image_alt_text }}">
                                </div>
                            </div>
                            <div class="rtBx">
                                <div class="contentbx">
                                    <div class="flx">
                                        <div class="name">{{ $cart->product->name }}</div>
                                        <div class="name">₹{{ $cart->productVariant->last_price }}</div>
                                    </div>
                                    <div class="catFlx">
                                        @foreach ($cart->productVariant->attributeValues as $attributeValue)
                                            <div class="cmNx">
                                                <div class="icon">
                                                    @if ($attributeValue->icon_value != null)
                                                        <img src="{{ $attributeValue->icon_value }}" width="16"
                                                            height="16" alt="{{ $attributeValue->value }}">
                                                    @else
                                                        <div class="rounded-circle" style="width:16px;height:16px;">
                                                            <span class="text-black">
                                                                {{ strtoupper(substr($attributeValue->value, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span>{{ $attributeValue->value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="quntyBx">
                                    <div class="qtyFlx">
                                        <button type="button" class="qtyBtn minus" data-id="{{ $cart->id }}">
                                            <div class="icon">
                                                <svg viewBox="0 0 17 16" fill="none">
                                                    <path d="M1.45312 7.64062H15.5469V9.23438H1.45312V7.64062Z" />
                                                </svg>
                                            </div>
                                        </button>
                                        <input type="number" step="1" min="1"
                                            name="quantity[{{ $cart->id }}]" value="{{ $cart->quantity }}"
                                            class="inputForm quantity" inputmode="numeric"
                                            data-id="{{ $cart->id }}">
                                        <button type="button" class="qtyBtn plus" data-id="{{ $cart->id }}">
                                            <div class="icon">
                                                <svg viewBox="0 0 17 16" fill="none">
                                                    <path
                                                        d="M15.2188 7.64062H9.14062V1.5625H7.54688V7.64062H1.14062V9.5625H7.54688V15.6406H9.14062V9.5625H15.2188V7.64062Z" />
                                                </svg>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="actnBtns">
                                        @if ($cart->productVariant->attributeValues->isNotEmpty())
                                            <div class="item">
                                                <button class="cBtns changeVariant" data-cart-id="{{ $cart->id }}"
                                                    data-product-id="{{ $cart->product_id }}">
                                                    <div class="icon">
                                                        <svg viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M18 10.0003L14 6.0003M2.5 21.5003L5.88437 21.1243C6.29786 21.0783 6.5046 21.0553 6.69785 20.9928C6.86929 20.9373 7.03245 20.8589 7.18289 20.7597C7.35245 20.6479 7.49955 20.5008 7.79373 20.2066L21 7.0003C22.1046 5.89573 22.1046 4.10487 21 3.0003C19.8955 1.89573 18.1046 1.89573 17 3.0003L3.79373 16.2066C3.49955 16.5008 3.35246 16.6478 3.24064 16.8174C3.14143 16.9679 3.06301 17.131 3.00751 17.3025C2.94496 17.4957 2.92198 17.7024 2.87604 18.1159L2.5 21.5003Z"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div>
                                        @endif
                                        <div class="item">
                                            <button type="button" class="cBtns deleteBtn"
                                                data-id="{{ $cart->id }}">
                                                <div class="icon">
                                                    <svg viewBox="0 0 20 20" fill="none">
                                                        <path
                                                            d="M7 1H13M1 4H19M17 4L16.2987 14.5193C16.1935 16.0975 16.1409 16.8867 15.8 17.485C15.4999 18.0118 15.0472 18.4353 14.5017 18.6997C13.882 19 13.0911 19 11.5093 19H8.49065C6.90891 19 6.11803 19 5.49834 18.6997C4.95276 18.4353 4.50009 18.0118 4.19998 17.485C3.85911 16.8867 3.8065 16.0975 3.70129 14.5193L3 4M8 8.5V13.5M12 8.5V13.5"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($addonCarts->isNotEmpty())
                        {{-- addons  --}}
                        <div class="subTitle">Addons</div>

                        <div class="addonList">
                            @foreach ($addonCarts as $addonCart)
                                <div class="item">
                                    <div class="addonItemBx">
                                        <div class="leftbx">
                                            <div class="imgBx">
                                                <img src="{{ $addonCart->productVariant->image_value ?? $addonCart->product->image_value }}"
                                                    width="95" height="95"
                                                    alt="{{ $addonCart->productVariant->image_alt_text ?? $addonCart->product->image_alt_text }}">
                                            </div>
                                        </div>
                                        <div class="rtBx">
                                            <div class="contentbx">
                                                <div class="flx">
                                                    <div class="name">{{ $addonCart->product->name }}
                                                    </div>
                                                    <div class="name">
                                                        ₹{{ $addonCart->productVariant->last_price }}</div>
                                                </div>
                                                <div class="catFlx">
                                                    @foreach ($addonCart->productVariant->attributeValues as $attributeValue)
                                                        <div class="cmNx">
                                                            <div class="icon">
                                                                @if ($attributeValue->icon_value != null)
                                                                    <img src="{{ $attributeValue->icon_value }}"
                                                                        width="16" height="16"
                                                                        alt="{{ $attributeValue->value }}">
                                                                @else
                                                                    <div class="rounded-circle"
                                                                        style="width:16px;height:16px;">
                                                                        <span class="text-black">
                                                                            {{ strtoupper(substr($attributeValue->value, 0, 1)) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span>{{ $attributeValue->value }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="quntyBx">
                                                <div class="qtyFlx">
                                                    <button type="button" class="qtyBtn minus addon"
                                                        data-id="{{ $addonCart->id }}">
                                                        <div class="icon">
                                                            <svg viewBox="0 0 17 16" fill="none">
                                                                <path
                                                                    d="M1.45312 7.64062H15.5469V9.23438H1.45312V7.64062Z" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                    <input type="number" step="1" min="1"
                                                        name="quantity[{{ $addonCart->id }}]" class="inputForm"
                                                        inputmode="numeric" value="{{ $addonCart->quantity }}">
                                                    <button type="button" class="qtyBtn plus addon"
                                                        data-id="{{ $addonCart->id }}">
                                                        <div class="icon">
                                                            <svg viewBox="0 0 17 16" fill="none">
                                                                <path
                                                                    d="M15.2188 7.64062H9.14062V1.5625H7.54688V7.64062H1.14062V9.5625H7.54688V15.6406H9.14062V9.5625H15.2188V7.64062Z" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                </div>

                                                <div class="actnBtns">
                                                    <div class="item">
                                                        <button type="button" class="cBtns deleteBtn addon"
                                                            data-id="{{ $addonCart->id }}">
                                                            <div class="icon">
                                                                <svg viewBox="0 0 20 20" fill="none">
                                                                    <path
                                                                        d="M7 1H13M1 4H19M17 4L16.2987 14.5193C16.1935 16.0975 16.1409 16.8867 15.8 17.485C15.4999 18.0118 15.0472 18.4353 14.5017 18.6997C13.882 19 13.0911 19 11.5093 19H8.49065C6.90891 19 6.11803 19 5.49834 18.6997C4.95276 18.4353 4.50009 18.0118 4.19998 17.485C3.85911 16.8867 3.8065 16.0975 3.70129 14.5193L3 4M8 8.5V13.5M12 8.5V13.5"
                                                                        stroke-width="1.5" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="btmBx">
        @include('modals.partials.summary')
    </div>
@endif
