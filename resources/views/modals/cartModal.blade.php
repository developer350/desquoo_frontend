<!--Cart  Modal -->
<div class="modal cartModal" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('modals.partials.cart-content')
        </div>
    </div>
</div>

<div class="modal removeModal" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="removeBx">
                <div class="mainTxt">Remove this item from your cart?</div>
                <div class="flxbx">
                    <div class="item">
                        <a href="javascript:void(0)" class="rmBtns removeBtn" aria-label="remove_button">
                            <span>Remove</span>
                        </a>
                    </div>
                    <div class="item">
                        <button data-bs-toggle="modal" data-bs-target="#removeModal" class="rmBtns"
                            aria-label="remove_button">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.editModal')

@push('js')
    <script>
        $(document).on('change', '.quantity', function() {
            var cartId = $(this).attr('data-id');
            var value = parseInt($(this).val());
            value = isNaN(value) ? 1 : value;
            if (value < 1) {
                value = 1;
                $(this).val(value);
            }
            var parent = $(this).closest('.cartItemBx');
            updateQty(parent, cartId, value, $(this));
        });

        $(document).on('click', '.plus, .minus', function() {
            var cartId = $(this).attr('data-id');
            var input = $(this).parent().find('input');
            var value = parseInt(input.val());
            value = isNaN(value) ? 0 : value;

            if ($(this).hasClass('plus'))
                value++;
            else
                value = value > 1 ? value - 1 : 1;

            if ($(this).hasClass('addon')) {
                var parent = $(this).closest('.addonItemBx');
            } else {
                var parent = $(this).closest('.cartItemBx');
            }

            updateQty(parent, cartId, value, input);
        });

        function updateQty(parent, cartId, value, input) {
            $.ajax({
                url: "{{ route('update-cart') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: cartId,
                    quantity: value
                },
                beforeSend: function() {
                    $(parent).css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        input.val(value);
                        $('#cartModal .btmBx').html(response.summary);
                        isCheckoutPage(response);
                    } else {
                        showToast('error', response.message);
                    }
                },
                complete: function() {
                    $(parent).css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                },
                error: function() {
                    $(parent).css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                }
            });
        }

        var cartId;
        var item;
        $(document).on('click', '.deleteBtn', function() {
            cartId = $(this).attr('data-id');
            item = $(this).hasClass('addon') ? $(this).closest('.addonItemBx').parent('.item') : $(this).closest(
                '.cartItemBx');

            $('#removeModal').modal('show');
        });

        $(document).on('click', '.removeBtn', function() {
            $.ajax({
                url: "{{ route('remove-from-cart') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: cartId
                },
                beforeSend: function() {
                    $('#removeModal').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        $('#cartModal .btmBx').html(response.summary);
                        $(item).remove();
                        $('#removeModal').modal('hide');
                        $('#totalItems').html(response.totalItems);
                        $('#cartCount').text(response.totalItems);

                        if (response.totalItems == 0) {
                            $('#cartModal .modal-content').html(`<div class="emptyCart">
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
                            </div>`);
                        }

                        if ($('.ListItem .addonList .item').length == 0) {
                            $('.subTitle').remove();
                        }

                        isCheckoutPage(response);
                    } else {
                        showToast('error', response.message);
                    }
                },
                complete: function() {
                    $('#removeModal').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                }
            });
        });

        function isCheckoutPage(response) {
            if (window.location.href.includes('checkout')) {
                if (response.totalItems == 0) {
                    $('#pageWrapper').empty().html(`<div class="emptyCart">
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
                    </div>`);
                } else {
                    $('#orderSummary').html(response.summaryCheckout);
                }
            }
        }

        let editProductId;
        let editCartId;
        $(document).on('click', '.changeVariant', function() {
            var productId = $(this).data('product-id');
            editProductId = productId;
            var cartId = $(this).data('cart-id');
            editCartId = cartId;

            var button = $(this);

            $.ajax({
                type: "get",
                url: "{{ route('change-variant') }}",
                data: {
                    cart_id: cartId,
                    product_id: productId
                },
                success: function(response) {
                    if (response.status) {
                        $('#editModal .modal-body').html(response.html);
                        $('#editModal').modal('show');
                    } else {
                        showToast('error', response.message);
                    }
                }
            });
        });

        $(document).on('click', '.addAddonFromEditToCart', function() {
            var productId = $(this).data('product-id');
            var variantId = $(this).data('variant-id');

            addonButton = $(this);
            addToCartFromEdit(productId, variantId, 1, true);
        });

        function addToCartFromEdit(productId, variantId, quantity = 1, isAddon = false) {
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
                    $('.editList').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        if (isAddon) {
                            // $(addonButton).html('Added');
                            $('.editList').css({
                                'opacity': '1',
                                'pointer-events': 'all'
                            });

                            showToast('success', 'Added');
                        } else {
                            changeVariant();
                        }
                        // $('.addToCart').addClass('outStockBtn').html('<span>Added</span>');
                        // $('#addToCartMobile').addClass('outStockBtn').html('<span>Added</span>');
                        $('#cartModal .modal-content').html(response.cartHtml);
                        $('#cartModal').modal('show');
                        $('#cartCount').text(response.cartCount);
                    } else {
                        $('.editList').css({
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
                    $('.editList').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });

                    if (isAddon) {
                        $(addonButton).html('Add To Cart');
                    }
                },
            });
        }

        $(document).on('change', '.attributesEditModal', function(e) {
            e.preventDefault();
            changeEditVariant();
        });

        function changeEditVariant() {
            var data = $('.attributesEditModal:checked').serializeArray();

            data.push({
                name: 'product_id',
                value: editProductId
            });

            $.ajax({
                type: "get",
                url: "{{ route('variant-info') }}",
                data: data,
                beforeSend: function() {
                    $('.editList').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                },
                success: function(response) {
                    if (response.status) {
                        $('#editimageSection').attr('src', response.image);
                        $('.editList').css({
                            'opacity': '1',
                            'pointer-events': 'all'
                        });
                    } else {
                        $('.editList').css({
                            'opacity': '1',
                            'pointer-events': 'all'
                        });
                    }
                },
                error: function() {
                    $('.editList').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                }
            });
        }

        $(document).on('click','#editChangeVariant',function(e) {
            e.preventDefault();

            var button = $(this);
            var data = $('.attributesEditModal:checked').serializeArray();

            data.push({
                name: 'product_id',
                value: editProductId
            }, {
                name: 'cart_id',
                value: editCartId
            },{
                name: '_token',
                value: '{{ csrf_token() }}'
            });

            $.ajax({
                type: "post",
                url: "{{ route('change-cart-variant') }}",
                data: data,
                beforeSend: function() {
                    $('.editList').css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });

                    button.prop('disabled', true);
                },
                success: function(response) {
                    if (response.status) {
                        $('#editModal').modal('hide');
                        $('#cartModal .modal-content').html(response.cartHtml);
                        $('#cartModal').modal('show');
                        $('#cartCount').text(response.cartCount);
                    } else {
                        $('.editList').css({
                            'opacity': '1',
                            'pointer-events': 'all'
                        });
                        showToast('error', response.message);
                    }
                },
                complete: function() {
                    $('.editList').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                    button.prop('disabled', false);
                },
                error: function() {
                    $('.editList').css({
                        'opacity': '1',
                        'pointer-events': 'all'
                    });
                }
            });
        });

    </script>
@endpush
