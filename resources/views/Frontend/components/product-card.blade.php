@php
    $product_image = $product->getImage($property_values ?? []);
    $product_price = $product->getPrice($property_values ?? []);
@endphp
<div class="card-product fl-item" id="{{ 'product-' . $product->id }}">
    <div class="card-product-wrapper">
        <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}" class="product-img">
            <img class="lazyload img-product" data-src="{{ $product_image }}" src="{{ $product_image }}"
                alt="image-product">
            <img class="lazyload img-hover" data-src="{{ $product_image }}" src="{{ $product_image }}"
                alt="image-product">
        </a>
        @if ($product->isOutOfStock())
            <div class="badge-out-of-stock bg_f5f5ec"
                style="position: absolute; top: 10px; left: 10px; color: #36614B; padding: 5px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; z-index: 9; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                Out of Stock
            </div>
        @endif
        <div class="list-product-btn">
            @if (!$product->isOutOfStock())
                <a href="#" class="box-icon bg_white quick-add tf-btn-loading add-to-cart-btn"
                    data-id="{{ $product->id }}" data-primary-property-values='@json($product->primary_property_values)'>
                    <i class="fas fa-cart-plus"></i>
                    <span class="tooltip">Quick Add</span>
                </a>
            @endif
            <a href="javascript:void(0);"
                class="box-icon bg_white btn-icon-action product-wishlist {{ $product->is_wishlisted ? 'active' : '' }}"
                data-id="{{ $product->id }}">
                <span class="icon icon-heart"></span>
                <span class="tooltip" id="wishlist-tooltip-{{ $product->id }}">
                    {{ $product->is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                </span>
                <span class="icon icon-delete"></span>
            </a>
        </div>
        @php $size_property_values = getProductPropertyValues($product->id, 'Size') @endphp
        @if ($size_property_values->isNotEmpty())
            <div class="size-list">
                @foreach ($size_property_values as $size_property_value)
                    <span>{{ $size_property_value->propertyValue->name }}</span>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-product-info">
        <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}"
            class="title link">{{ $product->name }}
        </a>
        <div class="tf-product-info-price">
            <div class="price-on-sale text_black" style="font-size:14px; font-weight: 600;">
                @if ($product_price)
                    {{ toCurrency($product_price->selling_price) }}
                @endif
            </div>

            @if ($product_price && ($product_price->discount_percentage > 0 || $product_price->discount_price > 0))
                <div class="compare-at-price" style="font-size:14px; font-weight: 600;">
                    {{ toCurrency($product_price->actual_price) }}
                </div>
                <div class="discount-percentage">
                    <span>{{ round($product_price->discount_percentage) }}</span>% OFF
                </div>
            @endif
        </div>
        <ul class="list-color-product">
            @php $color_property_values = getProductPropertyValues($product->id, 'Color') @endphp
            @if ($color_property_values->isNotEmpty())
                @foreach ($color_property_values as $color_property_value)
                    <li class="list-color-item color-swatch">
                        <span class="tooltip">{{ $color_property_value->propertyValue->name }}
                        </span>
                        <span class="swatch-value"
                            style="background: {{ $color_property_value->propertyValue->color }}">
                        </span>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
