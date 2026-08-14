<!-- deskTop -->
<div class="addressFlx">
    @foreach ($addresses as $billingAddress)
        <div class="item">
            <div class="adrsBx">
                <input type="radio" class="trigger type" id="billing_address_{{ $billingAddress->id }}"
                    name="billing_address" value="{{ $billingAddress->id }}" required data-trigger="Billingaddress"
                    {{ $loop->first ? 'checked' : '' }}>
                <label for="billing_address_{{ $billingAddress->id }}" class="label">
                    <div class="bxAdrs">
                        <div class="lSide">
                            <div class="title">{{ $billingAddress->name }}</div>
                            <div class="txt">{{ $billingAddress->address_line_1 }}
                                <br>{{ $billingAddress->address_line_2 }}
                                <br>{{ $billingAddress->city }}, {{ $billingAddress->state }} -
                                {{ $billingAddress->postal_code }}
                                <br>{{ $billingAddress->landmark }}
                            </div>
                            <div class="txt">{{ $billingAddress->phone_number }}</div>
                            <div class="txt">{{ $billingAddress->email }}</div>
                        </div>
                        <div class="RSide">
                            <button class="adsIcons edit-address" data-id="{{ $billingAddress->id }}"
                                data-type="billing">
                                <div class="icon">
                                    <img src="{{ asset('frontend/images/icon-edit.svg') }}"
                                        data-src="{{ asset('frontend/images/icon-edit.svg') }}" width="18"
                                        height="18" alt="Edit">
                                </div>
                                <div class="txt">{{ __('edit') }}</div>
                            </button>
                            <button class="adsIcons delete-address" data-id="{{ $billingAddress->id }}"
                                data-type="billing">
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
        @foreach ($addresses as $billingAddress)
            <div class="swiper-slide">
                <div class="adrsBx">
                    <input type="radio" class="trigger type" id="billing_address_mob_{{ $billingAddress->id }}"
                        name="billing_address" value="{{ $billingAddress->id }}" required data-trigger="Billingaddress"
                        {{ $loop->first ? 'checked' : '' }}>
                    <label for="billing_address_mob_{{ $billingAddress->id }}" class="label">
                        <div class="bxAdrs">
                            <div class="lSide">
                                <div class="title">{{ $billingAddress->name }}</div>
                                <div class="txt">{{ $billingAddress->address_line_1 }}
                                    <br>{{ $billingAddress->address_line_2 }}
                                    <br>{{ $billingAddress->city }}, {{ $billingAddress->state }} -
                                    {{ $billingAddress->postal_code }}
                                    <br>{{ $billingAddress->landmark }}
                                </div>
                                <div class="txt">{{ $billingAddress->phone_number }}</div>
                                <div class="txt">{{ $billingAddress->email }}</div>
                            </div>
                            <div class="RSide">
                                <button class="adsIcons edit-address" data-id="{{ $billingAddress->id }}"
                                    data-type="billing">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/images/icon-edit.svg') }}"
                                            data-src="{{ asset('frontend/images/icon-edit.svg') }}" width="18"
                                            height="18" alt="Edit">
                                    </div>
                                    <div class="txt">{{ __('edit') }}</div>
                                </button>
                                <button class="adsIcons delete-address" data-id="{{ $billingAddress->id }}"
                                    data-type="billing">
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
