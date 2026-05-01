@if ($products->isNotEmpty())
    @foreach ($products as $product)
        @php
            $product_image = $product->getImage($property_values ?? []);
            $product_price = $product->getPrice($property_values ?? []);
        @endphp
        <div class="card-product fl-item" id="product-{{ $product->id }}">
            <div class="card-product-wrapper">
                <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}" class="product-img">
                    <img class="lazyload img-product" data-src="{{ $product_image }}" src="{{ $product_image }}" alt="{{ $product->name }}">
                    <img class="lazyload img-hover" data-src="{{ $product_image }}" src="{{ $product_image }}" alt="{{ $product->name }}">
                </a>

                @if ($product->isOutOfStock())
                    <div class="badge-out-of-stock">Out of Stock</div>
                @elseif ($product_price && $product_price->discount_percentage > 0)
                    <div class="discount-badge">{{ round($product_price->discount_percentage) }}% OFF</div>
                @endif

                <div class="list-product-btn">
                    @if (!$product->isOutOfStock())
                    <a href="#" class="box-icon add-to-cart-btn"
                       data-id="{{ $product->id }}"
                       data-primary-property-values='@json($product->primary_property_values)'>
                        <i class="fas fa-cart-plus"></i>
                    </a>
                    @endif
                    <a href="javascript:void(0);" class="box-icon product-wishlist {{ $product->is_wishlisted ? 'active' : '' }}" data-id="{{ $product->id }}">
                        <span class="icon icon-heart"></span>
                        <span class="icon icon-delete"></span>
                    </a>
                </div>

                @php $size_property_values = getProductPropertyValues($product->id, 'Size') @endphp
                @if ($size_property_values->isNotEmpty())
                    <div class="size-list">
                        @foreach ($size_property_values as $spv)
                            <span>{{ $spv->propertyValue->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="card-product-info">
                <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}" class="title link">
                    {{ $product->name }}
                </a>
                <div class="tf-product-info-price">
                    @if ($product_price)
                        <span class="price-on-sale">{{ toCurrency($product_price->selling_price) }}</span>
                        @if ($product_price->discount_percentage > 0 || $product_price->discount_price > 0)
                            <span class="compare-at-price">{{ toCurrency($product_price->actual_price) }}</span>
                            <span class="discount-percentage">{{ round($product_price->discount_percentage) }}% OFF</span>
                        @endif
                    @endif
                </div>
                <ul class="list-color-product">
                    @php $color_property_values = getProductPropertyValues($product->id, 'Color') @endphp
                    @foreach ($color_property_values as $cpv)
                        <li class="color-swatch">
                            <span class="swatch-value" style="background:{{ $cpv->color_code }}" title="{{ $cpv->color_name }}"></span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state">
        <svg width="64" height="64" fill="none" stroke="#36614B" stroke-width="1.5" viewBox="0 0 24 24">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <p>No products found</p>
    </div>
@endif
