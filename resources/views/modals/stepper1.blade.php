<div class="cmnFlx">
    <div class="lftBx">
        <div class="topBx">
            <div class="cmnBx">
                <div class="listBox">
                    @php
                        $mainAttributeValues = $productAttributeValueMedias
                            ->filter(function ($media) {
                                return $media->attribute && $media->attribute->is_main_attribute == 1;
                            })
                            ->sortBy('sort_order');
                    @endphp
                    @php
                        $attributeIds = explode(',', $product->firstVariant->variant_name);
                    @endphp
                    @foreach ($mainAttributeValues as $mainAttributeValue)
                        <div class="listItem">
                            <div class="tableBx changeCustomVariant {{ in_array($mainAttributeValue->attribute_value_id, $attributeIds) ? 'active' : '' }}"
                                data-w="{{ $mainAttributeValue->width }}" data-d="{{ $mainAttributeValue->depth }}"
                                data-h="{{ $mainAttributeValue->height }}"
                                data-attribute-id="{{ $mainAttributeValue->attribute->id }}"
                                data-attribute-value-id="{{ $mainAttributeValue->attribute_value_id }}"
                                data-img="{{ $mainAttributeValue->image_value }}"
                                data-desc="{{ $mainAttributeValue->description }}">
                                <div class="leftbx">
                                    <div class="imgBx">
                                        <img src="{{ $mainAttributeValue->image_value }}" width="108" height="80"
                                            alt="{{ $mainAttributeValue->title }}">
                                    </div>
                                </div>
                                <div class="rtBx">
                                    <div class="txt">{{ $product->name }} <br> {{ $mainAttributeValue->title }}</div>
                                    <div class="price">₹{{ $mainAttributeValue->price }}</div>
                                    <div class="checkBx">
                                        <svg width="25" height="24" viewBox="0 0 25 24">
                                            <circle cx="12.7197" cy="12" r="12" fill="#111111" />
                                            <path d="M7.71973 12.8889L11.1043 16L18.7197 9" stroke="white"
                                                stroke-width="1.25" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="btmBx">
            <div class="flxBx">
                <div class="txt">Your Total</div>
                <div class="Amt amountChange">₹{{ $product->firstVariant->last_price }}</div>
            </div>
            <div class="buttons">
                <button class="next full" onclick="nextStep(2)">
                    Next Step - Colour
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
            <div class="mainTxt">Select a model that works best for your work setup</div>
            <div class="title" id="step1-desc"></div>
            <div class="desk-area">
                <div class="desk-holder" id="holder">
                    <div class="tableSec">
                        <img alt="Desk preview" id="deskImg" />
                        <div class="ruler" id="ruler">
                            <span class="ruler-label" id="wLabel">120 cm</span>
                            <div id="depth">
                                <div class="depth-line"></div>
                                <span id="dLabel"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
