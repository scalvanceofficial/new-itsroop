@extends('layouts.admin')
@section('title')
    Wishlist Products
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Wishlists</h5>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <a href="#" class="btn btn-primary d-flex align-items-center gap-1" id="exportWishlist">
                                    <i class="fas fa-file-excel"></i>
                                    <span>Export</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border table-bordered text-nowrap mb-0 align-middle" id="datatable">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th width="3%">
                                        <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-3 fw-semibold mb-0">Customer</h6>
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
    </section>
    <script type="text/javascript">
        $(function() {
            var dataTable = $('#datatable').DataTable({
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
                        data: 'customer',
                        name: 'wishlists.user_id'
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
        });

        $(document).ready(function() {
            // *** EXPORT wishlist *** //
            $('#exportWishlist').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/wishlists/export/excel",
                    type: "POST",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        const url = window.URL.createObjectURL(new Blob([data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'wishlist-products.xlsx');
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error exporting file:', error);
                    }
                });
            });

        });
    </script>
@endsection
