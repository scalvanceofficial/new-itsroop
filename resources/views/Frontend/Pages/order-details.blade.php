@extends('layouts.frontend')

@section('title')
Orders | Itsroop
@endsection

@section('content')

<style>
/* ===== Modern UI Styles ===== */
.order-page { background: #f8f9fa; }

.card {
    border-radius: 12px;
}

.info-card {
    padding: 15px;
    border-radius: 10px;
    text-align: center;
}

.progress-steps {
    gap: 20px;
}

.step-item {
    min-width: 90px;
}

.step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    margin-bottom: 6px;
    font-size: 14px;
}

.step-circle.active {
    background: #28a745;
    color: #fff;
}

.timeline-modern {
    border-left: 2px solid #eee;
    padding-left: 15px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item .dot {
    width: 10px;
    height: 10px;
    background: #28a745;
    border-radius: 50%;
    position: absolute;
    left: -21px;
    top: 5px;
}

.product-card img {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

@media (max-width: 576px) {
    .step-item small {
        font-size: 10px;
    }
}
</style>

<section class="order-page py-4">
<div class="container">

    <!-- Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">Order #{{ $order->order_number }}</h5>
                <small class="text-muted">{{ toIndianDateTime($order->created_at) }}</small>
            </div>

            <a href="{{ route('frontend.orders.pdf.download', $order->id) }}"
               class="btn btn-outline-danger btn-sm mt-2 mt-md-0"
               target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Invoice
            </a>
        </div>
    </div>

    <!-- Info -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card info-card">
                <small>Items</small>
                <h6>{{ $order->products()->count() }}</h6>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card info-card">
                <small>Order Date</small>
                <h6>{{ toIndianDateTime($order->created_at) }}</h6>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card info-card">
                <small>Delivery</small>
                <h6>
                    {{ $order->estimated_delivery_date
                        ? toIndianDate($order->estimated_delivery_date)
                        : toIndianDate($order->created_at->addDays(7)) }}
                </h6>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card info-card text-start">
                <small>Address</small>
                <p class="mb-0 small">
                    {{ $order->address->address_line_1 }},
                    {{ $order->address->address_line_2 }},
                    {{ $order->address->city }},
                    {{ $order->address->pincode }}
                </p>
            </div>
        </div>
    </div>

    <!-- Steps -->
    @php
        $steps = ['Pending','Confirmed','Packed','Shipped','Out for Delivery','Delivered'];
        $current_status_index = array_search($order->status, $steps);
        if ($current_status_index === false) $current_status_index = 0;
    @endphp

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Track Order</h6>

            <div class="progress-steps d-flex overflow-auto pb-2">
                @foreach ($steps as $index => $step)
                    <div class="step-item text-center flex-fill">
                        <div class="step-circle {{ $index <= $current_status_index ? 'active' : '' }}">
                            <i class="fas fa-check"></i>
                        </div>
                        <small>{{ $step }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Courier -->
    @if ($order->tracking_url)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-2">Courier Details</h6>

            @if($order->courier_name)
                <p><strong>Courier:</strong> {{ $order->courier_name }}</p>
            @endif

            @if($order->tracking_number)
                <p><strong>Tracking No:</strong> {{ $order->tracking_number }}</p>
            @endif

            <a href="{{ $order->tracking_url }}" target="_blank" class="tf-btn btn-fill animate-hover-btn rounded-0 mt-3">
                Track Package
            </a>
        </div>
    </div>
    @endif

    <!-- Timeline -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Tracking History</h6>

            @if ($tracking_activities->isNotEmpty())
                <div class="timeline-modern">
                    @foreach ($tracking_activities as $activity)
                        <div class="timeline-item">
                            <div class="dot"></div>
                            <div>
                                <strong>{{ strtoupper($activity->status) }}</strong>
                                @if ($activity->note)
                                    <p class="mb-1 small">{{ $activity->note }}</p>
                                @endif
                                <small class="text-muted">
                                    {{ toIndianDateTime($activity->created_at) }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Tracking history not available yet.</p>
            @endif
        </div>
    </div>

    <!-- Products -->
    @php
        $total_price = 0;

        // Get delivered datetime from tracking activity for accurate return window
        $delivered_activity   = $tracking_activities->firstWhere('status', 'Delivered');
        $delivered_at         = $delivered_activity ? $delivered_activity->created_at : $order->updated_at;
        $days_since_delivered = $delivered_at->diffInDays(now());
        $return_deadline      = $delivered_at->copy()->addDays(7);
        $return_window_open   = $order->status == 'Delivered' && $days_since_delivered <= 7;
    @endphp

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Items</h6>

            @foreach ($order->products as $order_product)

                @php
                    $product_image    = $order_product->product->getImage($order_product->property_values);
                    $total_price     += $order_product->price * $order_product->quantity;
                    $product_quantity = $order_product->quantity;
                    $returned_product = $order_product->returnedProduct;
                @endphp

                <div class="card product-card mb-3 border-0 shadow-sm">
                    <div class="card-body d-flex gap-3">
                        <img src="{{ $product_image }}" class="rounded">

                        <div class="flex-grow-1">
                            <h6>{{ $order_product->product->name }}</h6>

                            <small>Price: {{ toCurrency($order_product->price) }}</small><br>
                            <small>Qty: {{ $order_product->quantity }}</small><br>
                            <strong>Total: {{ toCurrency($order_product->total_amount) }}</strong>

                            <!-- Return badge if already returned -->
                            @if ($returned_product && $returned_product->return_quantity > 0)
                                <div class="mt-2">
                                    <span class="badge bg-info">
                                        {{ $returned_product->status }}
                                        ({{ $returned_product->return_quantity }}/{{ $product_quantity }})
                                    </span>
                                </div>
                            @endif

                            <!-- Return button / window status -->
                            @if ($order->status == 'Delivered')

                                @if ($return_window_open && (!$returned_product || $returned_product->return_quantity < $product_quantity))
                                    {{-- Window is open: show button + static deadline line + live countdown --}}
                                    <div class="mt-2">
                                        <button class="btn btn-outline-warning btn-sm return-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#returnProductModal"
                                                data-id="{{ $order_product->id }}"
                                                data-max="{{ $product_quantity - ($returned_product->return_quantity ?? 0) }}"
                                                data-name="{{ $order_product->product->name }}">
                                            <i class="fas fa-undo me-1"></i> Return Product
                                        </button>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-clock me-1"></i>
                                            Return window closes on {{ $return_deadline->format('d M Y') }}
                                        </small>
                                        <small class="text-warning d-block mt-1 fw-bold countdown-timer"
                                               data-deadline="{{ $return_deadline->toIso8601String() }}">
                                        </small>
                                    </div>

                                @elseif (!$return_window_open && (!$returned_product || $returned_product->return_quantity == 0))
                                    {{-- Window closed and no return was made --}}
                                    <div class="mt-2">
                                        <small class="text-danger">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Return window closed on {{ $return_deadline->format('d M Y') }}
                                        </small>
                                    </div>

                                @endif

                            @endif

                        </div>
                    </div>
                </div>

            @endforeach

            <div class="d-flex justify-content-between mt-3">
                <strong>Total</strong>
                <strong>{{ toCurrency($order->total_amount) }}</strong>
            </div>
        </div>
    </div>

    <!-- Cancel -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if ($order->status != 'Delivered' && $order->status != 'Cancelled' && $order->status != 'Shipped' && $order->created_at->diffInHours() < 24 && $order->shiprocket_status == 'NEW')
                <div class="alert alert-info">
                    <h6>Cancel Available</h6>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        Cancel Order
                    </button>
                </div>
            @elseif($order->status == 'Delivered')
                <div class="alert alert-success">Order Delivered</div>
            @elseif($order->status == 'Shipped')
                <div class="alert alert-info">
                    <i class="fas fa-truck me-2"></i> Your order has been shipped. Cancellation is no longer available.
                </div>
            @elseif($order->status == 'Cancelled' || $order->shiprocket_status == 'Cancelled')
                <div class="alert alert-warning">Order Cancelled</div>
            @else
                <div class="alert alert-secondary">Cancellation not available</div>
            @endif

        </div>
    </div>

</div>
</section>

<!-- Return Modal -->
<div class="modal fade" id="returnProductModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('frontend.orders.return', $order->route_key) }}" method="POST" id="returnOrderForm">
                @csrf
                <input type="hidden" name="order_product_id" id="return_order_product_id">
                <div class="modal-header">
                    <h5 class="modal-title">Return <span id="return_product_name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Quantity to Return</label>
                        <input type="number" name="return_quantity" id="return_qty_input" class="form-control" min="1" value="1">
                    </div>
                    <div class="mb-3">
                        <label>Reason for Return</label>
                        <textarea name="remark" class="form-control" placeholder="Please describe the reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning">Submit Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('frontend.orders.cancel', $order->route_key) }}" method="POST" id="cancelOrderForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Reason for Cancellation</label>
                    <textarea name="cancellation_reason" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger">Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    // ── Live countdown timers ──────────────────────────────────────────────
    function startCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(function(el) {
            const deadline = new Date(el.getAttribute('data-deadline')).getTime();

            function tick() {
                const diff = deadline - Date.now();

                if (diff <= 0) {
                    el.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Return window has expired</span>';
                    return;
                }

                const days    = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                el.innerHTML =
                    '<i class="fas fa-hourglass-half me-1"></i>' +
                    days    + 'd ' +
                    hours   + 'h ' +
                    minutes + 'm ' +
                    seconds + 's remaining';

                setTimeout(tick, 1000);
            }

            tick();
        });
    }

    startCountdowns();

    // ── Cancel order ───────────────────────────────────────────────────────
    $('#cancelOrderForm').on('submit', function(e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            if(response.status == 'success'){
                toastr.success(response.message);
                location.reload();
            }
        });
    });

    // ── Return modal setup ─────────────────────────────────────────────────
    $('.return-btn').on('click', function() {
        $('#return_order_product_id').val($(this).data('id'));
        $('#return_product_name').text($(this).data('name'));
        $('#return_qty_input').attr('max', $(this).data('max'));
    });

    // ── Return form submit ─────────────────────────────────────────────────
    $('#returnOrderForm').on('submit', function(e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            if(response.status == 'success'){
                toastr.success(response.message);
                location.reload();
            }
        }).fail(function(xhr){
            toastr.error(xhr.responseJSON.message || 'Something went wrong.');
        });
    });

});
</script>

@endsection