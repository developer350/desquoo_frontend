@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    {{-- SWIPER --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush
@section('content')
    <main id="pageWrapper" class="officePage bulkorderPage">
        <section id="InnerHero">
            <img src="{{ $cms->banner_value }}" width="1920" height="1080" loading="lazy"
                alt="{{ $cms->banner_alt_text_value }}" class="dsk">
            <img src="{{ $cms->banner_mobile_value }}" alt="{{ $cms->banner_alt_text_value }}" class="mob">
            <div class="container">
                <div class="mainTleWrap">
                    <div class="sTle">{{ $cms->banner_super_title }}</div>
                    <h1 class="mTle">{{ $cms->banner_title }}</h1>
                </div>
                <ul class="dFlx">
                    @if ($cms->show_want_to_chat)
                        <li class="cmnDrop">
                            <a href="#!" aria-label="expert" class="expertInfoBx" role="button"
                                data-bs-toggle="dropdown">
                                <div class="imgWrap">
                                    <img src="{{ $cms->expert_image_value }}" width="40" height="40" loading="lazy"
                                        alt="expert">
                                </div>
                                <div class="cntWrap">
                                    <div class="txt">Want to Chat?</div>
                                    <div class="txt disc"><span> {{ $cms->want_to_chat_text ?? 'Ask Our Expert' }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu">
                                @if ($siteSettings->whatsapp_number != null)
                                    <a class="chat" href="https://wa.me/{{ $siteSettings->formatted_whatsapp_number }}"
                                        target="_blank">Chat on Whatsapp</a>
                                @endif
                                @if ($siteSettings->phone_number != null)
                                    <a class="txt"
                                        href="tel:{{ $siteSettings->formatted_phone_number }}">{{ $siteSettings->phone_number }}</a>
                                @endif
                            </div>
                        </li>
                    @endif
                    @if ($cms->show_want_to_talk && $cms->want_to_talk_number != null)
                        <li>
                            <div class="expertInfoBx">
                                <div class="imgWrap">
                                    <img src="{{ asset('frontend/images/office_design-call.svg') }}" width="40"
                                        height="40" loading="lazy" alt="call">
                                </div>
                                <div class="cntWrap">
                                    <div class="txt">Want to Talk?</div>
                                    <div class="txt disc"><span>Call Us at <a href="tel:{{ $cms->formatted_phone_number }}"
                                                target="_blank">{{ $cms->want_to_talk_number }}</a></span> </div>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </section>

        @if ($bulkOrderProducts->isNotEmpty())
            <section id="products">
                <div class="sp-container rgt">
                    <div class="tleWrap center">
                        <div class="mTle">{{ $cms->section_one_title }}</div>
                    </div>
                    <div class="swiper productSlider">
                        <div class="swiper-wrapper">
                            @foreach ($bulkOrderProducts as $product)
                                <div class="swiper-slide">
                                    @include('products.partials.single-item')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($benefits->isNotEmpty())
            <section id="bulkBuy">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">{{ $cms->section_two_title }}</div>
                    </div>
                    <div class="swiper bulkSlider">
                        <div class="swiper-wrapper">
                            @foreach ($benefits as $benefit)
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)" class="bulkBox" aria-label="bolkbox">
                                        <div class="imgBx">
                                            <img src="{{ $benefit->icon_value }}" width="40" height="40"
                                                alt="{{ $benefit->icon_alt_text_value }}">
                                        </div>
                                        <div class="title">{{ $benefit->title }}</div>
                                        <p>{{ $benefit->description }}</p>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
            </section>
        @endif

        @if ($successStoryCategories->isNotEmpty())
            <section id="stories">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">{{ $cms->section_three_title }}</div>
                        <div class="sTle">{!! $cms->section_three_description !!}</div>
                    </div>
                </div>
                <div class="owl-carousel storySlider">
                    @foreach ($successStoryCategories as $successStoryCategory)
                        <div class="item">
                            <a href="javascipt:void(0)" class="storiesBx" araia-label="strory">
                                <div class="imgBx">
                                    <img src="{{ $successStoryCategory->image_value }}" class="lazy" width="1350"
                                        height="850" loading="lazy"
                                        alt="{{ $successStoryCategory->image_alt_text_value }}">
                                </div>
                                <div class="contentBx">
                                    <div class="name">{{ $successStoryCategory->title }}</div>
                                    <div class="place">{{ $successStoryCategory->city->name }} ,
                                        {{ $successStoryCategory->state->name }} </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($googleReviews->isNotEmpty())
            <section id="testimonial">
                <div class="container">
                    <div class="owl-carousel testSlider">
                        @foreach ($googleReviews as $googleReview)
                            <div class="item">
                                <a href="javascipt:void(0)" class="testBox" araia-label="testBox">
                                    <div class="imgBx">
                                        <img src="{{ $googleReview->avatar_value }}" class="lazy" width="1350"
                                            height="850" loading="lazy" alt="{{ $googleReview->avatar_alt_text }}">
                                    </div>
                                    <div class="contentBx">
                                        <div class="name">{{ $googleReview->name }}</div>
                                        <div class="place">{{ $googleReview->profession }}</div>
                                        <p>
                                            {{ $googleReview->review }}
                                        </p>

                                        <div class="starBx">
                                            <span>{{ $googleReview->rating }}</span>
                                            <div class="icon">
                                                <svg viewBox="0 0 19 18" fill="none">
                                                    <path
                                                        d="M8.71345 0.728092C9.23879 -0.0845054 10.4276 -0.0845056 10.9529 0.728092L13.2026 4.20792C13.3825 4.48621 13.6596 4.68753 13.9798 4.77264L17.9845 5.83688C18.9197 6.0854 19.287 7.21598 18.6766 7.96671L16.0622 11.1816C15.8532 11.4387 15.7473 11.7645 15.7653 12.0953L15.9907 16.2329C16.0433 17.1991 15.0816 17.8978 14.179 17.5492L10.3136 16.0563C10.0044 15.9369 9.66192 15.9369 9.35279 16.0563L5.48737 17.5492C4.58474 17.8978 3.62301 17.1991 3.67563 16.2329L3.901 12.0953C3.91903 11.7645 3.81319 11.4387 3.60411 11.1816L0.989784 7.96671C0.379296 7.21598 0.746644 6.0854 1.68181 5.83688L5.68651 4.77264C6.00677 4.68753 6.28387 4.48621 6.46378 4.20792L8.71345 0.728092Z"
                                                        fill="#F7D168" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($innovators->isNotEmpty())
            <section id="PartnerSection">
                <div class="tleWrap center">
                    <div class="mTle">{{ $cms->section_four_title }}</div>
                    <div class="sTle">{!! $cms->section_four_description !!}</div>
                </div>
                <div class="swiper partnerSlide">
                    <div class="swiper-wrapper">
                        @foreach ($innovators as $innovator)
                            <div class="swiper-slide">
                                <div class="partnerBx">
                                    <img src="{{ $innovator->logo_value }}" width="106" height="36"
                                        loading="lazy" alt="{{ $innovator->logo_alt_text_value }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section id="workSpace">
            <div class="container">
                <div class="WorkspaceBx" data-anchor="destinationPage" data-jarallax data-type="scroll"
                    class="jarallax">
                    <img src="{{ $cms->section_five_image_value }}" width="1790" height="560" loading="lazy"
                        class="lazy jarallax-img" alt="{{ $cms->section_five_image_alt_text_value }}">

                    <div class="contentBx">
                        <div class="tleWrap  ">
                            <div class="mTle">{{ $cms->section_five_title }}</div>
                            <div class="sTle">{!! $cms->section_five_description !!}</div>
                        </div>
                        <a href="#EnquirySection" class="createBt hoveranim">
                            <span>{{ $cms->section_five_button_title }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <section id="EnquirySection">
            <div class="container">
                <div class="tleWrap center">
                    <div class="mTle">{{ $cms->section_six_title }}</div>
                    <div class="sTle">{!! $cms->section_six_description !!}</div>
                </div>
                <div class="formWrap">
                    @include('partials.enquiry-form', ['model' => 'BulkOrderEnquiry'])
                </div>
            </div>
        </section>

    </main>
@endsection
@push('js')
    <!-- JARALLAX --->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jarallax/2.0.3/jarallax.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jarallax/2.0.3/jarallax.min.js"></script>



    {{-- GSAP --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"
        integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"
        integrity="sha512-P2IDYZfqSwjcSjX0BKeNhwRUH8zRPGlgcWl5n6gBLzdi4Y5/0O4zaXrtO4K9TZK6Hn1BenYpKowuCavNandERg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- SWIPER --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {

            gsap.registerPlugin(ScrollTrigger);

            // Freeze & parallax InnerHero
            gsap.to("#InnerHero", {
                filter: "blur(5px)",
                scale: 1.05,
                ease: "none",
                scrollTrigger: {
                    trigger: "#InnerHero",
                    start: "top top",
                    end: "bottom top",
                    scrub: true,
                    pin: true,
                    anticipatePin: 1
                }
            });

            ScrollTrigger.create({
                trigger: "#bulkBuy",
                start: "top  top",
                end: "top top",
                scrub: true
            });

            const productSlider = new Swiper('.productSlider', {
                loop: true,
                rewind: true,
                slidesPerView: 1,
                spaceBetween: 10,
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
                    478: {
                        slidesPerView: 1.5,
                    },
                    578: {
                        slidesPerView: 2,
                    },
                    578: {
                        slidesPerView: 2.5,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 3.1,
                        spaceBetween: 20,
                    }
                }
            });

            const bulkSlider = new Swiper('.bulkSlider', {
                loop: true,
                rewind: true,
                slidesPerView: 1,
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
                    376: {
                        slidesPerView: 1.3,
                    },
                    576: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 20,

                        grid: {
                            rows: 2,
                            fill: "row",
                        },
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                        grid: {
                            rows: 2,
                            fill: "row",
                        },

                    },
                    1551: {
                        spaceBetween: 30,
                        slidesPerView: 3,
                        grid: {
                            rows: 2,
                            fill: "row",
                        },

                    },

                }
            });


            const partnerSlide = new Swiper(".partnerSlide", {
                loop: true,
                slidesPerView: 3,
                spaceBetween: 10,

                // ✅ Continuous autoplay
                autoplay: {
                    delay: 1, // almost zero delay
                    disableOnInteraction: false,
                },

                speed: 3000, // higher = smoother / slower movement
                freeMode: true, // free scrolling
                freeModeMomentum: false, // disable momentum bounce
                allowTouchMove: false, // disable dragging if you want pure marquee

                breakpoints: {
                    376: {
                        slidesPerView: 3,
                    },
                    576: {
                        slidesPerView: 6,
                    },
                    1551: {
                        slidesPerView: 6,
                    },
                },
            });


            // const slideCount = document.querySelectorAll('.storySlider .swiper-slide').length;

            // const swiper = new Swiper(".storySlider", {
            //     loop: slideCount > 1, // only enable loop if more than 1 slide
            //     centeredSlides: true,
            //     slidesPerView: 1.2,
            //     spaceBetween: 20,
            //     navigation: {
            //         nextEl: ".swiper-button-next",
            //         prevEl: ".swiper-button-prev",
            //     },
            //     breakpoints: {
            //         768: {
            //             slidesPerView: 1.8,
            //         },
            //         1024: {
            //             slidesPerView: 2.5,
            //         },
            //     },
            // });


        })


        $(document).ready(function() {
            var itemCount = $(".storySlider .item").length;

            $(".storySlider").owlCarousel({
                loop: itemCount > 1, // ✅ only loop if more than 1 slide
                margin: 20,
                center: true,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                smartSpeed: 600,
                responsive: {
                    0: {
                        items: 1
                    },
                    568: {
                        items: 1.3
                    }
                }
            });
            $(".testSlider").owlCarousel({
                loop: itemCount > 1, // ✅ only loop if more than 1 slide
                margin: 20,
                center: true,
                dots: true,
                autoplay: false,
                autoplayTimeout: 3000,
                smartSpeed: 600,
                items: 1
            });
        });
    </script>
    @include('js.jquery-validate')
    <script>
        setupValidation('#enquiryForm');
    </script>
@endpush
