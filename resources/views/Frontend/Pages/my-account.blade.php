@extends('layouts.frontend')
@section('title')
    My Account | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title bg_green-1">
        <div class="container-full">
            <div class="heading text-center text_white-2">My Account</div>
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
                    <div class="my-account-content account-dashboard">
                        <div class="mb_60">
                            <h5 class="fw-5 mb_20">Hello Themesflat</h5>
                            <p>
                                From your account dashboard you can view your <a class="text_primary" href="#">recent
                                    orders</a>, manage your <a class="text_primary" href="#">shipping and billing
                                    address</a>, and <a class="text_primary" href="#">edit your password and account
                                    details</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
