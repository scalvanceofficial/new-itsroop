@extends('layouts.admin')
@section('title')
    Coupon Codes
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.coupon-codes.create') ? route('admin.coupon-codes.store') : route('admin.coupon-codes.update', ['coupon_code' => $couponcode->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="couponcode-form">
        @csrf
        {{ Route::is('admin.coupon-codes.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.coupon-codes.create') ? 'Create' : 'Edit' }} Coupon Code</h5>
                    </div>

                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label col-form-label">
                                    Code <sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <input type="text" class="form-control" name="coupon_code" placeholder="Enter Code here"
                                    value="{{ isset($couponcode) ? $couponcode->coupon_code : '' }}" />
                                <div id="coupon_code-error" style="color:red"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-form-label">
                                    Currency <sup class="tcul-star-restrict text-danger">*</sup>
                                </label>
                                <select name="currency_code" class="form-control">
                                    @foreach(\App\Models\Currency::where('is_active', true)->get() as $currency)
                                        <option value="{{ $currency->code }}" {{ (isset($couponcode) && $couponcode->currency_code == $currency->code) ? 'selected' : '' }}>
                                            {{ $currency->name }} ({{ $currency->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="currency_code-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Percentage <sup
                                        class="tcul-star-restrict text-danger">*</sup></label>
                                <input type="number" class="form-control" placeholder="Percentage" name="percentage"
                                    value="{{ isset($couponcode) ? $couponcode->percentage : '' }}" />
                                <div id="percentage-error" style="color:red"></div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Minimum Order Amount <sup
                                        class="tcul-star-restrict text-danger">*</sup></label>
                                <input type="number" class="form-control" placeholder="Minimum Amount"
                                    name="minimum_order_amount"
                                    value="{{ isset($couponcode) ? $couponcode->minimum_order_amount : '' }}" />
                                <div id="minimum_order_amount-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Start Date <sup
                                        class="text-danger">*</sup></label>
                                <input type="date" class="form-control" name="start_date"
                                    value="{{ isset($couponcode) ? $couponcode->start_date : '' }}" />
                                <div id="start_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">End Date <sup
                                        class="text-danger">*</sup></label>
                                <input type="date" class="form-control" name="end_date"
                                    value="{{ isset($couponcode) ? $couponcode->end_date : '' }}" />
                                <div id="end_date-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="spinner-span" role="status" aria-hidden="true"></span>
                            <span class="save-btn-text">Save</span>
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.coupon-codes.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#couponcode-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.coupon-codes.index') !!}";
                        }, 100);
                    } else {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm')
                        toastr.error('There is some error!!', '', {
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
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '-error').html(value);
                    });
                    $('html, body').animate({
                        scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                            .offset().top - 200
                    }, 500);
                }
            });
        });
    </script>
@endsection
