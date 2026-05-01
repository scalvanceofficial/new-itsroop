@extends('layouts.admin')
@section('title')
  Dashboard
@endsection

@section('content')
  <div class="row">
    <div class="col-2">
      <div class="item">
        <a href="#">
          <div class="card border-0 zoom-in bg-light-success shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="/backend/dist/images/dashboard-icon/customer-2.svg" width="50" height="50" class="mb-3" alt="" />
                <p class="fw-semibold fs-5 mb-1">Customers</p>
                <h5 class="fw-semibold text-success mb-0">{{ $total_users }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-2">
      <div class="item">
        <a href="#">
          <div class="card border-0 zoom-in bg-light-gray shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="/backend/dist/images/dashboard-icon/enquiry.svg" width="50" height="50" class="mb-3" alt="" />
                <p class="fw-semibold fs-5 mb-1">Enquiries</p>
                <h5 class="fw-semibold text-success mb-0">{{ $total_enquiries }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-2">
      <div class="item">
        <a href="#">
          <div class="card border-0 zoom-in bg-light-info shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="/backend/dist/images/dashboard-icon/order.svg" width="50" height="50" class="mb-3" alt="" />
                <p class="fw-semibold fs-5 mb-1">Orders</p>
                <h5 class="fw-semibold text-danger mb-0">{{ $total_orders }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-2">
      <div class="item">
        <a href="#">
          <div class="card border-0 zoom-in bg-light-danger shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="/backend/dist/images/dashboard-icon/product-2.svg" width="50" height="50" class="mb-3" alt="" />
                <p class="fw-semibold fs-5  mb-1">Cart</p>
                <h5 class="fw-semibold text-info mb-0">{{ $total_cart_products }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-2">
      <div class="item">
        <a href="#">
          <div class="card border-0 zoom-in bg-light-secondary shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="/backend/dist/images/dashboard-icon/category.svg" width="50" height="50" class="mb-3" alt="" />
                <p class="fw-semibold fs-5 mb-1">Categories</p>
                <h5 class="fw-semibold text-danger mb-0">{{ $total_categories }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-2">
      <div class="item">
        <a href="#">
          <div class="card border-0 zoom-in bg-light-primary shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="/backend/dist/images/dashboard-icon/product-2.svg" width="50" height="50" class="mb-3" alt="" />
                <p class="fw-semibold fs-5  mb-1">Products</p>
                <h5 class="fw-semibold text-info mb-0">{{ $total_products }}</h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
  <hr>
  <div class="row mt-4">
    <div class="col-6">
      <h4 class="text-primary">Track Orders</h4>
    </div>
    <div class="col-2">
      <div class="card bg-light-primary shadow-none">
        <select class="form-select fw-semibold" id="year">
          <option value="">All</option>
          <option value="2025" {{ $current_year == '2025' ? 'selected' : '' }}>2025</option>
          <option value="2026" {{ $current_year == '2026' ? 'selected' : '' }}>2026</option>
          <option value="2027" {{ $current_year == '2027' ? 'selected' : '' }}>2027</option>
        </select>
      </div>
    </div>
    <div class="col-2">
      <div class="card bg-light-primary shadow-none">
        <select class="form-select fw-semibold" id="month">
          <option value="">All</option>
          @foreach (get_months() as $key => $month)
            <option value="{{ $key }}" {{ $current_month == $key ? 'selected' : '' }}>
              {{ $month }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-2">
      <button class="btn btn-primary" id="filter">
        <i class="ti ti-adjustments-horizontal fs-4 me-2"></i>
        Apply
      </button>
    </div>
  </div>


  <div class="row">
    <!-- Order Count Card -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-body p-2">
          <div class="card-header bg_f5f5ec">
            <div class="row mb-3">
              <div class="col-3">
                <div class="round rounded bg-danger d-flex align-items-center justify-content-center">
                  <i class="fas fa-cart-plus text-white fs-7"></i>
                </div>
              </div>
              <div class="col-9 d-flex align-items-center justify-content-end text-end">
                <h3 class="mb-0 fw-semibold fs-5" id="total_orders"></h3>
              </div>
            </div>
            <div class="row text-center mt-4 justify-content-center">
              <div class="col-4">
                <div class="card border-danger">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">New</h6>
                    <span id="total_new_orders" class="fw-semibold text-danger"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-danger">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Shipped</h6>
                    <span id="total_shipped_orders" class="fw-semibold text-danger"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-danger">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Delivered</h6>
                    <span id="total_delivered_orders" class="fw-semibold text-danger"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-danger">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Cancelled</h6>
                    <span id="total_cancelled_orders" class="fw-semibold text-danger"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-danger">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Other</h6>
                    <span id="total_other_orders" class="fw-semibold text-danger"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-body p-2">
          <div class="card-header bg_f5f5ec">
            <div class="row mb-3">
              <div class="col-3">
                <div class="round rounded bg-success d-flex align-items-center justify-content-center">
                  <i class="fas fa-rupee-sign text-white fs-7"></i>
                </div>
              </div>
              <div class="col-9 d-flex align-items-center justify-content-end text-end">
                <h3 class="mb-0 fw-semibold fs-5" id="total_amount"></h3>
              </div>
            </div>
            <div class="row text-center mt-4 justify-content-center">
              <div class="col-4">
                <div class="card border-success">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">New</h6>
                    <span id="total_new_order_amount" class="fw-semibold text-success"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-success">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Shipped</h6>
                    <span id="total_shipped_order_amount" class="fw-semibold text-success"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-success">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Delivered</h6>
                    <span id="total_delivered_order_amount" class="fw-semibold text-success"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-success">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Cancelled</h6>
                    <span id="total_cancelled_order_amount" class="fw-semibold text-success"></span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-success">
                  <div class="card-body p-1">
                    <h6 class="fw-semibold">Other</h6>
                    <span id="total_other_order_amount" class="fw-semibold text-success"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fw-semibold text-primary mb-3">Currency Statistics</h4>
                <div class="row" id="currency_distribution_row">
                    <!-- Currency cards will be appended here via JS -->
                </div>
                <div class="mt-3">
                    <span class="fw-semibold">Most Used Currency: </span>
                    <span id="most_used_currency" class="badge bg-primary fs-3"></span>
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-6 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-body">
          <div class="row">
            <div class="col-8">
              <h4 class="card-title fw-semibold mb-1 text-primary">Order Summary</h4>
            </div>
            <div class="col-4">
              <select class="form-select fw-semibold mb-1" id="order-year" style="background-color: #ecf2ff">
                <option value="2025" {{ $current_year == '2025' ? 'selected' : '' }}>2025</option>
                <option value="2026" {{ $current_year == '2026' ? 'selected' : '' }}>2026</option>
                <option value="2027" {{ $current_year == '2027' ? 'selected' : '' }}>2027</option>
              </select>
            </div>
            <div id="order_summary" class="mb-7 pb-8"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 table-sm d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="row">
            <div class="col-12">
              <h4 class="card-title fw-semibold text-primary">Top 10 Selling Products</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12" id="sellingProducts" style="max-height: 300px; overflow-y: auto;">
              {{-- selling product Table Will Display Here --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-8 table-sm d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="row">
            <div class="col-12">
              <h4 class="card-title fw-semibold text-primary">Top 10 Wishlist Products</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12" id="wishlistProducts" style="max-height: 300px; overflow-y: auto;">
              {{-- wishlist product Table Will Display Here --}}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="row">
        <!-- Customers -->
        <div class="col-sm-6">
          <div class="card w-100">
            <div class="card-body p-3 bg_f5f5ec">
              <div class="p-2">
                <img src="/backend/dist/images/dashboard-icon/review.svg" width="50" height="50" class="mb-3" alt="" />
              </div>
              <h4 class="mb-1 fw-semibold d-flex align-content-center">{{ $total_reviews }}</h4>
              <h4 class="fs-5 mb-0">Reviews</h4>
            </div>
          </div>
        </div>
        <!-- Projects -->
        <div class="col-sm-6">
          <div class="card w-100">
            <div class="card-body p-3 bg_f5f5ec">
              <div class="p-2">
                <img src="/backend/dist/images/dashboard-icon/testimonial.svg" width="50" height="50" class="mb-3" alt="" />
              </div>
              <h4 class="mb-1 fw-semibold d-flex align-content-center">{{ $total_testimonial }}</h4>
              <h4 class="fs-5 mb-0">Testimonial</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="card bg-red-grey">
        <div class="card-body p-3 bg_f5f5ec">
          <div class="d-flex align-items-center justify-content-between">
            <div class="me-5 pe-1">
              <img src="/backend/dist/images/dashboard-icon/subscriber.svg" class="shadow-warning rounded-2" alt="" width="72" height="72">
            </div>
            <div class="text-end">
              <h5 class="fw-semibold fs-5 mb-2 text-end text-red">{{ $total_subscribers }} </h5>
              <h4 class="fs-5 mb-0">Subscribers</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher-js/7.2.0/pusher.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.min.js"></script>

  <script>
    $(document).ready(function() {
      getDashBoardData()
      getWishlistProducts()
      getOrderSummary()
      getSellingProducts()
    });

    function getDashBoardData() {
      const year = $('#year').val()
      const month = $('#month').val()

      $.ajax({
        url: '/admin/dashboard/data',
        type: 'POST',
        data: {
          _token: $('meta[name=csrf-token]').attr('content'),
          year: year,
          month: month
        },
        success: function(data) {
          if (data.status == 'success') {
            $('#total_orders').html('Orders - ' + data.data.total_orders);
            $('#total_new_orders').html(data.data.total_new_orders);
            $('#total_shipped_orders').html(data.data.total_shipped_orders);
            $('#total_delivered_orders').html(data.data.total_delivered_orders);
            $('#total_cancelled_orders').html(data.data.total_cancelled_orders);
            $('#total_other_orders').html(data.data.total_other_orders);

            $('#total_amount').html(data.data.total_amount);
            $('#total_new_order_amount').html(data.data.total_new_order_amount);
            $('#total_shipped_order_amount').html(data.data
              .total_shipped_order_amount);
            $('#total_delivered_order_amount').html(data.data.total_delivered_order_amount);
            $('#total_cancelled_order_amount').html(data.data.total_cancelled_order_amount);
            $('#total_other_order_amount').html(data.data.total_other_order_amount);

            // Populate currency distribution
            $('#most_used_currency').html(data.data.most_used_currency);
            let currencyHtml = '';
            data.data.currency_distribution.forEach(function(item) {
                currencyHtml += `
                    <div class="col-md-3">
                        <div class="card border">
                            <div class="card-body p-2 text-center">
                                <h6 class="fw-semibold mb-1">${item.code}</h6>
                                <div class="text-muted small">Orders: ${item.count}</div>
                                <div class="fw-bold text-success">${item.total}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#currency_distribution_row').html(currencyHtml);
          }
        },
        error: function(data) {
          toastr.error('Something went wrong!');
        }
      });

      $('#filter').click(function() {
        getDashBoardData()
      });
    }

    function getWishlistProducts() {
      const year = $('#year').val()
      const month = $('#month').val()

      $.ajax({
        url: '/admin/dashboard/wishlist-products',
        type: 'POST',
        data: {
          _token: $('meta[name=csrf-token]').attr('content'),
          year: year,
          month: month
        },
        success: function(response) {
          $('#wishlistProducts').html(response)
        },
        error: function(data) {
          toastr.error('Something went wrong!');
        }
      });
    }

    function getSellingProducts() {
      const year = $('#year').val()
      const month = $('#month').val()

      $.ajax({
        url: '/admin/dashboard/selling-products',
        type: 'POST',
        data: {
          _token: $('meta[name=csrf-token]').attr('content'),
          year: year,
          month: month
        },
        success: function(response) {
          $('#sellingProducts').html(response)
        },
        error: function(data) {
          toastr.error('Something went wrong!');
        }
      });
    }

    function getOrderSummary() {
      const year = $('#order-year').val()
      $.ajax({
        url: '/admin/dashboard/order-summary',
        type: 'POST',
        data: {
          _token: $('meta[name=csrf-token]').attr('content'),
          year: year,
        },
        success: function(data) {
          if (data.status == 'success') {
            const months = @json(get_ordermonths());
            const totalOrders = data.data.monthly_total_orders
            const totalAmounts = data.data.monthly_total_amounts

            var orders = {
              series: [{
                name: "Orders",
                data: totalOrders,
              }, ],

              chart: {
                toolbar: {
                  show: true,
                },
                height: 260,
                type: "bar",
                fontFamily: "Plus Jakarta Sans', sans-serif",
                foreColor: "#adb0bb",
              },
              colors: [
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332",
                "#214332"
              ],
              plotOptions: {
                bar: {
                  borderRadius: 5,
                  columnWidth: "75%",
                  distributed: true,
                  endingShape: "rounded",
                },
              },

              dataLabels: {
                enabled: false,
              },
              legend: {
                show: false,
              },
              grid: {
                yaxis: {
                  lines: {
                    show: false,
                  },
                },
                xaxis: {
                  lines: {
                    show: false,
                  },
                },
              },
              xaxis: {
                categories: Object.values(months),
                axisBorder: {
                  show: false,
                },
                axisTicks: {
                  show: false,
                },
              },
              yaxis: {
                labels: {
                  show: false,
                },
              },
              tooltip: {
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const orderAmounts = totalAmounts;
                  const totalOrders = series[seriesIndex][dataPointIndex];
                  const totalAmount = orderAmounts[dataPointIndex];
                  return `
                                                                                                                                                                                                                                                                                                                                                                                        <div style="padding: 8px; font-size: 14px;">
                                                                                                                                                                                                                                                                                                                                                                                            <strong>${w.globals.labels[dataPointIndex]}</strong><br/>
                                                                                                                                                                                                                                                                                                                                                                                            Orders: ${totalOrders}<br/>
                                                                                                                                                                                                                                                                                                                                                                                            Total Amount: ${totalAmount.toLocaleString()}
                                                                                                                                                                                                                                                                                                                                                                                        </div>`;
                }
              }
            };

            var chart = new ApexCharts(document.querySelector("#order_summary"), orders);
            chart.render();
          }
        },
        error: function(data) {
          toastr.error('Something went wrong!');
        }
      });
    }

    $('#order-year').change(function() {
      $('#order_summary').html('')
      getOrderSummary()
    });

    $('#filter').click(function() {
      getDashBoardData()
      getWishlistProducts()
      getSellingProducts()
    });
  </script>
@endsection
