@extends('layouts.frontend')
@section('title')
    Wishlist | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">My Wishlist</div>
        </div>
    </div>
    <!-- /page-title -->

    <!-- Section Product -->
    @if ($wishlists->isNotEmpty())
        <section class="flat-spacing-2">
            <div class="container">
                <div class="grid-layout wrapper-shop" data-grid="grid-4">
                    <!-- card product 1 -->
                    @foreach ($wishlists as $wishlist)
                        @php
                            $product_image = $wishlist->product->getImage();
                            $product_price = $wishlist->product->getPrice();
                            $stock = $product_price?->stock ?? 0;
                        @endphp
                        <div class="card-product fl-item" id="{{ 'product-' . $wishlist->product->id }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('frontend.products.product-details', ['slug' => $wishlist->product->slug]) }}"
                                    class="product-img">
                                    <img class="lazyload img-product" data-src="{{ $product_image }}"
                                        src="{{ $product_image }}" alt="image-product">
                                    <img class="lazyload img-hover" data-src="{{ $product_image }}"
                                        src="{{ $product_image }}" alt="image-product">
                                </a>
                                @if ($wishlist->product->isOutOfStock())
                                    <div class="badge-out-of-stock bg_f5f5ec"
                                        style="position: absolute; top: 10px; left: 10px; color: #36614B; padding: 5px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; z-index: 9; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                        Out of Stock
                                    </div>
                                @endif
                                <div class="list-product-btn absolute-2">
                                    @if (!$wishlist->product->isOutOfStock())
                                        <a href="#" class="box-icon bg_white quick-add tf-btn-loading add-to-cart-btn"
                                            data-id="{{ $wishlist->product->id }}"
                                            data-primary-property-values='@json($wishlist->product->primary_property_values)'>
                                            <i class="fas fa-cart-plus"></i>
                                            <span class="tooltip">Quick Add</span>
                                        </a>
                                    @endif
                                    <a href="javascript:void(0);"
                                        class="box-icon bg_white btn-icon-action tf-btn-loading product-wishlist {{ $wishlist->product->is_wishlisted ? 'active' : '' }}"
                                        data-id="{{ $wishlist->product->id }}">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip" id="wishlist-tooltip-{{ $wishlist->product->id }}">
                                            {{ $wishlist->product->is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                                        </span>
                                        <span class="icon icon-delete"></span>
                                    </a>
                                </div>
                                @php $size_property_values = getProductPropertyValues($wishlist->product->id, 'Size') @endphp
                                @if ($size_property_values->isNotEmpty())
                                    <div class="size-list">
                                        @foreach ($size_property_values as $size_property_value)
                                            <span>{{ $size_property_value->propertyValue->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="#" class="title link">{{ $wishlist->product->name }}</a>
                                <div class="tf-product-info-price">
                                    <div class="price-on-sale text_black" style="font-size:14px; font-weight: 600;">
                                        @if ($product_price)
                                            {{ toCurrency($product_price->selling_price) }}
                                        @endif
                                    </div>

                                    <div class="compare-at-price" style="font-size:14px; font-weight: 600;">
                                        @if ($product_price)
                                            {{ toCurrency($product_price->actual_price) }}
                                        @endif
                                    </div>
                                    <div class="discount-percentage">
                                        @if ($product_price && $product_price->discount_percentage)
                                            <span>{{ round($product_price->discount_percentage) }}</span>% OFF
                                        @endif
                                    </div>
                                </div>
                                <ul class="list-color-product">
                                    @php $color_property_values = getProductPropertyValues($wishlist->product->id, 'Color') @endphp
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
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section class="flat-spacing-2">
            <div class="container">
                <div class="tf-categories-wrap justify-content-center">
                    <div class="tf-categories-container">
                        <div class="collection-content text-center">
                            <h4 class="text_green-1">Your wishlist is empty!</h4>
                            <p class="mt-3">Nothing in your wishlist yet. Discover eco-friendly finds you'll love!</p>
                            <br>
                            <a href="{{ route('frontend.products') }}"
                                style="background:#4CAF50; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;">
                                Browse Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- /Section Product -->
@endsection
