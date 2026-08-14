<header id="Header" class="">
    <div class="headFlx">
        <div class="container">
            <div class="MainFlx">
                <div class="MenuBtn">
                    <button type="button" class="btn shape" id="menuOpen">
                        <div class="bx">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                </div>
                <div class="lSide">
                    <ul class="QckMenu">
                        @foreach ($headerCategories as $headerCategory)
                            <li class="dropdown">
                                <a href="{{ route('category-detail', ['slug' => $headerCategory->slug]) }}"
                                    class="fliplink {{ request()->routeIs('category-detail') && request()->slug == $headerCategory->slug ? 'active' : '' }}">
                                    <div class="Link" data-after="{{ $headerCategory->name }}">
                                        <span>
                                            {{ $headerCategory->name }}
                                        </span>
                                    </div>
                                </a>
                                @if ($headerCategory->children->isNotEmpty())
                                    <div class="dropDownMnu">
                                        <div class="drpbody">
                                            <div class="flxWrp">
                                                <div class="lftB">
                                                    <div class="prdSlide owl-carousel">
                                                        @foreach ($headerCategory->children as $childCategory)
                                                            <div class="item">
                                                                <a href="{{ route('sub-category-detail', ['slug' => $headerCategory->slug, 'subcategory' => $childCategory->slug]) }}"
                                                                    class="prdBx">
                                                                    <div class="imgB">
                                                                        <img src="{{ $childCategory->image_value }}"
                                                                            alt="{{ $childCategory->image_alt_text_value }}"
                                                                            width="272" height="199">
                                                                    </div>
                                                                    <div class="txtB">
                                                                        <div class="name">{{ $childCategory->name }}
                                                                        </div>
                                                                        @if ($childCategory->is_new)
                                                                            <div class="txt">New</div>
                                                                        @endif
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="ritB">
                                                    <a href="{{ route('category-detail', ['slug' => $headerCategory->slug]) }}"
                                                        class="vall">
                                                        <span class="ico">
                                                            <img src="{{ asset('frontend/images/rt.svg') }}"
                                                                alt="icon" width="19" height="13">
                                                        </span>
                                                        <span class="txt">View all</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="cntrBx">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ $siteSettings->header_logo_value ?? asset('frontend/images/logo.svg') }}"
                            alt="{{ $siteSettings->header_logo_alt_text_value ?? 'logo' }}" class="dsk">
                        <img src="{{ $siteSettings->header_mobile_logo_value ?? asset('frontend/images/MLogo.png') }}"
                            alt="{{ $siteSettings->header_mobile_logo_alt_text_value ?? 'logo' }}" class="mob">
                    </a>
                </div>
                <div class="RSide">
                    <ul class="QckMenu">
                        <li class="dropdown">
                            <a href="{{ route('office-design') }}"
                                class="fliplink {{ request()->routeIs('office-design') ? 'active' : '' }}">
                                <div class="Link" data-after="Office Design">
                                    <span>
                                        Office Design
                                    </span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('bulkOrder') }}"
                                class="fliplink {{ request()->routeIs('bulkOrder') ? 'active' : '' }}">
                                <div class="Link" data-after="Bulk Order">
                                    <span>
                                        Bulk Order
                                    </span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="search-wrapper search-trigger">
                        <button type="button" class="close">
                            <svg viewBox="0 0 16 15" fill="none">
                                <path
                                    d="M15.9009 7.648C15.9268 7.58672 15.9445 7.52219 15.9603 7.45681C15.9642 7.44119 15.9712 7.42637 15.9745 7.41034C15.991 7.32809 16 7.24381 16 7.15787C16 7.07194 15.991 6.98766 15.9745 6.90541C15.9712 6.88937 15.9642 6.87456 15.9603 6.85894C15.9445 6.79356 15.9268 6.729 15.9009 6.66775C15.8964 6.65747 15.8898 6.64844 15.8851 6.63816C15.823 6.50122 15.7393 6.37419 15.6299 6.26481L9.73519 0.370063C9.49669 0.131563 9.17928 0 8.84209 0C8.50491 0 8.1875 0.131563 7.949 0.370063C7.71053 0.608562 7.57894 0.925969 7.57894 1.26316C7.57894 1.60034 7.71053 1.91775 7.949 2.15625L11.6875 5.89472H1.26316C0.566625 5.89472 0 6.46134 0 7.15787C0 7.85441 0.566625 8.42103 1.26316 8.42103H11.6875L7.949 12.1595C7.71053 12.398 7.57894 12.7154 7.57894 13.0526C7.57894 13.3898 7.71053 13.7072 7.949 13.9457C8.18791 14.1842 8.50491 14.3158 8.84209 14.3158C9.17928 14.3158 9.49628 14.1842 9.73519 13.9457L15.6299 8.05097C15.7393 7.94159 15.823 7.81453 15.8851 7.67762C15.8898 7.66734 15.8964 7.65828 15.9009 7.648Z" />
                            </svg>
                        </button>
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Search..">
                            <button type="button" class="search-icon bfr" onclick="searchToggle(this, event);">
                                <svg viewBox="0 0 20 20">
                                    <path class="st0"
                                        d="M9.2,15.8c3.7,0,6.7-3,6.7-6.7s-3-6.7-6.7-6.7-6.7,3-6.7,6.7,3,6.7,6.7,6.7Z" />
                                    <path class="st0" d="M17.5,17.5l-3.6-3.6" />
                                </svg>
                            </button>
                            <button type="submit" class="search-icon sbmt">
                                <svg viewBox="0 0 20 20">
                                    <path class="st0"
                                        d="M9.2,15.8c3.7,0,6.7-3,6.7-6.7s-3-6.7-6.7-6.7-6.7,3-6.7,6.7,3,6.7,6.7,6.7Z" />
                                    <path class="st0" d="M17.5,17.5l-3.6-3.6" />
                                </svg>
                            </button>
                        </div>
                        <div class="search-results-wrapper">
                        </div>
                    </div>
                    <div class="btnWrap">
                        <div class="item">
                            <a href="javascript:void(0);" class="enquire" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('frontend/images/h1.png') }}" alt="btn" width="32"
                                    height="32">
                            </a>
                            <div class="dropdown-menu">
                                @if ($siteSettings->whatsapp_number != null)
                                    <a class="chat"
                                        href="https://wa.me/{{ $siteSettings->formatted_whatsapp_number }}"
                                        target="_blank">Chat on Whatsapp</a>
                                @endif
                                @if ($siteSettings->phone_number != null)
                                    <a class="txt"
                                        href="tel:{{ $siteSettings->formatted_phone_number }}">{{ $siteSettings->phone_number }}</a>
                                @endif
                            </div>
                        </div>
                        <div class="item">
                            <a href="{{ Auth::check() ? route('my-account') : route('login') }}" class="account">
                                <img src="{{ asset('frontend/images/h2.png') }}" alt="btn" width="32"
                                    height="32">
                            </a>
                        </div>
                        <div class="item">
                            <a href="#!" class="cart" data-bs-toggle="modal" data-bs-target="#cartModal">
                                <img src="{{ asset('frontend/images/h3.png') }}" alt="btn" width="32"
                                    height="32">
                                <div class="count" id="cartCount">{{ $cartCount }}</div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <div id="MainMenu">
                <div class="MenuBtn">
                    <button type="button" id="menuClose" class="btn ">
                        <span></span>
                        <span></span>
                    </button>
                </div>
                <div class="mainItm">
                    <div class="menuFlx">
                        <div class="accordion" id="AccordMenu1">
                            @foreach ($headerCategories as $headerCategory)
                                <div
                                    class="accordion-item {{ $headerCategory->children->isNotEmpty() ? 'dropdown' : '' }}">
                                    <div class="accordion-header" id="hd{{ $loop->iteration }}">
                                        <a href="{{ route('category-detail', ['slug' => $headerCategory->slug]) }}"
                                            class="dskLnk ">
                                            <div class="Link">
                                                <span>
                                                    {{ $headerCategory->name }}
                                                </span>
                                            </div>
                                        </a>
                                        @if ($headerCategory->children->isNotEmpty())
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#cd{{ $loop->iteration }}"
                                                aria-expanded="false" aria-controls="cd{{ $loop->iteration }}">
                                                <svg viewBox="0 0 12.834 7">
                                                    <path id="Down_Arrow_3_"
                                                        d="M26.417,47A.582.582,0,0,1,26,46.829L20.171,41A.583.583,0,1,1,21,40.171l5.421,5.421,5.421-5.421a.583.583,0,1,1,.825.825l-5.834,5.834A.582.582,0,0,1,26.417,47Z"
                                                        transform="translate(-20 -40)" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if ($headerCategory->children->isNotEmpty())
                                        <div id="cd{{ $loop->iteration }}" class="accordion-collapse collapse"
                                            aria-labelledby="hd{{ $loop->iteration }}"
                                            data-bs-parent="#AccordMenu{{ $loop->iteration }}">
                                            <div class="accordion-body">
                                                <ul>
                                                    @foreach ($headerCategory->children as $childCategory)
                                                        <li>
                                                            <a
                                                                href="{{ route('sub-category-detail', ['slug' => $headerCategory->slug, 'subcategory' => $childCategory->slug]) }}">{{ $childCategory->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            <div class="accordion-item">
                                <div class="accordion-header" id="hd4">
                                    <a href="{{ route('office-design') }}" class="dskLn ">
                                        <div class="Link">
                                            <span>
                                                Office Design
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header" id="hd5">
                                    <a href="{{ route('bulkOrder') }}" class="dskLn ">
                                        <div class="Link">
                                            <span>
                                                Bulk Order
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="bulkOrder">
        <div class="container">
            <div class="bbx">
                <div class="txt">Save big with Bulk Order • <a href="{{ route('bulkOrder') }}">Shop Now</a></div>
            </div>
        </div>
    </div>
</header>
