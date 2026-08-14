<footer id="Footer">
    <div class="container">
        <div class="ftTop">
            <div class="ftAccordion accordion" id="FtAcco">
                <div class="ftCol">
                    <div class="itemWrap">
                        <a href="{{ route('home') }}" class="logoWrap" aria-label="Logo">
                            <img src="{{ $siteSettings->footer_logo_value ?? asset('frontend/images/fLogo.png') }}"
                                width="426" height="92"
                                alt="{{ $siteSettings->footer_logo_alt_text_value ?? 'logo' }}">
                        </a>
                        @if ($socialLinks->isNotEmpty())
                            <ul class="socialUl">
                                @foreach ($socialLinks as $socialLink)
                                    <li>
                                        <a href="{{ $socialLink->url }}" target="_blank">
                                            <img src="{{ $socialLink->icon_value }}" width="24" height="24"
                                                loading="lazy" alt="twitter">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                @if ($categories->isNotEmpty())
                    <div class="accordion-item ftCol">
                        <div class="fitBx">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FtAccoItem1" aria-expanded="true" aria-controls="FtAccoItem1">
                                    <span class="ftTle">All Category</span>
                                </button>
                            </div>
                            <div id="FtAccoItem1" class="accordion-collapse collapse show" aria-labelledby="FtAccoItem1"
                                data-bs-parent="#FtAcco">
                                <div class="accordion-body">
                                    <ul class="ftUl">
                                        @foreach ($categories as $category)
                                            <li>
                                                <a href="{{ route('category-detail', ['slug' => $category->slug]) }}"
                                                    class="{{ request()->routeIs('category-detail') && request()->slug == $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($smartProducts->isNotEmpty())
                    <div class="accordion-item ftCol">
                        <div class="fitBx">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#FtAccoItem2" aria-expanded="true" aria-controls="FtAccoItem2">
                                    <span class="ftTle">Smart Products</span>
                                </button>
                            </div>
                            <div id="FtAccoItem2" class="accordion-collapse collapse show" aria-labelledby="FtAccoItem2"
                                data-bs-parent="#FtAcco">
                                <div class="accordion-body">
                                    <ul class="ftUl">
                                        @foreach ($smartProducts as $smartProduct)
                                            <li><a href="{{ route('custom-page', ['slug' => $smartProduct->slug]) }}"
                                                    class="{{ request()->routeIs('custom-page') && request()->slug == $smartProduct->slug ? 'active' : '' }}">{{ $smartProduct->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="accordion-item ftCol">
                    <div class="fitBx">
                        <div class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#FtAccoItem3" aria-expanded="true" aria-controls="FtAccoItem3">
                                <span class="ftTle">Company</span>
                            </button>
                        </div>
                        <div id="FtAccoItem3" class="accordion-collapse collapse show" aria-labelledby="FtAccoItem3"
                            data-bs-parent="#FtAcco">
                            <div class="accordion-body">
                                <ul class="ftUl">
                                    <li><a href="{{ route('product-listing') }}"
                                            class="{{ request()->routeIs('product-listing') ? 'active' : '' }}">Products</a>
                                    </li>
                                    <li><a href="{{ route('blog') }}"
                                            class="{{ request()->routeIs('blog') ? 'active' : '' }}">Blogs</a></li>
                                    <li><a href="{{ route('refund') }}"
                                            class="{{ request()->routeIs('refund') ? 'active' : '' }}">Returns &
                                            Refunds</a></li>
                                    <li><a href="{{ route('termsandcondition') }}"
                                            class="{{ request()->routeIs('termsandcondition') ? 'active' : '' }}">Terms
                                            &
                                            Conditions</a></li>
                                    <li><a href="{{ route('privacy') }}"
                                            class="{{ request()->routeIs('privacy') ? 'active' : '' }}">Privacy
                                            Poilcy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ftMid">
            <div class="lftB">
                <div class="ftTle">Contact us</div>
                <div class="flWp">
                    <div class="lt">
                        @if ($siteSettings->phone_number)
                            <a
                                href="tel:{{ $siteSettings->formatted_phone_number }}">{{ $siteSettings->phone_number }}</a>
                        @endif
                        <a href="mailto:{{ $siteSettings->email }}">{{ $siteSettings->email }}</a>
                        <div class="txt">{{ $siteSettings->working_hours }}</div>
                    </div>
                    <div class="rt">
                        <div class="txt">{!! $siteSettings->address !!}</div>
                    </div>
                </div>
            </div>
            <div class="ritB">
                <div class="newsLtr">
                    <div class="label">Stay up to date with our latest news</div>
                    <form action="{{ route('newsletter-submit') }}" method="post" id="newsletter-form">
                        @csrf
                        @honeypot
                        <div class="form-group jqv-group">
                            <input type="email" name="email" id="newsletterEmail" class="form-control"
                                placeholder="Enter your email address">
                            <div class="help-block error-block danger d-none"></div>
                            <div class="help-block success-block text-success d-none"></div>
                            <button type="submit" class="submit hoveranim">
                                <span>
                                    <img src="{{ asset('frontend/images/rit.png') }}" alt="icon" width="22"
                                        height="20">
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="ftBtm">
            <div class="lftSd">
                <div class="txt">© {{ date('Y') }} DESQOO - All Right reserved!</div>
            </div>
            <div class="rgtSd">
                {{-- <div class="txt">Developed By:
                    <a href="https://www.intersmartsolution.com/" target="_blank">
                        <img src="{{ asset('frontend/images/intersmart.svg') }}" alt="intersmart">
                    </a>
                </div> --}}
            </div>
        </div>

    </div>
</footer>

<script>
    window.MAPBOX_TOKEN = @json(config('services.mapbox.token'));
</script>


<ul id="fixedRgt">
    @if ($siteSettings->whatsapp_number)
        <li class="whatsapp">
            <a href="https://wa.me/{{ $siteSettings->formatted_whatsapp_number }}" target="_blank"
                aria-label="whatsapp">
                <img src="{{ asset('frontend/images/whatsapp.png') }}" width="24" height="24"
                    alt="whatsapp">
            </a>
        </li>
    @endif
</ul>
