<div class="row mt-3">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary text-blue">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Property</th>
                        <th>Quantity</th>
                        <th>Return Quantity</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orderProducts->isNotEmpty())
                        @foreach ($orderProducts as $orderproduct)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $orderproduct->product->name ?? '-' }}</td>
                                <td>{{ toindiancurrency($orderproduct->price ?? '-') }}</td>
                                <td>{{ $orderproduct->property_value_names ?? '-' }}</td>
                                <td>{{ $orderproduct->remaining_quantity }} </td>
                                <td>
                                    <input type="number" name="return_quantities[{{ $orderproduct->id }}]"
                                        class="form-control" placeholder="Qty">
                                </td>
                                <td>
                                    <input type="text" name="remarks[{{ $orderproduct->id }}]" class="form-control"
                                        placeholder="Remark" value="{{ old('remarks.' . $orderproduct->id) }}">
                                </td>

                                <input type="hidden" name="order_product_id[]" value="{{ $orderproduct->id }}">
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <h6 class="text_green-1">No orders found!</h6>
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
    </div>
</div>
