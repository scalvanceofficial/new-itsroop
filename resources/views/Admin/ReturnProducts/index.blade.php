@extends('layouts.admin')
@section('title')
    Return Product
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-3 d-flex align-items-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Return Product</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end gap-3 align-items-end">
                                    <div class="d-flex flex-column">
                                        <small class="d-block mb-1">From Date</small>
                                        <input type="date" name="from_date" class="form-control form-control-sm w-auto"
                                            id="from_date" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small class="d-block mb-1">To Date</small>
                                        <input type="date" name="to_date" class="form-control form-control-sm w-auto"
                                            id="to_date" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end gap-2 align-items-end">
                                <a href="#" class="btn btn-sm btn-primary d-flex align-items-center gap-2"
                                    id="exportReturnProduct">
                                    <i class="fas fa-file-excel"></i>
                                    <span>Export</span>
                                </a>
                                <a href="{{ route('admin.return-products.create') }}"
                                    class="btn btn-sm btn-info d-flex align-items-center gap-1">
                                    <span>Create</span>
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>


                    </div>
                    <div class="card-body p-4">
                        <div class="col-12 justify-content-end">
                            <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 rounded-2" id="pills-tab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                        id="PENDING-tab" data-bs-toggle="pill" data-bs-target="#pendingTable" type="button"
                                        role="tab" aria-controls="pending" aria-selected="true">
                                        <i class="fas fa-clock me-2 fs-3"></i>
                                        <span class="d-none d-md-block">PENDING</span>
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                        id="COMPLETED-tab" data-bs-toggle="pill" data-bs-target="#completedTable"
                                        type="button" role="tab" aria-controls="completed" aria-selected="false">
                                        <i class="fas fa-check-circle me-2 fs-3"></i>
                                        <span class="d-none d-md-block">COMPLETED</span>
                                    </button>
                                </li>

                            </ul>
                        </div>
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered text-nowrap mb-0 align-middle" id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status Log</h6>
                                        </th>
                                        <th width="4%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Date</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Return Number</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Transaction ID</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Product Received Remark</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Settlement Date </h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Order Number</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Customer</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Return Product</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Product Model</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Return Quantity</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Refund Amount</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Remark </h6>
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
    <div class="modal fade text-left" id="statusChangeModal" tabindex="-1" role="dialog"
        aria-labelledby="statusChangeModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <form id="statusChangeForm" action="{{ route('admin.return-product.status.update') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="hidden" id="routeKey" name="route_key">
                                <input type="hidden" id="id" name="id">
                                <select class="form-select" id="status" name="status">
                                    <option value="">Select Status</option>
                                    <option value="RETURN_IN_PROGRESS">Return in progress</option>
                                    <option value="PRODUCT_RECEIVED">Product received</option>
                                    <option value="REFUND_INITIATE">Refund initiated</option>
                                    <option value="REFUND_PROCESSED">Refund processced and pending for settlement</option>
                                    <option value="REFUND_COMPLETED">Refund completed</option>
                                </select>
                                <div class="col-md-12 mt-3 d-none" id="transactionIdWrapper">
                                    <fieldset class="form-group">
                                        <label for="transaction_id" class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" id="transaction_id"
                                            name="transaction_id">
                                        <div id="transaction_id-error" style="color:red"></div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 mt-3 d-none" id="productReceivedRemark">
                                    <fieldset class="form-group">
                                        <label for="product_received_remark" class="form-label">Remark</label>
                                        <input type="text" class="form-control" id="product_received_remark"
                                            name="product_received_remark">
                                        <div id="product_received_remark-error" style="color:red"></div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 mt-3 d-none" id="settlementDate">
                                    <fieldset class="form-group">
                                        <label for="settlement_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="settlement_date"
                                            name="settlement_date">
                                        <div id="settlement_date-error" style="color:red"></div>
                                    </fieldset>
                                </div>
                                <div id="status-error" style="color:red"></div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary status-submit">Save</button>
                        <img id="ajax-loader" class="Loader" style="width: 6%; display: none !important;"
                            src="https://i.gifer.com/ZC9Y.gif" alt="">
                    </div>
                </div>
            </form>
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
                    url: '{!! route('admin.return-product.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#pills-tab .nav-link.active').attr('id')?.replace('-tab', '');
                        console.log(d.status);
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
                        name: 'return_products.id',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'return_products.status'
                    },
                    {
                        data: 'created_at',
                        name: 'return_products.created_at'
                    },
                    {
                        data: 'return_number',
                        name: 'return_products.return_number'
                    },
                    {
                        data: 'transaction_id',
                        name: 'return_products.transaction_id'
                    },
                    {
                        data: 'product_received_remark',
                        name: 'return_products.product_received_remark'
                    },
                    {
                        data: 'settlement_date',
                        name: 'return_products.settlement_date'
                    },
                    {
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'return_product_name',
                        name: 'return_products.return_product_name'
                    },
                    {
                        data: 'product_model',
                        name: 'return_products.product_model'
                    },
                    {
                        data: 'return_quantity',
                        name: 'return_products.return_quantity'
                    },
                    {
                        data: 'return_price',
                        name: 'return_products.return_price'
                    },
                    {
                        data: 'remark',
                        name: 'return_products.remark'
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 6, 7, 8, 9],
                    className: "text-center"
                }, ],
            });

            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
                setTimeout(function() {
                    dataTable.ajax.reload();
                }, 50);
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            $('#from_date, #to_date').on('change', function() {
                dataTable.draw();
            });
        });

        $(document).on('click', '.return-product-status-switch', function() {
            var id = $(this).data('id');
            var routeKey = $(this).data('routekey');

            $('#id').val(id);
            $('#routeKey').val(routeKey);
        });

        $('#statusChangeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.status-submit').hide();
                    $('#ajax-loader').show();
                },
                complete: function() {
                    $('.status-submit').show();
                    $('#ajax-loader').hide();
                },
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message);
                        $('#statusChangeModal').modal('hide');
                        if ($.fn.DataTable.isDataTable("#datatable")) {
                            $('#datatable').DataTable().draw(false);
                        }
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while changing the status.');
                    }
                }
            });
        });

        $(document).ready(function() {
            // *** EXPORT order *** //
            $('#exportReturnProduct').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/return-products/export/excel",
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
                        link.setAttribute('download', 'return-products.xlsx');
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
    <script>
        $('#status').on('change', function() {
            const status = $(this).val();

            $('#transactionIdWrapper, #productReceivedRemark, #settlementDate').addClass('d-none');

            if (status === 'REFUND_PROCESSED') {
                $('#transactionIdWrapper').removeClass('d-none');
                $('#settlementDate').removeClass('d-none');
            } else if (status === 'PRODUCT_RECEIVED') {
                $('#productReceivedRemark').removeClass('d-none');
            }
        });

        $(document).on('click', '.return-product-status-switch', function() {
            $('#id').val($(this).data('id'));
            $('#routeKey').val($(this).data('routekey'));
            $('#status').val($(this).data('status'));
            $('#transaction_id').val($(this).data('transaction-id') || '');
            $('#settlement_date').val($(this).data('settlement-date') || '');
            $('#product_received_remark').val($(this).data('product-received-remark') || '');
        });

        $('#statusChangeModal').on('shown.bs.modal', function() {
            $('#status').trigger('change');
        });
    </script>
@endsection
