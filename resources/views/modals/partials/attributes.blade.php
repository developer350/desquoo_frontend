@if ($selectedAttributes->isNotEmpty())
    @if ($selectedAttributes->where('attribute.is_main_attribute', 1)->isNotEmpty())
        @php
            $mainAttribute = $selectedAttributes->where('attribute.is_main_attribute', 1)->first();
        @endphp
        <div class="item">
            <div class="selectInputBx">
                <div class="tle">Choose a {{ $mainAttribute['attribute']['name'] }}</div>
                <div class="modalFlx">
                    @foreach ($mainAttribute['values'] as $value)
                        <div class="item">
                            <div class="modalBx">
                                <input name="attribute_values[{{ $mainAttribute['attribute']['id'] }}]" type="radio"
                                    {{ $product->firstVariant->attributeValues->contains('id', $value['id']) ? 'checked' : '' }}
                                    value="{{ $value['id'] }}" class="attributes attributesEditModal">
                                <label class="cntWrap">
                                    {{ $value['value'] }}
                                    {{-- @if ($value['additional_price'] > 0)
                                            &nbsp; <span class="primary">+₹{{ number_format($value['additional_price'],2) }}</span>
                                        @endif --}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    @if ($selectedAttributes->where('attribute.is_main_attribute', 0)->isNotEmpty())
        @php
            $subAttributes = $selectedAttributes->where('attribute.is_main_attribute', 0);
        @endphp
        <div class="item">
            <div class="selectInputBx colorStripWrap">
                <div class="tle">Customise your {{ $product->name }}</div>
                <div class="colorStripFlx">
                    @foreach ($subAttributes as $subAttribute)
                        <div class="item">
                            <div class="colorStrip">
                                <div class="lftSd">
                                    <div class="txt">{{ $subAttribute['attribute']['name'] }}</div>
                                </div>
                                <div class="rgtSd">
                                    <div class="clrFlx">
                                        @foreach ($subAttribute['values'] as $value)
                                            <div class="item">
                                                <div class="colorBx">
                                                    <input name="attribute_values[{{ $subAttribute['attribute']['id'] }}]" type="radio"
                                                        class="attributes attributesEditModal" value="{{ $value['id'] }}"
                                                        {{ $product->firstVariant->attributeValues->contains('id', $value['id']) ? 'checked' : '' }}>
                                                    <label class="cntWrap">
                                                        <span class="icon">
                                                            @if ($value['icon'] == null)
                                                                <div class="rounded-circle bg-light text-muted"
                                                                    style="width:40px; height:40px;">
                                                                    <span class="text-mutes">
                                                                        {{ strtoupper(substr($value['value'], 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <img src="{{ $value['icon'] }}"
                                                                    alt="{{ $value['value'] }}" width="40"
                                                                    height="40">
                                                            @endif
                                                        </span>
                                                        <span class="txt">{{ $value['value'] }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endif
