@extends('layouts.admin')
@section('title')
    Coupon Codes
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Coupon Codes</h5>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.coupon-codes.create') }}" class="btn btn-info">
                                    Create
                                    &nbsp;
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm w-100 text-nowrap mb-0 align-middle"
                                id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Coupon Code</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Percentage</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Min Amount</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Start Date</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">End Date</h6>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="deleteCouponModal" class="modal fade" tabindex="-1" aria-labelledby="deleteCouponModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="deleteCouponModalLabel">Delete Coupon Code
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Coupon Code?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="#" method="POST" id="deleteCouponForm">
                        {{ method_field('DELETE') }}
                        @csrf
                        <input type="hidden" name="deleteCouponId" id="deleteCouponId">
                        <button type="submit" class="btn btn-danger" id="coupondeletesubmit-btn">
                            <span class="delete-spinner-span"></span> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            var dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                ajax: {
                    url: '{!! route('admin.coupon-codes.data') !!}',
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
                        data: 'action',
                        name: 'coupon_codes.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'coupon_codes.id',
                        searchable: false
                    },
                    {
                        data: 'coupon_code',
                        name: 'coupon_codes.coupon_code'
                    },
                    {
                        data: 'percentage',
                        name: 'coupon_codes.percentage'
                    },
                    {
                        data: 'minimum_order_amount',
                        name: 'coupon_codes.minimum_order_amount'
                    },
                    {
                        data: 'start_date',
                        name: 'coupon_codes.start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'coupon_codes.end_date'
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 2, 4, 5],
                    className: "text-center"
                }, ],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");
        });

        $(document).on('change', '.couponcode-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.coupon-codes.change.status') }}",
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

        $(document).on('click', '.couponcode-delete-btn', function(e) {
            var id = $(this).data('id');

            $('#deleteCouponId').val(id);
            $('#deleteCouponModal').modal('show');
        });

        $('#deleteCouponForm').submit(function(e) {
            e.preventDefault();

            $('#coupondeletesubmit-btn').attr('disabled', true);
            $('.delete-spinner-span').addClass('spinner-border spinner-border-sm');

            var deleteCouponId = $('#deleteCouponId').val();

            $.ajax({
                url: '/admin/coupon-codes/' + deleteCouponId,
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });

                        $('#deleteCouponModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error('There was an error deleting the Coupon Code.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }

                    $('#coupondeletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                },
                error: function(xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });

                    $('#coupondeletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                }
            });
        });
    </script>
@endsection
