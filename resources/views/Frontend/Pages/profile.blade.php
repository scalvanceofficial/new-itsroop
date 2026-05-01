@extends('layouts.frontend')
@section('title')
    Profile | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Profile Details</div>
        </div>
    </div>
    <!-- /page-title -->

    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('layouts.frontend.my-account-sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-edit">
                        <div class="">
                            <form id="editProfileForm" action="{{ route('frontend.profile.update') }}" method="POST">
                                @csrf
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder="" type="text" id="first_name"
                                        name="first_name" value="{{ isset($user) ? $user->first_name : '' }}">
                                    <label class="tf-field-label fw-4 text_black-2" for="first_name">First name</label>
                                    <span id="first_name_error" style="color:red;"></span>
                                </div>
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text" id="last_name"
                                        name="last_name" value="{{ isset($user) ? $user->last_name : '' }}">
                                    <label class="tf-field-label fw-4 text_black-2" for="last_name">Last name</label>
                                    <span id="last_name_error" style="color:red;"></span>
                                </div>
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="email" id="email"
                                        name="email" value="{{ isset($user) ? $user->email : '' }}" disabled>
                                    <label class="tf-field-label fw-4 text_black-2" for="email">Email</label>
                                </div>
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="email" id="mobile"
                                        name="mobile" value="{{ isset($user) ? $user->mobile : '' }}" disabled>
                                    <label class="tf-field-label fw-4 text_black-2" for="mobile">Mobile</label>
                                </div>
                                <div class="mb_20">
                                    <button type="submit"
                                        class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">Save
                                        Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page-cart -->

    <script>
        $('#editProfileForm').submit(function(e) {
            e.preventDefault();
            $('#first_name_error').empty();
            $('#last_name_error').empty();

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
                        toastr.success(
                            data.message,
                            '', {
                                showMethod: "slideDown",
                                timeOut: 1000,
                                closeButton: true,
                            });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '_error').html(value);
                    });
                }
            });
        });
    </script>
@endsection
