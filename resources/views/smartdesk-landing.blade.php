@extends('layouts.app')
@section('title', 'Smart desk landing')
@push('css')
    {{-- SWIPER --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush
@section('content')
    <main id="pageWrapper" class="smartdeskPage">
        <section id="InnerBanner">
            {{-- <img src="{{ asset('frontend/images/office_design-hero-1.jpg') }}" width="1920" height="1080" loading="lazy"
                alt="expert"> --}}
            <video class="lazy dsk video" autoplay muted loop playsinline width="1920"
                poster="https://placehold.co/1920x1080">
                <source type="video/mp4" src="{{ asset('frontend/videos/dwnld.mp4') }}">
            </video>
            <video class="lazy mob video" autoplay muted loop playsinline width="1080"
                poster="https://placehold.co/1080x1920">
                <source type="video/mp4" src="{{ asset('frontend/videos/dwnld.mp4') }}">
            </video>
            <div class="container">
                <div class="mainTleWrap">
                    <div class="sTle">Designed for comfort & productivity</div>
                    <h1 class="mTl9*/e">Elevate Your Workday
                        with Neo Smart Desk</h1>
                    <a href="#!" data-bs-toggle="modal" data-bs-target="#customizeModal"
                        class="customize hoveranim"><span>Customize your desk</span></a>
                    <a href="#!" class="bulkOrdr">Looking to place a Bulk Order?</a>
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
                        <div class="ttle">Neo SmartDesk™</div>
                        <a href="#prodViwSec">Overview</a>
                        <a href="#ProductFaqSection">Specs</a>
                        <a href="#FaqSection">FAQ</a>
                    </div>
                    <div class="ritB">
                        <a href="#!" class="customize" data-bs-toggle="modal"
                            data-bs-target="#customizeModal"><span>Customize & Order</span></a>
                    </div>
                </div>
            </div>
        </section>
        
        <div data-bs-spy="scroll" data-bs-target="#simple-list-example">
            <section id="prodViwSec">
                <div class="container">
                    <div class="tpoB">
                        <div class="txt">Neo Smart Desk is the result of listening, learning, and building for you the
                            change
                            makers. Shaped by years of research and feedback. More than a desk it's where productivity
                            thrives,
                            ideas grow, and your future begins.</div>
                    </div>
                    <div class="visualWrap">
                        <div class="infoTxt">Click & turn<br>
                            to explore</div>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/dsk1.png') }}" alt="product">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/dsk1.png') }}" alt="product">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab" tabindex="0">
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/dsk1.png') }}" alt="product">
                                </div>
                            </div>
                        </div>
                        <div class="btmFlxWrp">
                            <div class="navProduct">
                                <div class="itm">
                                    <div class="proNav">
                                        <input type="radio" id="a1" name="c1" value="" required=""
                                            checked>
                                        <label for="a1">
                                            <img src="{{ asset('frontend/images/z1.png') }}" alt="color">
                                        </label>
                                    </div>
                                </div>
                                <div class="itm">
                                    <div class="proNav">
                                        <input type="radio" id="a2" name="c1" value="" required="">
                                        <label for="a2">
                                            <img src="{{ asset('frontend/images/z2.png') }}" alt="color">
                                        </label>
                                    </div>
                                </div>
                                <div class="itm">
                                    <div class="proNav">
                                        <input type="radio" id="a3" name="c1" value=""
                                            required="">
                                        <label for="a3">
                                            <img src="{{ asset('frontend/images/z3.png') }}" alt="color">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab"
                                        aria-controls="pills-home" aria-selected="true">Standard</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">Plus</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">Pro</button>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </section>
            <section id="differanceSec">
                <div class="imgBx">
                    <img loading="lazy" src="{{ asset('frontend/images/diff.jpg') }}"
                        data-src="{{ asset('frontend/images/diff.jpg') }}" class="lazy" alt="differance"
                        width="1920" height="1080" />
                </div>
                <div class="txtBx">
                    <div class="title">“ It's the first desk<br>
                        that's actually made a difference in how I work and feel every day ”</div>
                    <div class="subT">Kanchan works from home for<br>
                        a leading crypto startup</div>
                </div>
            </section>

            <section id="healthSec">
                <div class="container">
                    <div class="titleWrp">
                        <div class="subT">HEALTH MEETS PRODUCTIVITY</div>
                        <div class="mainT">Effortlessly transition between sitting and standing, keeping you energised all
                            day
                        </div>
                        <a href="#!" class="more hoveranim"><span>Learn More</span></a>
                    </div>
                    <div class="flexWrap">
                        <div class="item">
                            <a href="#!" class="healthB">
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/qq.png') }}" alt="color">
                                </div>
                                <div class="title"><span>Supports spinal alignment</span> by encouraging a straight
                                    posture,
                                    reducing back
                                    pain and long-term strain.</div>
                                <div class="ico">
                                    <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="healthB">
                                <div class="title">Alternating positions engages your core and leg muscles gently
                                    throughout
                                    the day <span>strengthening muscles over time</span></div>
                                <div class="ico">
                                    <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="healthB">
                                <div class="title"><span>Reduces shoulder and
                                        neck tension.</span> Promotes relaxed upper body positioning during long work hours.
                                </div>
                                <div class="ico">
                                    <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="healthB">
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/qq1.png') }}" alt="color">
                                </div>
                                <div class="title">Enhances mental clarity and focus through better posture and movement
                                    <span>boosting oxygen flow to the brain</span>
                                </div>
                                <div class="ico">
                                    <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="sp-container rgt">
                    <div class="swiper healthSlide">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="#!" class="healthB bg1">
                                    <div class="imgB">
                                        <img src="{{ asset('frontend/images/qq.png') }}" alt="color">
                                    </div>
                                    <div class="title"><span>Supports spinal alignment</span> by encouraging a straight
                                        posture,
                                        reducing back
                                        pain and long-term strain.</div>
                                    <div class="ico">
                                        <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#!" class="healthB">
                                    <div class="title">Alternating positions engages your core and leg muscles gently
                                        throughout
                                        the day <span>strengthening muscles over time</span></div>
                                    <div class="ico">
                                        <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#!" class="healthB">
                                    <div class="title"><span>Reduces shoulder and
                                            neck tension.</span> Promotes relaxed upper body positioning during long work
                                        hours.
                                    </div>
                                    <div class="ico">
                                        <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#!" class="healthB">
                                    <div class="imgB">
                                        <img src="{{ asset('frontend/images/qq1.png') }}" alt="color">
                                    </div>
                                    <div class="title">Enhances mental clarity and focus through better posture and
                                        movement
                                        <span>boosting oxygen flow to the brain</span>
                                    </div>
                                    <div class="ico">
                                        <img src="{{ asset('frontend/images/rit1.png') }}" alt="arrow">
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="engineeringSec">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">Mindful Engineering</div>
                    </div>
                    <div class="blckbx">
                        <div class="item">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">Height setting memory</div>
                                    <div class="txt">One-touch memory settings that remember exactly how you like it.
                                        Adjust
                                        seamlessly with precision touch controls tailored to your needs.</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e0.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">3 Segment Legs</div>
                                    <div class="txt">The super-strong, powder-coated metal legs with 3 segments ensure
                                        unmatched stability and can carry weight up to 140kg. Height adjustable from 62cm to
                                        127cm</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e1.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">Noise free with DUTOR L3™ </div>
                                    <div class="txt">Powered by dual ultra-quiet motors that lifts up to 308 lbs with
                                        stable
                                        motion, no wobble, no lag. Precision sensors and adaptive intelligence ensure
                                        seamless,
                                        near-silent performance you can feel—and never hear.</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e2.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">3 Segment Legs</div>
                                    <div class="txt">The super-strong, powder-coated metal legs with 3 segments ensure
                                        unmatched stability and can carry weight up to 140kg. Height adjustable from 62cm to
                                        127cm</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e3.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper engineeringSlide">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">Height setting memory</div>
                                    <div class="txt">One-touch memory settings that remember exactly how you like it.
                                        Adjust
                                        seamlessly with precision touch controls tailored to your needs.</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e0.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">3 Segment Legs</div>
                                    <div class="txt">The super-strong, powder-coated metal legs with 3 segments ensure
                                        unmatched stability and can carry weight up to 140kg. Height adjustable from 62cm to
                                        127cm</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e1.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">Noise free with DUTOR L3™ </div>
                                    <div class="txt">Powered by dual ultra-quiet motors that lifts up to 308 lbs with
                                        stable
                                        motion, no wobble, no lag. Precision sensors and adaptive intelligence ensure
                                        seamless,
                                        near-silent performance you can feel—and never hear.</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e2.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="engineeringBx">
                                <div class="cntWrap">
                                    <div class="ttle">3 Segment Legs</div>
                                    <div class="txt">The super-strong, powder-coated metal legs with 3 segments ensure
                                        unmatched stability and can carry weight up to 140kg. Height adjustable from 62cm to
                                        127cm</div>
                                </div>
                                <div class="imgB">
                                    <img src="{{ asset('frontend/images/e3.png') }}" loading="lazy" alt="engineering">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <section id="deskSec">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">Find the right desk for you</div>
                    </div>

                    <div class="wrapBx">
                        <div class="item panel">
                            <div class="flxWrp">
                                <div class="lftB">
                                    <img src="{{ asset('frontend/images/d1.jpg') }}" alt="desk" width="1040"
                                        height="693">
                                </div>
                                <div class="ritB">
                                    <div class="title">Neo Smart Desk™ <span>Standard</span></div>
                                    <div class="scale">120 cm x 60 cm </div>
                                    <div class="txt">Best for minimal setup</div>
                                </div>
                            </div>
                        </div>
                        <div class="item panel">
                            <div class="flxWrp">
                                <div class="lftB">
                                    <img src="{{ asset('frontend/images/d2.jpg') }}" alt="desk" width="1040"
                                        height="693">
                                </div>
                                <div class="ritB">
                                    <div class="title">Neo Smart Desk™ <span>Pro</span></div>
                                    <div class="scale">150 cm x 75 cm</div>
                                    <div class="txt">Your perfect work setup</div>
                                </div>
                            </div>
                        </div>
                        <div class="item panel">
                            <div class="flxWrp">
                                <div class="lftB">
                                    <img src="{{ asset('frontend/images/d3.jpg') }}" alt="desk" width="1040"
                                        height="693">
                                </div>
                                <div class="ritB">
                                    <div class="title">Neo Smart Desk™ <span>Plus</span></div>
                                    <div class="scale">180 cm x 75 cm</div>
                                    <div class="txt">For gaming and more</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="ProductFaqSection">
                <div class="container">
                    <div class="mFlx">
                        <div class="lftSd">
                            <div class="imgWrap">
                                <img src="{{ asset('frontend/images/product-faq-1.jpg') }}" alt="nav"
                                    width="668" height="1000" loading="lazy">
                            </div>
                        </div>
                        <div class="rgtSd">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home-tab-pane" type="button" role="tab"
                                        aria-controls="home-tab-pane" aria-selected="true">Standard</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-tab-pane" type="button" role="tab"
                                        aria-controls="profile-tab-pane" aria-selected="false">Plus</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#contact-tab-pane" type="button" role="tab"
                                        aria-controls="contact-tab-pane" aria-selected="false">Pro</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="accordion specFaqAcco" id="SpecFaqAcco">
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <button class="accordion-button " type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#SpecFaqAccoItem_1"
                                                    aria-expanded="true" aria-controls="SpecFaqAccoItem_1">
                                                    Specs
                                                </button>
                                            </div>
                                            <div id="SpecFaqAccoItem_1" class="accordion-collapse collapse show"
                                                data-bs-parent="#SpecFaqAcco">
                                                <div class="accordion-body">
                                                    <div class="ckCntWrap">
                                                        <ul class="specs">
                                                            <li><span>Adjustable Height</span> 610 mm - 1260 mm
                                                            </li>
                                                            <li><span>Stroke</span> 650 mm</li>
                                                            <li><span>Max Speed</span>32 mm/s</li>
                                                            <li><span>Max Load</span>1200 N</li>
                                                            <li><span>Noise Level</span>
                                                                < 50 dB </li>
                                                            <li><span>Input Power</span>AC 100-220V @ 50-60Hz</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#SpecFaqAccoItem_2"
                                                    aria-expanded="false" aria-controls="SpecFaqAccoItem_2">
                                                    Features
                                                </button>
                                            </div>
                                            <div id="SpecFaqAccoItem_2" class="accordion-collapse collapse"
                                                data-bs-parent="#SpecFaqAcco">
                                                <div class="accordion-body">
                                                    <div class="ckCntWrap">
                                                        <h5>Lorem ipsum </h5>
                                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                            nostrum
                                                            eveniet tenetur dolores quasi ipsum omnis fuga minima quaerat
                                                            nobis
                                                            earum
                                                            reiciendis voluptas maxime at, illo est excepturi. Alias,
                                                            debitis.
                                                        </p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Ullam,
                                                                tempora.
                                                            </li>
                                                            <li>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Ullam,
                                                                tempora.
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#SpecFaqAccoItem_3"
                                                    aria-expanded="false" aria-controls="SpecFaqAccoItem_3">
                                                    Measurement & Dimension
                                                </button>
                                            </div>
                                            <div id="SpecFaqAccoItem_3" class="accordion-collapse collapse"
                                                data-bs-parent="#SpecFaqAcco">
                                                <div class="accordion-body">
                                                    <div class="ckCntWrap">
                                                        <h5>Lorem ipsum </h5>
                                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                            nostrum
                                                            eveniet tenetur dolores quasi ipsum omnis fuga minima quaerat
                                                            nobis
                                                            earum
                                                            reiciendis voluptas maxime at, illo est excepturi. Alias,
                                                            debitis.
                                                        </p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Ullam,
                                                                tempora.
                                                            </li>
                                                            <li>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Ullam,
                                                                tempora.
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#SpecFaqAccoItem_4"
                                                    aria-expanded="false" aria-controls="SpecFaqAccoItem_4">
                                                    Warranty and Shipping
                                                </button>
                                            </div>
                                            <div id="SpecFaqAccoItem_4" class="accordion-collapse collapse"
                                                data-bs-parent="#SpecFaqAcco">
                                                <div class="accordion-body">
                                                    <div class="ckCntWrap">
                                                        <h5>Lorem ipsum </h5>
                                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore,
                                                            nostrum
                                                            eveniet tenetur dolores quasi ipsum omnis fuga minima quaerat
                                                            nobis
                                                            earum
                                                            reiciendis voluptas maxime at, illo est excepturi. Alias,
                                                            debitis.
                                                        </p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Ullam,
                                                                tempora.
                                                            </li>
                                                            <li>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Ullam,
                                                                tempora.
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                    aria-labelledby="profile-tab" tabindex="0">...</div>
                                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel"
                                    aria-labelledby="contact-tab" tabindex="0">...</div>
                                <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel"
                                    aria-labelledby="disabled-tab" tabindex="0">...</div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section id="calculatorSec">
                <div class="container">
                    <div class="flexWrap">
                        <div class="titleWrap">
                            <div class="title">Optimal Desk Height Calculator</div>
                            <div class="txt">Discover your ideal desk height for both sitting and standing.Simply enter
                                your
                                height (with shoes) to get started.</div>
                        </div>
                        <div class="lftB">
                            <div class="wrapBx">
                                <div class="item">
                                    <div class="box">
                                        <input type="text" class="form-control" placeholder="Enter your height">
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="box">
                                        <input type="radio" id="a11" name="c11" value=""
                                            required="" checked>
                                        <label for="a11">
                                            in
                                        </label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="box">
                                        <input type="radio" id="a12" name="c11" value=""
                                            required="">
                                        <label for="a12">
                                            cm
                                        </label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="box">
                                        <input type="radio" id="a13" name="5" value=""
                                            required="" checked>
                                        <label for="a13">
                                            Standing
                                        </label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="box">
                                        <input type="radio" id="a14" name="5" value=""
                                            required="">
                                        <label for="a14">
                                            Sitting
                                        </label>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="box">
                                        <a href="#!" class="go">
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
                                <img src="{{ asset('frontend/images/tableAdd-2.png') }}" alt="nav" width="500"
                                    height="401" loading="lazy">
                            </div>
                            <div class="height">192 cm</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="assemblySec">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="subT">DIY ASSEMBLY</div>
                        <div class="mTle">Assembly is easier than you think,
                            done in less than 45min</div>
                    </div>
                    <div class="imgBx">
                        <img src="{{ asset('frontend/images/aa.png') }}" alt="assembly" width="1369" height="1159">
                    </div>
                    <div class="ttleB">
                        <div class="stle">Customer Support</div>
                        <div class="mtle">Help me Assemble</div>
                    </div>
                </div>
            </section>

            <section id="FaqSection">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">Frequently Asked Questions</div>
                    </div>
                    <div class="accordion FaqAcco" id="FaqAcco">
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_1" aria-expanded="false" aria-controls="FaqAccoItem_1">
                                    What is the height adjustment range of the SmartDesk?
                                </button>
                            </div>
                            <div id="FaqAccoItem_1" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">
                                        <p>The SmartDesk can be adjusted from 62 cm to 127 cm, making it suitable for both
                                            sitting and standing work position</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_2" aria-expanded="false" aria-controls="FaqAccoItem_2">
                                    How much weight can the SmartDesk support?
                                </button>
                            </div>
                            <div id="FaqAccoItem_2" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_3" aria-expanded="false" aria-controls="FaqAccoItem_3">
                                    How does the height adjustment mechanism work?
                                </button>
                            </div>
                            <div id="FaqAccoItem_3" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_4" aria-expanded="false" aria-controls="FaqAccoItem_4">
                                    Is the SmartDesk easy to assemble?
                                </button>
                            </div>
                            <div id="FaqAccoItem_4" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_5" aria-expanded="false" aria-controls="FaqAccoItem_5">
                                    Does the desk have memory presets for height?
                                </button>
                            </div>
                            <div id="FaqAccoItem_5" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_6" aria-expanded="false" aria-controls="FaqAccoItem_6">
                                    Is customization available for the SmartDesk?
                                </button>
                            </div>
                            <div id="FaqAccoItem_6" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_7" aria-expanded="false" aria-controls="FaqAccoItem_7">
                                    I What safety features does the SmartDesk include?
                                </button>
                            </div>
                            <div id="FaqAccoItem_7" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_8" aria-expanded="false" aria-controls="FaqAccoItem_8">
                                    What is the warranty policy for the SmartDesk?
                                </button>
                            </div>
                            <div id="FaqAccoItem_8" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_9" aria-expanded="false" aria-controls="FaqAccoItem_9">
                                    How quickly will I receive my SmartDesk after ordering?
                                </button>
                            </div>
                            <div id="FaqAccoItem_9" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_10" aria-expanded="false"
                                    aria-controls="FaqAccoItem_10">
                                    Can I get a GST invoice for my purchase, and is cash on delivery (COD) available?
                                </button>
                            </div>
                            <div id="FaqAccoItem_10" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FaqAccoItem_11" aria-expanded="false"
                                    aria-controls="FaqAccoItem_11">
                                    Does the DESQOO SmartDesk have built-in USB charging ports?
                                </button>
                            </div>
                            <div id="FaqAccoItem_11" class="accordion-collapse collapse" data-bs-parent="#FaqAcco">
                                <div class="accordion-body">
                                    <div class="ckCntWrap">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="ProductExpertSection">
                <div class="container">
                    <div class="proExpertBx">
                        <div class="lftSd">
                            <div class="cntWrap">
                                <div class="tle">Meet our workspace <br> experts at Desqoo. </div>
                                <div class="txt">Visit us in-store for a consultation or take a virtual tour from home.
                                    <br>
                                    We'll help you find your perfect setup.
                                </div>
                                <div class="btnWrap">
                                    <div>
                                        <button class="baseBtn_2 hoveranim" data-bs-toggle="modal"
                                            data-bs-target="#VisitStoreModal">
                                            <span>Visit our store</span>
                                        </button>
                                    </div>
                                    <div>
                                        <button data-bs-toggle="modal" data-bs-target="#VirtualModal"
                                            class="baseBtn_2 hoveranim">
                                            <span>Get a virtual demo</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rgtSd">
                            <div class="imgWrap">
                                <img src="{{ asset('frontend/images/product-expert-1.png') }}" width="100"
                                    height="100" loading="lazy" alt="product-expert-1">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="OtherProductSection">
                <div class="container">
                    <div class="tleWrap center">
                        <div class="mTle">Explore other products</div>
                    </div>
                </div>
                <div class="sp-container rgt">
                    <div class="exploreSlide owl-carousel">
                        <div class="item">
                            <a href="#!" class="proBx">
                                <div class="infoB">
                                    <div class="info">Bestseller</div>
                                    <div class="info">Bestseller</div>
                                </div>
                                <div class="imgBx">
                                    <img loading="lazy" src="{{ asset('frontend/images/prd1.png') }}"
                                        data-src="{{ asset('frontend/images/prd1.png') }}" class="lazy" alt="quote"
                                        width="580" height="580" />
                                </div>
                                <div class="txtBx">
                                    <div class="lftB">
                                        <div class="title">Neo SmartDesk</div>
                                        <div class="price">Starts at <span>₹35,000</span></div>
                                    </div>
                                    <div class="ritB">
                                        <div class="colrFlx">
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg1.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg2.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg3.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg4.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg5.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="proBx">
                                <div class="infoB">
                                    <div class="info">Coming Soon</div>
                                </div>
                                <div class="imgBx">
                                    <img loading="lazy" src="{{ asset('frontend/images/prd1.png') }}"
                                        data-src="{{ asset('frontend/images/prd1.png') }}" class="lazy" alt="quote"
                                        width="580" height="580" />
                                </div>
                                <div class="txtBx">
                                    <div class="lftB">
                                        <div class="title">Neo SmartDesk</div>
                                        <div class="price">Starts at <span>₹35,000</span></div>
                                    </div>
                                    <div class="ritB">
                                        <div class="colrFlx">
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg1.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg2.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg3.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg4.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg5.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="proBx">
                                {{-- <div class="infoB">
                                <div class="info">Coming Soon</div>
                            </div> --}}
                                <div class="imgBx">
                                    <img loading="lazy" src="{{ asset('frontend/images/prd1.png') }}"
                                        data-src="{{ asset('frontend/images/prd1.png') }}" class="lazy" alt="quote"
                                        width="580" height="580" />
                                </div>
                                <div class="txtBx">
                                    <div class="lftB">
                                        <div class="title">Neo SmartDesk</div>
                                        <div class="price">Starts at <span>₹35,000</span></div>
                                    </div>
                                    <div class="ritB">
                                        <div class="colrFlx">
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg1.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg2.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg3.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg4.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg5.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="proBx">
                                <div class="infoB">
                                    <div class="info">Coming Soon</div>
                                </div>
                                <div class="imgBx">
                                    <img loading="lazy" src="{{ asset('frontend/images/prd1.png') }}"
                                        data-src="{{ asset('frontend/images/prd1.png') }}" class="lazy" alt="quote"
                                        width="580" height="580" />
                                </div>
                                <div class="txtBx">
                                    <div class="lftB">
                                        <div class="title">Neo SmartDesk</div>
                                        <div class="price">Starts at <span>₹35,000</span></div>
                                    </div>
                                    <div class="ritB">
                                        <div class="colrFlx">
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg1.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg2.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg3.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg4.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                            <div class="itm">
                                                <img src="{{ asset('frontend/images/bg5.png') }}" alt="color"
                                                    width="53" height="53">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
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
                        <li>
                            <a href="#FaqSection">FAQ</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- VISIT_STORE_MODAL -->
        <div class="modal fade baseModal_1" id="VisitStoreModal" tabindex="-1" aria-labelledby="VisitStoreModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title" id="VisitStoreModalLabel">Visit Our Store</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="addressWrap">
                            <div class="locWrap">
                                <iframe src="https://maps.google.com/maps?q=25.3076008,51.4803216&z=16&output=embed"
                                    height="450" width="600"></iframe>
                            </div>
                            <div class="locInfoWrap">
                                <div class="item">
                                    <div class="locInfoBx">
                                        <div class="tle">Address</div>
                                        <div class="txt">
                                            <p>Desqoo Enterprises Private Limited, Hub Tower, 1st Floor, Seaport-Airport
                                                Road, Kakkanad, Kochi, Kerala - 682021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="locInfoBx">
                                        <div class="tle">Contact</div>
                                        <div class="txt">
                                            <a href="tel:+91 81139 90066">+91 81139 90066</a>
                                        </div>
                                        <div class="txt">
                                            <a href="mailto:info@desqoo.com">info@desqoo.com</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="formWrap">
                                <div class="formWrapTle">
                                    <div class="tle">Looking to try it out near you?</div>
                                    <div class="txt">Share your details below, and we’ll connect you to a space <br />
                                        where
                                        you can see it, feel it, and try it for yourself</div>
                                </div>

                                @include('partials.visit-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- VIRTUAL_MODAL -->
        <div class="modal fade baseModal_1" id="VirtualModal" tabindex="-1" aria-labelledby="VirtualModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title" id="VirtualModalLabel">Book a virtual Demo</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="virtualDemoWrap">
                            <div class="lftSd">
                                <div class="virtualDemoTle">
                                    <div class="imgWrap">
                                        <img src="{{ asset('frontend/images/virtualdemo-logo-1.svg') }}"
                                            alt="virtualdemo-logo" width="126" height="27" loading="lazy">
                                    </div>
                                    <div class="tle">Virtual Demo</div>
                                </div>
                                <div class="virtuaInfoFlx">
                                    <div class="item">
                                        <div class="virtualInfoBx">
                                            <div class="icon">
                                                <img src="{{ asset('frontend/images/virtualdemo-time.svg') }}"
                                                    alt="virtualdemo-time" width="20" height="20" loading="lazy">
                                            </div>
                                            <div class="txt">30 min</div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="virtualInfoBx">
                                            <div class="icon">
                                                <img src="{{ asset('frontend/images/virtualdemo-video.svg') }}"
                                                    alt="virtualdemo-video" width="20" height="20"
                                                    loading="lazy">
                                            </div>
                                            <div class="txt">Web conferencing details provided upon confirmation.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rgtSd">
                                <div class="calendarWrap">
                                    <div class="tle">Select a Date & Time</div>
                                    <div class="formWrap">
                                        @include('partials.book-calendar')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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


        })

        $(document).on('click', '.visualWrap', function() {
            $('.infoTxt').fadeOut(400, function() {
                $(this).remove();
            });
        });

        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray(".panel").forEach((panel, i) => {
            ScrollTrigger.create({
                trigger: panel,
                start: "top top",
                pin: true,
                pinSpacing: false
            });
        });

        // ScrollTrigger.create({
        //     snap: 1 / 4
        // });


        // efficient scroll handler that toggles .active on body
        (function() {
            const section = document.getElementById('stHead');
            if (!section) return;

            let ticking = false;
            const tolerance = 1; // px tolerance to avoid jitter

            function check() {
                const top = section.getBoundingClientRect().top;
                // Use Math.abs to catch near-exact alignment.
                if (Math.abs(top) <= tolerance) {
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

            // initial check (in case page opens already scrolled)
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
@endpush
