<div class="cmnFlx">
    <div class="lftBx">
        <div class="topBx">
            <div class="cmnBx">
                @php
                    $loopIndex = 0;
                    $attributeIds = explode(',', $product->firstVariant->variant_name);
                @endphp
                @foreach ($groupedByAttribute as $attribute)
                    @if ($attribute['attribute']->is_main_attribute)
                        @continue
                    @endif
                    @php
                        $loopIndex++;
                    @endphp
                    <div class="title">{{ $attribute['attribute']->name }}</div>
                    <div class="tableList">
                        @foreach ($attribute['values'] as $value)
                            <div class="itemList">
                                <div class="changeCustomVariant {{ $loopIndex == 1 ? 'FrameBx' : 'tableTopBx' }} {{ in_array($value['product_attribute_value_media']->attribute_value_id, $attributeIds) ? 'active' : '' }}"
                                    data-name="{{ $value['product_attribute_value_media']->title }}"
                                    data-img="{{ $value['product_attribute_value_media']->image_value }}"
                                    data-attribute-id="{{ $attribute['attribute']->id }}"
                                    data-attribute-value-id="{{ $value['product_attribute_value_media']->attribute_value_id }}">
                                    <div class="tileWrp">
                                        <div class="icon">
                                            @if ($value['attribute_value']->icon_value != null)
                                                <img src="{{ $value['attribute_value']->icon_value }}" width="108"
                                                    height="80" alt="{{ $value['attribute_value']->value }}">
                                            @else
                                                <div class="colorBx" style="background: #202020"></div>
                                            @endif
                                            <div class="checkBx">
                                                <svg width="25" height="24" viewBox="0 0 25 24">
                                                    <circle cx="12.7197" cy="12" r="12" fill="#111111" />
                                                    <path d="M7.71973 12.8889L11.1043 16L18.7197 9" stroke="white"
                                                        stroke-width="1.25" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="txt">{{ $value['product_attribute_value_media']->title }}</div>
                                    </div>
                                    @if ($value['product_attribute_value_media']->is_default)
                                        <div class="price">Default</div>
                                    @elseif($value['product_attribute_value_media']->price != null && $value['product_attribute_value_media']->price != 0)
                                        <div class="price">+₹{{ $value['product_attribute_value_media']->price }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div class="btmBx">
            <div class="flxBx">
                <div class="txt">Your Total</div>
                <div class="Amt amountChange">₹{{ $product->firstVariant->last_price }}</div>
            </div>
            <div class="buttons">
                <button class="prev" onclick="nextStep(1)"> Back</button>
                <button class="next full" onclick="nextStep(3)">
                    Select Add-ons
                    <div class="icon">
                        <svg viewBox="0 0 21 20">
                            <path d="M3.5531 10H16.8864M16.8864 10L11.8864 5M16.8864 10L11.8864 15" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <div class="rtBxfx">
        <div class="contentBx">
            <div class="mainTxt">Customise your {{ $product->name }}</div>
            <div class="title" id="step2title"></div>
            <div class="franeBox">
                @php
                    $mainAttributeCount = collect($groupedByAttribute)
                        ->where('attribute.is_main_attribute', 0)
                        ->count();
                @endphp
                <div class="Tframe">
                    <img src="" alt="Table Frame" class="table-frame active">
                </div>
                @if ($mainAttributeCount >= 2)
                    <div class="topBxo">
                        <img id="tableTop" class="table-top active" src="" alt="Table Top">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
