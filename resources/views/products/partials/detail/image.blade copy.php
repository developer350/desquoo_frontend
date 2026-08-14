<div class="proMainWrap">
    <div class="tagWrap desktop">
        @if ($product->firstVariant->three_d_value != null || $product->three_d_value != null)
            <div class="item">
                <button type="button" class="tagBx view3d"
                    data-src="{{ $product->firstVariant->three_d_value ?? $product->three_d_value }}">
                    <span>360˚</span>
                </button>
            </div>
        @endif
        @if ($product->firstVariant->qr_value != null || $product->qr_value != null)
            <div class="item">
                <button type="button" class="tagBx viewAr" data-src="{{ $product->firstVariant->qr_value ?? $product->qr_value }}">
                    <span>View in AR</span>
                </button>
            </div>
        @endif
    </div>
    <div class="swiper proMainSlide">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="proMainBx">
                    <a class="MagicZoom" href="{{ $product->firstVariant->image_value ?? $product->image_value }}"
                        data-zoom-id="zoom2"
                        data-image="{{ $product->firstVariant->image_value ?? $product->image_value }}">
                        <img decoding="async" src="{{ $product->firstVariant->image_value ?? $product->image_value }}"
                            loading="lazy" width="1080" height="1080"
                            alt="{{ $product->firstVariant->image_alt_text ?? $product->image_alt_text }}">
                    </a>
                </div>
                <div class="swiper-lazy-preloader"></div>
            </div>
            @foreach ($product->firstVariant->gallery->count() != 0 ? $product->firstVariant->gallery : $product->gallery as $gallery)
                <div class="swiper-slide">
                    <div class="proMainBx">
                        @if ($gallery->media_type == 'image')
                            <a class="MagicZoom" href="{{ $gallery->image_value }}" data-zoom-id="zoom2"
                                data-image="{{ $gallery->image_value }}">
                                <img decoding="async" src="{{ $gallery->image_value }}" loading="lazy" width="1080"
                                    height="1080" alt="{{ $gallery->image_alt_text }}">
                            </a>
                        @elseif ($gallery->media_type == 'video')
                            <a class="MagicZoom" href="{{ $gallery->video_value }}" data-zoom-id="zoom2"
                                data-image="{{ $gallery->video_thumbnail_image_value }}">
                                <video src="{{ $gallery->video_value }}" controls
                                    poster="{{ $gallery->video_thumbnail_image_value }}">
                                    {{-- <img decoding="async" src="{{ $gallery->video_thumbnail_image_value }}" loading="lazy"
                                    width="1080" height="1080" alt="{{ $gallery->image_alt_text }}"> --}}
                            </a>
                        @elseif ($gallery->media_type == 'video_url')
                            <a class="MagicZoom" href="{{ $gallery->video_url }}" data-zoom-id="zoom2"
                                data-image="{{ $gallery->video_url_thumbnail_image_value }}">
                                <video src="{{ $gallery->video_url }}" controls
                                    poster="{{ $gallery->video_url_thumbnail_image_value }}">
                                    {{-- <img decoding="async" src="{{ $gallery->video_url_thumbnail_image_value }}" loading="lazy"
                                    width="1080" height="1080" alt="{{ $gallery->image_alt_text }}"> --}}
                            </a>
                        @endif
                    </div>
                    <div class="swiper-lazy-preloader"></div>
                </div>
            @endforeach
        </div>

        <div class="tagWrap mobile">
            @if ($product->firstVariant->three_d_value != null || $product->three_d_value != null)
                <div class="item">
                    <button type="button" class="tagBx view3d"
                        data-src="{{ $product->firstVariant->three_d_value ?? $product->three_d_value }}">
                        <span class="icon">
                            <img src="{{ asset('frontend/images/icon-360.svg') }}" alt="product" loading="lazy"
                                width="16" height="16">
                        </span>
                        <span>360˚ view</span>
                    </button>
                </div>
            @endif
            @if ($product->firstVariant->qr_value != null || $product->qr_value != null)
                <div class="item">
                    <button type="button" class="tagBx viewAr" data-src="{{ $product->firstVariant->qr_value ?? $product->qr_value }}">
                        <span class="icon">
                            <img src="{{ asset('frontend/images/icon-space.svg') }}" alt="space" loading="lazy"
                                width="16" height="16">
                        </span>
                        <span>See in your space</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
