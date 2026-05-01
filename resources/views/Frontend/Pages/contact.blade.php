@extends('layouts.frontend')
@section('title')
    Contact | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Contact Us</div>
        </div>
    </div>
    <!-- /page-title -->


    <section class="flat-spacing-21">
        <div class="container">
            <div class="tf-grid-layout gap30 lg-col-2">
                {{-- get in touch --}}
                    <h5 class="mb_20">Get in Touch</h5>
                    <div class="mb_20">
                        <p class="mb_15"><strong>Address</strong></p>
                        <p>Its Roop Ltd, UK</p>
                    </div>
                    <div class="mb_20">
                        <p class="mb_15"><strong>Email</strong></p>
                        <p>info@itsroop.com</p>
                    </div>
                    <div>
                        <ul class="tf-social-icon d-flex gap-20 style-default contact-social">
                            <li><a href="#" class="social-icon-bw"><i class="icon fs-14 icon-fb"></i></a></li>
                            <li><a href="#" class="social-icon-bw"><i class="icon fs-12 icon-Icon-x"></i></a></li>
                            <li><a href="#" class="social-icon-bw"><i class="icon fs-14 icon-instagram"></i></a></li>
                            <li><a href="#" class="social-icon-bw"><i class="icon fs-14 icon-tiktok"></i></a></li>
                            <li><a href="#" class="social-icon-bw"><i class="icon fs-14 icon-pinterest-1"></i></a></li>
                        </ul>
                    </div>
                    <style>
                        .social-icon-bw {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            width: 34px;
                            height: 34px;
                            border-radius: 50%;
                            border: 1px solid #000;
                            color: #000;
                            transition: all 0.3s ease;
                        }
                        .social-icon-bw:hover {
                            background-color: #000;
                            color: #fff;
                        }
                    </style>
                {{-- get in touch --}}

                {{-- contact form --}}
                <div class="tf-content-right">
                    <h5 class="mb_20">Get in Touch</h5>
                    <p class="mb_24">If you’ve got great products your making or looking to work with us then drop us a
                        line.</p>
                    <div>
                        <form method="POST" action="{{ route('frontend.enquiry.store') }}" id="enquiryForm">
                            @csrf

                            <div class="d-flex gap-15 mb_15">
                                <fieldset class="w-100">
                                    <input type="text" name="first_name" class="form-control mb-1" id="first_name"
                                        placeholder="Enter first name*">
                                    <div class="text-start" style="color:red" id="first_name-error"></div>
                                </fieldset>
                                <fieldset class="w-100">
                                    <input type="text" name="last_name" class="form-control mb-1" id="last_name"
                                        placeholder="Enter last name*">
                                    <div class="text-start" style="color:red" id="last_name-error"></div>
                                </fieldset>
                            </div>
                            <div class="d-flex gap-15 mb_15">
                                <fieldset class="w-100">
                                    <input type="number" name="mobile" class="form-control" id="mobile"
                                        placeholder="Enter Mobile Number*">
                                    <div class="text-start" id="mobile-error" style="color:red"></div>
                                </fieldset>
                                <fieldset class="w-100">
                                    <input type="email" name="email" class="form-control mb-1" id="email"
                                        placeholder="Enter email address*">
                                    <div class="text-start" style="color:red" id="email-error"></div>
                                </fieldset>
                            </div>
                            <div class="mb_15">
                                <textarea name="message" class="form-control" id="message" cols="30" rows="10"
                                    placeholder="Enter your message*"></textarea>
                                <div class="text-start" style="color:red" id="message-error"></div>
                            </div>
                            <div class="send-wrap">
                                <div class="send-wrap d-flex justify-content-center">
                                    <button type="submit"
                                        class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center"
                                        id="enquiryBtn">
                                        <span id="enquiryBtnText">Send</span>
                                        <span id="enquiryBtnLoader" class="d-none">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
                {{-- contcat form --}}
            </div>
        </div>
    </section>


    <script>
        $('#enquiryForm').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();

            $('#enquiryBtnText').addClass('d-none');
            $('#enquiryBtnLoader').removeClass('d-none');
            $('#enquiryBtn').prop('disabled', true);

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

                    $('#enquiryBtnText').removeClass('d-none');
                    $('#enquiryBtnLoader').addClass('d-none');
                    $('#enquiryBtn').prop('disabled', false);
                    if (data.status === 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        setTimeout(function() {
                            window.location.href = '{!! route('frontend.contact') !!}';
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    $('#enquiryBtnText').removeClass('d-none');
                    $('#enquiryBtnLoader').addClass('d-none');
                    $('#enquiryBtn').prop('disabled', false);
                    console.log(xhr);
                    toastr.error('There are some errors in the form. Please check your inputs.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        console.log(xhr.responseJSON.errors);
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value.join(', '));
                        });

                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] +
                                '-error').offset().top - 200
                        }, 500);
                    } else {
                        toastr.error('An unexpected error occurred. Please try again later.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
            });
        });
    </script>
@endsection
