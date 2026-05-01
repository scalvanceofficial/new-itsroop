@extends('layouts.frontend')
@section('title')
    Checkout | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Checkout</div>
        </div>
    </div>
    <!-- /page-title -->


    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container">
            <div class="tf-page-cart-wrap layout-2">
                <div class="tf-page-cart-item">
                    <h5 class="fw-5 mb_20">Shipping address</h5>
                    {{-- write a code to select an address with radio button --}}
                    @if ($addresses->isNotEmpty())
                        @foreach ($addresses as $address)
                            <div class="account-address-item border rounded p-3 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="radio" name="address" id="address-{{ $loop->index }}"
                                        class="tf-check address" @if ($address->default === 'YES') checked @endif
                                        value="{{ $address->id }}">
                                    <div>
                                        <p class="mb-0">
                                            {{ $address->recipient_first_name }}
                                            {{ $address->recipient_last_name }} -
                                            {{ $address->address_line_1 }},
                                            {{ $address->address_line_2 }},
                                            {{ $address->city }},
                                            {{ $address->pincode }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{-- Add new address btn --}}
                    <button class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn add-address-btn">Add new
                        address</a>
                    </button>
                </div>
                @if ($user->carts->isNotEmpty())
                    <div class="tf-page-cart-footer">
                        <div class="tf-cart-footer-inner">
                            @if (Auth::check())
                                <h6 class="user-name">Hii, {{ Auth::user()->first_name }}
                                    {{ Auth::user()->last_name }}
                                </h6>
                            @else
                                <p>Guest User</p>
                            @endif
                            <h5 class="fw-5 mb_20">Your order</h5>
                            <form class="tf-page-cart-checkout widget-wrap-checkout">
                                <ul class="wrap-checkout-product">
                                    @php
                                        $total_amount = 0;
                                    @endphp
                                    @foreach ($user->carts as $cart)
                                        @php
                                            $cart_data = getCartData($cart);
                                            $total_product_price = $cart_data['price'] * $cart->quantity;
                                            $total_amount += $total_product_price;
                                        @endphp
                                        <li class="checkout-product-item">
                                            <figure class="img-product">
                                                <img src="{{ $cart_data['image'] }}" alt="product">
                                                <span class="quantity">{{ $cart->quantity }}</span>
                                            </figure>
                                            <div class="content">
                                                <div class="info">
                                                    <p class="name">{{ $cart->product->name }}</p>
                                                    <span class="variant">{{ $cart_data['property_values'] }}</span>
                                                </div>
                                                <span class="price">{{ toCurrency($total_product_price) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                {{-- @php
                                    $gst = ($total_amount * 18) / 100;
                                    $total_amount += $gst;
                                @endphp --}}
                                {{-- <div class="d-flex justify-content-between line pb_20">
                                    <span class="fw-5">GST (18%)</span>
                                    <span class="total fw-5">{{ toCurrency($gst) }}</span>
                                </div> --}}
                                <div class="d-flex justify-content-between line pb_20">
                                    <span class="fw-5">Shipping charges</span>
                                    <span class="total fw-5">{{ toCurrency(0) }}</span>
                                </div>
                                <div class="coupon-box">
                                    <input type="text" id="couponInput" placeholder="Enter Coupon Code"
                                        class="form-control">
                                    <a href="#" id="applyCouponBtn"
                                        class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn">Apply</a>
                                </div>
                                <div id="couponMessage" class="text-success"></div>
                                {{-- === ADDED hidden fields for coupon and amount --}}
                                <input type="hidden" id="appliedCouponId" value="">
                                <input type="hidden" id="finalPayableAmount" value="{{ $total_amount }}">

                                <div class="d-flex justify-content-between line pb_20">
                                    <h6 class="fw-5">Total</h6>
                                    <h6 class="total fw-5">{{ toCurrency($total_amount) }}</h6>
                                </div>
                                <div class="wd-check-payment">
                                    <div class="fieldset-radio mb_20">
                                        <input type="radio" name="payment_method" id="bank"
                                            class="tf-check payment-method" value="GATEWAY" checked hidden>
                                        {{-- <label for="bank">Direct bank transfer</label> --}}

                                    </div>
                                    {{-- <div class="fieldset-radio mb_20">
                                        <input type="radio" name="payment_method" id="delivery" value="COD"
                                            class="tf-check payment-method">
                                        <label for="delivery">Cash on delivery</label>
                                    </div> --}}
                                    <p class="text_black-2 mb_20">Your personal data will be used to process your order,
                                        support your experience throughout this website, and for other purposes described in
                                        our
                                        <a href="/privacy-policy" target="_blank" class="text-decoration-underline">privacy
                                            policy</a>.
                                    </p>
                                    <div class="box-checkbox fieldset-radio mb_20">
                                        <input type="checkbox" id="accept_terms" class="tf-check" name="accept_terms">
                                        <label for="accept_terms" class="text_black-2">I have read and agree to the website
                                            <a href="{{ route('frontend.terms-and-conditions') }}" target="_blank"
                                                class="text-decoration-underline">terms and conditions</a>.</label>
                                    </div>
                                    <span class="text-danger" id="accept_terms_error"></span>
                                </div>
                                @if ($user->addresses->isNotEmpty())
                                    <button
                                        class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center"
                                        id="placeOrderBtn">
                                        Place
                                        order
                                    </button>
                                @else
                                    <button
                                        class="tf-btn radius-3 tf-btn-loading btn-fill btn-icon animate-hover-btn justify-content-center add-address-btn">
                                        Place
                                        order
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @include('Frontend.Modals.address')

    {{-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.add-address-btn').click(function(e) {
                e.preventDefault();
                $('#address').modal('show');
            });

            $('#addressForm').submit(function(e) {
                e.preventDefault();
                $('#address_error').empty();
                $('#city_error').empty();
                $('#pincode_error').empty();

                $('#addressBtnText').addClass('d-none');
                $('#addressBtnLoader').removeClass('d-none');
                $('#addressBtn').prop('disabled', true);

                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $('#addressBtnText').removeClass('d-none');
                        $('#addressBtnLoader').addClass('d-none');
                        $('#addressBtn').prop('disabled', false);
                        if (response.status == 'success') {
                            toastr.success(
                                response.message,
                                '', {
                                    showMethod: "slideDown",
                                    timeOut: 1000,
                                    closeButton: true,
                                });

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $('#addressBtnText').removeClass('d-none');
                        $('#addressBtnLoader').addClass('d-none');
                        $('#addressBtn').prop('disabled', false);
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '_error').html(value);
                        });
                    }
                });
            });


            $('#applyCouponBtn').click(function(e) {
                e.preventDefault();

                var coupon = $('#couponInput').val();
                var totalAmount = {{ $total_amount }};
                var token = '{{ csrf_token() }}';

                if (coupon.trim() === '') {
                    toastr.error('Please enter a coupon code.');
                    return;
                }

                $.post('{{ route('frontend.apply.coupon.code') }}', {
                    _token: token,
                    coupon: coupon,
                    totalSellingPrice: totalAmount
                }, function(res) {
                    if (res.status === 'success') {
                        $('#couponMessage').text('Coupon applied! ' + res.formatted_discount_amount +
                            ' off.');

                        $('#appliedCouponId').val(res.coupon_code_id);
                        $('#finalPayableAmount').val(res.final_price);

                        $('.tf-page-cart-footer .line h6.total').text(res.formatted_final_price);

                        toastr.success('Coupon applied successfully!');
                    }

                }).fail(function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error('Invalid coupon.');
                    }
                });
            });

        function stripeInitiate() {
            let totalAmount = $('#finalPayableAmount').val();
            let acceptTerms = $('#accept_terms').is(':checked');
            let couponCodeId = $('#appliedCouponId').val();
            let addressId = $('.address:checked').val();

            if (!acceptTerms) {
                $('#accept_terms_error').html('Please accept the terms and conditions.');
                $('#placeOrderBtn').html('Place order');
                return;
            }

            $.ajax({
                url: "{{ route('frontend.orders.stripe-initiate') }}",
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    amount: totalAmount,
                    accept_terms: acceptTerms ? 1 : '',
                    coupon_code_id: couponCodeId,
                    address_id: addressId
                }),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.session_url;
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $('#placeOrderBtn').html('Place order');
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            if (key === 'address_id') {
                                toastr.error('Please select a shipping address.');
                            } else {
                                $('#' + key + '_error').html(value);
                            }
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        }

        $('#placeOrderBtn').on('click', function(e) {
            e.preventDefault();
            $(this).html('Redirecting to Payment...');
            stripeInitiate();
            });
        });
    </script>
@endsection
