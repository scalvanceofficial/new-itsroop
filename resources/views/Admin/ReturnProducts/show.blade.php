<div class="container">
    <!-- Summary (Compact View) -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-3">
            <div class="row text-sm ">
                <div class="col-md-4 mb-2">
                    <strong>Customer:</strong> {{ $return_product->order->user->full_name ?? '-' }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Order No:</strong> {{ $return_product->order->order_number ?? '-' }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Return No:</strong> {{ $return_product->return_number ?? '-' }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Return Qty:</strong> {{ $return_product->return_quantity ?? '-' }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Refund:</strong>
                    {{ toCurrency($return_product->orderProduct->price * $return_product->return_quantity) ?? '-' }}
                </div>
                <div class="col-md-12 mb-2">
                    <strong>Product:</strong> {{ $return_product->orderProduct->product->name ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Status Logs Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Transaction ID</th>
                            <th>Settlement Date</th>
                            <th>Remark</th>
                            <th>Log Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($status_logs as $log)
                            <tr>
                                <td>{{ ucwords(strtolower(str_replace('_', ' ', $log->status))) }}</td>
                                <td>{{ $log->transaction_id ?? '-' }}</td>
                                <td>{{ $log->settlement_date ? toIndianDate($log->settlement_date) : '-' }}</td>
                                <td>{{ $log->product_received_remark ?? '-' }}</td>
                                <td>{{ toIndianDateTime($log->created_at) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No status logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
