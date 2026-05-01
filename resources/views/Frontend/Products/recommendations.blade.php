<div class="tf-search-content-title fw-5 mt-4 mb-3">Recommendations</div>
<div class="tf-search-hidden-inner">
    @forelse($products as $product)
        <div class="tf-loop-item mb-3">
            <div class="image w-25">
                <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}">
                    <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                </a>
            </div>
            <div class="content flex-grow-1 ps-3">
                <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}" class="fw-6 text-dark d-block mb-1">{{ $product->name }}</a>
                <div class="tf-product-info-price">
                    <div class="price fw-5 text-muted small">
                        @if($product->getPrice())
                            {{ toCurrency($product->getPrice()->selling_price) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-4">
            <p class="text-muted">No products found matching your search.</p>
        </div>
    @endforelse
</div>
