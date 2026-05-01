@extends('layouts.admin')
@section('title')
  Orders
@endsection
@section('content')
  <section>
    <div class="row">
      <div class="col-12">
        <div class="card w-100 position-relative overflow-hidden">
          <div class="card-header px-4 py-3 border-bottom">
            <div class="row align-items-center mb-3">
              <div class="col-md-4">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Orders</h5>
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

                  <a href="#" class="btn btn-primary d-flex align-items-center gap-1" id="exportOrder">
                    <i class="fas fa-file-excel"></i>
                    <span>Export</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body p-4">
            <div class="col-12 justify-content-end">
              <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 rounded-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="NEW-tab" data-bs-toggle="pill" data-bs-target="#newTable" type="button" role="tab"
                    aria-controls="new" aria-selected="true">
                    <i class="fa fa-box me-2 fs-3"></i>
                    <span class="d-none d-md-block">New ({{ $counts['NEW'] }})</span>
                  </button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="SHIPPED-tab" data-bs-toggle="pill" data-bs-target="#shippedTable" type="button" role="tab"
                    aria-controls="shipped" aria-selected="false">
                    <i class="fa fa-truck me-2 fs-3"></i>
                    <span class="d-none d-md-block">Shipped ({{ $counts['SHIPPED'] }})</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="DELIVERED-tab" data-bs-toggle="pill" data-bs-target="#deliveredTable" type="button" role="tab"
                    aria-controls="delivered" aria-selected="false">
                    <i class="fa fa-check-circle me-2 fs-3"></i>
                    <span class="d-none d-md-block">Delivered ({{ $counts['DELIVERED'] }})</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="Cancelled-tab" data-bs-toggle="pill" data-bs-target="#cancelledTable" type="button" role="tab"
                    aria-controls="cancelled" aria-selected="false">
                    <i class="fa fa-times me-2 fs-3"></i>
                    <span class="d-none d-md-block">Cancelled ({{ $counts['Cancelled'] }})</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="OTHER-tab" data-bs-toggle="pill" data-bs-target="#otherTable" type="button" role="tab"
                    aria-controls="other" aria-selected="false">
                    <i class="fa fa-ellipsis-h me-2 fs-3"></i> <span class="d-none d-md-block">Other ({{ $counts['OTHER'] }})</span>
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
                    <th width="4%">
                      <h6 class="fs-3 fw-semibold mb-0">Payment Status</h6>
                    </th>
                    <th>
                      <h6 class="fs-3 fw-semibold mb-0">Invoice</h6>
                    </th>
                    <th>
                      <h6 class="fs-3 fw-semibold mb-0">Shiprocket</h6>
                    </th>
                    <th>
                      <h6 class="fs-3 fw-semibold mb-0">Order Status</h6>
                    </th>
                    <th>
                      <h6 class="fs-3 fw-semibold mb-0">Order Number</h6>
                    </th>
                    <th>
                      <h6 class="fs-3 fw-semibold mb-0">Tracking Number</h6>
                    </th>
                    <th>
                      <h6 class="fs-3 fw-semibold mb-0">Customer</h6>
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
      </div>
    </div>
  </section>

  {{-- create  --}}
  <div class="modal fade" id="shiprocketOrderModal" tabindex="-1" role="dialog" aria-labelledby="shiprocketOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shiprocketOrderModalLabel">Add Tracking Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="shiprocketOrderForm">
            @csrf
            <input type="hidden" name="order_id" id="order_id">
            <input type="hidden" name="status" id="tracking_create_status">
            <input type="hidden" name="estimated_delivery_date" id="tracking_create_date">

            <div class="form-group mb-2">
              <label for="courier_name">Courier Name</label>
              <input type="text" name="courier_name" class="form-control" id="td_courier_name" placeholder="Enter courier name">
            </div>

            <div class="form-group mb-2">
              <label for="tracking_number">Tracking Number <sup>*</sup></label>
              <input type="text" name="tracking_number" class="form-control" id="td_tracking_number" placeholder="Enter tracking number" required>
            </div>

            <div class="form-group mb-2">
              <label for="tracking_url">Tracking URL</label>
              <input type="url" name="tracking_url" class="form-control" id="td_tracking_url" placeholder="Enter tracking URL">
            </div>

            <div class="row">
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- update --}}
  <div class="modal fade" id="shiprocketUpdateOrderModal" tabindex="-1" role="dialog" aria-labelledby="shiprocketUpdateOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shiprocketUpdateOrderModalLabel">Create Shiprocket Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="shiprocketUpdateOrderForm">
            @csrf
            <input type="hidden" name="update_order_id" id="update_order_id">

            <div class="form-group mb-2">
              <label for="length">Length (cm)</label>
              <input type="text" name="length" class="form-control" id="length" placeholder="Enter length">
              <span class="text-danger" id="length-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="breadth">Breadth (cm)</label>
              <input type="text" name="breadth" class="form-control" id="breadth" placeholder="Enter breadth">
              <span class="text-danger" id="breadth-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="height">Height (cm)</label>
              <input type="text" name="height" class="form-control" id="height" placeholder="Enter height">
              <span class="text-danger" id="height-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="weight">Weight (kg)</label>
              <input type="text" name="weight" class="form-control" id="weight" placeholder="Enter weight">
              <span class="text-danger" id="weight-error"></span>
            </div>

            <div class="row">
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="trackingUpdateOrderModal" tabindex="-1" role="dialog" aria-labelledby="trackingUpdateOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="trackingUpdateOrderModalLabel">Update Order Tracking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="trackingUpdateOrderForm">
            @csrf
            <input type="hidden" name="order_id" id="tracking_update_order_id">

            <div class="form-group mb-2">
              <label for="tracking_status">Order Status <sup>*</sup></label>
              <select name="status" class="form-select mb-2" id="tracking_status" required>
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Packed">Packed</option>
                <option value="Shipped">Shipped</option>
                <option value="Out for Delivery">Out for Delivery</option>
                <option value="Delivered">Delivered</option>
              </select>
              <span class="text-danger" id="status-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="courier_name">Courier Name</label>
              <input type="text" name="courier_name" class="form-control" id="tracking_courier" placeholder="Enter courier name">
              <span class="text-danger" id="courier_name-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="tracking_number">Tracking Number</label>
              <input type="text" name="tracking_number" class="form-control" id="tracking_num" placeholder="Enter tracking number">
              <span class="text-danger" id="tracking_number-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="tracking_url">Tracking URL</label>
              <input type="url" name="tracking_url" class="form-control" id="tracking_url" placeholder="Enter tracking URL">
              <span class="text-danger" id="tracking_url-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="estimated_delivery_date">Estimated Delivery Date</label>
              <input type="date" name="estimated_delivery_date" class="form-control" id="tracking_date">
              <span class="text-danger" id="estimated_delivery_date-error"></span>
            </div>

            <div class="form-group mb-2">
              <label for="note">Note (Optional)</label>
              <textarea name="note" class="form-control" id="tracking_note" placeholder="Enter tracking note" rows="2"></textarea>
              <span class="text-danger" id="note-error"></span>
            </div>

            <div class="row">
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="shiprocketOrderTrackingModal" tabindex="-1" role="dialog" aria-labelledby="shiprocketOrderTrackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shiprocketOrderTrackingModalLabel">Order Shipment Tracking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="trackingTable">

        </div>
      </div>
    </div>
  </div>


  <!-- Product Detail Modal -->
  <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content shadow-sm border-0 rounded-4">
        <div class="modal-header bg_f5f5ec text-white rounded-top-4">
          <h5 class="modal-title fw-bold" id="productDetailModalLabel">Product Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 py-3">
          <dl class="row mb-0">
            <dt class="col-sm-4 fw-semibold text-primary">Name:</dt>
            <dd class="col-sm-8 fs-4" id="modalProductName"></dd>

            <dt class="col-sm-4 fw-semibold text-primary">Price:</dt>
            <dd class="col-sm-8 fs-4" id="modalProductPrice"></dd>

            <dt class="col-sm-4 fw-semibold text-primary">Variants:</dt>
            <dd class="col-sm-8 fs-4" id="modalProductVariants"></dd>

            <dt class="col-sm-4 fw-semibold text-primary">Model:</dt>
            <dd class="col-sm-8 fs-4" id="modalProductModel"></dd>
          </dl>
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
          url: '{!! route('admin.orders.data') !!}',
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
            data: 'payment_status',
            name: 'orders.payment_status'
          },
          {
            data: 'invoice',
            name: 'orders.id'
          },
          {
            data: 'shiprocket',
            name: 'orders.id',
          },
          {
            data: 'shiprocket_status',
            name: 'orders.shiprocket_status'
          },
          {
            data: 'order_number',
            name: 'orders.order_number'
          },

          {
            data: 'tracking_number',
            name: 'orders.tracking_number'
          },
          {
            data: 'customer',
            name: 'orders.user_id'
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
          targets: [0, 1, 2, 3, 4],
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

    $(document).on('click', '.shiprocket-create-order-btn', function() {
      var orderId = $(this).data('id');
      var status = $(this).data('status');
      var date = $(this).data('date');

      $('#order_id').val(orderId);
      $('#tracking_create_status').val(status);
      $('#tracking_create_date').val(date);
      $('#td_courier_name').val('');
      $('#td_tracking_number').val('');
      $('#td_tracking_url').val('');

      $('#shiprocketOrderModal').modal('show');
    });

    $('#shiprocketOrderForm').on('submit', function(e) {
      e.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        url: "{{ route('admin.orders.update.tracking') }}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function(response) {
          toastr.success(response.message);
          $('#shiprocketOrderModal').modal('hide');
          $('#datatable').DataTable().draw(false);
        },
        error: function(xhr, status, error) {
          if (xhr.responseJSON && xhr.responseJSON.status == "error") {
            toastr.error(xhr.responseJSON.message);
          }
        }
      });
    });


    $(document).on('click', '.shiprocket-update-order-btn', function() {
      var orderId = $(this).data('id');
      console.log("Order ID clicked:", orderId);
      $('#update_order_id').val(orderId);
      $('#shiprocketUpdateOrderModal').modal('show');
    });

    $('#shiprocketUpdateOrderForm').on('submit', function(e) {
      e.preventDefault();

      var formData = $(this).serialize();
      console.log("Form data being sent:", formData);
      $.ajax({
        url: "{{ route('admin.shiprocket.order.update') }}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function(response) {
          console.log("Success response:", response);
          toastr.success(response.message);
          $('#shiprocketUpdateOrderModal').modal('hide');
          $('#datatable').DataTable().draw(false);
        },
        error: function(xhr, status, error) {
          console.error("Error response:", xhr.responseJSON);
          $.each(xhr.responseJSON.errors, function(key, value) {
            $('#' + key + '-error').html(value);
          });

          if (xhr.responseJSON.status == "error") {
            toastr.error(xhr.responseJSON.message);
          }
        }
      });
    });


    $(document).on('click', '.tracking-update-order-btn', function() {
      var orderId = $(this).data('id');
      var status = $(this).data('status');
      var courier = $(this).data('courier');
      var tracking = $(this).data('tracking');
      var url = $(this).data('url');
      var date = $(this).data('date');

      $('#tracking_update_order_id').val(orderId);
      if(status) $('#tracking_status').val(status);
      $('#tracking_courier').val(courier);
      $('#tracking_num').val(tracking);
      $('#tracking_url').val(url);
      $('#tracking_date').val(date);
      $('#tracking_note').val('');

      $('#trackingUpdateOrderModal').modal('show');
    });

    $('#trackingUpdateOrderForm').on('submit', function(e) {
      e.preventDefault();

      var formData = $(this).serialize();
      $.ajax({
        url: "{{ route('admin.orders.update.tracking') }}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function(response) {
          toastr.success(response.message);
          $('#trackingUpdateOrderModal').modal('hide');
          $('#datatable').DataTable().draw(false);
        },
        error: function(xhr, status, error) {
          $('.text-danger').html(''); 
          if(xhr.responseJSON.errors) {
              $.each(xhr.responseJSON.errors, function(key, value) {
                $('#' + key + '-error').html(value);
              });
          }
          if (xhr.responseJSON && xhr.responseJSON.status == "error") {
            toastr.error(xhr.responseJSON.message);
          }
        }
      });
    });

    $(document).on('click', '.shiprocket-track-order-btn', function() {
      var orderId = $(this).data('id');

      $.ajax({
        url: "{{ route('admin.shiprocket.order.track') }}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          order_id: orderId
        },
        success: function(response) {
          $('#trackingTable').html(response);
          $('#shiprocketOrderTrackingModal').modal('show');
        },
        error: function(xhr, status, error) {
          if (xhr.responseJSON.status == "failed") {
            toastr.error(xhr.responseJSON.message);
          }
        }
      });
    });

    // shiprocket-cancel-order-btn with confirmation
    $(document).on('click', '.shiprocket-cancel-order-btn', function() {
      var orderId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: "You want to cancel this order!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.shiprocket.order.cancel') }}",
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              order_id: orderId
            },
            success: function(response) {
              toastr.success(response.message);
              $('#datatable').DataTable().draw(false);
            },
            error: function(xhr, status, error) {
              if (xhr.responseJSON.status == "failed") {
                toastr.error(xhr.responseJSON.message);
              }
            }
          });
        }
      });
    });

    $(document).on('click', '.cancel-order-btn', function() {
      var orderId = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You want to cancel this order!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.order.cancel') }}",
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              order_id: orderId
            },
            success: function(data) {
              if (data.status == 'success') {
                toastr.success(data.message, '', {
                  showMethod: "slideDown",
                  hideMethod: "slideUp",
                  timeOut: 1500,
                  closeButton: true,
                });
                setTimeout(function() {
                  $('#datatable').DataTable().draw(false);
                }, 100);
              }
            },
            error: function(xhr, status, error) {
              if (xhr.responseJSON.status == "failed") {
                toastr.error(xhr.responseJSON.message);
              }
            }
          });
        }
      });
    });

    $(document).on('click', '.restore-order-btn', function() {
      var orderId = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You want to restore this order!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.restore.order') }}",
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              order_id: orderId
            },
            success: function(data) {
              if (data.status == 'success') {
                toastr.success(data.message, '', {
                  showMethod: "slideDown",
                  hideMethod: "slideUp",
                  timeOut: 1500,
                  closeButton: true,
                })
                setTimeout(function() {
                  $('#datatable').DataTable().draw(false);
                }, 100);
              }
            },
            error: function(xhr, status, error) {
              if (xhr.responseJSON.status == "failed") {
                toastr.error(xhr.responseJSON.message);
              }
            }
          });
        }
      });
    });


    $(document).ready(function() {
      // *** EXPORT order *** //
      $('#exportOrder').click(function(e) {
        e.preventDefault();

        $.ajax({
          url: "/admin/orders/export/excel",
          type: "POST",
          data: {
            _token: $('meta[name=csrf-token]').attr('content'),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
          },
          xhrFields: {
            responseType: 'blob' // Important to get binary blob
          },
          success: function(data) {
            // data is already a Blob thanks to responseType
            const url = window.URL.createObjectURL(data);

            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'orders.xlsx');

            document.body.appendChild(link);
            link.click();
            link.remove();

            window.URL.revokeObjectURL(url); // clean up
          },
          error: function(xhr, status, error) {
            console.error('Error exporting file:', error);
            alert('Export failed. Please try again.');
          }
        });
      });

    });
  </script>

  <script>
    $(document).on('click', '.product-detail-btn', function() {
      $('#modalProductName').text($(this).data('name'));
      $('#modalProductPrice').text($(this).data('price'));
      $('#modalProductVariants').text($(this).data('variants'));
      $('#modalProductModel').text($(this).data('model'));
    });
  </script>
@endsection
