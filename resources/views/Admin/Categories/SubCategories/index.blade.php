@extends('layouts.admin')
@section('title')
    Sub Category
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-3">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="col-12 d-flex justify-content-start mb-5">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                Back
                                &nbsp;
                                <i class="ti ti-arrow-back-up-double"></i>
                            </a>
                        </div>
                        <div class="ms-auto align-self-start">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <!-- Left side: Service Category name -->
                                    <div class="ms-3 align-self-center">
                                        <h4 class="mb-0 fs-5">Category</h4>
                                        <span class="text-muted">{{ $category->name }}</span>
                                    </div>
                                    <!-- Right side: Service Icon (Gear Icon) -->
                                    <div class="ms-auto align-self-center">
                                        <i class="fas fa-tools  fs-4 text-primary"></i> <!-- Gear Icon for Service -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-9">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Sub Categories</h5>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.categories.sub_categories.create', ['category' => $category->id]) }}"
                                    class="btn btn-info">
                                    Create
                                    &nbsp;
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle"
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
                                            <h6 class="fs-3 fw-semibold mb-0">Show in Navbar</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    <div id="deleteSubCategoryModal" class="modal fade" tabindex="-1" aria-labelledby="deleteSubCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="deleteSubCategoryModalLabel">Delete Sub Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Sub Category?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="#" method="POST" id="deleteSubCategoryForm">
                        {{ method_field('DELETE') }}
                        @csrf
                        <input type="hidden" name="deleteSubCategoryId" id="deleteSubCategoryId">
                        <button type="submit" class="btn btn-danger" id="subcatdeletesubmit-btn">
                            <span class="delete-spinner-span"></span> Delete
                        </button>
                    </form>
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
                scrollX: false,
                ajax: {
                    url: '{{ route('admin.categories.sub_categories.data', ['category' => $category->route_key]) }}',
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
                        name: 'sub_categories.id',
                        searchable: false
                    },
                    {
                        data: 'show_in_navbar',
                        name: 'sub_categories.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'sub_categories.id',
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'sub_categories.name'
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0],
                    className: "text-center"
                }],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            // Status / show_in_navbar switch
            $(document).on('change', '.sub_category-status-switch, .sub_category-show_in_navbar-switch', function(e) {
                e.preventDefault();
                var id     = $(this).data('id');
                var column = $(this).data('column') || 'status';
                var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
                $.ajax({
                    url: "{{ route('admin.categories.sub_categories.change.status', ['category' => $category]) }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        column: column,
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
                    error: function() {
                        toastr.error('Something went wrong!');
                    }
                });
            });

            // Delete button — open modal
            $(document).on('click', '.subcategory-delete-btn', function(e) {
                var id = $(this).data('id');
                $('#deleteSubCategoryId').val(id);
                $('#deleteSubCategoryModal').modal('show');
            });

            // Delete form submit
            $('#deleteSubCategoryForm').submit(function(e) {
                e.preventDefault();

                $('#subcatdeletesubmit-btn').attr('disabled', true);
                $('.delete-spinner-span').addClass('spinner-border spinner-border-sm');

                var deleteSubCategoryId = $('#deleteSubCategoryId').val();

                $.ajax({
                    url: '/admin/categories/{{ $category->route_key }}/sub_categories/' + deleteSubCategoryId,
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
                            $('#deleteSubCategoryModal').modal('hide');
                            $('#datatable').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error('There was an error deleting the Sub Category.', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                        $('#subcatdeletesubmit-btn').attr('disabled', false);
                        $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                    },
                    error: function() {
                        toastr.error('Something went wrong. Please try again.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        $('#subcatdeletesubmit-btn').attr('disabled', false);
                        $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                    }
                });
            });
        });
    </script>
@endsection
