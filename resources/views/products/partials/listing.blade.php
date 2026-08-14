@forelse ($products as $product)
    <div class="item">
        @include('products.partials.single-item')
    </div>
@empty
    <div class="col-12 text-center py-5">
        <h4 class="mb-0">No Products Available</h4>
    </div>
@endforelse
