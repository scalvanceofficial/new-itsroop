@extends('layouts.frontend')
@section('title')
    Home | Itsroop
@endsection
@section('content')

    <!-- Slider -->
    <div class="tf-slideshow slider-effect-fade position-relative">
        <div dir="ltr" class="swiper tf-sw-slideshow" data-preview="1" data-tablet="1" data-mobile="1" data-centered="false"
            data-space="0" data-loop="true" data-auto-play="false" data-delay="0" data-speed="1000">

            <div class="swiper-wrapper">

                @foreach ($sliders as $slider)
                    <div class="swiper-slide">
                        <div class="wrap-slider">

                            <img src="{{ Storage::url($slider->image) }}" alt="slider-image"
                                style="width: 100%; height: 600px; object-fit: cover; object-position: center;">

                            <div class="box-content">
                                <div class="container">

                                    <h1 class="fade-item fade-item-1 slider-title">
                                        {{ $slider->title }}
                                    </h1>



                                    <a href="/products"
                                        class="fade-item fade-item-3 tf-btn btn-fill animate-hover-btn btn-xl radius-3"><span>Shop
                                            collection</span><i class="icon icon-arrow-right"></i></a>

                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="wrap-pagination">
            <div class="container">
                <div class="sw-dots sw-pagination-slider justify-content-center"></div>
            </div>
        </div>
    </div>

    <!-- /Slider -->

    <div class="tf-marquee bg_yellow-2">
        <div class="wrap-marquee">
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
            <div class="marquee-item">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="15" height="20"
                        viewBox="0 0 15 20">
                        <path d="M14.5833 8H8.61742L9.94318 0L0 12H5.96591L4.64015 20L14.5833 8"></path>
                    </svg>
                </div>
                <p class="text">Spring Clearance Event: Save Up to 70%</p>
            </div>
        </div>

    </div>


    <!-- Categories -->
    <section class="flat-spacing-4 flat-categorie">
        <div class="container-full">
            <div class="flat-title-v2">
                <div class="box-sw-navigation">
                    <div class="nav-sw nav-next-slider nav-next-collection">
                        <span class="icon icon-arrow-left"></span>
                    </div>
                    <div class="nav-sw nav-prev-slider nav-prev-collection">
                        <span class="icon icon-arrow-right"></span>
                    </div>
                </div>

                <span class="text-3 fw-7 text-uppercase title wow fadeInUp" data-wow-delay="0s">
                    SHOP BY CATEGORIES
                </span>
            </div>

            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-8">
                    <div dir="ltr" class="swiper tf-sw-collection" data-preview="3" data-tablet="2"
                        data-mobile="2" data-space-lg="30" data-space-md="30" data-space="15" data-loop="false"
                        data-auto-play="false">

                        <div class="swiper-wrapper">

                            @foreach ($categories as $category)
                                <div class="swiper-slide lazy">
                                    <div class="collection-item style-left hover-img">
                                        <div class="collection-inner">

                                            <a href="{{ route('frontend.products', ['category_slug' => $category->slug]) }}"
                                                class="collection-image img-style">

                                                <img class="lazyload" data-src="{{ Storage::url($category->image) }}"
                                                    src="{{ Storage::url($category->image) }}"
                                                    alt="{{ $category->name }}">
                                            </a>

                                            <div class="collection-content">
                                                <a href="{{ route('frontend.products', ['category_slug' => $category->slug]) }}"
                                                    class="tf-btn collection-title hover-icon fs-15">

                                                    <span>{{ $category->name }}</span>
                                                    <i class="icon icon-arrow1-top-left"></i>

                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-4">
                    <div class="discovery-new-item">
                        <h5>Discovery all new items</h5>
                        <a href="{{ route('frontend.products') }}">
                            <i class="icon-arrow1-top-left"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /Categories -->


    {{-- products --}}
    <section class="flat-spacing-5 pt_0 flat-seller">
        <div class="container">
            <div class="flat-title">
                <span class="title wow fadeInUp" data-wow-delay="0s">Best Seller</span>
                <p class="sub-title wow fadeInUp" data-wow-delay="0s">
                    Shop the Latest Styles: Stay ahead of the curve with our newest arrivals
                </p>
            </div>

            <div class="grid-layout loadmore-item wow fadeInUp" data-wow-delay="0s" data-grid="grid-4">

                @foreach ($products as $product)
                    @php
                        $product_image = $product->getImage();
                        $product_price = $product->getPrice();
                        $color_property_values = getProductPropertyValues($product->id, 'Color');
                    @endphp

                    <div class="card-product fl-item">
                        <div class="card-product-wrapper">

                            <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}"
                                class="product-img">

                                <img class="lazyload img-product" data-src="{{ $product_image }}"
                                    src="{{ $product_image }}" alt="image-product">

                                <img class="lazyload img-hover" data-src="{{ $product_image }}"
                                    src="{{ $product_image }}" alt="image-product">

                            </a>

                            @if ($product->isOutOfStock())
                                <div class="badge-out-of-stock"
                                    style="position:absolute;top:10px;left:10px;background:#f5f5ec;color:#36614B;padding:5px 10px;border-radius:6px;font-size:12px;">
                                    Out of Stock
                                </div>
                            @endif


                            <div class="list-product-btn">

                                @if (!$product->isOutOfStock())
                                    <a href="#" class="box-icon bg_white quick-add tf-btn-loading add-to-cart-btn"
                                        data-id="{{ $product->id }}"
                                        data-primary-property-values='@json($product->primary_property_values)'>

                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                @endif


                                <a href="javascript:void(0);"
                                    class="box-icon bg_white tf-btn-loading btn-icon-action product-wishlist {{ $product->is_wishlisted ? 'active' : '' }}"
                                    data-id="{{ $product->id }}">

                                    <span class="icon icon-heart"></span>

                                    <span class="tooltip" id="wishlist-tooltip-{{ $product->id }}">
                                        {{ $product->is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                                    </span>

                                    <span class="icon icon-delete"></span>
                                </a>

                            </div>

                        </div>


                        <div class="card-product-info">

                            <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}"
                                class="title link">
                                {{ $product->name }}
                            </a>

                            <div class="tf-product-info-price">

                                <span class="price">
                                    @if ($product_price)
                                        {{ toCurrency($product_price->selling_price) }}
                                    @endif
                                </span>

                                @if ($product_price && $product_price->actual_price > $product_price->selling_price)
                                    <span class="compare-at-price" style="text-decoration: line-through; color:#777;">
                                        {{ toCurrency($product_price->actual_price) }}
                                    </span>
                                @endif

                            </div>


                            <ul class="list-color-product">

                                @if ($color_property_values->isNotEmpty())
                                    @foreach ($color_property_values as $color_property_value)
                                        <li class="list-color-item color-swatch">

                                            <span class="swatch-value"
                                                style="background: {{ $color_property_value->color_code }}"></span>

                                        </li>
                                    @endforeach
                                @endif

                            </ul>

                        </div>

                    </div>
                @endforeach

            </div>


            <div class="tf-pagination-wrap view-more-button text-center">

                <button class="tf-btn-loading tf-loading-default style-2 btn-loadmore">
                    <span class="text">Load more</span>
                </button>

            </div>

        </div>
    </section>
    {{-- greenhouse --}}

    <section class="flat-spacing-6">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Shop the look</span>
            <p class="sub-title">Inspire and let yourself be inspired, from one unique fashion to another.</p>
        </div>
        <div dir="ltr" class="swiper tf-sw-lookbook" data-preview="2" data-tablet="2" data-mobile="1"
            data-space-lg="0" data-space-md="0">
            <div class="swiper-wrapper">
                <div class="swiper-slide" lazy="true">
                    <div class="wrap-lookbook lookbook-1">
                        <div class="image">
                            <img class="lazyload" data-src="/frontend/images/shop/file/lookbook-3.jpg"
                                src="/frontend/images/shop/file/lookbook-3.jpg" alt="image-lookbook">
                        </div>
                        <div class="lookbook-item item-1">
                            <div class="inner">
                                <div class="btn-group dropdown dropup dropdown-center">
                                    <button class="tf-pin-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span></span>
                                    </button>
                                    <ul class="dropdown-menu p-0 border-0">
                                        <li>
                                            <div class="lookbook-product">
                                                <a href="#0" class="image">
                                                    <img class="lazyload"
                                                        data-src="/frontend/images/shop/products/img-p2.png"
                                                        src="/frontend/images/shop/products/img-p2.png"
                                                        alt="lookbook-item">
                                                </a>
                                                <div class="content-wrap">
                                                    <div class="product-title">
                                                        <a href="#">Jersey thong body</a>
                                                    </div>
                                                    <div class="price">{{ toCurrency(112.0) }}</div>
                                                </div>
                                                <a href="#quick_view" data-bs-toggle="modal" class=""><i
                                                        class="icon-view"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="lookbook-item item-2">
                            <div class="inner">
                                <div class="btn-group dropdown dropup dropdown-center">
                                    <button class="tf-pin-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span></span>
                                    </button>
                                    <ul class="dropdown-menu p-0 border-0">
                                        <li>
                                            <div class="lookbook-product">
                                                <a href="#0" class="image">
                                                    <img class="lazyload"
                                                        data-src="/frontend/images/shop/products/img-p4.png"
                                                        src="/frontend/images/shop/products/img-p4.png" alt="">
                                                </a>
                                                <div class="content-wrap">
                                                    <div class="product-title">
                                                        <a href="#">Ribbed modal T-shirt</a>
                                                    </div>
                                                    <div class="price">{{ toCurrency(20.0) }}</div>
                                                </div>
                                                <a href="#quick_view" data-bs-toggle="modal" class=""><i
                                                        class="icon-view"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide" lazy="true">
                    <div class="wrap-lookbook lookbook-2">
                        <div class="image">
                            <img class="lazyload" data-src="/frontend/images/shop/file/lookbook-4.jpg"
                                src="/frontend/images/shop/file/lookbook-4.jpg" alt="image-lookbook">
                        </div>
                        <div class="lookbook-item item-1">
                            <div class="inner">
                                <div class="btn-group dropdown dropup dropdown-center">
                                    <button class="tf-pin-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span></span>
                                    </button>
                                    <ul class="dropdown-menu p-0 border-0">
                                        <li>
                                            <div class="lookbook-product">
                                                <a href="#0" class="image">
                                                    <img class="lazyload"
                                                        data-src="/frontend/images/shop/products/img-p5.png"
                                                        src="/frontend/images/shop/products/img-p5.png" alt="">
                                                </a>
                                                <div class="content-wrap">
                                                    <div class="product-title">
                                                        <a href="#">Ribbed Tank Top</a>
                                                    </div>
                                                    <div class="price">{{ toCurrency(20.0) }}</div>
                                                </div>
                                                <a href="#quick_view" data-bs-toggle="modal" class=""><i
                                                        class="icon-view"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap-pagination">
                <div class="container-full">
                    <div class="sw-dots sw-pagination-lookbook justify-content-center"></div>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonial -->
    @if ($testimonials->isNotEmpty())
        <section class="flat-spacing-5 pt_0 flat-testimonial">
            <div class="container">

                <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                    <span class="title">Happy Clients</span>
                    <p class="sub-title">Hear what they say about us</p>
                </div>

                <div class="wrap-carousel">
                    <div dir="ltr" class="swiper tf-sw-testimonial" data-preview="3" data-tablet="2"
                        data-mobile="1" data-space-lg="30" data-space-md="15">

                        <div class="swiper-wrapper">

                            @foreach ($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="testimonial-item style-column wow fadeInUp" data-wow-delay="0s">

                                        <div class="rating">
                                            <i class="icon-star"></i>
                                            <i class="icon-star"></i>
                                            <i class="icon-star"></i>
                                            <i class="icon-star"></i>
                                            <i class="icon-star"></i>
                                        </div>

                                        <div class="heading">
                                            {{ $testimonial->title }}
                                        </div>

                                        <div class="text">
                                            {{ $testimonial->description }}
                                        </div>

                                        <div class="author">
                                            <div class="name">
                                                {{ $testimonial->name }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="nav-sw nav-next-slider nav-next-testimonial lg">
                        <span class="icon icon-arrow-left"></span>
                    </div>

                    <div class="nav-sw nav-prev-slider nav-prev-testimonial lg">
                        <span class="icon icon-arrow-right"></span>
                    </div>

                    <div class="sw-dots style-2 sw-pagination-testimonial justify-content-center"></div>

                </div>

            </div>
        </section>
    @endif
    <!-- /Testimonial -->

    <section class="flat-spacing-5 pt_0">
        <div class="container">
            <div dir="ltr" class="swiper tf-sw-brand" data-loop="false" data-play="false" data-preview="6"
                data-tablet="3" data-mobile="2" data-space-lg="0" data-space-md="0">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="brand-item">
                            <img class="lazyload" data-src="/frontend/images/brand/brand-01.png"
                                src="/frontend/images/brand/brand-01.png" alt="image-brand">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-item">
                            <img class="lazyload" data-src="/frontend/images/brand/brand-02.png"
                                src="/frontend/images/brand/brand-02.png" alt="image-brand">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-item">
                            <img class="lazyload" data-src="/frontend/images/brand/brand-03.png"
                                src="/frontend/images/brand/brand-03.png" alt="image-brand">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-item">
                            <img class="lazyload" data-src="/frontend/images/brand/brand-04.png"
                                src="/frontend/images/brand/brand-04.png" alt="image-brand">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-item">
                            <img class="lazyload" data-src="/frontend/images/brand/brand-05.png"
                                src="/frontend/images/brand/brand-05.png" alt="image-brand">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-item">
                            <img class="lazyload" data-src="/frontend/images/brand/brand-06.png"
                                src="/frontend/images/brand/brand-06.png" alt="image-brand">
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dots style-2 sw-pagination-brand justify-content-center"></div>
        </div>
    </section>



    @if ($collections->isNotEmpty())
        @foreach ($collections as $collection)
            @php
                $products = getCollectionProducts($collection);
            @endphp
            <section class="flat-spacing-11">
                <div class="container">
                    <div class="flat-title">
                        <span class="title wow fadeInUp" data-wow-delay="0s">{{ $collection->name }}</span>
                    </div>
                    <div class="hover-sw-nav hover-sw-2">
                        <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4"
                            data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2"
                            data-pagination-md="3" data-pagination-lg="3">
                            <div class="swiper-wrapper">
                                @if ($products->isNotEmpty())
                                    @foreach ($products as $product)
                                        @php
                                            $product_image = $product->getImage();
                                            $product_price = $product->getPrice();
                                        @endphp
                                        <div class="swiper-slide" lazy="true">
                                            <div class="card-product style-3">
                                                <div class="card-product-wrapper">
                                                    <a href="{{ route('frontend.products.product-details', ['slug' => $product->slug]) }}"
                                                        class="product-img">
                                                        <img class="lazyload img-product" data-src="{{ $product_image }}"
                                                            src="{{ $product_image }}" alt="image-product">
                                                        <img class="lazyload img-hover" data-src="{{ $product_image }}"
                                                            src="{{ $product_image }}" alt="image-product">
                                                    </a>
                                                    <div class="list-product-btn column-right">
                                                        <a href="javascript:void(0);"
                                                            class="box-icon bg_white wishlist tf-btn-loading btn-icon-action product-wishlist {{ $product->is_wishlisted ? 'active' : '' }}"
                                                            data-id="{{ $product->id }}">
                                                            <span class="icon icon-heart"></span>
                                                            <span class="tooltip"
                                                                id="wishlist-tooltip-{{ $product->id }}">
                                                                {{ $product->is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                                                            </span>
                                                            <span class="icon icon-delete"></span>
                                                        </a>
                                                        {{-- <a href="#quick_view" data-bs-toggle="modal"
                                                            class="box-icon bg_white quickview tf-btn-loading">
                                                            <span class="icon icon-view"></span>
                                                            <span class="tooltip">Quick View</span>
                                                        </a> --}}
                                                    </div>
                                                    <div class="list-product-btn absolute-3">
                                                        <a href="#"
                                                            class="box-icon bg_white quick-add tf-btn-loading style-2 add-to-cart-btn"
                                                            data-id="{{ $product->id }}"
                                                            data-primary-property-values='@json($product->primary_property_values)'>
                                                            <i class="fas fa-cart-plus"></i>
                                                            <span class="text">QUICK ADD</span>
                                                        </a>
                                                    </div>
                                                    @php $size_property_values = getProductPropertyValues($product->id, 'Size') @endphp
                                                    @if ($size_property_values->isNotEmpty())
                                                        <div class="size-list style-2">
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
                                                        <div class="price-on-sale text_black"
                                                            style="font-size:14px; font-weight: 600;">
                                                            @if ($product_price)
                                                                {{ toCurrency($product_price->selling_price) }}
                                                            @endif
                                                        </div>

                                                        <div class="compare-at-price"
                                                            style="font-size:14px; font-weight: 600;">
                                                            @if ($product_price)
                                                                {{ toCurrency($product_price->actual_price) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <ul class="list-color-product">
                                                        @php $color_property_values = getProductPropertyValues($product->id, 'Color') @endphp
                                                        @if ($color_property_values->isNotEmpty())
                                                            @foreach ($color_property_values as $color_property_value)
                                                                <li class="list-color-item color-swatch">
                                                                    <span
                                                                        class="tooltip">{{ $color_property_value->propertyValue->name }}
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
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span
                                class="icon icon-arrow-left"></span></div>
                        <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span
                                class="icon icon-arrow-right"></span></div>
                        <div class="sw-dots style-2 sw-pagination-product justify-content-center"></div>
                    </div>
                </div>
            </section>
        @endforeach
    @endif


    <section class="flat-spacing-7">
        <div class="container">
            <div class="flat-title wow fadeInUp" data-wow-delay="0s">
                <span class="title">Shop Gram</span>
                <p class="sub-title">
                    Inspire and let yourself be inspired, from one unique fashion to another.
                </p>
            </div>

            <div class="wrap-carousel wrap-shop-gram">
                <div dir="ltr" class="swiper tf-sw-shop-gallery" data-preview="5" data-tablet="3" data-mobile="2"
                    data-space-lg="7" data-space-md="7">

                    <div class="swiper-wrapper">

                        @foreach ($products as $product)
                            @php
                                $product_image = $product->getImage();
                            @endphp

                            <div class="swiper-slide">
                                <div class="gallery-item hover-img wow fadeInUp" data-wow-delay=".2s">

                                    <!-- Product Image -->
                                    <div class="img-style">
                                        <img class="lazyload img-hover" data-src="{{ $product_image }}"
                                            src="{{ $product_image }}" alt="{{ $product->name }}">
                                    </div>

                                    <!-- Quick Add Button -->
                                    @if (!$product->isOutOfStock())
                                        <a href="javascript:void(0);" class="box-icon add-to-cart-btn tf-btn-loading"
                                            data-id="{{ $product->id }}"
                                            data-primary-property-values='@json($product->primary_property_values)'>

                                            <span class="icon icon-bag"></span>
                                            <span class="tooltip">Quick Add</span>
                                        </a>
                                    @else
                                        <a href="javascript:void(0);" class="box-icon">
                                            <span class="tooltip">Out of Stock</span>
                                        </a>
                                    @endif

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="sw-dots sw-pagination-gallery justify-content-center"></div>
            </div>
        </div>
    </section>

    <!-- Icon Cards Section -->
    <section class="flat-spacing-5 pt_0">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="tf-icon-box style-bordred text-center wow fadeInUp" data-wow-delay="0s">
                        <div class="icon">
                            <i class="icon-shipping"></i>
                        </div>
                        <div class="content">
                            <h6>Free Shipping</h6>
                            <p>Free shipping over order {{ toCurrency(100) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="tf-icon-box style-bordred text-center wow fadeInUp" data-wow-delay="0.1s">
                        <div class="icon">
                            <i class="icon-card"></i>
                        </div>
                        <div class="content">
                            <h6>Flexible Payment</h6>
                            <p>Pay with Multiple Credit Cards</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="tf-icon-box style-bordred text-center wow fadeInUp" data-wow-delay="0.2s">
                        <div class="icon">
                            <i class="icon-return"></i>
                        </div>
                        <div class="content">
                            <h6>14 Day Returns</h6>
                            <p>Within 30 days for an exchange</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="tf-icon-box style-bordred text-center wow fadeInUp" data-wow-delay="0.3s">
                        <div class="icon">
                            <i class="icon-premium-support"></i>
                        </div>
                        <div class="content">
                            <h6>Premium Support</h6>
                            <p>Outstanding premium support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .tf-icon-box.style-bordred {
            border: 1px solid #e5e5e5;
            padding: 40px 20px;
            border-radius: 10px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .tf-icon-box.style-bordred:hover {
            border-color: #000;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .tf-icon-box .icon {
            font-size: 40px;
            margin-bottom: 20px;
            color: #000;
        }

        .tf-icon-box h6 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .tf-icon-box p {
            font-size: 14px;
            color: #777;
            margin-bottom: 0;
        }
    </style>
@endsection
