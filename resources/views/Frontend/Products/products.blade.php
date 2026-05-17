@extends('layouts.frontend')
@section('title')
    Products | Itsroop
@endsection


<style>
    /* ── Google Font ── */
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --cream:   #F7F4EF;
        --ink:     #1C1C1C;
        --sage:    #000000ff;
        --sage-lt: #EAF0EC;
        --dust:    #D9D0C5;
        --white:   #FFFFFF;
        --pill-r:  999px;
        --radius:  12px;
        --shadow:  0 4px 24px rgba(28,28,28,.08);
    }

    body { background: var(--cream); font-family: 'DM Sans', sans-serif; }

    /* ── Page Header ── */
    .itsroop-header {
        padding: 48px 0 32px;
        margin-top: 20px;           /* breathing room below the nav */
        text-align: center;
    }
    .itsroop-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        color: var(--ink);
        letter-spacing: -.5px;
        margin: 0 0 6px;
    }
    .itsroop-header p {
        font-size: .95rem;
        color: #888;
        margin: 0;
    }

    /* ── Category Pills ── */
    .cat-scroll {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 8px 18px 16px;        /* more top padding, more bottom clearance */
        margin-bottom: 12px;        /* extra gap before the filter toggle / layout */
        scrollbar-width: none;
    }
    .cat-scroll::-webkit-scrollbar { display: none; }
    .cat-pill {
        flex: 0 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
    .cat-pill img {
        width: 68px; height: 68px;
        border-radius: 50%;
        object-fit: cover;
        border: 2.5px solid transparent;
        transition: border-color .2s, transform .2s;
    }
    .cat-pill:hover img,
    .cat-pill.active img { border-color: var(--sage); transform: scale(1.06); }
    .cat-pill span {
        font-size: .75rem;
        font-weight: 500;
        color: var(--ink);
        white-space: nowrap;
    }
    .cat-pill.active span { color: var(--sage); font-weight: 600; }

    /* ── Shop layout bottom clearance ── */
    .shop-layout {
        display: grid;
        grid-template-columns: 272px 1fr;
        gap: 28px;
        align-items: start;
        padding-bottom: 60px;       /* space above the footer */
    }
    @media (max-width: 991px) {
        /* On mobile: products take full width, sidebar is an overlay */
        .shop-layout {
            grid-template-columns: 1fr;
        }
    }

    /* ── Filter Sidebar ── */
    .filter-sidebar {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px 20px;
        position: sticky;
        top: 90px;
        /* Constrain height so sidebar never bleeds into the footer */
        max-height: calc(100vh - 110px);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--dust) transparent;
    }
    .filter-sidebar::-webkit-scrollbar { width: 4px; }
    .filter-sidebar::-webkit-scrollbar-track { background: transparent; }
    .filter-sidebar::-webkit-scrollbar-thumb { background: var(--dust); border-radius: 4px; }

    /* ── Mobile: sidebar becomes a full-screen slide-in drawer ── */
    @media (max-width: 991px) {
        .filter-sidebar {
            /* Hidden off-screen to the left by default */
            display: block !important;           /* override the old display:none */
            position: fixed;
            top: 0;
            left: 0;
            width: min(320px, 88vw);
            height: 100%;
            overflow-y: auto;
            z-index: 1050;
            border-radius: 0;
            padding: 20px 18px 100px;
            transform: translateX(-110%);        /* slide out of view */
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            box-shadow: 4px 0 32px rgba(0,0,0,.18);
        }
        .filter-sidebar.open {
            transform: translateX(0);            /* slide in */
        }
    }

    /* ── Backdrop (mobile only) ── */
    #filter-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1049;
        backdrop-filter: blur(2px);
    }
    #filter-backdrop.show { display: block; }

    /* ── Close button inside sidebar (mobile only) ── */
    .btn-close-sidebar {
        display: none;
        width: 100%;
        margin-bottom: 18px;
        padding: 11px 14px;
        background: var(--sage);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    @media (max-width: 991px) {
        .btn-close-sidebar { display: flex; }
    }

    .filter-sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .filter-sidebar-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        color: var(--ink);
        margin: 0;
    }
    .btn-clear-all {
        font-size: .78rem;
        color: var(--sage);
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        padding: 0;
        display: none;
    }
    .btn-clear-all.visible { display: inline; }

    /* Filter Group */
    .fg { margin-bottom: 22px; border-top: 1px solid var(--dust); padding-top: 18px; }
    .fg:first-of-type { border-top: none; padding-top: 0; }
    .fg-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        margin-bottom: 12px;
    }
    .fg-head span:first-child {
        font-size: .82rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ink);
    }
    .fg-head .arrow {
        width: 16px; height: 16px;
        border-right: 1.5px solid #aaa;
        border-bottom: 1.5px solid #aaa;
        transform: rotate(45deg);
        transition: transform .2s;
        flex-shrink: 0;
    }
    .fg.collapsed .fg-head .arrow { transform: rotate(-135deg); }
    .fg-body { overflow: hidden; transition: max-height .3s ease; }
    .fg.collapsed .fg-body { max-height: 0 !important; }

    /* Radio Pills */
    .pill-group { display: flex; flex-wrap: wrap; gap: 7px; }
    .pill-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: var(--pill-r);
        border: 1.5px solid var(--dust);
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .18s;
        user-select: none;
        color: var(--ink);
    }
    .pill-label:hover { border-color: var(--sage); color: var(--sage); }
    input[type="radio"].pill-input { display: none; }
    input[type="radio"].pill-input:checked + .pill-label {
        background: var(--sage);
        border-color: var(--sage);
        color: #fff;
    }

    /* Color Swatches */
    .swatch-group { display: flex; flex-wrap: wrap; gap: 8px; }
    .swatch-wrap { position: relative; }
    .swatch-wrap input[type="radio"] { display: none; }
    .swatch-dot {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: block;
        border: 2px solid transparent;
        cursor: pointer;
        transition: transform .15s, box-shadow .15s;
        position: relative;
    }
    .swatch-dot::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid transparent;
        transition: border-color .15s;
    }
    .swatch-dot:hover { transform: scale(1.12); }
    .swatch-wrap input:checked + .swatch-dot::after { border-color: var(--sage); }
    .swatch-dot[title] { cursor: pointer; }

    /* Sub-category links */
    .subcat-list { display: flex; flex-direction: column; gap: 4px; }
    .subcat-link {
        font-size: .83rem;
        color: #666;
        text-decoration: none;
        padding: 5px 0;
        border-bottom: 1px dashed transparent;
        transition: color .15s, border-color .15s;
    }
    .subcat-link:hover { color: var(--sage); border-color: var(--dust); }

    /* ── Toolbar ── */
    .shop-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .applied-chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--sage-lt);
        color: var(--sage);
        border-radius: var(--pill-r);
        padding: 4px 11px 4px 13px;
        font-size: .78rem;
        font-weight: 500;
    }
    .chip-remove {
        background: none; border: none; cursor: pointer;
        color: var(--sage); font-size: .85rem; padding: 0; line-height: 1;
    }
    .product-count {
        font-size: .82rem;
        color: #888;
        white-space: nowrap;
    }

    /* Mobile filter toggle */
    .btn-filter-toggle {
        display: none;
        align-items: center;
        gap: 8px;
        background: var(--white);
        border: 1.5px solid var(--dust);
        border-radius: var(--pill-r);
        padding: 8px 18px;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        color: var(--ink);
    }
    @media (max-width: 991px) { .btn-filter-toggle { display: inline-flex; } }

    /* ── Product Grid ── */
    #products-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    @media (max-width: 1200px) { #products-container { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px)  { #products-container { grid-template-columns: repeat(2, 1fr); gap: 12px; } }

    /* Product Card */
    .card-product {
        background: var(--white);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(28,28,28,.06);
        transition: transform .22s, box-shadow .22s;
        position: relative;
    }
    .card-product:hover { transform: translateY(-4px); box-shadow: var(--shadow); }
    .card-product-wrapper { position: relative; overflow: hidden; }
    .card-product-wrapper .product-img { display: block; aspect-ratio: 3/4; overflow: hidden; }
    .card-product-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
    .card-product:hover .img-hover { transform: scale(1.04); }

    .badge-out-of-stock {
        position: absolute; top: 10px; left: 10px;
        background: rgba(247,244,239,.92);
        color: var(--sage); padding: 4px 10px;
        border-radius: 6px; font-size: .72rem; font-weight: 700;
        z-index: 9;
    }
    .discount-badge {
        position: absolute; top: 10px; right: 10px;
        background: var(--sage); color: #fff;
        padding: 4px 8px; border-radius: 6px;
        font-size: .7rem; font-weight: 700; z-index: 9;
    }

    .list-product-btn {
        position: absolute; bottom: 10px; right: 10px;
        display: flex; flex-direction: column; gap: 6px;
        opacity: 0; transform: translateX(8px);
        transition: opacity .22s, transform .22s;
    }
    .card-product:hover .list-product-btn { opacity: 1; transform: translateX(0); }
    .box-icon {
        width: 36px; height: 36px;
        background: var(--white);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,.12);
        text-decoration: none; color: var(--ink);
        font-size: .85rem; transition: background .15s, color .15s;
        cursor: pointer;
    }
    .box-icon:hover, .box-icon.active { background: var(--sage); color: #fff; }

    .size-list {
        display: flex; gap: 4px; padding: 8px 10px 4px;
    }
    .size-list span {
        font-size: .68rem; font-weight: 600;
        border: 1px solid var(--dust); border-radius: 4px;
        padding: 2px 6px; color: #666;
    }

    .card-product-info { padding: 12px 14px 16px; }
    .card-product-info .title {
        font-size: .88rem; font-weight: 500; color: var(--ink);
        text-decoration: none; display: block;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        margin-bottom: 6px;
    }
    .tf-product-info-price { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; margin-bottom: 8px; }
    .price-on-sale { font-size: .92rem; font-weight: 700; color: var(--ink); }
    .compare-at-price { font-size: .8rem; color: #aaa; text-decoration: line-through; }
    .discount-percentage { font-size: .75rem; font-weight: 700; color: #E05C2A; }

    .list-color-product { list-style: none; padding: 0; margin: 0; display: flex; gap: 5px; flex-wrap: wrap; }
    .color-swatch { position: relative; }
    .swatch-value {
        display: block; width: 16px; height: 16px;
        border-radius: 50%; border: 1.5px solid rgba(0,0,0,.1);
        cursor: pointer; transition: transform .15s;
    }
    .swatch-value:hover { transform: scale(1.2); }
    .tooltip { display: none; }

    /* Empty State */
    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state svg { margin-bottom: 16px; opacity: .3; }
    .empty-state p { font-size: 1rem; }

    /* Loading Skeleton */
    .skeleton-card {
        background: var(--white);
        border-radius: var(--radius);
        overflow: hidden;
        animation: shimmer 1.4s infinite;
    }
    .skeleton-img { aspect-ratio: 3/4; background: #eee; }
    .skeleton-line { height: 12px; background: #eee; border-radius: 4px; margin: 10px 14px; }
    .skeleton-line.short { width: 60%; }
    @keyframes shimmer {
        0%,100% { opacity: 1; }
        50% { opacity: .5; }
    }

    /* Spinner */
    #filter-loading {
        display: none;
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(247,244,239,.6);
        align-items: center; justify-content: center;
    }
    #filter-loading.show { display: flex; }
    .spinner {
        width: 40px; height: 40px;
        border: 3px solid var(--dust);
        border-top-color: var(--sage);
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

@section('content')
{{-- Loading Overlay --}}
<div id="filter-loading"><div class="spinner"></div></div>

{{-- Mobile Filter Backdrop --}}
<div id="filter-backdrop"></div>

<div class="itsroop-header">
    <h1>New Arrivals</h1>
    <p>Shop through our latest selection of fashion</p>
</div>

<div class="container">

    {{-- Category Pills --}}
    <div class="cat-scroll mb-4">
        @foreach ($product_categories as $category)
            <a href="{{ route('frontend.products', ['category_slug' => $category->slug]) }}"
               class="cat-pill {{ isset($selected_category_slug) && $selected_category_slug === $category->slug ? 'active' : '' }}">
                <img src="{{ asset(Storage::url($category->image)) }}" alt="{{ $category->name }}">
                <span>{{ $category->name }}</span>
            </a>
        @endforeach
    </div>

    {{-- Mobile Filter Toggle --}}
    <button class="btn-filter-toggle mb-3" id="mobile-filter-toggle">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="10" y1="18" x2="14" y2="18"/>
        </svg>
        Filters
    </button>

    <div class="shop-layout">

        {{-- ── Filter Sidebar ── --}}
        <aside class="filter-sidebar" id="filter-sidebar">

            {{-- Close button: visible on mobile only --}}
            <button class="btn-close-sidebar" id="close-filter-sidebar">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Close Filters
            </button>

            <div class="filter-sidebar-header">
                <h3>Filters</h3>
                <button class="btn-clear-all" id="btn-clear-all">Clear all</button>
            </div>

            {{-- Sub Categories --}}
            @if($subCategories->isNotEmpty())
            <div class="fg" data-group="subcat">
                <div class="fg-head">
                    <span>Sub Category</span>
                    <span class="arrow"></span>
                </div>
                <div class="fg-body" style="max-height:200px">
                    <div class="subcat-list">
                        @foreach ($subCategories as $subCategory)
                            <a href="{{ request()->fullUrlWithQuery(['sub_category' => $subCategory->name]) }}"
                               class="subcat-link {{ request('sub_category') == $subCategory->name ? 'fw-semibold' : '' }}">
                                {{ $subCategory->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Dynamic Properties --}}
            @if ($properties->isNotEmpty())
                @foreach ($properties as $property)
                    @php
                        $property_values = $property->propertyValues()->orderBy('index','asc')->get();
                    @endphp

                    @if ($property->label == 'Color')
                    <div class="fg" data-group="color">
                        <div class="fg-head">
                            <span>{{ $property->label }}</span>
                            <span class="arrow"></span>
                        </div>
                        <div class="fg-body" style="max-height:200px">
                            <div class="swatch-group">
                                @foreach ($property_values as $pv)
                                    <div class="swatch-wrap" title="{{ $pv->name }}">
                                        <input type="radio" name="{{ $property->label }}"
                                               class="pill-input property-value color-radio"
                                               id="color_{{ $pv->id }}"
                                               value="{{ $pv->id }}"
                                               data-label="{{ $pv->name }}">
                                        <label for="color_{{ $pv->id }}"
                                               class="swatch-dot"
                                               style="background:{{ $pv->color }}"
                                               title="{{ $pv->name }}"></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="fg" data-group="{{ $property->label }}">
                        <div class="fg-head">
                            <span>{{ $property->label }}</span>
                            <span class="arrow"></span>
                        </div>
                        <div class="fg-body" style="max-height:200px">
                            <div class="pill-group">
                                @foreach ($property_values as $pv)
                                    <input type="radio" name="{{ $property->label }}"
                                           class="pill-input property-value"
                                           id="prop_{{ $pv->id }}"
                                           value="{{ $pv->id }}"
                                           data-label="{{ $pv->name }}">
                                    <label for="prop_{{ $pv->id }}" class="pill-label">{{ $pv->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif

            {{-- Gender --}}
            <div class="fg" data-group="gender">
                <div class="fg-head"><span>Gender</span><span class="arrow"></span></div>
                <div class="fg-body" style="max-height:200px">
                    <div class="pill-group">
                        @foreach(['Men','Women','Unisex'] as $g)
                            <input type="radio" name="gender" class="pill-input gender-filter" id="g_{{ $g }}" value="{{ $g }}"
                                data-label="{{ $g }}"
                                {{ ($selected_gender ?? '') === $g ? 'checked' : '' }}>
                            <label for="g_{{ $g }}" class="pill-label">{{ $g }}</label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sort by Price --}}
            <div class="fg" data-group="price">
                <div class="fg-head"><span>Sort by Price</span><span class="arrow"></span></div>
                <div class="fg-body" style="max-height:200px">
                    <div class="pill-group">
                        <input type="radio" name="price_sort" class="pill-input price-sort" id="p_lth" value="low_to_high" data-label="Price: Low → High">
                        <label for="p_lth" class="pill-label">Low → High</label>
                        <input type="radio" name="price_sort" class="pill-input price-sort" id="p_htl" value="high_to_low" data-label="Price: High → Low">
                        <label for="p_htl" class="pill-label">High → Low</label>
                    </div>
                </div>
            </div>

            {{-- Sort by Rating --}}
            <div class="fg" data-group="rating">
                <div class="fg-head"><span>Sort by Rating</span><span class="arrow"></span></div>
                <div class="fg-body" style="max-height:200px">
                    <div class="pill-group">
                        <input type="radio" name="rating_sort" class="pill-input rating-sort" id="r_lth" value="low_to_high" data-label="Rating: Low → High">
                        <label for="r_lth" class="pill-label">Low → High</label>
                        <input type="radio" name="rating_sort" class="pill-input rating-sort" id="r_htl" value="high_to_low" data-label="Rating: High → Low">
                        <label for="r_htl" class="pill-label">High → Low</label>
                    </div>
                </div>
            </div>

            {{-- Stock --}}
            <div class="fg" data-group="stock">
                <div class="fg-head"><span>Stock</span><span class="arrow"></span></div>
                <div class="fg-body" style="max-height:200px">
                    <div class="pill-group">
                        <input type="radio" name="stock_sort" class="pill-input stock-sort" id="s_avail" value="available" data-label="In Stock">
                        <label for="s_avail" class="pill-label">In Stock</label>
                        <input type="radio" name="stock_sort" class="pill-input stock-sort" id="s_out" value="out_of_stock" data-label="Out of Stock">
                        <label for="s_out" class="pill-label">Out of Stock</label>
                    </div>
                </div>
            </div>

        </aside>

        {{-- ── Product Area ── --}}
        <div>
            {{-- Toolbar --}}
            <div class="shop-toolbar">
                <div class="applied-chips" id="applied-chips"></div>
                <span class="product-count" id="product-count"></span>
            </div>

            @if ($products->isNotEmpty())
            <div id="products-container">
                @foreach ($products as $product)
                    @php
                        $product_image = $product->getImage();
                        $product_price = $product->getPrice();
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
            </div>
            @else
            <div id="products-container">
                <div class="empty-state">
                    <svg width="64" height="64" fill="none" stroke="#000000ff" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <p>No products found</p>
                </div>
            </div>
            @endif
        </div>

    </div>{{-- /shop-layout --}}
</div>{{-- /container --}}

<script>
$(document).ready(function () {

    // ── Clear any stale/ghost checked states on page load ──
    $('input[type="radio"]').prop('checked', false);

    // ── Collapsible filter groups ──────────────────────────────
    $('.fg-head').on('click', function () {
        $(this).closest('.fg').toggleClass('collapsed');
    });

    // ── Mobile sidebar open ────────────────────────────────────
    $('#mobile-filter-toggle').on('click', function () {
        $('#filter-sidebar').addClass('open');
        $('#filter-backdrop').addClass('show');
        $('body').css('overflow', 'hidden'); // prevent background scroll
    });

    // ── Mobile sidebar close (button or backdrop tap) ──────────
    $('#close-filter-sidebar, #filter-backdrop').on('click', function () {
        $('#filter-sidebar').removeClass('open');
        $('#filter-backdrop').removeClass('show');
        $('body').css('overflow', '');
    });

    // ── Core filter function ───────────────────────────────────
    function applyFilters() {
        $('#filter-loading').addClass('show');

        const propertyValues = getCheckedValues('.property-value');
        const priceSort      = $('input.price-sort:checked').val()  || '';
        const ratingSort     = $('input.rating-sort:checked').val() || '';
        const stockSort      = $('input.stock-sort:checked').val()  || '';
        const gender         = $('input.gender-filter:checked').val() || '';

        const urlParams      = new URLSearchParams(window.location.search);
        const search         = urlParams.get('search') || '';
        const subCategory    = urlParams.get('sub_category') || '';

        const pathParts      = window.location.pathname.split('/products/');
        const categorySlug   = pathParts[1] ? decodeURIComponent(pathParts[1]) : '';
        const genderSlugs    = ['men', 'women', 'unisex'];
        const effectiveCat   = (categorySlug && !genderSlugs.includes(categorySlug.toLowerCase())) ? categorySlug : '';

        $.ajax({
            url: "{{ route('frontend.products.filters') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category_slug:    effectiveCat,
                property_values:  propertyValues,
                price_sort:       priceSort,
                rating_sort:      ratingSort,
                stock_sort:       stockSort,
                gender:           gender,
                search:           search,
                sub_category:     subCategory
            },
            success: function (html) {
                $('#products-container').html(html);
                updateChips();
                updateCount();
            },
            error: function (err) {
                console.error('Filter error:', err);
            },
            complete: function () {
                $('#filter-loading').removeClass('show');
            }
        });
    }

    // ── Collect checked values ────────────────────────────────
    function getCheckedValues(selector) {
        return $(selector + ':checked').map(function () { return $(this).val(); }).get();
    }

    // ── Applied filter chips ──────────────────────────────────
    function updateChips() {
        let chips  = '';
        let hasAny = false;

        $('input[type="radio"]:checked').each(function () {
            const val  = $(this).val();
            const name = $(this).attr('name');
            const id   = $(this).attr('id');

            if (!val) return;

            const dataLabel   = $(this).data('label');
            const labelText   = $('label[for="' + id + '"]').text().trim();
            const displayName = dataLabel || labelText || val;

            hasAny = true;
            chips += `<span class="chip" data-name="${name}">
                ${displayName}
                <button class="chip-remove" data-name="${name}" title="Remove">×</button>
            </span>`;
        });

        $('#applied-chips').html(chips);
        $('#btn-clear-all').toggleClass('visible', hasAny);
    }

    // ── Product count text ────────────────────────────────────
    function updateCount() {
        const n = $('#products-container .card-product').length;
        $('#product-count').text(n > 0 ? n + ' product' + (n !== 1 ? 's' : '') : '');
    }

    // ── Remove single chip ────────────────────────────────────
    $(document).on('click', '.chip-remove', function () {
        const name = $(this).data('name');
        $('input[name="' + name + '"]:checked').prop('checked', false);
        applyFilters();
    });

    // ── Clear all ─────────────────────────────────────────────
    $('#btn-clear-all').on('click', function () {
        $('input[type="radio"]:checked').prop('checked', false);
        applyFilters();
    });

    // ── Wire up filter change events ──────────────────────────
    $(document).on('change', '.property-value, .price-sort, .rating-sort, .stock-sort, .gender-filter', function () {
        applyFilters();
    });

    // ── Init count ────────────────────────────────────────────
    updateCount();
    updateChips();
});
</script>

@endsection