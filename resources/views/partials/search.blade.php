<div class="search-results">
    <ul class="item">
        @forelse ($products as $product)
            <li>
                <a href="{{ route('product-detail', $product->slug) }}" class="prodBx" aria-label="{{ $product->name }}">
                    <div class="imgBx">
                        <img src="{{ $product->image_value }}" alt="{{ $product->image_alt_text ?? $product->name }}"
                            loading="lazy" />
                    </div>
                    <div class="txtB">
                        <div class="name">{{ $product->name }}</div>
                        <div class="txt">{{ $product->category->name }}</div>
                    </div>
                </a>
            </li>
        @empty
            <li>No Products Found</li>
        @endforelse
    </ul>
</div>
