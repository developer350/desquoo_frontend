@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
    {{-- Swiper --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush
@section('content')
    <div id="pageWrapper" class="homePage">
        @if ($sliders->isNotEmpty())
            <section id="MainBanner" class="carousel slide panel carousel-fade" data-bs-touch="false" data-bs-ride="carousel"
                data-bs-interval="5000">
                <div class="carousel-inner">
                    @foreach ($sliders as $slider)
                        <div @class(['carousel-item', 'active' => $loop->first])>
                            <div class="bgImgWrap">
                                @if ($slider->media_type === 'image')
                                    <picture>
                                        <source media="(min-width: 468px)" srcset="{{ $slider->image_value }}"
                                            data-srcset="{{ $slider->image_value }}">
                                        <img alt="{{ $slider->image_alt_text_value }}" loading="lazy" class="lazy"
                                            src="{{ $slider->image_mobile_value }}"
                                            data-src="{{ $slider->image_mobile_value }}" width="1920" height="1080" />
                                    </picture>
                                @else
                                    <video class="lazy dsk video" autoplay muted loop playsinline width="1920"
                                        poster="{{ $slider->video_thumbnail_image_value }}">
                                        <source type="video/mp4" src="{{ $slider->video_value }}">
                                    </video>
                                    <video class="lazy mob video" autoplay muted loop playsinline width="1080"
                                        poster="{{ $slider->video_thumbnail_image_mobile_value }}">
                                        <source type="video/mp4" src="{{ $slider->video_mobile_value }}">
                                    </video>
                                @endif
                            </div>
                            <div class="container">
                                <div class="dFlx">
                                    <div class="cntWrap">
                                        <h1 class="mHead">{{ $slider->title }}</h1>
                                        @if ($slider->action_type === 'url')
                                            <a href="{{ $slider->action_url }}" class="explore" @linkTarget($slider->action_url)>
                                                <span>{{ $slider->action_title }}</span>
                                                <div class="ico">
                                                    <img src="{{ asset('frontend/images/right.png') }}" alt="arrow">
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <a href="#categorySec" class="scroll">Scroll to explore</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($categories->isNotEmpty())
            <section id="categorySec">
                <div class="container">
                    <div class="flexWrap">
                        <div class="itmWrp">
                            @foreach ($categories as $category)
                                @if ($loop->iteration == 3)
                                    @continue
                                @endif
                                @php
                                    $nameParts = explode(' ', $category->name);
                                @endphp
                                <div class="item wow animate__fadeInUp">
                                    <a href="{{ route('category-detail', ['slug' => $category->slug]) }}" class="categ">
                                        <div class="title">{{ $nameParts[0] ?? '' }}
                                            <span>{{ $nameParts[1] ?? '' }}</span>
                                        </div>
                                        <div class="imgB wow animate__fadeInRight">
                                            <img src="{{ $catgeory->home_image_value ?? $category->image_value }}"
                                                alt="{{ $category->image_alt_text ?? $category->name }}" width="720"
                                                height="720">
                                        </div>
                                        <div class="ico">
                                            <img src="{{ asset('frontend/images/arw.png') }}" alt="arrow" width="85"
                                                height="85">
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        @if ($categories->count() == 3)
                            @php
                                $category = $categories->last();
                                $nameParts = explode(' ', $category->name);
                            @endphp
                            <div class="itmWrp">
                                <div class="item wow animate__fadeInUp">
                                    <a href="{{ route('category-detail', ['slug' => $category->slug]) }}" class="categ">
                                        <div class="title">{{ $nameParts[0] ?? '' }}
                                            <span>{{ $nameParts[1] ?? '' }}</span>
                                        </div>
                                        <div class="imgB wow animate__fadeInRight">
                                            <img src="{{ $catgeory->home_image_value ?? $category->image_value }}"
                                                alt="{{ $category->image_alt_text ?? $category->name }}" width="720"
                                                height="720">
                                        </div>
                                        <div class="ico">
                                            <img src="{{ asset('frontend/images/arw.png') }}" alt="arrow" width="85"
                                                height="85">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <section id="quoteSec" class="panel1">
            @if ($homeCms->section_one_image_value != null)
                <div class="imgBx scaleUp">
                    <img loading="lazy" src="{{ $homeCms->section_one_image_value }}"
                        data-src="{{ $homeCms->section_one_image_value }}" class="lazy"
                        alt="{{ $homeCms->section_one_image_alt_text_value }}" width="1920" height="1080" />
                </div>
            @endif
            <div class="txtB">
                <div class="container">
                    <div class="title">
                        {{ $homeCms->section_one_title }}
                    </div>
                </div>
            </div>
        </section>

        @if ($favouriteProducts->isNotEmpty())
            <section id="customerFavSec" class="panel1">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">{{ $homeCms->section_two_title }}</div>
                    </div>
                </div>
                <div class="sp-container rgt">
                    <div class="exploreSlide owl-carousel">
                        @include('products.partials.listing', ['products' => $favouriteProducts])
                    </div>
                </div>
            </section>
        @endif

        @if ($homeFeatures->isNotEmpty())
            <section id="attentionSec">
                <div class="sp-container rgt">
                    <div class="swiper-expression dsk wow animate__fadeIn">
                        <div class="swiper-wrapper">
                            @foreach ($homeFeatures as $homeFeature)
                                <div class="swiper-slide">
                                    <div class="expressionBx">
                                        <div class="bgImg">
                                            <img loading="lazy" src="{{ $homeFeature->image_value }}"
                                                data-src="{{ $homeFeature->image_value }}" class="lazy"
                                                alt="{{ $homeFeature->image_alt_text_value }}" width="1680"
                                                height="1080" />
                                        </div>
                                        <div class="txtB">
                                            <div class="title">
                                                <span>{{ $homeFeature->title }}</span>
                                                {!! $homeFeature->subtitle !!}
                                            </div>
                                            {!! $homeFeature->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="swiper-expression mob wow animate__fadeIn">
                        <div class="swiper-wrapper">
                            @foreach ($homeFeatures as $homeFeature)
                                <div class="swiper-slide">
                                    <div class="expressionBx">
                                        <div class="ritImg">
                                            <img loading="lazy" src="{{ $homeFeature->image_mobile_value }}"
                                                data-src="{{ $homeFeature->image_mobile_value }}" class="lazy"
                                                alt="{{ $homeFeature->image_alt_text_value }}" width="1680"
                                                height="1080" />
                                        </div>
                                        <div class="txtB">
                                            <div class="title">
                                                <span>{{ $homeFeature->title }}</span>
                                                {!! $homeFeature->subtitle !!}
                                            </div>
                                            {!! $homeFeature->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($trustedBrands->isNotEmpty() || $usps->isNotEmpty())
            <section id="creatorsSec">
                @if ($trustedBrands->isNotEmpty())
                    <div class="container">
                        <div class="tleWrap center">
                            <div class="mTle">{{ $homeCms->section_three_title }}</div>
                            <div class="subT">{{ $homeCms->section_three_description }}</div>
                        </div>
                        <div class="brandSlide owl-carousel">
                            @foreach ($trustedBrands as $trustedBrand)
                                <div class="item">
                                    <div class="imgB">
                                        <img src="{{ $trustedBrand->logo_value }}"
                                            alt="{{ $trustedBrand->logo_alt_text_value }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($usps->isNotEmpty())
                    <div class="sp-container rgt">
                        <div class="ftreSlide owl-carousel">
                            @foreach ($usps as $usp)
                                <div class="item">
                                    <div class="featureBx">
                                        <div class="ico">
                                            <img src="{{ $usp->icon_value }}" alt="{{ $usp->icon_alt_text_value }}">
                                        </div>
                                        <div class="title">{{ $usp->title }}</div>
                                        <div class="txt">{{ $usp->description }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        @endif

        @if ($googleReviews->isNotEmpty())
            <section id="communitySec">
                <div class="container">
                    <div class="titleWrap">
                        <div class="subT">{{ $homeCms->section_four_title }}</div>
                        <div class="flB">
                            <div class="ico">
                                <img src="{{ asset('frontend/images/google.png') }}" alt="google" width="200"
                                    height="74">
                            </div>
                            <div class="txt">{!! $homeCms->section_four_description !!}</div>
                        </div>
                    </div>
                    <div class="reviewBx">
                        @foreach ($googleReviews as $googleReview)
                            <div class="item">
                                <div class="review">
                                    <div class="nameWrap">
                                        <div class="ico">
                                            <img src="{{ $googleReview->avatar_value }}"
                                                alt="{{ $googleReview->avatar_alt_text }}" width="64"
                                                height="64">
                                        </div>
                                        <div class="ritb">
                                            <div class="name">{{ $googleReview->name }}</div>
                                            <div class="post">{{ $googleReview->profession }}</div>
                                        </div>
                                    </div>
                                    <div class="txt">
                                        {{ $googleReview->review }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-community wow animate__fadeIn">
                        <div class="swiper-wrapper">
                            @foreach ($googleReviews->chunk(2) as $googleReview)
                                <div class="swiper-slide">
                                    <div class="split">
                                        @foreach ($googleReview as $review)
                                            <div class="item">
                                                <div class="review">
                                                    <div class="nameWrap">
                                                        <div class="ico">
                                                            <img src="{{ $review->avatar_value }}"
                                                                alt="{{ $review->avatar_alt_text }}" width="64"
                                                                height="64">
                                                        </div>
                                                        <div class="ritb">
                                                            <div class="name">{{ $review->name }}</div>
                                                            <div class="post">{{ $review->profession }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="txt">
                                                        {{ $review->review }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($spaceCategories->isNotEmpty())
            <section id="designSec">
                <div class="container">
                    <div class="designB">
                        <div class="swiper-design wow animate__fadeIn">
                            <div class="swiper-wrapper">
                                @foreach ($spaceCategories as $spaceCategory)
                                    <div class="swiper-slide">
                                        <div class="imgBx">
                                            <img loading="lazy" src="{{ $spaceCategory->image_value }}"
                                                data-src="{{ $spaceCategory->image_value }}" class="lazy"
                                                alt="{{ $spaceCategory->image_alt_text_value ?? $spaceCategory->title }}"
                                                width="1920" height="1080" />
                                            <div class="txtB">
                                                <div class="title">{{ $spaceCategory->title }}</div>
                                                <div class="txt">{{ $spaceCategory->city->name }} ,
                                                    {{ $spaceCategory->state->name }} </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tleWrap center">
                            <div class="mTle">
                                {!! $homeCms->section_five_title !!}
                            </div>
                        </div>
                    </div>
                    @if ($clients->isNotEmpty())
                        <div class="sliderWrap">
                            <div class="clientSlide owl-carousel">
                                @foreach ($clients as $client)
                                    <div class="item">
                                        <div class="imgB">
                                            <img src="{{ $client->logo_value }}"
                                                alt="{{ $client->logo_alt_text_value }}" width="296" height="125">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <section id="futureSec">
            <div class="container">
                <div class="ftrBx">
                    @if ($homeCms && $homeCms->section_six_image_value != null)
                        <div class="imgBx">
                            <img loading="lazy" src="{{ $homeCms->section_six_image_value }}"
                                data-src="{{ $homeCms->section_six_image_value }}" class="lazy"
                                alt="{{ $homeCms->section_six_image_alt_text_value }}" width="1920" height="1080" />
                        </div>
                    @endif
                    <div class="txtBx">
                        <div class="title">{{ $homeCms->section_six_title }}</div>
                        {!! $homeCms->section_six_description !!}
                    </div>
                </div>
            </div>
        </section>

        @if ($accrediteds->isNotEmpty())
            <section id="logoSec">
                <div class="container">
                    <div class="logoSlide owl-carousel">
                        @foreach ($accrediteds as $accredited)
                            <div class="item">
                                <div class="imgB">
                                    <img src="{{ $accredited->logo_value }}"
                                        alt="{{ $accredited->logo_alt_text_value }}" width="260" height="120">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif


    </div>
@endsection
@push('js')
    {{-- Placeholder for pushing JavaScript files specific to this page into the stack --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"
        integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"
        integrity="sha512-P2IDYZfqSwjcSjX0BKeNhwRUH8zRPGlgcWl5n6gBLzdi4Y5/0O4zaXrtO4K9TZK6Hn1BenYpKowuCavNandERg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray(".panel").forEach((panel, i) => {
            ScrollTrigger.create({
                trigger: panel,
                start: "0 0",
                pin: true,
                pinSpacing: false,
                onLeave: () => {
                    panel.classList.add("hide");
                },
                onEnterBack: () => {
                    panel.classList.remove("hide");
                }
            });
        });

        gsap.to(".scaleUp", {
            scale: 1,
            borderRadius: "0px",
            ease: "none",
            scrollTrigger: {
                trigger: ".panel1",
                pin: ".panel1",
                start: "-20%",
                end: "+=100vh",
                scrub: 1,
                snap: {
                    snapTo: [0, 0.7, 1],
                    duration: {
                        min: 0.2,
                        max: 0.5
                    },
                    delay: 0.05
                },
                onUpdate: (self) => {
                    if (self.progress >= 0.7) {
                        gsap.set(".scaleUp", {
                            borderRadius: "0px"
                        });
                        document.querySelector(".panel1").classList.add("show");
                    } else {
                        gsap.set(".scaleUp", {
                            borderRadius: ""
                        });
                        document.querySelector(".panel1").classList.remove("show");
                    }
                }
            }
        });

        $('.exploreSlide').owlCarousel({
            loop: true,
            rewind: false,
            autoplay: true,
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

        new Swiper('.swiper-expression', {
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
                    slidesPerView: 1.1,
                    spaceBetween: 26
                }
            }
        });

        $('.brandSlide').owlCarousel({
            loop: false,
            rewind: false,
            autoplay: false,
            nav: false,
            dots: false,
            items: 3,
            margin: 10,
            responsive: {
                468: {
                    margin: 12,
                    items: 5,
                },
                768: {
                    margin: 15,
                    items: 5,
                },
                992: {
                    margin: 18,
                    items: 5,
                },
                1200: {
                    margin: 22,
                    items: 6,
                },
                1441: {
                    margin: 26,
                    items: 6,
                }
            }
        });

        $('.ftreSlide').owlCarousel({
            loop: true,
            rewind: false,
            autoplay: false,
            nav: false,
            dots: false,
            items: 1.4,
            margin: 12,
            responsive: {
                468: {
                    margin: 12,
                    items: 3,
                },
                768: {
                    margin: 15,
                    items: 4,
                },
                992: {
                    margin: 18,
                    items: 4.2,
                },
                1200: {
                    margin: 22,
                    items: 4.2,
                },
                1441: {
                    margin: 37,
                    items: 4.2,
                }
            }
        });

        new Swiper('.swiper-community', {
            lazy: true,
            loop: false,
            freeMode: true,
            slidesPerView: 1,
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


        new Swiper('.swiper-design', {
            autoplay: {
                delay: 3000,
            },
            effect: "fade",
            fadeEffect: {
                crossFade: true,
            }

        });


        $('.clientSlide').owlCarousel({
            loop: true,
            rewind: false,
            autoplay: true,
            autoplayTimeout: 3500,
            autoplayHoverPause: true,
            nav: false,
            dots: false,
            items: 5,
            margin: 5,
            responsive: {
                468: {
                    margin: 5,
                    items: 5,
                },
                768: {
                    margin: 15,
                    items: 4,
                },
                992: {
                    margin: 18,
                    items: 5,
                },
                1200: {
                    margin: 22,
                    items: 5,
                },
                1441: {
                    margin: 32,
                    items: 5,
                }
            }
        });

        $('.logoSlide').owlCarousel({
            loop: false,
            rewind: false,
            autoplay: false,
            nav: false,
            dots: false,
            items: 3,
            margin: 40,
            responsive: {
                468: {
                    margin: 40,
                    items: 3,
                },
                768: {
                    margin: 15,
                    items: 4,
                },
                992: {
                    margin: 18,
                    items: 5,
                },
                1200: {
                    margin: 30,
                    items: 4,
                },
                1441: {
                    margin: 53,
                    items: 4,
                }
            }
        });
    </script>
@endpush
