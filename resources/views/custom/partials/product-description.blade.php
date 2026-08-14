@if ($product->firstVariant->dimensions != null || $product->dimensions != null)
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#SpecFaqAccoItem_1"
                aria-expanded="true" aria-controls="SpecFaqAccoItem_1">
                Specs
            </button>
        </div>
        <div id="SpecFaqAccoItem_1" class="accordion-collapse collapse show" data-bs-parent="#SpecFaqAcco">
            <div class="accordion-body">
                <div class="ckCntWrap" id="specsInner">
                    {!! $product->firstVariant->dimensions ?? $product->dimensions !!}
                </div>
            </div>
        </div>
    </div>
@endif
@if ($product->firstVariant->features != null || $product->features != null)
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#SpecFaqAccoItem_2" aria-expanded="false" aria-controls="SpecFaqAccoItem_2">
                Features
            </button>
        </div>
        <div id="SpecFaqAccoItem_2" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
            <div class="accordion-body">
                <div class="ckCntWrap">
                    {!! $product->firstVariant->features ?? $product->features !!}
                </div>
            </div>
        </div>
    </div>
@endif
@if ($product->firstVariant->materials_certifications != null || $product->materials_certifications != null)
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#SpecFaqAccoItem_3" aria-expanded="false" aria-controls="SpecFaqAccoItem_3">
                Materials & Certifications
            </button>
        </div>
        <div id="SpecFaqAccoItem_3" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
            <div class="accordion-body">
                <div class="ckCntWrap">
                    {!! $product->firstVariant->materials_certifications ?? $product->materials_certifications !!}
                </div>
            </div>
        </div>
    </div>
@endif
@if ($product->firstVariant->warranty_shipping != null || $product->warranty_shipping != null)
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#SpecFaqAccoItem_4" aria-expanded="false" aria-controls="SpecFaqAccoItem_4">
                Warranty and Shipping
            </button>
        </div>
        <div id="SpecFaqAccoItem_4" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
            <div class="accordion-body">
                <div class="ckCntWrap">
                    {!! $product->firstVariant->warranty_shipping ?? $product->warranty_shipping !!}
                </div>
            </div>
        </div>
    </div>
@endif
