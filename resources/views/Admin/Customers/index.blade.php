@extends('layouts.admin')
@section('title')
    Customers
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Customer</h5>
                            </div>

                            <div class="col-md-8">
                                <div class="d-flex justify-content-end gap-2 align-items-end">
                                    <div class="d-flex flex-column">
                                        <small class="d-block mb-1">From Date</small>
                                        <input type="date" name="from_date" class="form-control w-auto" id="from_date" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small class="d-block mb-1">To Date</small>
                                        <input type="date" name="to_date" class="form-control w-auto" id="to_date" />
                                    </div>

                                    <a href="#" class="btn btn-primary d-flex align-items-center gap-1"
                                        id="exportCustomer">
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
                                            <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Email</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Register</h6>
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
                    url: '{!! route('admin.customers.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'users.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'users.status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'full_name',
                        name: 'users.id',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'email',
                        name: 'users.email',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'mobile',
                        name: 'users.mobile',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'users.created_at',
                        orderable: false,
                        searchable: true
                    }
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2],
                    className: "text-center"
                }, ],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            $('#from_date, #to_date').on('change', function() {
                dataTable.draw();
            });

        });


        $(document).on('change', '.customer-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.customers.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    route_key: routeKey,
                    status: status
                },
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        if ($.fn.DataTable.isDataTable("#datatable")) {
                            $('#datatable').DataTable().draw(false);
                        }
                    } else {
                        toastr.error(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(data) {
                    toastr.error('Something went wrong!');
                }
            });
        });



        $(document).ready(function() {
            // *** EXPORT customer *** //
            $('#exportCustomer').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/customers/export/excel",
                    type: "POST",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val()
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        const url = window.URL.createObjectURL(new Blob([data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'customers.xlsx');
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
