@extends('layouts.admin')
@section('title')
    Reviews
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Reviews</h5>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.reviews.create') }}" class="btn btn-info">
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
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Product</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Customer Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Title</h6>
                                        </th>
                                        <th width="30">
                                            <h6 class="fs-3 fw-semibold mb-0">Description</h6>
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
    <div id="deleteReviewModal" class="modal fade" tabindex="-1" aria-labelledby="deleteReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="deleteReviewModalLabel">Delete Review
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Review?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="#" method="POST" id="deleteReviewForm">
                        {{ method_field('DELETE') }}
                        @csrf
                        <input type="hidden" name="deleteReviewId" id="deleteReviewId">
                        <button type="submit" class="btn btn-danger" id="reviewdeletesubmit-btn">
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
                    url: '{!! route('admin.reviews.data') !!}',
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
                        name: 'reviews.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'reviews.id',
                        searchable: false
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'title',
                        name: 'reviews.title'
                    },
                    {
                        data: 'description',
                        name: 'reviews.description'
                    },

                ],
                order: [],
                columnDefs: [{
                    targets: [0, 2],
                    className: "text-center"
                }, ],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");
        });

        $(document).on('change', '.review-status-switch', function(e) {
            e.preventDefault();
            var review_id = $(this).data('review_id');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.reviews.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    review_id: review_id,
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
                            $('#datatable').DataTable().draw();
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

        $(document).on('click', '.review-delete-btn', function(e) {
            var id = $(this).data('id');

            $('#deleteReviewId').val(id);
            $('#deleteReviewModal').modal('show');
        });

        $('#deleteReviewForm').submit(function(e) {
            e.preventDefault();

            $('#reviewdeletesubmit-btn').attr('disabled', true);
            $('.delete-spinner-span').addClass('spinner-border spinner-border-sm');

            var deleteReviewId = $('#deleteReviewId').val();

            $.ajax({
                url: '/admin/reviews/' + deleteReviewId,
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

                        $('#deleteReviewModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error('There was an error deleting the Review.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }

                    $('#reviewdeletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                },
                error: function(xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });

                    $('#reviewdeletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                }
            });
        });
    </script>
@endsection
