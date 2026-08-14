@extends('layouts.app')
@section('title', 'Checkout')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <main id="pageWrapper" class="checkoutPage">
        @if ($cartProducts->isNotEmpty())
            <section id="Checkout-shippingaddresss" class="checkout-address">
                <div class="container">
                    <div class="tleWrap">
                        <div class="mTle">Shipping Address</div>
                        <div class="flxLabl">
                            <input type="checkbox" id="bs2" name="same_bill_address" value="yes" checked>
                            <label for="bs2">Use the Same Address for Billing</label>
                        </div>
                    </div>
                    <div class="dFlx">
                        <div class="lSide">
                            <div class="addrsWrapper">
                                <div class="adrs-Item">
                                    <div class="shippingAddresses addressesMainDiv" id="shippingAddresses">
                                        @include('checkout.partials.shipping_addresses')
                                    </div>
                                    <div class="btnWraps">
                                        <button class="newAddress" data-bs-toggle="collapse"
                                            data-bs-target="#Shipping-Address"
                                            aria-expanded="{{ $addresses->isEmpty() ? 'true' : 'false' }}"
                                            aria-controls="Shipping-Address">ADD NEW ADDRESS
                                        </button>
                                    </div>
                                    <div id="Shipping-Address"
                                        class="accordion-collapse collapse {{ $addresses->isEmpty() ? 'show' : '' }}"
                                        aria-labelledby="headingOne" data-bs-parent="#UpdateAddress">
                                        <div class="accordion-body">
                                            <div class="tleWrap">
                                                <div class="mTle">Add New Address</div>
                                            </div>
                                            <form class="FormWrap" action="{{ route('addresses.store') }}"
                                                id="shippingAddressForm" method="POST">
                                                @csrf
                                                <div class="FormBx">
                                                    <div class="row">
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="name">Name*</label>
                                                                <input type="text" name="name" placeholder="Name"
                                                                    class="form-control" required data-rule-validName="true"
                                                                    minlength="2" maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="email">Email*</label>
                                                                <input type="email" name="email" placeholder="Email"
                                                                    class="form-control" required data-rule-emailOnly="true"
                                                                    maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="phone_number">Phone*</label>
                                                                <div class="mblCode">
                                                                    <input type="hidden" class="country_code"
                                                                        name="country_code">
                                                                    <input type="text" name="phone_number"
                                                                        placeholder="Your Phone" required
                                                                        data-rule-validPhoneNumber="true"
                                                                        data-msg-required="Please enter a valid phone number"
                                                                        class="form-control phone_number">
                                                                </div>
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="address_line_1">Address Line 1*</label>
                                                                <input type="text" name="address_line_1"
                                                                    placeholder="Address Line 1" class="form-control"
                                                                    required data-rule-noHtml="true" minlength="3"
                                                                    maxlength="500">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="address_line_2">Address Line 2</label>
                                                                <input type="text" name="address_line_2"
                                                                    placeholder="Address Line 2" class="form-control"
                                                                    data-rule-noHtml="true" minlength="3" maxlength="500">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="city">City*</label>
                                                                <select name="city" data-state-id="#shipping-state"
                                                                    class="form-control city" required
                                                                    data-placeholder="Select City">
                                                                    <option value=""></option>
                                                                </select>
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="state">State*</label>
                                                                <select name="state" id="shipping-state"
                                                                    class="form-control state" required
                                                                    data-placeholder="Select State">
                                                                    <option value=""></option>
                                                                    <option value="Kerala" selected>Kerala</option>
                                                                </select>
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="postal_code">Postal Code*</label>
                                                                <input type="number" name="postal_code" id="postal_code"
                                                                    placeholder="Postal Code" class="form-control"
                                                                    required data-rule-noHtml="true" minlength="3"
                                                                    maxlength="20">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="landmark">Landmark</label>
                                                                <input type="text" name="landmark" id="landmark"
                                                                    placeholder="Landmark" class="form-control"
                                                                    data-rule-noHtml="true" minlength="3"
                                                                    maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="landmark">Gst Number</label>
                                                                <input type="text" name="gstnumber" id="gstnumber"
                                                                    placeholder="Gst Number" class="form-control"
                                                                    data-rule-noHtml="true" minlength="3"
                                                                    maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btnWrap">
                                                    <div class="item">
                                                        <a href="javascript:void(0)"
                                                            class="cmnBtn cncl hoveranim shipping-address-cancel-btn"
                                                            aria-label="cancelBtn">
                                                            <span>CANCEL</span>
                                                        </a>
                                                    </div>
                                                    <div class="item">
                                                        <button type="submit" class="hoveranim save cmnBtn">
                                                            <span>SAVE</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="adrs-Item fadeAdrs menuitemshow">
                                    <div class="tleWrap">
                                        <div class="mTle">Billing Address</div>
                                    </div>
                                    <div class="item addressesMainDiv" id="billingAddresses">
                                        @include('checkout.partials.billing_addresses')
                                    </div>
                                    <div class="btnWraps">
                                        <button class="newAddress" data-bs-toggle="collapse"
                                            data-bs-target="#Billing-Address" aria-expanded="false"
                                            aria-controls="Billing-Address">
                                            <div class="txt">ADD NEW ADDRESS</div>
                                        </button>
                                    </div>
                                    <div id="Billing-Address" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#Billing-Address">
                                        <div class="accordion-body">
                                            <div class="tleWrap">
                                                <div class="mTle">Add New Address</div>
                                            </div>
                                            <form class="FormWrap" action="{{ route('addresses.store') }}"
                                                id="billingAddressForm" method="POST">
                                                @csrf
                                                <div class="FormBx">
                                                    <div class="row">
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="name">Name*</label>
                                                                <input type="text" name="name" placeholder="Name"
                                                                    class="form-control" required
                                                                    data-rule-validName="true" minlength="2"
                                                                    maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="email">Email*</label>
                                                                <input type="email" name="email" placeholder="Email"
                                                                    class="form-control" required
                                                                    data-rule-emailOnly="true" maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="phone_number">Phone*</label>
                                                                <div class="mblCode">
                                                                    <input type="hidden" class="country_code"
                                                                        name="country_code">
                                                                    <input type="text" name="phone_number"
                                                                        placeholder="Your Phone" required
                                                                        data-rule-validPhoneNumber="true"
                                                                        data-msg-required="Please enter a valid phone number"
                                                                        class="form-control phone_number">
                                                                </div>
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="address_line_1">Address Line 1*</label>
                                                                <input type="text" name="address_line_1"
                                                                    placeholder="Address Line 1" class="form-control"
                                                                    required data-rule-noHtml="true" minlength="3"
                                                                    maxlength="500">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="address_line_2">Address Line 2</label>
                                                                <input type="text" name="address_line_2"
                                                                    placeholder="Address Line 2" class="form-control"
                                                                    data-rule-noHtml="true" minlength="3"
                                                                    maxlength="500">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="city">City*</label>
                                                                <select name="city" data-state-id="#billing-state"
                                                                    class="form-control city" required
                                                                    data-placeholder="Select City">
                                                                    <option value=""></option>
                                                                </select>
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="state">State*</label>
                                                                <select name="state" id="billing-state"
                                                                    class="form-control state" required
                                                                    data-placeholder="Select State">
                                                                    <option value=""></option>
                                                                    <option value="Kerala" selected>Kerala</option>
                                                                </select>
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="postal_code">Postal Code*</label>
                                                                <input type="number" name="postal_code" id="postal_code"
                                                                    placeholder="Postal Code" class="form-control"
                                                                    required data-rule-noHtml="true" minlength="3"
                                                                    maxlength="20">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="landmark">Landmark</label>
                                                                <input type="text" name="landmark" id="landmark"
                                                                    placeholder="Landmark" class="form-control"
                                                                    data-rule-noHtml="true" minlength="3"
                                                                    maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-sm-6">
                                                            <div class="form-group jqv-group">
                                                                <label for="landmark">Gst Number</label>
                                                                <input type="text" name="gstnumber" id="gstnumber"
                                                                    placeholder="Gst Number" class="form-control"
                                                                    data-rule-noHtml="true" minlength="3"
                                                                    maxlength="191">
                                                                <div class="help-block danger"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="btnWrap">
                                                    <div class="item">
                                                        <a href="javascript:void(0)"
                                                            class="cmnBtn cncl hoveranim billing-address-cancel-btn"
                                                            aria-label="cancelBtn">
                                                            <span>CANCEL</span>
                                                        </a>
                                                    </div>
                                                    <div class="item">
                                                        <button type="submit" class="hoveranim save cmnBtn">
                                                            <span>SAVE</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="RSide">
                            <div class="accordion accordionOrder" id="accordionOrder">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button title" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#orderAcco-1" aria-expanded="true"
                                            aria-controls="orderAcco-1">
                                            Order Summary
                                        </button>
                                    </h2>
                                    <div id="orderAcco-1" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionOrder">
                                        <div class="accordion-body">
                                            <div class="ordBx">
                                                <div id="orderSummary">
                                                    @include('checkout.partials.summary')
                                                </div>
                                                <form method="POST" action="{{ route('place-order') }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <textarea name="note" placeholder="Order Note" class="form-control" id="note" data-rule-noHtml="true"
                                                                    data-rule-validMessage="true" maxlength="1000" minlength="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btnSec">
                                                        <div class="item">
                                                            <button type="submit" class="hoveranim cmnBtn"
                                                                id="checkoutBtn">
                                                                <span>Continue Payment</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <div class="emptyCart">
                <div class="emptyBx">
                    <div class="wrpabx">
                        <div class="imgBx">
                            <img src="{{ asset('frontend/images/cartEmpty.png') }}" width="300" height="270"
                                alt="emptyCart">
                        </div>
                        <div class="titles">Looks like your cart is empty</div>
                        <div class="txt">But your next great workspace could be just a click away!</div>
                    </div>
                </div>
                <a href="{{ route('product-listing') }}" class="checkoutBtn hoveranim" aria-label="checkout_Btn">
                    <span>Explore Products</span>
                </a>
            </div>
        @endif
    </main>

    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Processing Your Payment</h3>
            <p>Please don't close this window...</p>
        </div>
    </div>

    @include('partials.address-modals')
