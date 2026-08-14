@extends('layouts.app')
<x-meta-tags :metaData="[
    'metaTitle' => $cms->meta_title ?? $cms->title,
    'metaKeywords' => $cms->meta_keywords ?? '',
    'metaDescription' => $cms->meta_description ?? '',
    'otherMetaTags' => $cms->other_meta_tags ?? '',
]" />
@push('css')
    {{-- SWIPER --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        @media (max-width: 992px) {
            .scroll-layout {
                display: none;
            }

            .mobile-swiper-wrap {
                display: block;
            }

            .section {
                padding: 56px 20px 80px;
            }

            .section-title {
                margin-bottom: 40px;
            }
        }
    </style>
@endpush
@section('content')
    <main id="pageWrapper" class="smartdeskPage">
        <section id="InnerBanner">
            @if ($cms->banner_type == 'image')
                <img src="{{ $cms->banner_image_value }}" width="1920" height="1080" loading="lazy"
                    alt="{{ $cms->banner_image_alt_text }}" class="dsk">
                <img src="{{ $cms->banner_mobile_value }}" alt="{{ $cms->banner_alt_text_value }}" class="mob">
            @else
                <video class="lazy dsk video" autoplay muted loop playsinline width="1920"
                    poster="{{ $cms->video_thumbnail_image_value }}">
                    <source type="video/mp4" src="{{ $cms->banner_video_value }}">
                </video>
                <video class="lazy mob video" autoplay muted loop playsinline width="1080"
                    poster="{{ $cms->video_thumbnail_image_mobile_value }}">
                    <source type="video/mp4" src="{{ $cms->video_mobile_value }}">
                </video>
            @endif
            <div class="container">
                <div class="mainTleWrap">
                    <div class="sTle">{{ $cms->banner_super_title }}</div>
                    <h1 class="mTle">{{ $cms->banner_title }}</h1>
                    @if ($cms->banner_btn_show && $productAttributeValueMedias->isNotEmpty())
                        <a href="#!" data-bs-toggle="modal" data-bs-target="#customizeModal"
                            class="customize hoveranim"><span>{{ $cms->banner_btn_text ?? 'Customize' }}</span></a>
                    @endif
                    @if ($cms->banner_bulk_order_btn_text != null)
                        <a href="{{ route('bulkOrder') }}" class="bulkOrdr">{{ $cms->banner_bulk_order_btn_text }}</a>
                    @endif
                </div>

            </div>
        </section>

        <section id="stHead">
            <div class="container">
                <div class="flxBx">
                    <div class="lftB simple-list-example-scrollspy" id="simple-list-example">
                        <button type="button" id="sideMenuBtn" class="MenuBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path d="M3 18V16H21V18H3ZM3 13V11H21V13H3ZM3 8V6H21V8H3Z" fill="white" />
                            </svg>
                        </button>
                        <div class="ttle">{{ $cms->title }}</div>
                        <a href="#prodViwSec">Overview</a>
                        <a href="#ProductFaqSection">Specs</a>
                        @if ($faqs->isNotEmpty())
                            <a href="#FaqSection">FAQ</a>
                        @endif
                    </div>
                    @if ($productAttributeValueMedias->isNotEmpty())
                        <div class="ritB">
                            <a href="#!" class="customize" data-bs-toggle="modal"
                                data-bs-target="#customizeModal"><span>Customize & Order</span></a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <div data-bs-spy="scroll" data-bs-target="#simple-list-example">
            <section id="prodViwSec">
                <div class="container">
                    <div class="tpoB">
                        <div class="txt">{{ $cms->overview_description }}</div>
                    </div>
                    <div class="visualWrap">
                        <div class="infoTxt">Click & turn<br>
                            to explore</div>
                        {{-- to-do: need to change this section --}}
                        <div class="imgB" id="productGlb">
                            @if ($product->firstVariant->three_d_value != null || $product->three_d_value != null)
                                <model-viewer src="{{ $product->firstVariant->three_d_value ?? $product->three_d_value }}"
                                    id="3dImage" ar ar-modes="webxr" camera-controls disable-zoom touch-action="pan-y">
                                </model-viewer>
                            @else
                                <img src="{{ $product->firstVariant->image_value ?? $product->image_value }}"
                                    alt="{{ $product->image_alt_text }}">
                            @endif
                        </div>
                        <div class="btmFlxWrp">
                            @foreach ($selectedAttributes as $selectedAttribute)
                                <div class="navProduct">
                                    @foreach ($selectedAttribute['values'] as $value)
                                        @php
                                            $isSelected = $product->firstVariant->attributeValues
                                                ->pluck('id')
                                                ->contains($value['id']);
                                        @endphp
                                        <div class="itm">
                                            <div class="proNav">
                                                <input type="radio" id="attribute_value_{{ $value['id'] }}"
                                                    class="changeImage"
                                                    name="attribute_{{ $selectedAttribute['attribute']->id }}"
                                                    value="{{ $value['id'] }}" required
                                                    {{ $isSelected ? 'checked' : '' }}>
                                                <label for="attribute_value_{{ $value['id'] }}"
                                                    title="{{ $value['value'] }}">
                                                    @if ($value['icon'] != null)
                                                        <img src="{{ $value['icon'] }}" alt="{{ $value['value'] }}">
                                                    @else
                                                        {{ strtoupper($value['value'][0]) }}
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section id="differanceSec">
                @if ($cms->hasMedia('overview_image'))
                    <div class="imgBx">
                        <img loading="lazy" src="{{ $cms->overview_image_value }}"
                            data-src="{{ $cms->overview_image_value }}" class="lazy" alt="differance" width="1920"
                            height="1080" />
                    </div>
                @endif
                <div class="txtBx">
                    @if ($cms->overview_quote_text != null)
                        <div class="title">“ {!! $cms->overview_quote_text !!} ”</div>
                    @endif
                    <div class="subT">{!! $cms->overview_quote_description !!}</div>
                </div>
            </section>

            @if ($productivities->isNotEmpty())
                <section id="healthSec">
                    <div class="container">
                        <div class="titleWrp">
                            <div class="subT">{{ $cms->productivity_super_title }}</div>
                            <div class="mainT">
                                {{ $cms->productivity_title }}
                            </div>
                            @if ($cms->productivity_btn_url != null)
                                <a href="{{ $cms->productivity_btn_url }}" class="more hoveranim"
                                    target="_blank"><span>{{ $cms->productivity_btn_text ?? 'Learn More' }}</span></a>
                            @endif
                        </div>
                        <div class="flexWrap">
                            @foreach ($productivities as $productivity)
                                <div class="item">
                                    <a href="{{ $productivity->url != null ? $productivity->url : 'javascript:void(0);' }}"
                                        class="healthB">
                                        <div class="imgB">
                                            <img src="{{ $productivity->image_value }}"
                                                alt="{{ $productivity->image_alt_text }}">
                                        </div>
                                        <div class="title"><span>{{ $productivity->title }}</span>
                                            {{ $productivity->description }}</div>
                                        <div class="ico">
                                            <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="sp-container rgt">
                        <div class="swiper healthSlide">
                            <div class="swiper-wrapper">
                                @foreach ($productivities as $productivity)
                                    <div class="swiper-slide">
                                        <a href="{{ $productivity->url != null ? $productivity->url : 'javascript:void(0);' }}"
                                            class="healthB {{ $loop->first ? 'bg1' : '' }}">
                                            <div class="imgB">
                                                <img src="{{ $productivity->image_value }}"
                                                    alt="{{ $productivity->image_alt_text }}">
                                            </div>
                                            <div class="title">{{ $productivity->title }}
                                                <span>{{ $productivity->description }}</span>
                                            </div>
                                            <div class="ico">
                                                <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            @if ($mindfulEngineerings->isNotEmpty())
                <section id="engineeringSec">
                    <div class="container">
                        <div class="tleWrap center">
                            <div class="mTle">{{ $cms->mindful_engineering_title }}</div>
                        </div>
                        <div class="blckbx">
                            @foreach ($mindfulEngineerings as $mindfulEngineering)
                                <div class="item">
                                    <div class="engineeringBx">
                                        <div class="cntWrap">
                                            <div class="ttle">{{ $mindfulEngineering->title }}</div>
                                            <div class="txt">{{ $mindfulEngineering->description }}</div>
                                        </div>
                                        <div class="imgB">
                                            <img src="{{ $mindfulEngineering->image_value }}" loading="lazy"
                                                alt="{{ $mindfulEngineering->image_alt_text }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="swiper engineeringSlide">
                        <div class="swiper-wrapper">
                            @foreach ($mindfulEngineerings as $mindfulEngineering)
                                <div class="swiper-slide">
                                    <div class="engineeringBx">
                                        <div class="cntWrap">
                                            <div class="ttle">{{ $mindfulEngineering->title }}</div>
                                            <div class="txt">{{ $mindfulEngineering->description }}</div>
                                        </div>
                                        <div class="imgB">
                                            <img src="{{ $mindfulEngineering->image_value }}" loading="lazy"
                                                alt="{{ $mindfulEngineering->image_alt_text }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </section>
            @endif

            @if ($models->isNotEmpty())
                <section id="deskSec">
                    <div class="container">
                        <div class="tleWrap center">
                            <div class="mTle">{{ $cms->find_the_right_product_title }}</div>
                        </div>

                        <div class="scroll-layout">

                            <!-- Sticky left image -->
                            <div class="scroll-images" id="imgPanel">
                                @foreach ($models as $model)
                                    <img class="scroll-images__img {{ $loop->first ? 'active' : '' }}"
                                        data-idx="{{ $loop->index }}" src="{{ $model->image_value }}"
                                        alt="{{ $model->image_alt_text }}" />
                                @endforeach
                            </div>

                            <!-- Scrollable right text -->
                            <div class="scroll-items" id="scrollItems">
                                @foreach ($models as $model)
                                    <div class="scroll-item active" data-idx="{{ $loop->index }}">
                                        <div class="ritB">
                                            <div class="title">{{ $cms->title }}<span>{{ $model->title }}</span>
                                            </div>
                                            <div class="scale">{{ $model->description }} </div>
                                            <div class="txt">{{ $model->recommended_text }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ══ MOBILE: Swiper ══ -->
                        <div class="mobile-swiper-wrap">
                            <div class="swiper deskswiper">
                                <div class="swiper-wrapper">
                                    @foreach ($models as $model)
                                        <div class="swiper-slide">
                                            <div class="slide-img">
                                                <img src="{{ $model->image_value }}"
                                                    alt="{{ $model->image_alt_text }}" />
                                            </div>
                                            <div class="ritB">
                                                <div class="title">{{ $cms->title }}<span>{{ $model->title }}</span>
                                                </div>
                                                <div class="scale">{{ $model->description }} </div>
                                                <div class="txt">{{ $model->recommended_text }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <section id="ProductFaqSection">
                <div class="container">
                    <div class="mFlx">
                        <div class="lftSd">
                            <div
                                class="imgWrap {{ $product->firstVariant->desc_image_value == null && $product->desc_image_value == null ? 'd-none' : '' }}">
                                <img src="{{ $product->firstVariant->desc_image_value ?? $product->desc_image_value }}"
                                    alt="nav" width="668" height="1000" loading="lazy">
                            </div>
                        </div>
                        <div class="rgtSd">
                            {{-- to-do: style attributes --}}
                            <div class="attributeList">
                                @foreach ($selectedAttributes as $selectedAttribute)
                                    <div class="navProduct">
                                        @foreach ($selectedAttribute['values'] as $value)
                                            @php
                                                $isSelected = $product->firstVariant->attributeValues
                                                    ->pluck('id')
                                                    ->contains($value['id']);
                                            @endphp
                                            <div class="itm">
                                                <div class="proNav">
                                                    <input type="radio" id="attributeValue_{{ $value['id'] }}"
                                                        class="changeDesc"
                                                        name="attribute_id_{{ $selectedAttribute['attribute']->id }}"
                                                        value="{{ $value['id'] }}" required
                                                        {{ $isSelected ? 'checked' : '' }}>
                                                    <label for="attributeValue_{{ $value['id'] }}"
                                                        title="{{ $value['value'] }}">
                                                        {{ strtoupper($value['value']) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <div class="accordion specFaqAcco" id="SpecFaqAcco">
                                @include('custom.partials.product-description')
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @if ($cms->show_height_calculator)
                <section id="calculatorSec">
                    <div class="container">
                        <div class="flexWrap">
                            <div class="titleWrap">
                                <div class="title">{{ $cms->height_calculator_title }}</div>
                                <div class="txt">{{ $cms->height_calculator_description }}</div>
                            </div>
                            <div class="lftB">
                                <div class="wrapBx">
                                    <div class="item">
                                        <div class="box">
                                            <input type="numer" min="1" max="300" class="form-control"
                                                placeholder="Enter your height" name="height" id="heightCalc">
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="box">
                                            <input type="radio" id="a11" name="measurement" value="inches"
                                                required checked>
                                            <label for="a11">
                                                in
                                            </label>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="box">
                                            <input type="radio" id="a12" name="measurement" value="centimeters"
                                                required>
                                            <label for="a12">
                                                cm
                                            </label>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="box">
                                            <input type="radio" id="a13" name="type" value="standing"
                                                required checked>
                                            <label for="a13">
                                                Standing
                                            </label>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="box">
                                            <input type="radio" id="a14" name="type" value="sitting"
                                                required>
                                            <label for="a14">
                                                Sitting
                                            </label>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="box">
                                            <a href="javascript:void(0);" class="go" id="calculate">
                                                Go
                                                <div class="ico">
                                                    <img src="{{ asset('frontend/images/rit1.png') }}" alt="nav"
                                                        width="26" height="26" loading="lazy">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ritB">
                                <div class="imgB">
                                    <img src="{{ $cms->standing_desk_image_value }}" alt="height-image" width="500"
                                        height="401" loading="lazy" id="heightImage"
                                        data-standing-img="{{ $cms->standing_desk_image_value }}"
                                        data-sitting-img="{{ $cms->sitting_desk_image_value }}">
                                </div>
                                <div class="msreB">
                                    <div class="top"></div>
                                    <div class="height d-none" id="dskHeight"></div>
                                    <div class="btm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            @if ($cms->show_assembly_section)
                <section id="assemblySec">
                    <div class="container">
                        <div class="tleWrap center">
                            <div class="subT">{{ $cms->assembly_super_title }}</div>
                            <div class="mTle">{{ $cms->assembly_title }}</div>
                        </div>
                        <div class="imgBx">
                            <img src="{{ $cms->assembly_image_value }}" alt="assembly" width="1369" height="1159">
                        </div>
                        <div class="ttleB">
                            <div class="stle">{{ $cms->assembly_support_text }}</div>
                            <div class="mtle">{{ $cms->assembly_help_text }}</div>
                        </div>
                    </div>
                </section>
            @endif

            @if ($faqs->isNotEmpty())
                <section id="FaqSection">
                    <div class="container">
                        <div class="tleWrap center">
                            <div class="mTle">Frequently Asked Questions</div>
                        </div>
                        <div class="accordion FaqAcco" id="FaqAcco">
                            @foreach ($faqs as $faq)
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#FaqAccoItem_{{ $loop->iteration }}" aria-expanded="false"
                                            aria-controls="FaqAccoItem_{{ $loop->iteration }}">
                                            {{ $faq->question }}
                                        </button>
                                    </div>
                                    <div id="FaqAccoItem_{{ $loop->iteration }}" class="accordion-collapse collapse"
                                        data-bs-parent="#FaqAcco">
                                        <div class="accordion-body">
                                            <div class="ckCntWrap">
                                                {!! $faq->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <x-support-section />

            @if ($relatedProducts->isNotEmpty())
                <section id="OtherProductSection">
                    <div class="container">
                        <div class="tleWrap center">
                            <div class="mTle">Explore other products</div>
                        </div>
                    </div>
                    <div class="sp-container rgt">
                        <div class="exploreSlide owl-carousel">
                            @include('products.partials.listing', ['products' => $relatedProducts])

                        </div>
                    </div>
                </section>
            @endif

            <!-- QUESTION_MODAL -->
            <div class="modal fade questionModal" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title" id="NotifyModalLabel">Got a question ?</div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="notifyForm">
                                <div class="txt">Feel free to contact.</div>

                                @include('partials.question-form')

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" form="question-form" class="baseBtn_1 hoveranim" aria-label="submit">
                                <span>Submit</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sideMenu">
            <div class="MenuBtn">
                <button type="button" id="sidemenuClose" class="btn ">
                    <span></span>
                    <span></span>
                </button>
            </div>
            <div class="mainItm">
                <div class="menuFlx">
                    <ul id="simple-list-example" class="simple-list-example-scrollspy">
                        <li>
                            <a href="#prodViwSec">Overview</a>
                        </li>
                        <li>
                            <a href="#ProductFaqSection">Specs</a>
                        </li>
                        @if ($faqs->isNotEmpty())
                            <li>
                                <a href="#FaqSection">FAQ</a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </main>

    @include('modals.customizeModal')
@endsection
@push('js')
    {{-- GSAP --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"
        integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"
        integrity="sha512-P2IDYZfqSwjcSjX0BKeNhwRUH8zRPGlgcWl5n6gBLzdi4Y5/0O4zaXrtO4K9TZK6Hn1BenYpKowuCavNandERg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- SWIPER --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- glb viewer --}}
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>

    {{-- customise modal js --}}
    <script src="{{ asset('frontend/js/stepper.min.js') }}"></script>
    <script>
        // addon slider
        function addonSlider() {
            const addonSlider = new Swiper('.addonSlider', {
                loop: false,
                rewind: true,
                slidesPerView: 3,
                spaceBetween: 15,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },

                speed: 800,
                freeMode: false,
                freeModeFluid: true,
                freeModeMinimumVelocity: 0.02,
                freeModeSticky: false,

                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preventClicks: true,
                preventClicksPropagation: true,

                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    420: {
                        slidesPerView: 1.2,
                    },
                    578: {
                        slidesPerView: 1.4,
                    },
                    992: {
                        slidesPerView: 2,
                    },
                    1200: {
                        slidesPerView: 2.2,
                    },
                    1771: {
                        slidesPerView: 3,
                    }
                }
            });
        }
    </script>


    <script>
        window.addEventListener('DOMContentLoaded', function() {

            const whyChooseSlide = new Swiper('.engineeringSlide', {
                loop: true,
                rewind: true,
                spaceBetween: 15,
                slidesPerView: "auto",
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },

                speed: 800,
                freeMode: false,
                freeModeFluid: true,
                freeModeMinimumVelocity: 0.02,
                freeModeSticky: false,

                resistance: true,
                resistanceRatio: 0.85,
                threshold: 5,
                longSwipesRatio: 0.5,
                longSwipesMs: 300,

                centeredSlides: true,
                centerInsufficientSlides: true,
                centeredSlidesBounds: true,

                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preventClicks: true,
                preventClicksPropagation: true,

                breakpoints: {
                    376: {
                        slidesPerView: "auto",
                        spaceBetween: 25,
                    },
                    576: {
                        slidesPerView: "auto",
                        spaceBetween: 20,
                    },
                    1551: {
                        slidesPerView: "auto",
                        spaceBetween: 25,
                    }
                }
            });

            new Swiper('.healthSlide', {
                lazy: true,
                loop: true,
                freeMode: true,
                slidesPerView: 1.06,
                spaceBetween: 12,
                autoplay: {
                    delay: 5000,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 1.05,
                        spaceBetween: 26
                    }
                }
            });

            // OTHER_PRODUCTS
            $('.exploreSlide').owlCarousel({
                loop: true,
                rewind: false,
                autoplay: false,
                nav: false,
                dots: false,
                items: 1.06,
                margin: 10,
                responsive: {
                    576: {
                        margin: 12,
                        items: 2.1,
                    },
                    768: {
                        margin: 15,
                        items: 2.1,
                    },
                    840: {
                        margin: 15,
                        items: 3.1,
                    },
                    992: {
                        margin: 18,
                        items: 3.1,
                    },
                    1200: {
                        margin: 22,
                        items: 3.1,
                    },
                    1441: {
                        margin: 26,
                        items: 3.1,
                    }
                }
            });

            new Swiper('.deskswiper', {
                slidesPerView: 1.15,
                spaceBetween: 10,
                centeredSlides: true,
                grabCursor: true,
                breakpoints: {
                    560: {
                        slidesPerView: 2,
                        spaceBetween: 12,
                        centeredSlides: false,
                    },
                    768: {
                        slidesPerView: 2.8,
                        centeredSlides: false,
                        spaceBetween: 15,
                    },
                }
            });

            /* Desktop scroll observer */
            (function() {
                if (window.innerWidth <= 992) return;

                const items = document.querySelectorAll('.scroll-item');
                const images = document.querySelectorAll('.scroll-images__img');
                let current = 0;

                function activate(idx) {
                    if (idx === current) return;
                    current = idx;
                    items.forEach((el, i) => el.classList.toggle('active', i === idx));
                    images.forEach((el, i) => el.classList.toggle('active', i === idx));
                }

                const io = new IntersectionObserver(
                    entries => {
                        entries.forEach(e => {
                            if (e.isIntersecting) activate(+e.target.dataset.idx);
                        });
                    }, {
                        rootMargin: '-38% 0px -38% 0px',
                        threshold: 0
                    }
                );

                items.forEach(item => io.observe(item));
            })();

        })

        $(document).on('click', '.visualWrap', function() {
            $('.infoTxt').fadeOut(400, function() {
                $(this).remove();
            });
        });

        gsap.registerPlugin(ScrollTrigger);

        window.addEventListener("load", () => {

            let panels = gsap.utils.toArray("#sticky-wrp .panel");
            let totalPanels = panels.length;

            if (totalPanels <= 1) return;

            let mm = gsap.matchMedia();

            mm.add({
                isDesktop: "(min-width: 1441px)",
                isMobile: "(max-width: 1440px)"
            }, (context) => {

                let {
                    isDesktop
                } = context.conditions;

                gsap.to(panels, {
                    xPercent: -100 * (panels.length - 1),
                    ease: "none",
                    scrollTrigger: {
                        trigger: "#sticky-wrp",
                        pin: true,
                        scrub: 1,
                        snap: 1 / (panels.length - 1),
                        start: isDesktop ? "-200px top" : "-150px top",
                        end: () => "+=" + document.querySelector("#sticky-wrp .wrapBx").offsetWidth
                    }
                });

            });

        });


        // efficient scroll handler that toggles .active on body
        (function() {
            let ticking = false;

            function check() {
                const scrollTop = window.scrollY;
                const triggerPoint = window.innerHeight * 0.75; // 75vh

                if (scrollTop >= triggerPoint) {
                    document.body.classList.add('active');
                } else {
                    document.body.classList.remove('active');
                }
            }

            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(() => {
                        check();
                        ticking = false;
                    });
                }
            }

            // initial check
            check();

            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            window.addEventListener('resize', () => requestAnimationFrame(check));
        })();

        $("#sideMenuBtn").on("click", function() {
            $("body").toggleClass("sideMenuOpen");
            $(".MenuBtn").toggleClass("open");
        });

        $("#sidemenuClose").on("click", function() {
            $("body").removeClass("sideMenuOpen");
            $(".MenuBtn").removeClass("open");
        });

        $(document).ready(function() {
            $('#simple-list-example a').on('click', function() {
                // Add a small delay to allow smooth scrolling to complete
                $("body").removeClass("sideMenuOpen");
            });
        });
    </script>

    <script>
        $('.changeImage').change(function(e) {
            e.preventDefault();
            var attributeValues = $('.changeImage:checked').map(function() {
                return this.value;
            }).get();
            $.ajax({
                type: "get",
                url: "{{ route('get-variant-glb') }}",
                data: {
                    product_id: "{{ $product->id }}",
                    attributeValues: attributeValues,
                },
                success: function(response) {
                    if (response.status) {
                        if (response.data.isGlb) {
                            $('#productGlb').html(`<model-viewer src="` + response.data.url +
                                `"
                                    id="3dImage" ar ar-modes="webxr" camera-controls touch-action="pan-y"></model-viewer>`
                            );
                        } else {

                            $('#productGlb').html(`<img src="` + response.data.url + `"
                                    alt="` + response.data.alt_text + `">`);
                        }
                    }
                }
            });

        });

        $(document).ready(function() {
            $('#specsInner ul').addClass('specs');
        });

        $(document).on('change', '.changeDesc', function() {
            var attributeValues = $('.changeDesc:checked').map(function() {
                return this.value;
            }).get();

            $.ajax({
                type: "get",
                url: "{{ route('get-variant-description') }}",
                data: {
                    product_id: "{{ $product->id }}",
                    attributeValues: attributeValues,
                },
                success: function(response) {
                    if (response.status) {
                        $('#SpecFaqAcco').html(response.description);
                        if (response.description_image != null) {
                            $('#ProductFaqSection .imgWrap img').attr('src', response
                                .description_image);
                            $('#ProductFaqSection .imgWrap').removeClass('d-none');
                        } else {
                            $('#ProductFaqSection .imgWrap').addClass('d-none');
                        }
                    }
                }
            });
        });

        $('#calculate').on('click', function() {
            if (!$('#heightCalc').val()) {
                showToast('error', 'Please enter your height');
                return;
            }

            let measurement = $('input[name="measurement"]:checked').val();
            let type = $('input[name="type"]:checked').val();
            let userHeight = $('#heightCalc').val();

            let heightImage = $('#heightImage');

            if (type == 'sitting') {
                heightImage.attr('src', heightImage.data('sitting-img'));
            } else {
                heightImage.attr('src', heightImage.data('standing-img'));
            }
            // Sitting Desk Height = User Height× 0.45

            // Standing Desk Height = User Height× 0.63

            var deskHeight = userHeight * (type == 'sitting' ? 0.45 : 0.63);

            $('#dskHeight').text(deskHeight.toFixed(2) + ' ' + (measurement == 'inches' ? 'in' : 'cm'));

            $('#dskHeight').removeClass('d-none');
        });
    </script>

    {{-- customisation --}}
    <script>
        var selectedAttributeValues = [];
        var firstVariant = @json($product->firstVariant);

        firstVariant.attribute_values.forEach(function(attributeValue) {
            selectedAttributeValues.push({
                'attribute_id': attributeValue.attribute_id,
                'attribute_value_id': attributeValue.id,
            });
        });

        $(document).on('click', '.changeCustomVariant', function() {
            var attributeId = $(this).data('attribute-id');
            var attributeValueId = $(this).data('attribute-value-id');

            //check in selectedAttributeValues And replace its variant value with new one
            selectedAttributeValues.forEach(function(attributeValue, index) {
                if (attributeValue.attribute_id == attributeId) {
                    selectedAttributeValues[index].attribute_value_id = attributeValueId;
                }
            });


            const attributeValueIds = selectedAttributeValues.map(function(attributeValue) {
                return attributeValue.attribute_value_id;
            });

            $.ajax({
                type: "get",
                url: "{{ route('get-variant-info') }}",
                data: {
                    'product_id': "{{ $product->id }}",
                    'selectedAttributeValues': attributeValueIds
                },
                success: function(response) {
                    if (response.status) {
                        $('.amountChange').html('₹' + response.price);
                        $('#variantImage').attr('src', response.image);
                    }
                }
            });
        });

        $(document).on('click', '.addAddon', function() {
            var productId = $(this).data('addon-id');
            var variantId = $(this).data('variant-id');
            var button = $(this);

            const attributeValueIds = selectedAttributeValues.map(function(attributeValue) {
                return attributeValue.attribute_value_id;
            });

            $.ajax({
                type: "post",
                url: "{{ route('add-to-cart') }}",
                data: {
                    product_id: productId,
                    variant_id: variantId,
                    quantity: 1,
                    isFrom: 'custom',
                    currentProductId: "{{ $product->id }}",
                    selectedAttributeValues: attributeValueIds,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    button.parent().css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        $('#step3').html(response.step3Html);
                        $('#cartModal .modal-content').html(response.cartHtml);
                        $('#cartCount').text(response.cartCount);
                        addonSlider();
                    } else {
                        showToast('error', response.message);
                        button.parent().css({
                            'pointer-events': 'all',
                            'opacity': '1'
                        });
                    }
                },
                error: function() {
                    button.parent().css({
                        'pointer-events': 'all',
                        'opacity': '1'
                    });
                }
            });
        });

        $(document).on('click', '.removeAddon', function() {
            var productId = $(this).data('addon-id');
            var variantId = $(this).data('variant-id');
            var button = $(this);

            const attributeValueIds = selectedAttributeValues.map(function(attributeValue) {
                return attributeValue.attribute_value_id;
            });

            $.ajax({
                type: "post",
                url: "{{ route('remove-addon-from-cart') }}",
                data: {
                    product_id: productId,
                    variant_id: variantId,
                    isFrom: 'custom',
                    currentProductId: "{{ $product->id }}",
                    selectedAttributeValues: attributeValueIds,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    button.parent().css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        $('#step3').html(response.step3Html);
                        $('#cartModal .modal-content').html(response.cartHtml);
                        $('#cartCount').text(response.cartCount);
                        addonSlider();
                    } else {
                        showToast('error', response.message);
                        button.parent().css({
                            'pointer-events': 'all',
                            'opacity': '1'
                        });
                    }
                },
                error: function() {
                    button.parent().css({
                        'pointer-events': 'all',
                        'opacity': '1'
                    });
                }
            });
        });

        $(document).on('click', '#customAddToCart', function(e) {
            e.preventDefault();

            var button = $(this);
            var buttonText = button.html();

            var product_id = '{{ $product->id }}';
            const attributeValueIds = selectedAttributeValues.map(function(attributeValue) {
                return attributeValue.attribute_value_id;
            });

            $.ajax({
                type: "post",
                url: "{{ route('custom-add-to-cart') }}",
                data: {
                    product_id: product_id,
                    selectedAttributeValues: attributeValueIds,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    button.html('Adding...');
                    button.parent().css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        button.html(`Add to Cart
                            <div class="icon">
                                <svg width="20" height="20" viewBox="0 0 20 20">
                                    <path
                                        d="M18.0264 13.709H6.39551L6.97949 12.5195L16.6826 12.502C17.0107 12.502 17.292 12.2676 17.3506 11.9434L18.6943 4.42187C18.7295 4.22461 18.6768 4.02148 18.5478 3.86719C18.4841 3.79125 18.4047 3.73008 18.315 3.68792C18.2252 3.64577 18.1274 3.62363 18.0283 3.62305L5.68457 3.58203L5.5791 3.08594C5.5127 2.76953 5.22754 2.53906 4.90332 2.53906H1.88574C1.70289 2.53906 1.52752 2.6117 1.39823 2.741C1.26893 2.8703 1.19629 3.04566 1.19629 3.22852C1.19629 3.41137 1.26893 3.58674 1.39823 3.71603C1.52752 3.84533 1.70289 3.91797 1.88574 3.91797H4.34473L4.80566 6.10937L5.94043 11.6035L4.47949 13.9883C4.40362 14.0907 4.35793 14.2123 4.34757 14.3393C4.33722 14.4663 4.36262 14.5937 4.4209 14.707C4.53809 14.9395 4.77441 15.0859 5.03613 15.0859H6.26269C6.00121 15.4332 5.85997 15.8563 5.86035 16.291C5.86035 17.3965 6.75879 18.2949 7.86426 18.2949C8.96973 18.2949 9.86816 17.3965 9.86816 16.291C9.86816 15.8555 9.72363 15.4316 9.46582 15.0859H12.6123C12.3508 15.4332 12.2096 15.8563 12.21 16.291C12.21 17.3965 13.1084 18.2949 14.2139 18.2949C15.3193 18.2949 16.2178 17.3965 16.2178 16.291C16.2178 15.8555 16.0732 15.4316 15.8154 15.0859H18.0283C18.4072 15.0859 18.7178 14.7773 18.7178 14.3965C18.7166 14.2138 18.6433 14.039 18.5138 13.9102C18.3842 13.7814 18.209 13.7091 18.0264 13.709ZM5.97168 4.94141L17.2178 4.97852L16.1162 11.1465L7.28223 11.1621L5.97168 4.94141ZM7.86426 16.9082C7.52441 16.9082 7.24707 16.6309 7.24707 16.291C7.24707 15.9512 7.52441 15.6738 7.86426 15.6738C8.2041 15.6738 8.48144 15.9512 8.48144 16.291C8.48144 16.4547 8.41642 16.6117 8.30067 16.7274C8.18493 16.8432 8.02795 16.9082 7.86426 16.9082ZM14.2139 16.9082C13.874 16.9082 13.5967 16.6309 13.5967 16.291C13.5967 15.9512 13.874 15.6738 14.2139 15.6738C14.5537 15.6738 14.8311 15.9512 14.8311 16.291C14.8311 16.4547 14.766 16.6117 14.6503 16.7274C14.5345 16.8432 14.3776 16.9082 14.2139 16.9082Z"
                                        fill="white" />
                                </svg>
                            </div>`);
                        // button.attr('disabled', 'disabled');
                        button.parent().css({
                            'pointer-events': 'all',
                            'opacity': '1'
                        });
                        $('#cartModal .modal-content').html(response.cartHtml);
                        $('#cartModal').modal('show');
                        $('#cartCount').text(response.cartCount);
                    } else {
                        button.html(buttonText);
                        button.parent().css({
                            'pointer-events': 'all',
                            'opacity': '1'
                        });
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    button.html(buttonText);
                    button.parent().css({
                        'pointer-events': 'all',
                        'opacity': '1'
                    });
                }
            });
        });
    </script>
    @include('js.intel-phone-setup')
    @include('js.jquery-validate')
    <script>
        setupValidation('#visitForm');
        setupValidation('#question-form', {}, {}, afterQuestionSubmit);

        function afterQuestionSubmit(response) {
            $('#questionModal').modal('hide');
        }

        $(document).on('shown.bs.modal', '#questionModal', function() {
            initializePhoneInputs('.question_phone_number', '.question_country_code', 'in', ['in']);
        });
    </script>
@endpush
