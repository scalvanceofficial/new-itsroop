<div class="table-responsive">
    <table class="table table-sm table-bordered align-middle text-nowrap mb-0">
        <thead>
            <tr>
                <th>Product</th>
                <th>Property</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody class="border-top">
            @foreach ($selling_products as $selling_product)
                <tr>
                    <td>
                        <p class="mb-0 fs-3 text-dark">{{ $selling_product->product_name }}</p>
                    </td>
                    <td>
                        <p class="mb-0 fs-3 text-dark">{{ $selling_product->property_name }}</p>
                    </td>
                    <td>
                        <p class="mb-0 fs-3 text-dark text-center">{{ formatNumber($selling_product->total_views) }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
