<a href="{{ route('product-detail', ['slug' => $product->slug]) }}" aria-label="product" class="proBx">
    @if ($product->is_best_seller)
        <div class="infoB">
            <div class="info">Bestseller</div>
        </div>
    @endif
    <div class="imgBx">
        <img src="{{ $product->image_value }}" width="580" height="580" loading="lazy" alt="call">
    </div>
    <div class="txtBx">
        <div class="lftB">
            <div class="title">{{ $product->name }}</div>
            <div class="price"> <span>₹{{ $product->firstVariant->last_price }}/-</span>
            </div>
        </div>
        <div class="ritB">
            <div class="colrFlx">
                @foreach ($product->productDefaultAttributeValues as $productDefaultAttributeValue)
                    <div class="itm">
                        @if ($productDefaultAttributeValue->hasMedia('icon'))
                            <img src="{{ $productDefaultAttributeValue->icon_value }}"
                                alt="{{ $productDefaultAttributeValue->value }}" width="53" height="53">
                        @else
                            <div class="rounded-circle bg-dark" style="width:53px; height:53px;">
                                <span class="text-white">
                                    {{ strtoupper(substr($productDefaultAttributeValue->value, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</a>
