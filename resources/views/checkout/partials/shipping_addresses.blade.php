<!-- deskTop -->
<div class="addressFlx">
    @foreach ($addresses as $shippingAddress)
        <div class="item">
            <div class="adrsBx">
                <input type="radio" class="trigger type" id="shipping_address_{{ $shippingAddress->id }}"
                    name="shipping_address" value="{{ $shippingAddress->id }}" required data-trigger="Shippingaddress"
                    {{ $loop->first ? 'checked' : '' }}>
                <label for="shipping_address_{{ $shippingAddress->id }}" class="label">
                    <div class="bxAdrs">
                        <div class="lSide">
                            <div class="title">{{ $shippingAddress->name }}</div>
                            <div class="txt">{{ $shippingAddress->address_line_1 }}
                                <br>{{ $shippingAddress->address_line_2 }}
                                <br>{{ $shippingAddress->city }}, {{ $shippingAddress->state }} -
                                {{ $shippingAddress->postal_code }}
                                <br>{{ $shippingAddress->landmark }}
                            </div>
                            <div class="txt">{{ $shippingAddress->phone_number }}</div>
                            <div class="txt">{{ $shippingAddress->email }}</div>
                        </div>
                        <div class="RSide">
                            <button class="adsIcons edit-address" data-id="{{ $shippingAddress->id }}"
                                data-type="shipping">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon-edit.svg') }}"
                                        data-src="{{ asset('frontend/images/icon-edit.svg') }}" width="18"
                                        height="18" alt="Edit">
                                </div>
                                <div class="txt">{{ __('edit') }}</div>
                            </button>
                            <button class="adsIcons delete-address" data-id="{{ $shippingAddress->id }}"
                                data-type="shipping">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon-dlt.svg') }}"
                                        data-src="{{ asset('frontend/images/icon-dlt.svg') }}" width="18"
                                        height="18" alt="Delete">
                                </div>
                                <div class="txt">{{ __('delete') }}</div>
                            </button>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    @endforeach
</div>

<!-- mobile address slide -->
<div class="swiper adrs-Slider">
    <div class="swiper-wrapper">
        @foreach ($addresses as $shippingAddress)
            <div class="swiper-slide">
                <div class="adrsBx">
                    <input type="radio" class="trigger type" id="shipping_address_mob_{{ $shippingAddress->id }}"
                        name="shipping_address" value="{{ $shippingAddress->id }}" required
                        data-trigger="Shippingaddress" {{ $loop->first ? 'checked' : '' }}>
                    <label for="shipping_address_mob_{{ $shippingAddress->id }}" class="label">
                        <div class="bxAdrs">
                            <div class="lSide">
                                <div class="title">{{ $shippingAddress->name }}</div>
                                <div class="txt">{{ $shippingAddress->address_line_1 }}
                                    <br>{{ $shippingAddress->address_line_2 }}
                                    <br>{{ $shippingAddress->city }}, {{ $shippingAddress->state }} -
                                    {{ $shippingAddress->postal_code }}
                                    <br>{{ $shippingAddress->landmark }}
                                </div>
                                <div class="txt">{{ $shippingAddress->phone_number }}</div>
                                <div class="txt">{{ $shippingAddress->email }}</div>
                            </div>
                            <div class="RSide">
                                <button class="adsIcons edit-address" data-id="{{ $shippingAddress->id }}"
                                    data-type="shipping">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/images/icon-edit.svg') }}"
                                            data-src="{{ asset('frontend/images/icon-edit.svg') }}" width="18"
                                            height="18" alt="Edit">
                                    </div>
                                    <div class="txt">{{ __('edit') }}</div>
                                </button>
                                <button class="adsIcons delete-address" data-id="{{ $shippingAddress->id }}"
                                    data-type="shipping">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/images/icon-dlt.svg') }}"
                                            data-src="{{ asset('frontend/images/icon-dlt.svg') }}" width="18"
                                            height="18" alt="Delete">
                                    </div>
                                    <div class="txt">{{ __('delete') }}</div>
                                </button>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>
