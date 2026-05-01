@extends('layouts.admin')
@section('title')
    Return Product
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.return-products.create') ? route('admin.return-products.store') : route('admin.return-products.update', ['return_product' => $return_product->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="return-product-form">
        @csrf
        {{ Route::is('admin.return-products.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.return-products.create') ? 'Create' : 'Edit' }} Return Product</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Order Number <sup
                                        class="text-danger">*</sup></label>
                                <select class="form-control select2" name="order_id" id="order_id">
                                    <option value="">Select Order</option>
                                    @foreach ($orders as $order)
                                        <option value="{{ $order->id }}"
                                            {{ isset($return_product) && $return_product->order_id == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_number }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="order_id-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="order-product-container"></div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="spinner-span" role="status" aria-hidden="true"></span>
                            <span class="save-btn-text">Save</span>
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.return-products.index') }}" type="button" class="btn btn-secondary">
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
        $(document).ready(function() {
            $('#order_id').on('change', function() {
                var orderId = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.return-product.order-product') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_id: orderId
                    },
                    success: function(response) {
                        $('#order-product-container').html(response);
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr);
                    }
                });
            });
        });
    </script>

    <script>
        $('#return-product-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.return-products.index') !!}";
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

                    let message = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            message += value + '<br>';
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else {
                        message = 'Something went wrong.';
                    }

                    toastr.error(message);
                }
            });
        });
    </script>
@endsection
