@extends('layouts.app')
<x-meta-tags :metaData="@$meta" />
@push('css')
@endpush
@section('content')
    <main id="pageWrapper" class="dashPage">
        <section id="OrderFailedSection">
            <div class="container">

                <div class="item">
                    <div class="orderStatusBx">
                        <div class="imgWrap">
                            <img src="{{ asset('frontend/images/order-failed.svg') }}" width="100" height="100"
                                loading="lazy" alt="order-failed">
                        </div>
                        <div class="cntWrap">
                            <div class="tle">Could not place your order</div>
                            <div class="txt">Something went wrong while processing your payment. Please check your
                                payment details or try again in a few minutes.</div>
                            <div class="btnWrap">
                                <div>
                                    <a href="#" class="baseBtn_2 hoveranim" data-bs-toggle="modal"
                                        data-bs-target="#cartModal" aria-label="Cart">
                                        <span>Go Back to Cart</span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="baseBtn_1 hoveranim" aria-label="Retry" data-bs-toggle="modal"
                                        data-bs-target="#cartModal">
                                        <span>Retry Payment</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
@push('js')
    <script></script>
@endpush
