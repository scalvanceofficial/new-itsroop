<div class="table-responsive">
    <table class="table table-sm table-bordered align-middle text-nowrap mb-0">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody class="border-top">
            @foreach ($wishlist_products as $wishlist_product)
                <tr>
                    <td>
                        <p class="mb-0 fs-3 text-dark">{{ $wishlist_product->product_name }}</p>
                    </td>

                    <td>
                        <p class="mb-0 fs-3 text-dark text-center">{{ formatNumber($wishlist_product->total_views) }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
