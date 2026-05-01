@extends('layouts.admin')
@section('title')
    Product Images
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5>{{ $product->name }} prices</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <form action="{{ route('admin.products.prices.store', ['product' => $product->route_key]) }}" method="POST"
                enctype="multipart/form-data" id="productPricesForm">
                @csrf
                <div class="card w-100">
                    <div class="card-header">
                        <h5>Combinations</h5>
                    </div>
                    <div class="card-body" id="properies">
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-blue">
                                            <tr>
                                                <th width="15%">Variants</th>
                                                <th>Actual Price</th>
                                                <th>Discount (%)</th>
                                                <th>Discounted Price</th>
                                                <th>Selling Price</th>
                                                <th>Stock</th>
                                                <th>Model</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $row_number = 1; @endphp
                                            @foreach ($combinations as $label => $combination)
                                                @php
                                                    $actual_price = '';
                                                    $discount_percentage = '';
                                                    $discount_price = '';
                                                    $selling_price = '';
                                                    $stock = '';
                                                    $model = '';
                                                @endphp
                                                @if ($product->productPrices->isNotEmpty())
                                                    @foreach ($product->productPrices as $product_price)
                                                        @if ($product_price->label == $label)
                                                            @php
                                                                $actual_price = $product_price->actual_price;
                                                                $discount_percentage =
                                                                    $product_price->discount_percentage;
                                                                $discount_price = $product_price->discount_price;
                                                                $selling_price = $product_price->selling_price;
                                                                $stock = $product_price->stock;
                                                                $model = $product_price->model;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <input class="form-control" type="hidden"
                                                    name="prices[{{ $label }}][property_values]"
                                                    value="{{ json_encode(array_keys($combination)) }}">

                                                <input class="form-control" type="hidden"
                                                    name="prices[{{ $label }}][label]" value="{{ $label }}">
                                                <tr>
                                                    <td><b>{{ $label }}</b></td>
                                                    <td class="text-danger text-center">
                                                        <input class="form-control actual-price" type="number"
                                                            name="prices[{{ $label }}][actual_price]"
                                                            value="{{ $actual_price }}"
                                                            id="actual_price_{{ $row_number }}"
                                                            data-row={{ $row_number }}>
                                                    </td>
                                                    <td class="text-danger text-center">
                                                        <input class="form-control discount-percentage" type="number"
                                                            name="prices[{{ $label }}][discount_percentage]"
                                                            value="{{ $discount_percentage }}"
                                                            id="discount_percentage_{{ $row_number }}"
                                                            data-row={{ $row_number }} readonly>

                                                    </td>
                                                    <td class="text-danger text-center">
                                                        <input class="form-control discount-price" type="number"
                                                            name="prices[{{ $label }}][discount_price]"
                                                            value="{{ $discount_price }}"
                                                            id="discount_price_{{ $row_number }}"
                                                            data-row={{ $row_number }}>
                                                    </td>
                                                    <td class="text-danger text-center selling-price">
                                                        <input class="form-control" type="number"
                                                            name="prices[{{ $label }}][selling_price]"
                                                            value="{{ $selling_price }}"
                                                            id="selling_price_{{ $row_number }}"
                                                            data-row={{ $row_number }} readonly>
                                                    </td>
                                                    <td class="text-danger text-center stock">
                                                        <input class="form-control" type="number"
                                                            name="prices[{{ $label }}][stock]"
                                                            value="{{ $stock }}" id="stock_{{ $row_number }}"
                                                            data-row={{ $row_number }}>
                                                    </td>
                                                    <td class="text-danger text-center model">
                                                        <input class="form-control" type="text"
                                                            name="prices[{{ $label }}][model]"
                                                            value="{{ $model }}" id="model_{{ $row_number }}"
                                                            data-row={{ $row_number }}>

                                                    </td>
                                                </tr>
                                                @php $row_number++ @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <span class="spinner-span" role="status" aria-hidden="true"></span>
                                    <span class="save-btn-text">Save</span>
                                    &nbsp;
                                    <i class="ti ti-device-floppy"></i>
                                </button>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('admin.products.index') }}" type="button" class="btn btn-secondary">
                                    Cancel
                                    &nbsp;
                                    <i class="ti ti-arrow-back-up-double"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(e) {
            $(document).ready(function(e) {
                $('.actual-price, .discount-price').on('input', function(e) {
                    let rowNumber = $(this).data('row');

                    let actualPrice = parseFloat($('#actual_price_' + rowNumber).val()) || 0;
                    let discountPrice = parseFloat($('#discount_price_' + rowNumber).val()) || 0;

                    // Calculate Discount Percentage in %
                    let discountPercentage = 0;
                    if (actualPrice > 0 && discountPrice > 0) {
                        discountPercentage = (discountPrice / actualPrice) * 100;
                    }

                    // Calculate Selling Price
                    let sellingPrice = actualPrice - discountPrice;

                    // Set calculated values
                    $('#discount_percentage_' + rowNumber).val(discountPercentage.toFixed(2));
                    $('#selling_price_' + rowNumber).val(sellingPrice.toFixed(2));
                });
            });

            $('#productPricesForm').submit(function(e) {
                e.preventDefault();

                $('#submit-btn').attr('disabled', true)
                $('.spinner-span').addClass('spinner-border spinner-border-sm')

                $('div[id$="-error"]').empty();
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            setTimeout(function() {
                                window.location.href = "{!! route('admin.products.index') !!}";
                            }, 100);
                        } else {
                            $('#submit-btn').attr('disabled', false)
                            $('.spinner-span').removeClass('spinner-border spinner-border-sm')

                            toastr.error(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm')

                        // toastr.error('There are some errors in Form. Please check your inputs',
                        //     '', {
                        //         showMethod: "slideDown",
                        //         hideMethod: "slideUp",
                        //         timeOut: 1500,
                        //         closeButton: true,
                        //     });
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                            toastr.error(xhr.responseJSON.message)
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] +
                                    '-error')
                                .offset().top - 200
                        }, 500);
                    }
                });
            });
        });
    </script>
@endsection
