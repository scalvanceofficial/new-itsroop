@extends('layouts.admin')
@section('title')
    Product
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-5 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Products</h5>
                            </div>
                            <div class="col-md-4 d-flex justify-content-end gap-2">
                                <fieldset class="form-group">
                                    <select class="form-control " name="category_id" id="categoryId">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>

                                <fieldset class="form-group">
                                    <select class="form-control" name="sub_category_id" id="subCategoryId">
                                        <option value="">Select Sub Category</option>
                                        @foreach ($sub_categories as $sub_category)
                                            <option value="{{ $sub_category->id }}">{{ $sub_category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-3 d-flex justify-content-end gap-2">
                                <a href="{{ route('export.products') }}" class="btn btn-primary">
                                    <i class="fas fa-file-excel"></i>
                                    <span>Export</span>
                                </a>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-info">
                                    Create&nbsp;<i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered  text-nowrap mb-0 align-middle" id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Actions</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Featured</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Index</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Category Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Sub Category Name</h6>
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
    </section>

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
                        <input type="hidden" name="product_id" id="productId">
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
                                <button type="submit" class="btn btn-primary ">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteProductModal" class="modal fade" tabindex="-1" aria-labelledby="deleteProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="deleteProductModalLabel">Delete Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Product?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="#" method="POST" id="deleteProductForm">
                        {{ method_field('DELETE') }}
                        @csrf
                        <input type="hidden" name="deleteProductId" id="deleteProductId">
                        <button type="submit" class="btn btn-danger" id="deletesubmit-btn">
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
                scrollX: true,
                ajax: {
                    url: '{!! route('admin.products.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.category_id = $('#categoryId').val();
                        d.sub_category_id = $('#subCategoryId').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'products.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'products.status',
                        searchable: false
                    },
                    {
                        data: 'featured',
                        name: 'products.featured'
                    },
                    {
                        data: 'index',
                        name: 'products.index'
                    },
                    {
                        data: 'category_ids',
                        name: 'products.category_ids'
                    },
                    {
                        data: 'sub_category_ids',
                        name: 'products.sub_category_ids'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4],
                    className: "text-center"
                }, ],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");
        });
        $('#categoryId').on('change', function() {
            $('#datatable').DataTable().ajax.reload();
        });
        $('#subCategoryId').on('change', function() {
            $('#datatable').DataTable().ajax.reload();
        });



        $(document).ready(function(e) {
            $('#updateIndexForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.products.index.update') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#sliderId').val('');
                        $("#indexDropdown").val('');
                        $("#index-error").html('');
                        $('#datatable').DataTable().draw(false);
                        $('#updateIndexModal').modal('hide');

                        toastr.success('Index updated successfully.');
                    },
                    error: function(xhr) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });

                        if (xhr.responseJSON.status == "failed") {
                            toastr.error(xhr.responseJSON.message);
                        }
                    }
                });
            });
        })

        $(document).on('click', '.productIndexBtn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var index = $(this).data('index');

            $('#updateIndexModal').modal('show');
            $('#productId').val(id);
            $("#indexDropdown").val(index).val(index);
        });

        $(document).on('change', '.product-status-switch, .product-featured-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var column = $(this).data('column');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

            $.ajax({
                url: '{!! route('admin.products.change.status') !!}',
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    route_key: routeKey,
                    status: status,
                    column: column
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


        $(document).on('click', '.product-delete-btn', function(e) {
            var id = $(this).data('id');

            $('#deleteProductId').val(id);
            $('#deleteProductModal').modal('show');
        });

        $('#deleteProductForm').submit(function(e) {
            e.preventDefault();

            $('#deletesubmit-btn').attr('disabled', true);
            $('.delete-spinner-span').addClass('spinner-border spinner-border-sm');

            var deleteProductId = $('#deleteProductId').val();

            $.ajax({
                url: '/admin/products/' + deleteProductId,
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

                        $('#deleteProductModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error('There was an error deleting the Product.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }

                    $('#deletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                },
                error: function(xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });

                    $('#deletesubmit-btn').attr('disabled', false);
                    $('.delete-spinner-span').removeClass('spinner-border spinner-border-sm');
                }
            });
        });
    </script>
@endsection
