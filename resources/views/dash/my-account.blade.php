@extends('layouts.app')
@section('title', 'Dashboard - My Account')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush
@section('content')
    <main id="pageWrapper" class="dashPage">
        <section id="DashSection">
            <div class="container">
                <div class="userBox">
                    <div class="imgWrap">
                        <img src="{{ asset('frontend/images/dash-user-1.jpg') }}" width="126" height="126" loading="lazy"
                            alt="dash-user-1">
                    </div>
                    <div class="cntWrap">
                        <div class="tle">{{ Auth::user()->name }}</div>
                        <div class="txt"><a href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a></div>
                        <div class="txt addDesignation"><a href="#" id="logout">Logout</a></div>
                    </div>
                </div>

                <div class="accordion accountAcco" id="AccountAcco">
                    <div class="headWrap">
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#AccountAccoItem_1" aria-expanded="true"
                                    aria-controls="AccountAccoItem_1">
                                    Orders
                                </button>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#AccountAccoItem_3" aria-expanded="false"
                                    aria-controls="AccountAccoItem_3">
                                    Addresses
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#AccountAccoItem_1" aria-expanded="true" aria-controls="AccountAccoItem_1">
                                Orders
                            </button>
                        </div>
                        <div id="AccountAccoItem_1" class="accordion-collapse collapse show" data-bs-parent="#AccountAcco">
                            <div class="accordion-body">
                                <div class="ordersWrap">
                                    @forelse ($orders as $order)
                                        <div class="item">
                                            <div class="orderInfoBx">
                                                <div class="infoFlx">
                                                    <div class="txt1">#{{ $order->uuid }}</div>
                                                    <div class="txt1">₹ {{ $order->grand_total }}</div>
                                                </div>
                                                <div class="infoFlx">
                                                    <div class="txt">{{ $order->order_date_formatted }}</div>
                                                    <div class="txt">{{ $order->item_count }} Item</div>
                                                </div>
                                                <hr />
                                                @foreach ($order->orderItems as $orderItem)
                                                    <div class="orderProBx">
                                                        <div class="imgWrap">
                                                            <img src="{{ $orderItem->productVariant->image_value ?? $orderItem->product->image_value }}"
                                                                width="126" height="126" loading="lazy"
                                                                alt="{{ $orderItem->productVariant->image_alt_text ?? ($orderItem->product->image_alt_text ?? $orderItem->product->name) }}">
                                                        </div>
                                                        <div class="cntWrap">
                                                            <div>
                                                                <div class="txt1">{{ $orderItem->product->name }}</div>
                                                                @foreach ($orderItem->productVariant->attributeValues as $attributeValue)
                                                                    <div class="txt">
                                                                        @if ($attributeValue->icon_value != null)
                                                                            <img src="{{ $attributeValue->icon_value }}"
                                                                                width="16" height="16"
                                                                                alt="{{ $attributeValue->value }}">
                                                                        @else
                                                                            <div class="rounded-circle"
                                                                                style="width:16px;height:16px;">
                                                                                <span class="text-black">
                                                                                    {{ strtoupper(substr($attributeValue->value, 0, 1)) }}
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                        <span>{{ $attributeValue->value }}</span>
                                                                    </div>
                                                                @endforeach
                                                                {{-- <div class="txt">Tracking Number : 90114306951</div> --}}
                                                            </div>
                                                            <div>
                                                                <div class="txt">Qty: {{ $orderItem->quantity }}</div>
                                                                <span>₹ {{ $orderItem->price }}</span>
                                                            </div>
                                                            @if ($orderItem->discount_amount > 0)
                                                                <div>
                                                                    <div class="txt">Subtotal (Incl. Tax): ₹
                                                                        {{ $orderItem->sub_total }}</div>
                                                                    <div class="txt">Discount: - ₹
                                                                        {{ $orderItem->discount_amount }}</div>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="txt price">₹ {{ $orderItem->total }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (!$loop->last)
                                                        <hr />
                                                    @endif
                                                @endforeach
                                                <div class="orderBtnWrap">
                                                    @if ($order->payment_status == 'paid')
                                                        <div>
                                                            <div class="baseBtn_1 trackBtn" aria-label="Track Order">
                                                                <span>Track Order</span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('order-invoice', ['uuid' => $order->uuid]) }}"
                                                                class="baseBtn_1 hoveranim" aria-label="Download Invoice">
                                                                <span>Download Invoice</span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="baseBtn_1 toggleBtn" aria-label="Show Order Details">
                                                            <span>Show Order Details</span>
                                                            <div class="icon">
                                                                <svg width="20" height="20" viewBox="0 0 20 20"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="white"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="orderTrackingWrap">
                                                    <hr />
                                                    <div class="trackingOuter">
                                                        <div class="trackingFlx">
                                                            <div class="item completed">
                                                                <div class="txt">Order Placed</div>
                                                                <span class="is-complete"></span>
                                                            </div>
                                                            @if ($order->status == 'cancelled')
                                                                <div class="item completed">
                                                                    <div class="txt">Cancelled</div>
                                                                    <span class="is-complete"></span>
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="item {{ $order->checkStatusIsAfter('confirmed') ? 'completed' : '' }}">
                                                                    <div class="txt">Confirmed</div>
                                                                    <span class="is-complete"></span>
                                                                </div>
                                                                <div
                                                                    class="item {{ $order->checkStatusIsAfter('processing') ? 'completed' : '' }}">
                                                                    <div class="txt">Processing</div>
                                                                    <span class="is-complete"></span>
                                                                </div>
                                                                <div
                                                                    class="item {{ $order->checkStatusIsAfter('shipped') ? 'completed' : '' }}">
                                                                    <div class="txt">Shipped</div>
                                                                    <span class="is-complete"></span>
                                                                </div>
                                                                <div
                                                                    class="item {{ $order->checkStatusIsAfter('delivered') ? 'completed' : '' }}">
                                                                    <div class="txt">Delivered</div>
                                                                    <span class="is-complete"></span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        {{-- <hr /> --}}
                                                        {{-- <div class="trackingDtlFlx">
                                                            <div class="item">
                                                                <div class="trackingDtlBx">
                                                                    <div class="infoDisc">
                                                                        <div class="txt"><b>23 <br>Jun</b></div>
                                                                        <div class="delmt"></div>
                                                                    </div>
                                                                    <div style="width: 35%">
                                                                        <div class="txt"><b>Location: </b>Bangalore
                                                                        </div>
                                                                    </div>
                                                                    <div style="width: 25%">
                                                                        <div class="txt"><b>Time: </b>05:34</div>
                                                                    </div>
                                                                    <div style="width: 40%">
                                                                        <div class="txt"><b>Remark: </b>SHIPMENT FURTHER
                                                                            CONNECTED
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item">
                                                                <div class="trackingDtlBx">
                                                                    <div class="infoDisc">
                                                                        <div class="txt"><b>23 <br>Jun</b></div>
                                                                        <div class="delmt"></div>
                                                                    </div>
                                                                    <div style="width: 35%">
                                                                        <div class="txt"><b>Location: </b>Bangalore
                                                                        </div>
                                                                    </div>
                                                                    <div style="width: 25%">
                                                                        <div class="txt"><b>Time: </b>05:34</div>
                                                                    </div>
                                                                    <div style="width: 40%">
                                                                        <div class="txt"><b>Remark: </b>SHIPMENT FURTHER
                                                                            CONNECTED
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item">
                                                                <div class="trackingDtlBx">
                                                                    <div class="infoDisc">
                                                                        <div class="txt"><b>23 <br>Jun</b></div>
                                                                        <div class="delmt"></div>
                                                                    </div>
                                                                    <div style="width: 35%">
                                                                        <div class="txt"><b>Location: </b>Bangalore
                                                                        </div>
                                                                    </div>
                                                                    <div style="width: 25%">
                                                                        <div class="txt"><b>Time: </b>05:34</div>
                                                                    </div>
                                                                    <div style="width: 40%">
                                                                        <div class="txt"><b>Remark: </b>SHIPMENT FURTHER
                                                                            CONNECTED
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <div class="orderSummaryWrap">
                                                    <hr />
                                                    <div class="cost">Cost Breakup</div>
                                                    <div class="infoFlx">
                                                        <div class="txt">Subtotal (Incl. Tax):</div>
                                                        <div class="txt">₹ {{ $order->sub_total }}</div>
                                                    </div>
                                                    <div class="infoFlx">
                                                        <div class="txt">Discount:</div>
                                                        <div class="txt">- ₹ {{ $order->discount_amount }}</div>
                                                    </div>
                                                    <div class="infoFlx">
                                                        <div class="txt">Shipping Cost:</div>
                                                        <div class="txt">₹ 0</div>
                                                    </div>
                                                    <div class="infoFlx">
                                                        <div class="txt">Tax:</div>
                                                        <div class="txt">₹ {{ $order->tax_amount }}</div>
                                                    </div>
                                                    <div class="infoFlx">
                                                        <div class="txt">Grand Total:</div>
                                                        <div class="txt">₹ {{ $order->grand_total }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="noOrder">No Order Found</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="AccountAccoItem_3" class="accordion-collapse collapse" data-bs-parent="#AccountAcco">
                        <div class="accordion-body">
                            <div class="addrsWrap">
                                <div class="formWrap">

                                    <div class="cmNtxt">My Address</div>
                                    @if ($addresses->isNotEmpty())
                                        <div class="addressFlx">
                                            @foreach ($addresses as $address)
                                                <div class="item">
                                                    <div class="adrsBx">
                                                        <div class="label">
                                                            <div class="bxAdrs">
                                                                <div class="lSide">
                                                                    <div class="title">{{ $address->name }}</div>
                                                                    <div class="txt">{{ $address->address_line_1 }}
                                                                        <br>{{ $address->address_line_2 }}
                                                                        <br>{{ $address->city }}, {{ $address->state }} -
                                                                        {{ $address->postal_code }}
                                                                        <br>{{ $address->landmark }}
                                                                    </div>
                                                                    <div class="txt">{{ $address->phone_number }}</div>
                                                                    <div class="txt">{{ $address->email }}</div>
                                                                </div>
                                                                <div class="RSide">
                                                                    <button class="adsIcons editAdrs"
                                                                        data-id="{{ $address->id }}">
                                                                        <div class="icon">
                                                                            <img src="{{ asset('frontend/images/icon-edit.svg') }}"
                                                                                width="18" height="18"
                                                                                alt="Edit">
                                                                        </div>
                                                                        <div class="txt">Edit</div>
                                                                    </button>
                                                                    <button class="adsIcons deleteAdrs"
                                                                        data-id="{{ $address->id }}">
                                                                        <div class="icon">
                                                                            <img src="{{ asset('frontend/images/icon-dlt.svg') }}"
                                                                                width="18" height="18"
                                                                                alt="Delete">
                                                                        </div>
                                                                        <div class="txt">Delete</div>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- mobile address slide -->
                                        <div class="swiper adrs-Slider">
                                            <div class="swiper-wrapper">
                                                @foreach ($addresses as $address)
                                                    <div class="swiper-slide">
                                                        <div class="adrsBx">
                                                            <div class="label">
                                                                <div class="bxAdrs">
                                                                    <div class="lSide">
                                                                        <div class="title">{{ $address->name }}</div>
                                                                        <div class="txt">{{ $address->address_line_1 }}
                                                                            <br>{{ $address->address_line_2 }}
                                                                            <br>{{ $address->city }},
                                                                            {{ $address->state }} -
                                                                            {{ $address->postal_code }}
                                                                            <br>{{ $address->landmark }}
                                                                        </div>
                                                                        <div class="txt">{{ $address->phone_number }}
                                                                        </div>
                                                                        <div class="txt">{{ $address->email }}</div>
                                                                    </div>
                                                                    <div class="RSide">
                                                                        <button class="adsIcons editAdrs"
                                                                            data-id="{{ $address->id }}">
                                                                            <div class="icon">
                                                                                <img src="{{ asset('frontend/images/icon-edit.svg') }}"
                                                                                    width="18" height="18"
                                                                                    alt="Edit">
                                                                            </div>
                                                                            <div class="txt">Edit</div>
                                                                        </button>
                                                                        <button class="adsIcons deleteAdrs"
                                                                            data-id="{{ $address->id }}">
                                                                            <div class="icon">
                                                                                <img src="{{ asset('frontend/images/icon-dlt.svg') }}"
                                                                                    width="18" height="18"
                                                                                    alt="Delete">
                                                                            </div>
                                                                            <div class="txt">Delete</div>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="noOrder">No Address Found</div>
                                    @endif

                                    <div class="btnWraps">
                                        <button class="newAddress" data-bs-toggle="collapse"
                                            data-bs-target="#Shipping-Address" aria-expanded="false"
                                            aria-controls="Shipping-Address">ADD NEW ADDRESS
                                        </button>
                                    </div>
                                    <div id="Shipping-Address" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#UpdateAddress">
                                        <div class="accordion-body">
                                            <div class="tleWrap">
                                                <div class="cmNtxt">Add New Address</div>
                                            </div>
                                            @include('partials.address-form')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- VIRTUAL_MODAL -->
    @include('partials.address-modals')
@endsection
@push('js')
    <!-- select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        window.addEventListener('DOMContentLoaded', function() {
            $('.orderSummaryWrap').hide();
            // Toggle functionality
            $('.orderInfoBx').on('click', '.toggleBtn', function(e) {
                e.preventDefault();

                var $this = $(this);
                var orderSummary = $this.closest('.orderInfoBx').find('.orderSummaryWrap');
                var button = $this;
                var buttonIcon = $this.find('.icon');

                // Slide toggle with animation
                orderSummary.slideToggle(300, function() {
                    // Animation complete callback
                    if (orderSummary.is(':visible')) {
                        orderSummary.addClass('active');
                        button.addClass('active');
                        button.find('span').text('Hide Order Details');
                        buttonIcon.css('transform', 'rotate(180deg)');
                    } else {
                        orderSummary.removeClass('active');
                        button.removeClass('active');
                        button.find('span').text('Show Order Details');
                        buttonIcon.css('transform', 'rotate(0deg)');
                    }
                });
            });

            $('.orderTrackingWrap').hide();
            // Toggle functionality
            $('.orderInfoBx').on('click', '.trackBtn', function(e) {
                e.preventDefault();

                var $this = $(this);
                var orderSummary = $this.closest('.orderInfoBx').find('.orderTrackingWrap');
                var button = $this;
                var buttonIcon = $this.find('.icon');

                // Slide toggle with animation
                orderSummary.slideToggle(300, function() {
                    // Animation complete callback
                    if (orderSummary.is(':visible')) {
                        orderSummary.addClass('active');
                        button.addClass('active');
                        // button.find('span').text('Hide Order Details');
                        buttonIcon.css('transform', 'rotate(180deg)');
                    } else {
                        orderSummary.removeClass('active');
                        button.removeClass('active');
                        // button.find('span').text('Show Order Details');
                        buttonIcon.css('transform', 'rotate(0deg)');
                    }
                });
            });

        });

        $(document).on('click', '#logout', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to logout!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}";
                }
            })
        });

        $(document).on('click', '.editAdrs', function() {
            var addressId = $(this).data('id');
            var url = "{{ route('addresses.update', ['address' => ':id']) }}".replace(':id', addressId);
            $('#addressEditForm').attr('action', url);
            $.ajax({
                url: '{{ route('addresses.edit', ['address' => ':id']) }}'.replace(':id', addressId),
                method: 'GET',
                success: function(response) {
                    $('#edit_name').val(response.name);
                    $('#edit_email').val(response.email);
                    $('#edit_phone_number').val(response.phone_number);
                    initializePhoneInputs('.edit_phone_number', '.edit_country_code', 'in', ['in']);

                    $('#edit_address_line_1').val(response.address_line_1);
                    $('#edit_address_line_2').val(response.address_line_2);
                    $('#edit_postal_code').val(response.postal_code);
                    $('#edit_landmark').val(response.landmark);
                    //set state and trigger change to load cities
                    var stateOption = new Option(response.state, response.state, true, true);
                    $('#edit_state').append(stateOption).trigger('change');

                    //set city
                    var cityOption = new Option(response.city, response.city, true, true);
                    $('#edit_city').append(cityOption).trigger('change');
                    $('#addressEditModal').modal('show');
                },
                error: function() {
                    alert('Failed to fetch address details. Please try again.');
                }
            });
        });

        $(document).on('shown.bs.modal', '#addressEditModal', function() {
            selectInit();
        });

        function selectInit() {
            $('#edit_city').select2({
                dropdownParent: $('#addressEditModal'),
                closeOnSelect: true,
                theme: "select2-custom",
                ajax: {
                    url: "{{ route('addresses.getCities') }}",
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            state_id: $('#edit_state').val(),
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

            $("#edit_state").select2({
                dropdownParent: $('#addressEditModal'),
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
                $('#edit_city').val('').trigger('change');
            })
        }

        @include('js.intel-phone-setup')
        @include('js.jquery-validate')

        setupValidation('#addressEditForm', {}, {}, afterSuccess);

        function afterSuccess() {
            $('#addressEditModal').modal('hide');
            location.reload();
        }

        var deleteUrl;
        $(document).on('click', '.deleteAdrs', function() {
            var addressId = $(this).data('id');
            deleteUrl = "{{ route('addresses.destroy', ['address' => ':id']) }}".replace(':id', addressId);
            $('#removeModalAddress').modal('show');
            $('#removeModalAddress').css({
                'opacity': '1',
                'pointer-events': 'all'
            });
        });

        $(document).on('click', '.deleteAddress', function() {
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'DELETE'
                },
                beforeSend: function() {
                    $('#removeModalAddress').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        showToast('success', response.message);
                        $('#removeModalAddress').modal('hide');
                        location.reload();
                    } else {
                        showToast('error', response.message);
                    }
                },
                complete: function() {
                    $('#removeModalAddress').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                }
            });
        });

        $('#city').select2({
            closeOnSelect: true,
            theme: "select2-custom",
            ajax: {
                url: "{{ route('addresses.getCities') }}",
                data: function(params) {
                    return {
                        q: params.term, // search term
                        state_id: $('#state').val(),
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

        $("#state").select2({
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
            $('#city').val('').trigger('change');
        });

        setupValidation('#addressForm', {}, {}, afterAddressAdd);

        function afterAddressAdd() {
            location.reload();
        }
    </script>
@endpush
