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
                <button type="button" class="tagBx viewAr"
                    data-src="{{ $product->firstVariant->qr_value ?? $product->qr_value }}">
                    <span>View in AR</span>
                </button>
            </div>
        @endif
    </div>
    <div class="swiper proMainSlide">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="{{ $product->firstVariant->image_value ?? $product->image_value }}" data-fancyBox="gallery"
                    class="galImg">
                    <img decoding="async" src="{{ $product->firstVariant->image_value ?? $product->image_value }}"
                        loading="lazy" width="1080" height="1080"
                        alt="{{ $product->firstVariant->image_alt_text ?? $product->image_alt_text }}">
                </a>
            </div>
            @foreach ($product->firstVariant->gallery->count() != 0 ? $product->firstVariant->gallery : $product->gallery as $gallery)
                @if ($gallery->media_type == 'video')
                    <div class="swiper-slide">
                        <a href="{{ $gallery->video_value }}" data-fancyBox="gallery" class="galImg">
                            <video class="lazy dsk video" controls muted loop playsinline width="1920"
                                poster="{{ $gallery->video_thumbnail_image_value }}">
                                <source type="video/mp4" src="{{ $gallery->video_value }}">
                            </video>
                        </a>
                    </div>
                @elseif ($gallery->media_type == 'video_url')
                    <div class="swiper-slide">
                        <a href="{{ $gallery->video_url }}" data-fancyBox="gallery" class="galImg">
                            <video class="lazy dsk video" controls muted loop playsinline width="1920"
                                poster="{{ $gallery->video_url_thumbnail_image_value }}">
                                <source type="video/mp4" src="{{ $gallery->video_url }}">
                            </video>
                        </a>
                    </div>
                @else
                    <div class="swiper-slide">
                        <a href="{{ $gallery->image_value }}" data-fancyBox="gallery" class="galImg">
                            <img decoding="async" src="{{ $gallery->image_value }}" loading="lazy" width="1080"
                                height="1080" alt="{{ $gallery->image_alt_text }}">
                        </a>
                    </div>
                @endif
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
                    <button type="button" class="tagBx viewAr"
                        data-src="{{ $product->firstVariant->qr_value ?? $product->qr_value }}">
                        <span class="icon">
                            <img src="{{ asset('frontend/images/icon-space.svg') }}" alt="space" loading="lazy"
                                width="16" height="16">
                        </span>
                        <span>See in your space</span>
                    </button>
                </div>
            @endif
        </div>
        <div class="navWrp">
            <div class="nav swiper-button-prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <g opacity="0.3">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M17.0311 2.46888C16.9615 2.39903 16.8787 2.34362 16.7876 2.30581C16.6965 2.268 16.5988 2.24854 16.5001 2.24854C16.4015 2.24854 16.3038 2.268 16.2127 2.30581C16.1216 2.34362 16.0388 2.39903 15.9691 2.46888L6.96912 11.4689C6.89928 11.5385 6.84386 11.6213 6.80606 11.7124C6.76825 11.8035 6.74878 11.9012 6.74878 11.9999C6.74878 12.0985 6.76825 12.1962 6.80606 12.2873C6.84386 12.3784 6.89928 12.4612 6.96912 12.5309L15.9691 21.5309C16.11 21.6717 16.301 21.7508 16.5001 21.7508C16.6993 21.7508 16.8903 21.6717 17.0311 21.5309C17.172 21.39 17.2511 21.199 17.2511 20.9999C17.2511 20.8007 17.172 20.6097 17.0311 20.4689L8.56062 11.9999L17.0311 3.53088C17.101 3.46121 17.1564 3.37844 17.1942 3.28733C17.232 3.19621 17.2515 3.09853 17.2515 2.99988C17.2515 2.90122 17.232 2.80354 17.1942 2.71243C17.1564 2.62131 17.101 2.53854 17.0311 2.46888Z"
                            fill="black" />
                    </g>
                </svg>
            </div>
            <div class="nav swiper-button-next">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <g opacity="0.3">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.96888 2.46888C7.03854 2.39903 7.12131 2.34362 7.21243 2.30581C7.30354 2.268 7.40122 2.24854 7.49988 2.24854C7.59853 2.24854 7.69621 2.268 7.78733 2.30581C7.87844 2.34362 7.96121 2.39903 8.03088 2.46888L17.0309 11.4689C17.1007 11.5385 17.1561 11.6213 17.1939 11.7124C17.2318 11.8035 17.2512 11.9012 17.2512 11.9999C17.2512 12.0985 17.2318 12.1962 17.1939 12.2873C17.1561 12.3784 17.1007 12.4612 17.0309 12.5309L8.03088 21.5309C7.89005 21.6717 7.69904 21.7508 7.49988 21.7508C7.30071 21.7508 7.10971 21.6717 6.96888 21.5309C6.82805 21.39 6.74893 21.199 6.74893 20.9999C6.74893 20.8007 6.82805 20.6097 6.96888 20.4689L15.4394 11.9999L6.96888 3.53088C6.89903 3.46121 6.84362 3.37844 6.80581 3.28733C6.768 3.19621 6.74854 3.09853 6.74854 2.99988C6.74854 2.90122 6.768 2.80354 6.80581 2.71243C6.84362 2.62131 6.89903 2.53854 6.96888 2.46888Z"
                            fill="black" />
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <div class="imgBWrp">
        <div class="item">
            <a href="{{ $product->firstVariant->image_value ?? $product->image_value }}" data-fancyBox="gallery"
                class="galImg">
                <img decoding="async" src="{{ $product->firstVariant->image_value ?? $product->image_value }}"
                    loading="lazy" width="1080" height="1080"
                    alt="{{ $product->firstVariant->image_alt_text ?? $product->image_alt_text }}">
            </a>
        </div>
        @foreach ($product->firstVariant->gallery->count() != 0 ? $product->firstVariant->gallery : $product->gallery as $gallery)
            @if ($gallery->media_type == 'video')
                <div class="item">
                    <a href="{{ $gallery->video_value }}" data-fancyBox="gallery" class="galImg">
                        <video class="lazy dsk video" controls muted loop playsinline width="1920"
                            poster="{{ $gallery->video_thumbnail_image_value }}">
                            <source type="video/mp4" src="{{ $gallery->video_value }}">
                        </video>
                    </a>
                </div>
            @elseif ($gallery->media_type == 'video_url')
                <div class="item">
                    <a href="{{ $gallery->video_url }}" data-fancyBox="gallery" class="galImg">
                        <video class="lazy dsk video" controls muted loop playsinline width="1920"
                            poster="{{ $gallery->video_url_thumbnail_image_value }}">
                            <source type="video/mp4" src="{{ $gallery->video_url }}">
                        </video>
                    </a>
                </div>
            @else
                <div class="item">
                    <a href="{{ $gallery->image_value }}" data-fancyBox="gallery" class="galImg">
                        <img decoding="async" src="{{ $gallery->image_value }}" loading="lazy" width="1080"
                            height="1080" alt="{{ $gallery->image_alt_text }}">
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>
