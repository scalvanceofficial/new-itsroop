@extends('layouts.frontend')
@section('title')
    Orders | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">My Orders</div>
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
                    <div class="my-account-content account-order">
                        <div class="wrap-account-order">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="fw-6">Sr No</th>
                                            <th class="fw-6">Order Number</th>
                                            <th class="fw-6">Date</th>
                                            <th class="fw-6">Status</th>
                                            <th class="fw-6">Estimate Delivery On</th>
                                            <th class="fw-6">Total</th>
                                            <th class="fw-6">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($orders->isNotEmpty())
                                            @php $i = 1; @endphp
                                            @foreach ($orders as $order)
                                                <tr class="tf-order-item">
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('frontend.orders.details', $order->id) }}">
                                                            {{ $order->order_number }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ toIndianDateTime($order->created_at) }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="tf-order-status tf-order-status-processing">{{ $order->shiprocket_status == 'NEW' ? 'Order Placed' : $order->shiprocket_status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ toIndianDate($order->created_at->addDays(10)) }}
                                                    </td>
                                                    <td>
                                                        {{ toCurrency($order->total_amount) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('frontend.orders.details', $order->route_key) }}"
                                                            class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">
                                                            <span>View</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    <h6 class="text_green-1">No orders found!</h6>
                                                    <p>Looks like you haven't placed any orders yet. Start shopping now!</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page-cart -->


    <div class="btn-sidebar-account">
        <button data-bs-toggle="offcanvas" data-bs-target="#mbAccount" aria-controls="offcanvas"><i
                class="icon icon-sidebar-2"></i></button>
    </div>
@endsection
