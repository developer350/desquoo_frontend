<div class="cmnFlx">
    <div class="lftBx">
        <div class="topBx">
            <div class="cmnBx">
                @if ($product->addons->isNotEmpty())
                    <div class="addonList">
                        @foreach ($product->addons as $addon)
                            <div class="item">
                                <div class="addonBox {{ is_added($addon->firstVariant->id) ? 'active' : '' }}">
                                    <div class="ltbx">
                                        <div class="imgbx">
                                            <img src="{{ $addon->image_value }}" width="108" height="80"
                                                alt="{{ $addon->image_alt_text }}">
                                        </div>
                                    </div>
                                    <div class="rtBx">
                                        <div class="contNx">
                                            <div class="tile">{{ $addon->name }}</div>
                                            <div class="subT">{{ $addon->category->name }}</div>
                                            <div class="amt">₹{{ $addon->firstVariant->last_price }}</div>
                                        </div>
                                        <div class="flx">
                                            <button type="button" class="cmBtn addBtn" aria-label="add_button">
                                                @if (is_added($addon->firstVariant->id))
                                                    <span class="added">Added</span>
                                                    <button class="cmBtn remove removeAddon"
                                                        data-addon-id="{{ $addon->id }}"
                                                        data-variant-id="{{ $addon->firstVariant->id }}"
                                                        aria-label="Remove_button">Remove</button>
                                                @else
                                                    <span class="price addAddon" data-addon-id="{{ $addon->id }}"
                                                        data-variant-id="{{ $addon->firstVariant->id }}">Add to desk •
                                                        +₹{{ $addon->firstVariant->last_price }}</span>
                                                @endif
                                            </button>
                                        </div>

                                        {{-- mpbile Buttons --}}

                                        <div class="mobFlx active">

                                            @if (is_added($addon->firstVariant->id))
                                                <button class="mobAddBtn removeBtn removeAddon"
                                                    data-addon-id="{{ $addon->id }}"
                                                    data-variant-id="{{ $addon->firstVariant->id }}">
                                                    <div class="icon">
                                                        <svg viewBox="0 0 8 2">
                                                            <path d="M1 1.22461H7" stroke="white" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>

                                                    </div>
                                                </button>
                                            @else
                                                <button class="mobAddBtn addBtn addAddon"
                                                    data-addon-id="{{ $addon->id }}"
                                                    data-variant-id="{{ $addon->firstVariant->id }}">
                                                    <div class="icon">
                                                        <svg viewBox="0 0 8 8">
                                                            <path d="M4 1.22461V7.22461M1 4.22461H7" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </button>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="addonList">
                        <div class="item">
                            <p>No add-on available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="btmBx">
            <div class="flxBx">
                <div class="txt">Your Total</div>
                <div class="Amt amountChange">₹{{ $product->firstVariant->last_price }}</div>
            </div>
            <div class="buttons">
                <button class="prev" onclick="nextStep(2)"> Back</button>
                <button class="next full" id="customAddToCart">
                    Add to Cart
                    <div class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <path
                                d="M18.0264 13.709H6.39551L6.97949 12.5195L16.6826 12.502C17.0107 12.502 17.292 12.2676 17.3506 11.9434L18.6943 4.42187C18.7295 4.22461 18.6768 4.02148 18.5478 3.86719C18.4841 3.79125 18.4047 3.73008 18.315 3.68792C18.2252 3.64577 18.1274 3.62363 18.0283 3.62305L5.68457 3.58203L5.5791 3.08594C5.5127 2.76953 5.22754 2.53906 4.90332 2.53906H1.88574C1.70289 2.53906 1.52752 2.6117 1.39823 2.741C1.26893 2.8703 1.19629 3.04566 1.19629 3.22852C1.19629 3.41137 1.26893 3.58674 1.39823 3.71603C1.52752 3.84533 1.70289 3.91797 1.88574 3.91797H4.34473L4.80566 6.10937L5.94043 11.6035L4.47949 13.9883C4.40362 14.0907 4.35793 14.2123 4.34757 14.3393C4.33722 14.4663 4.36262 14.5937 4.4209 14.707C4.53809 14.9395 4.77441 15.0859 5.03613 15.0859H6.26269C6.00121 15.4332 5.85997 15.8563 5.86035 16.291C5.86035 17.3965 6.75879 18.2949 7.86426 18.2949C8.96973 18.2949 9.86816 17.3965 9.86816 16.291C9.86816 15.8555 9.72363 15.4316 9.46582 15.0859H12.6123C12.3508 15.4332 12.2096 15.8563 12.21 16.291C12.21 17.3965 13.1084 18.2949 14.2139 18.2949C15.3193 18.2949 16.2178 17.3965 16.2178 16.291C16.2178 15.8555 16.0732 15.4316 15.8154 15.0859H18.0283C18.4072 15.0859 18.7178 14.7773 18.7178 14.3965C18.7166 14.2138 18.6433 14.039 18.5138 13.9102C18.3842 13.7814 18.209 13.7091 18.0264 13.709ZM5.97168 4.94141L17.2178 4.97852L16.1162 11.1465L7.28223 11.1621L5.97168 4.94141ZM7.86426 16.9082C7.52441 16.9082 7.24707 16.6309 7.24707 16.291C7.24707 15.9512 7.52441 15.6738 7.86426 15.6738C8.2041 15.6738 8.48144 15.9512 8.48144 16.291C8.48144 16.4547 8.41642 16.6117 8.30067 16.7274C8.18493 16.8432 8.02795 16.9082 7.86426 16.9082ZM14.2139 16.9082C13.874 16.9082 13.5967 16.6309 13.5967 16.291C13.5967 15.9512 13.874 15.6738 14.2139 15.6738C14.5537 15.6738 14.8311 15.9512 14.8311 16.291C14.8311 16.4547 14.766 16.6117 14.6503 16.7274C14.5345 16.8432 14.3776 16.9082 14.2139 16.9082Z"
                                fill="white" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <div class="rtBxfx">
        <div class="contentBx">

            <div class="titleBz">
                <div class="mainTxt">Select add-ons to complete your {{ $product->name }}</div>
                <div class="title" id="currentSetup">Current Setup : {{ $currentSelectedVariantName }} </div>
            </div>

            <div class="tablewrapBox">
                <div class="tableStand">
                    <img src="{{ $product->firstVariant->image_value ?? $product->image_value }}" width="108"
                        height="80" alt="table" id="variantImage">
                </div>
            </div>

            {{-- add on slider --}}

            @if ($addedAddons->isNotEmpty())
                <div class="addonWrpBox">
                    <div class="title mob">Addons</div>

                    <div class="addonSlider">
                        <div class="swiper-wrapper">
                            @foreach ($addedAddons as $addedAddon)
                                <div class="swiper-slide">
                                    <div class="addonWrap">
                                        <div class="lftBix">
                                            <div class="imgBx">
                                                <img src="{{ $addedAddon->image_value }}" width="160" height="160"
                                                    alt="addon">
                                            </div>
                                        </div>
                                        <div class="rtbx">
                                            <div class="contents">
                                                <div class="titles">{{ $addedAddon->name }}</div>
                                                <div class="catGr">{{ $addedAddon->category->name }}</div>
                                            </div>
                                            <button class="removeButtn removeAddon" data-addon-id="{{ $addon->id }}"
                                                data-variant-id="{{ $addon->firstVariant->id }}">
                                                <div class="icon">
                                                    <svg viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                            stroke="#8E9194" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>

                                                </div>
                                                <span>Remove</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
