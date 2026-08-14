@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
    {{-- SWIPER --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush
@section('content')
    <main id="pageWrapper" class="officePage">
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

        @if ($whyChooseUses->isNotEmpty())
            <section id="WhyChooseSection">
                <div class="tleWrap center">
                    <div class="mTle">{{ $cms->section_one_title }}</div>
                    <div class="sTle">{!! $cms->section_one_description !!}</div>
                </div>
                <div class="swiper whyChooseSlide">
                    <div class="swiper-wrapper">
                        @foreach ($whyChooseUses as $whyChooseUs)
                            <div class="swiper-slide">
                                <div class="whyChooseBx cursorHover">
                                    <div class="cursor"></div>
                                    <div class="cntWrap">
                                        <div class="count">{{ sprintf('0%d', $loop->iteration) }}</div>
                                        <div class="tle">{{ $whyChooseUs->title }}</div>
                                        <div class="txt">{!! $whyChooseUs->description !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section id="DriveSection">
            <div class="tleWrap center">
                <div class="mTle">{{ $cms->drive_us_title }}</div>
                <div class="sTle"> {!! $cms->drive_us_description !!}</div>
            </div>
        </section>

        @if ($partners->isNotEmpty())
            <section id="PartnerSection">
                <div class="tleWrap center">
                    <div class="mTle">{{ $cms->section_two_title }}</div>
                    <div class="sTle">{!! $cms->section_two_description !!}</div>
                </div>
                <div class="swiper partnerSlide">
                    <div class="swiper-wrapper">
                        @foreach ($partners as $partner)
                            <div class="swiper-slide">
                                <div class="partnerBx">
                                    <img src="{{ $partner->logo_value }}" width="106" height="36" loading="lazy"
                                        alt="{{ $partner->logo_alt_text_value }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($solutions->isNotEmpty())
            <section id="ExperienceSection">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">{{ $cms->section_three_title }}</div>
                        <div class="sTle"></div>
                    </div>
                    <div class="dFlx">
                        @foreach ($solutions as $solution)
                            <div>
                                <div class="experienceBx cursorHover">
                                    <div class="cursor"></div>
                                    <div class="cntWrap">
                                        <div class="tle">{{ $solution->title }}</div>
                                        <div class="txt">{!! $solution->description !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($spaceCategories->isNotEmpty())
            <section id="SpaceSection">
                <div class="tleWrap center">
                    <div class="mTle"> {{ $cms->section_four_title }}</div>
                    <div class="sTle"></div>
                </div>
                <div class="sp-container rgt">
                    <div class="swiper spaceSlide">
                        <div class="swiper-wrapper">
                            @foreach ($spaceCategories as $spaceCategory)
                                <div class="swiper-slide">
                                    <div class="spaceBx">
                                        <img src="{{ $spaceCategory->image_value }}" width="560" height="420"
                                            loading="lazy" alt="{{ $spaceCategory->image_alt_text_value }}">
                                        <div class="cntWrap">
                                            <div class="tle">{{ $spaceCategory->title }}</div>
                                            <div class="txt">{{ $spaceCategory->city->name }} ,
                                                {{ $spaceCategory->state->name }} </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section id="EnquirySection">
            <div class="container">
                <div class="tleWrap center">
                    <div class="mTle">{{ $cms->section_five_title }}</div>
                    <div class="sTle">{!! $cms->section_five_description !!}</div>
                </div>
                <div class="formWrap">
                    @include('partials.enquiry-form', ['model' => 'OfficeEnquiry'])
                </div>
            </div>
        </section>

    </main>
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
                    anticipatePin: 1,
                }
            });

            // WhyChooseSection scrolls over InnerHero
            ScrollTrigger.create({
                trigger: "#WhyChooseSection",
                start: "top bottom",
                end: "top top",
                scrub: true,
            });

            const whyChooseSlide = new Swiper('.whyChooseSlide', {
                loop: true,
                rewind: false,
                spaceBetween: 15,
                slidesPerView: "auto",
                // autoplay: {
                //     delay: 3000,
                //     disableOnInteraction: false,
                //     pauseOnMouseEnter: false,
                // },
                speed: 800,
                freeMode: false,
                freeModeFluid: true,
                freeModeMinimumVelocity: 0.02,
                freeModeSticky: false,
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

            const partnerSlide = new Swiper('.partnerSlide', {
                loop: true,
                rewind: true,
                slidesPerView: 3,
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
                    376: {
                        slidesPerView: 3,
                    },
                    576: {
                        slidesPerView: 6,
                    },
                    1551: {
                        slidesPerView: 6,
                    }
                }
            });

            const spaceSlide = new Swiper('.spaceSlide', {
                loop: true,
                rewind: true,
                spaceBetween: 10,
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
                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preventClicks: true,
                preventClicksPropagation: true,

                breakpoints: {
                    376: {
                        slidesPerView: "auto",
                        spaceBetween: 15,
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

            // HOVER_ANIMATION
            const infraBoxes = document.querySelectorAll(".cursorHover");

            infraBoxes.forEach((box) => {
                const cursor = box.querySelector(".cursor"); // Get the `.cursor` specific to this box

                // Mousemove: Update cursor position relative to the box
                box.addEventListener("mousemove", (event) => {
                    const boxRect = box
                        .getBoundingClientRect(); // Get the box's position and dimensions
                    const x = event.clientX - boxRect
                        .left; // Calculate cursor X relative to the box
                    const y = event.clientY - boxRect.top; // Calculate cursor Y relative to the box
                    cursor.style.left = `${x}px`;
                    cursor.style.top = `${y}px`;
                });

                // Mouseover: Show the cursor
                box.addEventListener("mouseenter", () => {
                    cursor.style.opacity = 1;
                    cursor.classList.add("active");
                });

                // Mouseout: Hide the cursor
                box.addEventListener("mouseleave", () => {
                    cursor.style.opacity = 0;
                    cursor.classList.remove("active");
                });
            });

        });
    </script>
    @include('js.jquery-validate')
    <script>
        setupValidation('#enquiryForm');
    </script>
@endpush
