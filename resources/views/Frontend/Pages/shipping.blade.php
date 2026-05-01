@extends('layouts.frontend')
@section('title')
    Shipping | Its Roop
@endsection
@section('content')
  
     <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Shipping</div>
        </div>
    </div>
    <!-- /page-title -->
    <!-- main-page -->
    <section class="flat-spacing-25">
        <div class="container">
            <div class="tf-main-area-page">
                <h4>Shipping Details & Fees</h4>
                <p>Our store is pleased to offer free standard shipping on orders over £150. Please see the table below for available delivery options.</p>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Delivery Options</th>
                                <th>Price (£)</th>
                                <th>Delivery Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard Delivery</td>
                                <td>FREE for all orders over £150</td>
                                <td>2 to 3 Business Days</td>
                            </tr>
                            <tr>
                                <td>Standard Delivery</td>
                                <td>£4.95</td>
                                <td>2 to 3 Business Days</td>
                            </tr>
                            <tr>
                                <td>Overnight Delivery Service</td>
                                <td>£14.95</td>
                                <td>Next business day*</td>
                            </tr>
                            <tr>
                                <td>Nominated Day Delivery</td>
                                <td>£9.95</td>
                                <td>Choose your delivery day**</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p>*Only for orders placed by 12:30 p.m. (local time) Monday to Thursday. Saturdays, Sundays and public holidays are not considered working days.</p>
                <p>Overnight orders placed on Fridays, Saturdays and Sundays will be delivered on the following Tuesday at the earliest.</p>
            </div>
        </div>
    </section>
    <!-- /main-page -->
@endsection