@endsection
@push('js')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <!-- select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var CertificationSlide = new Swiper(".adrs-Slider", {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 1500,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                360: {
                    slidesPerView: 1.2,
                    spaceBetween: 15,
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
            },
        });


        $('#bs2').change(function() {
            if ($(this).is(":checked")) {
                $('.fadeAdrs').addClass("menuitemshow");
            } else {
                $('.fadeAdrs').removeClass("menuitemshow");
            }
        });

        @include('js.intel-phone-setup')
        @include('js.jquery-validate')

        selectInit();

        setupValidation('#shippingAddressForm', {}, {}, () => {
            $('button[data-bs-target="#Shipping-Address"]').trigger('click');
            $('#shippingAddressForm').trigger('reset');
            reloadAddress();
        });

        setupValidation('#billingAddressForm', {}, {}, () => {
            $('button[data-bs-target="#Billing-Address"]').trigger('click');
            $('#billingAddressForm').trigger('reset');
            reloadAddress();
        });

        function selectInit() {
            $('.city').select2({
                closeOnSelect: true,
                theme: "select2-custom",
                ajax: {
                    url: "{{ route('addresses.getCities') }}",
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            state_id: $($(this).data('state-id')).val(),
                        };
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(function(city) {
                                return {
                                    id: city.name,
                                    text: city.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('change', function() {
                $(this).valid();
            });

            $(".state").select2({
                closeOnSelect: true,
                theme: "select2-custom",
                ajax: {
                    url: "{{ route('addresses.getStates') }}",
                    data: function(params) {
                        return {
                            q: params.term, // search term
                        };
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(function(state) {
                                return {
                                    id: state.name,
                                    text: state.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('change', function() {
                $(this).closest('form').find('.city').val(null).trigger('change');
            })
        }

        function reloadAddress() {
            let url = "{{ route('user-address') }}";

            $.ajax({
                type: "get",
                url: url,
                dataType: "json",
                beforeSend: function() {
                    $('.addressesMainDiv').addClass('menuitemshow');
                },
                success: function(response) {
                    $('html, body').animate({
                        scrollTop: $('.checkoutPage').offset().top
                    }, 500); // 500 = animation speed in milliseconds
                    if (response.status == true) {
                        $('#shippingAddresses').html(response.shippingAddress);
                        $('#billingAddresses').html(response.billingAddress);
                        checkCheckBox();
                        if (response.count < 1) {
                            $('#shippingAddressForm').trigger('reset');
                            $('#Shipping-Address').collapse('show');
                            $('#billingAddressForm').trigger('reset');
                        } else {
                            $(".shipping-address-cancel-btn").trigger('click');
                            $(".billing-address-cancel-btn").trigger('click');
                        }
                    }
                }
            });
        }

        $(document).on('click', '.shipping-address-cancel-btn', function() {
            $('#Shipping-Address').collapse('hide');
            $('#shippingAddressForm').trigger('reset');
            $('#shippingAddressForm').find('.help-block').html('');
        });

        // area validation because first time it is disabled
        $(document).on('click', '.billing-address-cancel-btn', function() {
            $('#Billing-Address').collapse('hide');
            $('#billingAddressForm').trigger('reset');
            $('#billingAddressForm').find('.help-block').html('');
        });

        $(document).on('click', '#checkoutBtn', function(e) {
            e.preventDefault();
            let same_bill_address = $('input[name="same_bill_address"]:checked').val();
            let is_same = same_bill_address == 'yes' ? 1 : 0;

            var button = $(this);
            var buttonText = button.html();

            $.ajax({
                type: "post",
                url: "{{ route('place-order') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    note: $('#note').val(),
                    same_bill_address: is_same,
                    billingAddressId: $('input[name="billing_address"]:checked').val(),
                    shippingAddressId: $('input[name="shipping_address"]:checked').val()
                },
                beforeSend: function() {
                    button.html('Placing Order...');
                    button.parent().css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        if (response.url) {
                            window.location.href = response.url;
                        } else {
                            // showToast('success', response.message);
                            razorPayCheckout(response);
                        }
                    } else {
                        if (response.url) {
                            window.location.href = response.url;
                        } else {
                            showToast('success', response.message);
                        }
                    }
                },
                complete: function() {
                    button.html(buttonText);
                    button.parent().css({
                        'pointer-events': 'all',
                        'opacity': '1'
                    });
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

        function razorPayCheckout(response) {
            var options = {
                key: response.data.key,
                amount: response.data.amount,
                currency: "INR",
                name: "DESQOO",
                description: "Order #" + response.data.order_uuid,
                image: "{{ asset('frontend/images/Logo.png') }}",
                order_id: response.data.order_id,
                handler: function(paymentResponse) {
                    console.log(paymentResponse);

                    // Send payment response to server
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "{{ route('razorpay-payment') }}",
                        data: paymentResponse,
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(
                            captureResponse) {
                            if (captureResponse
                                .status) {
                                if (captureResponse.url) {
                                    window.location.href = captureResponse.url;
                                }
                            } else {
                                showToast('error',
                                    captureResponse
                                    .message);

                                if (captureResponse.url) {
                                    window.location.href = captureResponse.url;
                                }
                            }
                        },
                        complete: function() {
                            hideLoading();
                        },
                        error: function() {
                            showToast('error',
                                "Error verifying payment.");
                            hideLoading();
                        }
                    });
                },
                prefill: {
                    name: response.data.name,
                    email: response.data.email,
                },
                theme: {
                    color: "#010100"
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        }

        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }
    </script>
    @include('checkout.partials.address-js')
@endpush
