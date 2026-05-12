@extends('layouts.admin')
@section('title')
    Testimonials
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Testimonials</h5>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-info">
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
                                        <th width="5">
                                            <h6 class="fs-3 fw-semibold mb-0">Index</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Customer Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Title</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Purchase item</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Photo</h6>
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
    <div id="deleteTestimonialModal" class="modal fade" tabindex="-1" aria-labelledby="deleteTestimonialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="deleteTestimonialModalLabel">Delete Testimonial
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Testimonial?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="#" method="POST" id="deleteTestimonialForm">
                        {{ method_field('DELETE') }}
                        @csrf
                        <input type="hidden" name="deleteTestimonialId" id="deleteTestimonialId">
                        <button type="submit" class="btn btn-danger" id="testideletesubmit-btn">
                            <span class="delete-spinner-span"></span> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateIndexModal" tabindex="-1" role="dialog" aria-labelledby="updateIndexModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateIndexModalLabel">Update Index</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateIndexForm">
                        @csrf
                        <input type="hidden" name="testimonial_id" id="testimonialId">
                        <div class="form-group mb-2">
                            <label for="index">Index</label>
                            <select class="form-control" id="indexDropdown" name="index">
                                @for ($i = 1; $i <= $max_index; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <span class="text-danger" id="index-error"></span>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <span class="spinner-span" role="status" aria-hidden="true"></span>
                                    <span class="save-btn-text">Save</span>
                                    &nbsp;
                                    <i class="ti ti-device-floppy"></i>
                                </button>
                            </div>
                        </div>
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
                    url: '{!! route('admin.testimonials.data') !!}',
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
                        name: 'testimonials.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'testimonials.id',
                        searchable: false
                    },
                    {
                        data: 'index',
                        name: 'testimonials.index',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'testimonials.name'
                    },
                    {
                        data: 'title',
                        name: 'testimonials.title'
                    },
                    {
                        data: 'purchase_item',
                        name: 'testimonials.purchase_item'
                    },
                    {
                        data: 'image',
                        name: 'testimonials.id',
                        render: function(data, type, full, meta) {
                            var imageUrl = '{{ asset(Storage::url(':filename')) }}';
                            return '<img src="' + imageUrl.replace(':filename', data) +
                                '" alt="image" class="img-fluid" style="max-width: 80px; max-height: 80px;">';
                        }
                    },
                    {
                        data: 'description',
                        name: 'testimonials.description'
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

            $('#updateIndexForm').on('submit', function(e) {
                e.preventDefault();
                $('#submit-btn').attr('disabled', true)
                $('.spinner-span').addClass('spinner-border spinner-border-sm')

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.testimonials.index.update') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm');

                        $('#testimonialId').val('');
                        $("#indexDropdown").val('');
                        $("#index-error").html('');
                        $('#datatable').DataTable().draw(false);
                        $('#updateIndexModal').modal('hide');

                        toastr.success('Index updated successfully.');
                    },
                    error: function(xhr) {
                        $('#submit-btn').attr('disabled', false);
                        $('.spinner-span').removeClass('spinner-border spinner-border-sm')
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });

                        if (xhr.responseJSON.status == "failed") {
                            toastr.error(xhr.responseJSON.message);
                        }
                    }
                });
            });

        });

        $(document).on('click', '.testimonialIndexBtn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var index = $(this).data('index');

            $('#updateIndexModal').modal('show');
            $('#testimonialId').val(id);
            $("#indexDropdown").val(index).val(index);
        });

        $(document).on('change', '.testimonial-status-switch', function(e) {
            e.preventDefault();
            var testimonial_id = $(this).data('testimonial_id');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.testimonials.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    testimonial_id: testimonial_id,
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

        $(document).on('click', '.testimonial-delete-btn', function(e) {
            var id = $(this).data('id');

            $('#deleteTestimonialId').val(id);
            $('#deleteTestimonialModal').modal('show');
        });

        $('#deleteTestimonialForm').submit(function(e) {
            e.preventDefault();

            $('#testideletesubmit-btn').attr('disabled', true);
            $('.delete-spinner-span').addClass('spinner-border spinner-border-sm');

            var deleteTestimonialId = $('#deleteTestimonialId').val();

            $.ajax({
                url: '/admin/testimonials/' + deleteTestimonialId,
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

                        $('#deleteTestimonialModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error('There was an error deleting the Testimonial.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }

                    $('#testideletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                },
                error: function(xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });

                    $('#testideletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                }
            });
        });
    </script>
@endsection
