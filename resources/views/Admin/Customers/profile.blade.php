@extends('layouts.admin')
@section('title')
    Customer Profile
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-lg-3 d-flex align-items-stretch">
                <div class="card w-100 position-relative overflow-hidden shadow-lg rounded-3 border-0"
                    style="height: 300px; display: flex; flex-direction: column; justify-content: center;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <img src="/backend/dist/images/profile/user-1.jpg" alt="User Profile"
                                class="img-fluid rounded-circle border border-3 border-primary shadow" width="80"
                                height="80">
                        </div>
                        <h5 class="fw-bold mb-1 text-primary">{{ $user->full_name }}</h5>
                        <p class="text-muted mb-2"><i class="ti ti-mail me-2"></i> {{ $user->email }}</p>
                        <p class="text-muted"><i class="ti ti-phone me-2"></i> {{ $user->mobile }}</p>
                        <b> {{ toCurrency($total_order_amount) }}</b>

                        <hr class="my-3">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="mailto:{{ $user->email }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-mail"></i> Email
                            </a>
                            <a href="tel:{{ $user->mobile }}" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-phone"></i> Call
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab"
                    role="tablist">
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="orders-tab" data-bs-toggle="pill" data-bs-target="#ordersTable" type="button"
                            role="tab" aria-controls="orders" aria-selected="true">
                            <i class="ti ti-brand-shopee me-2 fs-6"></i>
                            <span class="d-none d-md-block">Orders ({{ $total_orders }})</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="cart-tab" data-bs-toggle="pill" data-bs-target="#cartTable" type="button" role="tab"
                            aria-controls="cart" aria-selected="false">
                            <i class="ti ti-shopping-cart me-2 fs-6"></i>
                            <span class="d-none d-md-block">Cart ({{ $total_cart_products }})</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="wishlist-tab" data-bs-toggle="pill" data-bs-target="#wishlistTable" type="button"
                            role="tab" aria-controls="wishlist" aria-selected="false">
                            <i class="ti ti-heart me-2 fs-6"></i>
                            <span class="d-none d-md-block">Wishlist ({{ $total_wishlist_products }})</span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="ordersTable" role="tabpanel" aria-labelledby="orders-tab"
                        tabindex="0">
                        <div class="table-responsive rounded-2 mb-4">
                            <div class="card-body p-4">
                                <table class="table border table-bordered text-nowrap mb-0 align-middle"
                                    id="orderDatatable">
                                    <thead class="text-dark fs-3">
                                        <tr>
                                            <th width="3%">
                                                <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                            </th>
                                            <th>
                                                <h6 class="fs-3 fw-semibold mb-0">Payment Status</h6>
                                            </th>
                                            <th>
                                                <h6 class="fs-3 fw-semibold mb-0">Address</h6>
                                            </th>
                                            <th>
                                                <h6 class="fs-3 fw-semibold mb-0">Amount</h6>
                                            </th>
                                            <th>
                                                <h6 class="fs-3 fw-semibold mb-0">Products</h6>
                                            </th>
                                            <th>
                                                <h6 class="fs-3 fw-semibold mb-0">Date</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="cartTable" role="tabpanel" aria-labelledby="cart-tab" tabindex="0">
                        <div class="table-responsive rounded-2 mb-4">
                            <div class="card-body p-4">
                                <div class="table-responsive rounded-2 mb-4">
                                    <table class="table border table-bordered text-nowrap mb-0 align-middle"
                                        id="cartDatatable">
                                        <thead class="text-dark fs-3">
                                            <tr>
                                                <th width="3%">
                                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Products</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="wishlistTable" role="tabpanel" aria-labelledby="wishlist-tab"
                        tabindex="0">
                        <div class="table-responsive rounded-2 mb-4">
                            <div class="card-body p-4">
                                <div class="table-responsive rounded-2 mb-4">
                                    <table class="table border table-bordered text-nowrap mb-0 align-middle"
                                        id="wishlistDatatable">
                                        <thead class="text-dark fs-3">
                                            <tr>
                                                <th width="3%">
                                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Products</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        // Orders Datatable
        $(document).ready(function() {
            drawOrderDatatable();
            drawCartDatatable();
            drawWishlistDatatable();
        });

        function drawOrderDatatable() {
            var dataTable = $('#orderDatatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {
                    url: '{!! route('admin.orders.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.user_id = '{{ $user->id }}';
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'payment_status',
                        name: 'orders.payment_status'
                    },
                    {
                        data: 'address',
                        name: 'orders.address_id'
                    },
                    {
                        data: 'total_amount',
                        name: 'orders.total_amount'
                    },
                    {
                        data: 'order_products',
                        name: 'orders.id'
                    },
                    {
                        data: 'created_at',
                        name: 'orders.created_at'
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 4],
                    className: "text-center"
                }]
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

        }

        function drawCartDatatable() {
            var dataTable = $('#cartDatatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {
                    url: '{!! route('admin.carts.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'products',
                        name: 'products'
                    }
                ],
                order: [],
                columnDefs: [{
                    targets: [0],
                    className: "text-center"
                }, ],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                $('#cartDatatable').DataTable().columns.adjust().draw();
            });
        }

        function drawWishlistDatatable() {
            var dataTable = $('#wishlistDatatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {
                    url: '{!! route('admin.wishlists.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'products',
                        name: 'wishlists.id'
                    }
                ],
                order: [],
                columnDefs: [{
                    targets: [0],
                    className: "text-center"
                }, ],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                $('#wishlistDatatable').DataTable().columns.adjust().draw();
            });
        }
    </script>
@endsection
