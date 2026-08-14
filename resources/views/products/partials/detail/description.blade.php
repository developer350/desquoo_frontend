<div class="container">
    <div class="mFlx">
        @if ($product->firstVariant->desc_image_value || $product->desc_image_value)
            <div class="lftSd">
                <div class="imgWrap">
                    <img src="{{ $product->firstVariant->desc_image_value ?? $product->desc_image_value }}" alt="nav"
                        width="668" height="1000" loading="lazy">
                </div>
            </div>
        @endif
        <div class="rgtSd">
            <div class="accordion specFaqAcco" id="SpecFaqAcco">
                @if ($product->firstVariant->features || $product->features)
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#SpecFaqAccoItem_1" aria-expanded="false"
                                aria-controls="SpecFaqAccoItem_1">
                                Features
                            </button>
                        </div>
                        <div id="SpecFaqAccoItem_1" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
                            <div class="accordion-body">
                                <div class="ckCntWrap">
                                    {!! $product->firstVariant->features ?? $product->features !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($product->firstVariant->warranty_shipping || $product->warranty_shipping)
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#SpecFaqAccoItem_5" aria-expanded="false"
                                aria-controls="SpecFaqAccoItem_5">
                                Warranty & Shipping
                            </button>
                        </div>
                        <div id="SpecFaqAccoItem_5" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
                            <div class="accordion-body">
                                <div class="ckCntWrap">
                                    {!! $product->firstVariant->warranty_shipping ?? $product->warranty_shipping !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($product->firstVariant->dimensions || $product->dimensions)
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#SpecFaqAccoItem_2" aria-expanded="false"
                                aria-controls="SpecFaqAccoItem_2">
                                Measurement & Dimensions
                            </button>
                        </div>
                        <div id="SpecFaqAccoItem_2" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
                            <div class="accordion-body">
                                <div class="ckCntWrap">
                                    {!! $product->firstVariant->dimensions ?? $product->dimensions !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($product->firstVariant->materials_certifications || $product->materials_certifications)
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#SpecFaqAccoItem_4" aria-expanded="false"
                                aria-controls="SpecFaqAccoItem_4">
                                Material & Certfication
                            </button>
                        </div>
                        <div id="SpecFaqAccoItem_4" class="accordion-collapse collapse" data-bs-parent="#SpecFaqAcco">
                            <div class="accordion-body">
                                <div class="ckCntWrap">
                                    {!! $product->firstVariant->materials_certifications ?? $product->materials_certifications !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
