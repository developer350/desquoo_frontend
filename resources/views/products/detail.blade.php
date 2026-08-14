@extends('layouts.app')
<x-meta-tags :metaData="[
    'metaTitle' => $product->meta_title ?? $product->name,
    'metaKeywords' => $product->meta_keywords ?? '',
    'metaDescription' => $product->meta_description ?? '',
    'otherMetaTags' => $product->other_meta_tags ?? '',
]" />
@section('image', $product->image_value)
@push('css')
    <!-- SWIPER -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    {{-- MAGIC_ZOOM --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/magiczoom.min.css') }}">
    {{-- STAR_RATING --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/star-rating-svg.css') }}">
    <!-- FANCYBOX -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.6/jquery.fancybox.min.css" />

    <style>
        model-viewer {
            width: 100%;
            height: 100%;
        }
    </style>
@endpush
@section('content')
    <main id="pageWrapper" class="productPage proDtail">

        <section id="ProductDetailSection">
            <div class="container">
                <div class="mFlx">
                    <div class="lftSd" id="imageSection">
                        @include('products.partials.detail.image')
                    </div>
                    <div class="rgtSd">
                        <div class="mCntWrap">
                            @if ($product->bulkOrders->count() > 0)
                                <div class="bulkOrderBtnWrap">
                                    <div class="bulkOrderBtn">
                                        <span>Enable Bulk Order</span>
                                        <span>
                                            <label class="toggleSwitch">
                                                <input name="bulkOrderCheckbox" type="checkbox">
                                                <div></div>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="discWrap">
                                <div class="tleFlx">
                                    <div class="tle">{{ $product->name }}</div>
                                    @if ($product->reviews_avg_rating != null)
                                        <div class="rewiewCountBx">
                                            {{ number_format($product->reviews_avg_rating, 1) }}
                                            <span class="icon">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6.16021 1.299C6.55421 0.689549 7.44579 0.68955 7.83979 1.299L9.52704 3.90887C9.66198 4.11759 9.8698 4.26858 10.11 4.33241L13.1135 5.13059C13.8149 5.31698 14.0904 6.16492 13.6325 6.72796L11.6718 9.13913C11.515 9.33196 11.4356 9.57627 11.4491 9.82444L11.6182 12.9276C11.6576 13.6523 10.9363 14.1763 10.2593 13.9148L7.36029 12.7952C7.12844 12.7056 6.87156 12.7056 6.63971 12.7952L3.74065 13.9148C3.06367 14.1763 2.34238 13.6523 2.38185 12.9276L2.55087 9.82444C2.56439 9.57627 2.48501 9.33196 2.32821 9.13913L0.36746 6.72796C-0.0904069 6.16492 0.185105 5.31698 0.886478 5.13059L3.89 4.33241C4.1302 4.26858 4.33802 4.11759 4.47296 3.90887L6.16021 1.299Z"
                                                        fill="#F7D168" />
                                                </svg>
                                            </span>
                                            <span>({{ $product->reviews_count }})</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="priceDsk">
                                    <div class="txt1 productPrice">₹{{ $product->firstVariant->last_price }}</div>
                                    <div class="txt">
                                        <span>Inclusive of all taxes</span>
                                        {{-- <span>• No Cost EMI starts at <span class="primary">₹1,361</span>
                                            <a href="#" class="infotip" data-bs-toggle="tooltip"
                                                data-bs-title="Some information about the product">
                                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M7.9987 11.1668V8.50016M7.9987 5.8335H8.00536M14.6654 8.50016C14.6654 12.1821 11.6806 15.1668 7.9987 15.1668C4.3168 15.1668 1.33203 12.1821 1.33203 8.50016C1.33203 4.81826 4.3168 1.8335 7.9987 1.8335C11.6806 1.8335 14.6654 4.81826 14.6654 8.50016Z"
                                                        stroke="#202020" stroke-width="1.33333" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </span> --}}
                                    </div>
                                </div>
                                <div class="priceMob">
                                    <div class="flxWrp">
                                        <div class="txt1 productPrice">₹{{ $product->firstVariant->last_price }}</div>
                                        {{-- <div class="txt">No Cost EMI starts at <span class="primary">₹1,361</span>
                                            <a href="#" class="infotip" data-bs-toggle="tooltip"
                                                data-bs-title="Some information about the product">
                                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M7.9987 11.1668V8.50016M7.9987 5.8335H8.00536M14.6654 8.50016C14.6654 12.1821 11.6806 15.1668 7.9987 15.1668C4.3168 15.1668 1.33203 12.1821 1.33203 8.50016C1.33203 4.81826 4.3168 1.8335 7.9987 1.8335C11.6806 1.8335 14.6654 4.81826 14.6654 8.50016Z"
                                                        stroke="#202020" stroke-width="1.33333" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div> --}}
                                    </div>
                                    <div class="txt3">Inclusive of all taxes</div>
                                </div>
                                <div class="txt" id="short_description">{{ $product->firstVariant?->short_description ?? $product->short_description }}</div>
                            </div>
                            <hr />
                            <div id="attributes">
                                @include('products.partials.detail.attributes')
                            </div>
                            <div class="item">
                                <div class="placeOrderWrap">
                                    @if ($product->bulkOrders->count() > 0)
                                        <div class="orderBtnFlx" id="BulkOrderWrap" style="display: none">
                                            <div class="item qtyBtnWrap">
                                                <div class="qtyBtn quantity buttons_added">
                                                    <input type="button" value="-" class="minus">
                                                    <input type="number" step="1" min="1" name="quantity"
                                                        readonly value="25" title="Qty" class="input-text qty"
                                                        size="4" pattern="" inputmode="numeric">
                                                    <input type="button" value="+" class="plus">
                                                </div>
                                            </div>
                                            <div class="item orderBtnWrap">
                                                <a href="javascript:void(0)"
                                                    class="baseBtn_1 {{ $product->is_manage_stock && $product->firstVariant->stock == 0 ? 'outStockBtn' : '' }} hoveranim"
                                                    aria-label="Add To Cart" id="bulkOrderCart">
                                                    @if ($product->is_manage_stock && $product->firstVariant->stock == 0)
                                                        <span>Out of Stock</span>
                                                    @else
                                                        <span>Add To Cart</span>
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="item">
                                                <div class="table-responsive">
                                                    <table class="bulkOrderTable">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Quantity</th>
                                                                @foreach ($product->bulkOrders as $bulkOrder)
                                                                    <td>{{ $bulkOrder->title }}</td>
                                                                @endforeach
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Discount</th>
                                                                @foreach ($product->bulkOrders as $bulkOrder)
                                                                    <td>{{ number_format($bulkOrder->discount_percentage, 0) }}%
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div id="actions">
                                        @include('products.partials.detail.actions')
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="deliveryInputBx">
                                    <div class="tle">Delivery</div>
                                    <form action="{{ route('check-pincode') }}" method="POST" id="checkPincode">
                                        @csrf
                                        <div class="deliveryInput jqv-group">
                                            <input name="pincode" id="pincode" type="number"
                                                placeholder="Enter Pincode" class="form-control" minlength="3"
                                                maxlength="10" required data-msg-required="Please enter a pincode">
                                            <button type="submit" class="btn hoveranim">
                                                <span>Check</span>
                                            </button>
                                            <div class="help-block danger"></div>
                                        </div>
                                    </form>
                                    <div class="deliveryTxt">Enter PIN code to check delivery time &
                                        Availability</div>
                                    <div class="deliveryTxt success d-none"></div>
                                    <div class="deliveryTxt error d-none">Sorry, we do not ship to your pincode</div>
                                </div>
                            </div>
                            @if ($product->addons->isNotEmpty())
                                <hr />
                                <div class="item">
                                    <div class="addOnWrapBx">
                                        <div class="tleFlx">
                                            <div class="tle">Add ons</div>
                                            <div class="slideNavBtn">
                                                <div class="prev navBtn" aria-label="navigate to previous">
                                                    <img src="{{ asset('frontend/images/icon-slide-nav.svg') }}"
                                                        alt="nav" width="40" height="40" loading="lazy">
                                                </div>
                                                <div class="next navBtn" aria-label="navigate to next">
                                                    <img src="{{ asset('frontend/images/icon-slide-nav.svg') }}"
                                                        alt="nav" width="40" height="40" loading="lazy">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper addOnSlide">
                                            <div class="swiper-wrapper">
                                                @foreach ($product->addons as $addon)
                                                    <div class="swiper-slide">
                                                        <div class="addOnBx">
                                                            <div class="imgWrap">
                                                                <img src="{{ $addon->image_value }}" width="208"
                                                                    height="153" loading="lazy"
                                                                    alt="{{ $addon->image_alt_text }}">
                                                            </div>
                                                            <div class="cntWrap">
                                                                <div class="tle">{{ $addon->name }}</div>
                                                                <div class="txt">
                                                                    <span>{{ $addon->category->name }}</span>
                                                                </div>
                                                                <br />
                                                                <div class="txt price">
                                                                    ₹{{ $addon->firstVariant->last_price }}
                                                                </div>
                                                                <div class="txt primary">
                                                                    {{-- @if (is_added($addon->firstVariant->id))
                                                                        <a href="javascript:void(0)"
                                                                            class="outStockBtn">Added</a>
                                                                    @else --}}
                                                                    <a href="javascript:void(0)"
                                                                        data-product-id="{{ $addon->id }}"
                                                                        data-type="{{ $addon->type }}"
                                                                        data-variant-id="{{ $addon->firstVariant->id }}"
                                                                        class="addAddonToCart">Add to Cart</a>
                                                                    {{-- @endif --}}
                                                                    •
                                                                    +₹{{ $addon->firstVariant->last_price }}
                                                                </div>
                                                            </div>
                                                            <div class="addCartBtn">+</div>
                                                        </div>
                                                        <div class="swiper-lazy-preloader"></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($product->productFeatures->isNotEmpty())
                                <hr />
                                <div class="item">
                                    <div class="specWrapBx">
                                        <div class="dFlx">
                                            @foreach ($product->productFeatures as $feature)
                                                <div class="item">
                                                    <div class="specBx">
                                                        <div class="imgWrap">
                                                            <img src="{{ $feature->icon_value }}" width="24"
                                                                height="24" loading="lazy"
                                                                alt="{{ $feature->icon_alt_text_value }}">
                                                        </div>
                                                        <div class="tle">{{ $feature->title }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="item">
                                <div class="reviewWrap">
                                    <div class="tleFlx">
                                        <div class="tle">Ratings & Reviews</div>
                                        @if ($product->can_review)
                                            <div class="btnWrap">
                                                <button type="button" class="rateProBtn hoveranim"
                                                    data-bs-toggle="modal" data-bs-target="#ReviewModal">
                                                    <span>Rate Product</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="reviewInfoBx">
                                        <div class="lftSd">
                                            <div class="rwTle">{{ number_format($product->reviews_avg_rating, 1) ?? 0 }}
                                            </div>
                                            <div class="starRating reviewInfoStarrating"
                                                data-value="{{ number_format($product->reviews_avg_rating, 1) ?? 0 }}">
                                            </div>
                                            <div class="rwTxt">{{ $product->reviews_count ?? 0 }} Reviews</div>
                                        </div>
                                        <div class="rgtSd">
                                            <div class="item">
                                                <div class="txt text-start">{{ $product->star_percentages_cached[5] }}%
                                                </div>
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="{{ $product->star_percentages_cached[5] }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar"
                                                        style="width: {{ $product->star_percentages_cached[5] }}%"></div>
                                                </div>
                                                <div class="txt text-end">5 ★</div>
                                            </div>
                                            <div class="item">
                                                <div class="txt text-start">{{ $product->star_percentages_cached[4] }}%
                                                </div>
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="{{ $product->star_percentages_cached[4] }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar"
                                                        style="width: {{ $product->star_percentages_cached[4] }}%"></div>
                                                </div>
                                                <div class="txt text-end">4 ★</div>
                                            </div>
                                            <div class="item">
                                                <div class="txt text-start">{{ $product->star_percentages_cached[3] }}%
                                                </div>
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="{{ $product->star_percentages_cached[3] }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar"
                                                        style="width: {{ $product->star_percentages_cached[3] }}%"></div>
                                                </div>
                                                <div class="txt text-end">3 ★</div>
                                            </div>
                                            <div class="item">
                                                <div class="txt text-start">{{ $product->star_percentages_cached[2] }}%
                                                </div>
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="{{ $product->star_percentages_cached[2] }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar"
                                                        style="width: {{ $product->star_percentages_cached[2] }}%"></div>
                                                </div>
                                                <div class="txt text-end">2 ★</div>
                                            </div>
                                            <div class="item">
                                                <div class="txt text-start">{{ $product->star_percentages_cached[1] }}%
                                                </div>
                                                <div class="progress" role="progressbar" aria-label="Basic example"
                                                    aria-valuenow="{{ $product->star_percentages_cached[1] }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar"
                                                        style="width: {{ $product->star_percentages_cached[1] }}%"></div>
                                                </div>
                                                <div class="txt text-end">1 ★</div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($product->can_review)
                                        <div class="btnWrap mobile">
                                            <button type="button" class="rateProBtn hoveranim" data-bs-toggle="modal"
                                                data-bs-target="#ReviewModal">
                                                <span>Rate Product</span>
                                            </button>
                                        </div>
                                    @endif
                                    <div class="reviewBxWrap">
                                        @foreach ($product->reviews as $review)
                                            <div class="item">
                                                <div class="reviewBx">
                                                    <div class="rwTleFlx">
                                                        <div class="rwImg">
                                                            @php
                                                                $initials = getInitials(
                                                                    $review->display_name ?? 'User',
                                                                );
                                                                $colors = [
                                                                    'primary',
                                                                    'danger',
                                                                    'success',
                                                                    'warning',
                                                                    'info',
                                                                    'secondary',
                                                                    'dark',
                                                                ];
                                                                $colorIndex = ord($initials[0]) % count($colors);
                                                                $bgColor = $colors[$colorIndex];
                                                            @endphp

                                                            <div
                                                                class="rounded-circle bg-{{ $bgColor }} text-white d-flex align-items-center justify-content-center">
                                                                {{ $initials }}
                                                            </div>
                                                        </div>
                                                        <div class="rwCnt">
                                                            <div class="rwTle">{{ $review->display_name }}</div>
                                                            <div class="rwTxt">{{ $review->profession }}</div>
                                                            <div class="starRating reviewBxStarRating"
                                                                data-value="{{ $review->rating }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rwCntWrap">
                                                        <div class="add-read-more">{{ $review->comment }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($product->reviews->isNotEmpty())
                                            <div class="item">
                                                <a href="#!" class="seeAllReviewBtn">
                                                    <span>See all reviews</span>
                                                    <span class="icon">
                                                        <svg width="22" height="22" viewBox="0 0 22 22"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M3.61719 11.2106H17.933M17.933 11.2106C17.933 11.2106 13.8751 8.54189 13.0506 6.32812M17.933 11.2106C17.933 11.2106 14.3457 13.7182 13.0506 16.093"
                                                                stroke="#202020" stroke-width="1.80832"
                                                                stroke-linecap="square" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($siteSettings->whatsapp_number != null || $siteSettings->phone_number != null)
                                <div class="item">
                                    <a href="#!" aria-label="expert" class="expertBx" role="button"
                                        data-bs-toggle="dropdown">
                                        <div class="bxImg">
                                            <img src="{{ asset('frontend/images/expert-1.jpg') }}" alt="user-1"
                                                width="24" height="24" loading="lazy">
                                        </div>
                                        <div class="bxCnt">
                                            <div class="bxTle">Need shopping help?</div>
                                            <div class="bxTxt">Ask an expert</div>
                                        </div>
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="ProductFaqSection">
            @include('products.partials.detail.description')
        </section>

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

        @if ($product->highlightReviews->isNotEmpty())
            <section id="ExperienceSection">
                <div class="container">
                    <div class="tleFlx">
                        <div class="item">
                            <div class="tle">Because your experience matters too</div>
                        </div>
                        <div class="item">
                            <div class="rewiewCountBx">
                                {{ number_format($product->reviews_avg_rating, 1) ?? 0 }}
                                <span class="icon">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.16021 1.299C6.55421 0.689549 7.44579 0.68955 7.83979 1.299L9.52704 3.90887C9.66198 4.11759 9.8698 4.26858 10.11 4.33241L13.1135 5.13059C13.8149 5.31698 14.0904 6.16492 13.6325 6.72796L11.6718 9.13913C11.515 9.33196 11.4356 9.57627 11.4491 9.82444L11.6182 12.9276C11.6576 13.6523 10.9363 14.1763 10.2593 13.9148L7.36029 12.7952C7.12844 12.7056 6.87156 12.7056 6.63971 12.7952L3.74065 13.9148C3.06367 14.1763 2.34238 13.6523 2.38185 12.9276L2.55087 9.82444C2.56439 9.57627 2.48501 9.33196 2.32821 9.13913L0.36746 6.72796C-0.0904069 6.16492 0.185105 5.31698 0.886478 5.13059L3.89 4.33241C4.1302 4.26858 4.33802 4.11759 4.47296 3.90887L6.16021 1.299Z"
                                            fill="#F7D168" />
                                    </svg>
                                </span>
                                <span>({{ $product->reviews_count }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="experienceSlideWrap">
                        <div class="slideNavBtn">
                            <div class="prev navBtn" aria-label="navigate to previous">
                                <img src="{{ asset('frontend/images/icon-slide-nav.svg') }}" alt="nav"
                                    width="40" height="40" loading="lazy">
                            </div>
                            <div class="next navBtn" aria-label="navigate to next">
                                <img src="{{ asset('frontend/images/icon-slide-nav.svg') }}" alt="nav"
                                    width="40" height="40" loading="lazy">
                            </div>
                        </div>
                        <div class="swiper experienceSlide">
                            <div class="swiper-wrapper">
                                @foreach ($product->highlightReviews as $highlightReview)
                                    <div class="swiper-slide">
                                        <div class="experienceBx">
                                            @if ($highlightReview->review_image_value != null)
                                                <img src="{{ $highlightReview->review_image_value }}" width="440"
                                                    height="476" loading="lazy"
                                                    alt="experience-{{ $highlightReview->id }}">
                                            @endif
                                            <div class="cntOuter">
                                                <div class="cntOuterWrap">
                                                    <div class="authorInfo">
                                                        <div class="imgB">
                                                            @php
                                                                $initials = getInitials(
                                                                    $review->display_name ?? 'User',
                                                                );
                                                                $colors = [
                                                                    'primary',
                                                                    'danger',
                                                                    'success',
                                                                    'warning',
                                                                    'info',
                                                                    'secondary',
                                                                    'dark',
                                                                ];
                                                                $colorIndex = ord($initials[0]) % count($colors);
                                                                $bgColor = $colors[$colorIndex];
                                                            @endphp

                                                            <div class="rounded-circle bg-{{ $bgColor }} text-white d-flex align-items-center justify-content-center fw-bold"
                                                                style="width: 60px; height: 60px; font-size: 10px;">
                                                                {{ $initials }}
                                                            </div>
                                                        </div>
                                                        <div class="tle">{{ $highlightReview->display_name }}</div>
                                                        <div class="dTxt">{{ $highlightReview->profession }}</div>
                                                    </div>
                                                    <div class="cntWrap">
                                                        <p>{{ $highlightReview->comment }}</p>
                                                    </div>
                                                    <div class="rewiewCountBx">
                                                        {{ $highlightReview->rating }}
                                                        <span class="icon">
                                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M6.16021 1.299C6.55421 0.689549 7.44579 0.68955 7.83979 1.299L9.52704 3.90887C9.66198 4.11759 9.8698 4.26858 10.11 4.33241L13.1135 5.13059C13.8149 5.31698 14.0904 6.16492 13.6325 6.72796L11.6718 9.13913C11.515 9.33196 11.4356 9.57627 11.4491 9.82444L11.6182 12.9276C11.6576 13.6523 10.9363 14.1763 10.2593 13.9148L7.36029 12.7952C7.12844 12.7056 6.87156 12.7056 6.63971 12.7952L3.74065 13.9148C3.06367 14.1763 2.34238 13.6523 2.38185 12.9276L2.55087 9.82444C2.56439 9.57627 2.48501 9.33196 2.32821 9.13913L0.36746 6.72796C-0.0904069 6.16492 0.185105 5.31698 0.886478 5.13059L3.89 4.33241C4.1302 4.26858 4.33802 4.11759 4.47296 3.90887L6.16021 1.299Z"
                                                                    fill="#F7D168" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <div class="placeOrderWrap mobile">
            <div class="orderBtnFlx">
                <div class="item priceWrap">
                    <div class="tle">{{ $product->name }}</div>
                    <div class="tle" id="priceMobile">₹{{ $product->firstVariant->last_price }}</div>
                </div>
                <div class="item qtyBtnWrap">
                    <div class="qtyBtn quantity buttons_added">
                        <input type="button" value="-" class="minus">
                        <input type="number" step="1" min="1" max="999" name="quantity" readonly
                            value="25" title="Qty" class="input-text qty" size="4" pattern=""
                            inputmode="numeric">
                        <input type="button" value="+" class="plus">
                    </div>
                </div>
                <div class="item orderBtnWrap">
                    <a href="javascript:void(0);" id="addToCartMobile"
                        class="baseBtn_1 hoveranim {{ $product->is_manage_stock && $product->firstVariant->stock == 0 ? 'outStockBtn' : '' }}"
                        aria-label="navigate to home">
                        @if ($product->is_manage_stock && $product->firstVariant->stock == 0)
                            <span>Out of Stock</span>
                        @else
                            <span>Add To Cart</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>


        <!-- NOTIFY_MODAL -->
        <div class="modal fade notifyModal" id="NotifyModal" tabindex="-1" aria-labelledby="NotifyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title" id="NotifyModalLabel">Notify when available</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="notifyForm">
                            <div class="txt">It’s worth the wait. Leave your details, and we'll be in touch as soon as
                                it's available again!</div>

                            @include('partials.notify-form')

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="notifyForm" class="baseBtn_1 hoveranim" aria-label="submit">
                            <span>Notify Me</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->check() && $product->can_review)
            <!-- REVIEW_MODAL -->
            <div class="modal fade notifyModal" id="ReviewModal" tabindex="-1" aria-labelledby="ReviewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title" id="ReviewModalLabel">Rate Product</div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="reviewForm">

                                @include('partials.review-form')

                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btnWrp">
                                <button type="submit" form="reviewForm" class="baseBtn_1 hoveranim"
                                    aria-label="submit">
                                    <span>Add Rating</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- 3D_MODAL -->
        @include('products.partials.detail.modals.three-d-modal')

        <!-- SPACE_MODAL -->
        @include('products.partials.detail.modals.space-modal')
    </main>
@endsection
@push('js')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
    {{-- STAR_RATING --}}
    <script src="{{ asset('frontend/js/jquery.star-rating-svg.min.js') }}" defer></script>
    {{-- MAGIC_ZOOM --}}
    <script src="{{ asset('frontend/js/magiczoom.min.js') }}" defer></script>
    <!-- SWIPER -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <!-- FANCYBOX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.6/jquery.fancybox.min.js"></script>
    <script>
        (function($) {
            'use strict';

            // Helper function to get decimal places
            const getDecimals = (value) => {
                const match = String(value).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
                return match ? Math.max(0, (match[1]?.length || 0) - (match[2] ? +match[2] : 0)) : 0;
            };

            // Add plus/minus buttons to quantity inputs
            const initQuantityButtons = () => {
                $('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').each(function() {
                    const $container = $(this);
                    $container.addClass('buttons_added');
                    $container.children().first().before(
                        '<input type="button" value="-" class="minus" />');
                    $container.children().last().after(
                        '<input type="button" value="+" class="plus" />');
                });
            };

            // Handle quantity changes
            const handleQuantityChange = (e) => {
                const $button = $(e.currentTarget);
                const $quantity = $button.closest('.quantity').find('.qty');
                const isPlus = $button.hasClass('plus');

                // Get current values
                let current = parseFloat($quantity.val()) || 0;
                const max = parseFloat($quantity.attr('max')) || Infinity;
                const min = parseFloat($quantity.attr('min')) || 0;
                const step = parseFloat($quantity.attr('step')) || 1;
                const decimals = getDecimals(step);

                // Calculate new value
                let newValue;
                if (isPlus) {
                    newValue = current >= max ? max : (current + step).toFixed(decimals);
                } else {
                    newValue = current <= min ? min : Math.max(0, current - step).toFixed(decimals);
                }

                // Update and trigger change
                $quantity.val(newValue).trigger('change');
            };

            // Initialize on document ready
            $(document).ready(initQuantityButtons);

            // Re-initialize on cart/checkout updates
            $(document).on('updated_wc_div', initQuantityButtons);

            // Handle button clicks
            $(document).on('click', '.plus, .minus', handleQuantityChange);

        })(jQuery);

        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl))

            // SWIPER
            var proThumpSlide = new Swiper(".proThumpSlide", {
                slidesPerView: 3,
                loop: false,
                spaceBetween: 10,
                watchSlidesProgress: true,
                lazy: {
                    loadPrevNext: true,
                },
                autoplay: false,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    576: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 15,
                    },
                },
            });

            initSlider();

            // BULK_ORDER_ONCHNAGE
            $(document).ready(function() {
                $("input[name='bulkOrderCheckbox']").on("change", function() {
                    if ($(this).is(":checked")) {
                        $("#BulkOrderWrap").show();
                        $(".MinimumOrderWrap").hide();
                    } else {
                        $("#BulkOrderWrap").hide();
                        $(".MinimumOrderWrap").show();
                    }
                });
            });

            // ADD_ON_SLIDE
            const addOnSlide = new Swiper('.addOnSlide', {
                loop: false,
                rewind: false,
                slidesPerView: 1,
                spaceBetween: 10,
                grid: {
                    rows: 2,
                },
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

                navigation: {
                    nextEl: '.navBtn.next',
                    prevEl: '.navBtn.prev',
                },

                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preventClicks: true,
                preventClicksPropagation: true,

                breakpoints: {
                    576: {
                        slidesPerView: 1,
                        spaceBetween: 10,
                        grid: {
                            rows: 1,
                        },
                    },
                },
            });

            // STAR_RATING
            $(".reviewInfoStarrating").each(function() {
                var initialRating = $(this).attr("data-value");
                $(this).starRating({
                    initialRating: initialRating,
                    emptyColor: "#f6f6f6",
                    activeColor: '#f7d168',
                    ratedColor: "#f00",
                    useGradient: false,
                    starSize: 25,
                    readOnly: true,
                    starShape: 'rounded',
                });
            });
            $(".reviewBxStarRating").each(function() {
                var initialRating = $(this).attr("data-value");
                $(this).starRating({
                    initialRating: initialRating,
                    emptyColor: "#e3e3e3",
                    activeColor: '#f7d168',
                    ratedColor: "#f00",
                    useGradient: false,
                    starSize: 15,
                    readOnly: true,
                    starShape: 'rounded',
                });
            });

            // READ_MORE
            function AddReadMore(limit = 400) {
                const readMoreTxt = " ...See more";
                const readLessTxt = " See less";

                $(".add-read-more").each(function() {
                    const $this = $(this);

                    // Skip if already processed
                    if ($this.data("has-readmore")) return;

                    const content = $this.text().trim();
                    if (content.length <= limit) return;

                    const visibleText = content.substring(0, limit);
                    const hiddenText = content.substring(limit);

                    $this.html(`
                        ${visibleText}
                        <span class="more-text" style="display:none;">${hiddenText}</span>
                        <span class="toggle-read">${readMoreTxt}</span>
                        `);

                    // Mark as initialized
                    $this.data("has-readmore", true);
                });

                // Delegate event (works for all)
                $(document).off("click", ".toggle-read").on("click", ".toggle-read", function() {
                    const $btn = $(this);
                    const $moreText = $btn.siblings(".more-text");

                    $moreText.toggle();
                    $btn.text($moreText.is(":visible") ? readLessTxt : readMoreTxt);
                });
            }

            // Run once (can be called again safely)
            AddReadMore();

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

            const experienceSlide = new Swiper('.experienceSlide', {
                loop: false,
                rewind: false,
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

                navigation: {
                    nextEl: '.navBtn.next',
                    prevEl: '.navBtn.prev',
                },

                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preventClicks: true,
                preventClicksPropagation: true,
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 15,
                    },
                    1551: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
            });

        });

        function initSlider() {
            var proMainSlide = new Swiper(".proMainSlide", {
                loop: false,
                rewind: false,
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

                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }

        $(document).on('change', '.attributes', function(e) {
            e.preventDefault();
            changeVariant();
        });

        function changeVariant() {
            var data = $('.attributes:checked').serializeArray();

            data.push({
                name: 'product_id',
                value: "{{ $product->id }}"
            });

            $.ajax({
                type: "get",
                url: "{{ route('get-product-variant') }}",
                data: data,
                beforeSend: function() {
                    $('#ProductDetailSection').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        $('#imageSection').html(response.imageHtml);
                        $('#ProductFaqSection').html(response.descriptionHtml);
                        $('#actions').html(response.actionsHtml);
                        $('#ProductDetailSection').css({
                            'opacity': '1',
                            'pointer-events': 'all'
                        });

                        let product = response.product;

                        if (product.firstVariant.short_description) {
                            $('#short_description').html(product.firstVariant.short_description);
                        } else {
                            $('#short_description').html(product.short_description);
                        }

                        $('.productPrice').html('₹' + product.firstVariant.last_price);
                        $('#priceMobile').html('₹' + product.firstVariant.last_price);

                        var is_added = $('input[name="is_added"]').val();
                        var isOutOfStock = $('input[name="out_of_stock"]').val();
                        //mobile bulk
                        // if (is_added) {
                        //     $('#addToCartMobile').addClass('outStockBtn').html('<span>Added</span>');
                        // } else {
                        if (isOutOfStock) {
                            $('#addToCartMobile').addClass('outStockBtn').html('<span>Out Of Stock</span>');
                        } else {
                            $('#addToCartMobile').removeClass('outStockBtn').html(
                                '<span>Add To Cart</span>');
                        }
                        // }

                        //desktop bulk
                        // if (is_added) {
                        //     $('#bulkOrderCart').addClass('outStockBtn').html('<span>Added</span>');
                        // } else {
                        if (isOutOfStock) {
                            $('#bulkOrderCart').addClass('outStockBtn').html('<span>Out Of Stock</span>');
                        } else {
                            $('#bulkOrderCart').removeClass('outStockBtn').html(
                                '<span>Add To Cart</span>');
                        }
                        // }

                        if ($("input[name='bulkOrderCheckbox']").is(":checked")) {
                            $("#BulkOrderWrap").show();
                            $(".MinimumOrderWrap").hide();
                        } else {
                            $("#BulkOrderWrap").hide();
                            $(".MinimumOrderWrap").show();
                        }

                        //desktop slider re initialize with magic zoom
                        MagicZoom.refresh();
                        initSlider();
                    } else {
                        $('#ProductDetailSection').css({
                            'opacity': '1',
                            'pointer-events': 'all'
                        });
                    }
                },
                error: function() {
                    $('#ProductDetailSection').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                }
            });
        }

        //addtocart
        $(document).on('click', '.addToCart', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            var variantId = $(this).data('variant-id');
            addToCart(productId, variantId);
        });

        function addToCart(productId, variantId, quantity = 1, isAddon = false) {
            $.ajax({
                type: "post",
                url: "{{ route('add-to-cart') }}",
                data: {
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#ProductDetailSection').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        if (isAddon) {
                            // $(addonButton).html('Added');
                            $('#ProductDetailSection').css({
                                'opacity': '1',
                                'pointer-events': 'all'
                            });
                        } else {
                            changeVariant();
                        }
                        // $('.addToCart').addClass('outStockBtn').html('<span>Added</span>');
                        // $('#addToCartMobile').addClass('outStockBtn').html('<span>Added</span>');
                        $('#cartModal .modal-content').html(response.cartHtml);
                        $('#cartModal').modal('show');
                        $('#cartCount').text(response.cartCount);
                    } else {
                        $('#ProductDetailSection').css({
                            'opacity': '1',
                            'pointer-events': 'all'
                        });
                        showToast('error', response.message);

                        if (isAddon) {
                            $(addonButton).html('Add To Cart');
                        }
                    }
                },
                error: function() {
                    $('#ProductDetailSection').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });

                    if (isAddon) {
                        $(addonButton).html('Add To Cart');
                    }
                },
            });
        }

        $(document).on('click', '.view3d', function() {
            $('#3dImage').attr('src', $(this).data('src'));
            $('#ProductViewModal').modal('show');
        });

        $(document).on('click', '#addFromModal', function() {
            $('#ProductViewModal').modal('hide');
            $('.addToCart').trigger('click');
        });

        $(document).on('click', '.viewAr', function(e) {
            $('#arImage').attr('src', $(this).data('src'));
            $('#SpaceModal').modal('show');
        });

        $(document).on('click', '#viewArFromModal', function() {
            $('#ProductViewModal').modal('hide');
            $('.viewAr').trigger('click');
        });

        $(document).on('click', '#addToCartMobile, #bulkOrderCart', function(e) {
            e.preventDefault();

            var product_id = $('input[name="product_id"]').val();
            var variant_id = $('input[name="variant_id"]').val();
            var quantity = $(this).parent().parent().find('.qty').val();

            addToCart(product_id, variant_id, quantity);
        });

        var addonButton;
        $(document).on('click', '.addAddonToCart', function() {
            var productId = $(this).data('product-id');
            var variantId = $(this).data('variant-id');

            addonButton = $(this);
            addToCart(productId, variantId, 1, true);
        });
    </script>

    @include('js.intel-phone-setup')
    @include('js.jquery-validate')
    <script>
        setupValidation('#visitForm');

        $('#ReviewModal').on('show.bs.modal', function(e) {
            setupValidation('#reviewForm', {}, {}, afterReviewSubmit);
        });

        function afterReviewSubmit(response) {
            if (response.status) {
                $('#ReviewModal').modal('hide');

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }

        $(document).on('click', '#notifyProduct', function() {
            $('#notify_product_id').val($(this).data('product-id'));
            $('#notify_product_variant_id').val($(this).data('variant-id'));
            $('#NotifyModal').modal('show');
        });

        $('#NotifyModal').on('show.bs.modal', function(e) {
            setupValidation('#notifyForm', {}, {}, afterNotifySubmit);
        });

        function afterNotifySubmit(response) {
            if (response.status) {
                $('#NotifyModal').modal('hide');
            }
        }

        $('#pincode').on('input', function() {
            $('.deliveryTxt.success').addClass('d-none');
            $('.deliveryTxt.error').addClass('d-none');
        });

        setupValidation('#checkPincode', {}, {}, afterPincodeCheck, false, null, null, false);

        function afterPincodeCheck(response) {
            if (response.data) {
                $('.deliveryTxt').addClass('d-none');
                let days = response.data.delivery_days;
                let today = new Date();
                let deliveryDate = new Date();
                deliveryDate.setDate(today.getDate() + days);
                let deliveryMessage = 'Delivery Expected by ' + deliveryDate.toLocaleDateString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                $('.deliveryTxt.success').removeClass('d-none').text(deliveryMessage);
            } else {
                $('.deliveryTxt').addClass('d-none');
                $('.deliveryTxt.error').removeClass('d-none');
            }
        }
    </script>
@endpush
