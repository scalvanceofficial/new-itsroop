@extends('layouts.frontend')
@section('title')
    Products | Itsroop
@endsection
@section('content')
    <style>
        /* ========== GOOGLE FONTS ========== */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

        :root {
            --brand: #2d6a4f;
            --brand-light: #40916c;
            --brand-pale: #d8f3dc;
            --accent: #f4a261;
            --dark: #1a1a2e;
            --mid: #4a4a6a;
            --muted: #8888a0;
            --bg: #f8f9fc;
            --card: #ffffff;
            --border: #e8e8f0;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(26,26,46,0.08);
            --shadow-hover: 0 8px 40px rgba(26,26,46,0.15);
        }

        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--dark); }

        /* ========== PAGE TITLE ========== */
        .tf-page-title {
            background: linear-gradient(135deg, #1a1a2e 0%, #2d6a4f 60%, #40916c 100%);
            padding: 36px 0 28px;
            position: relative;
            overflow: hidden;
        }
        .tf-page-title::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .tf-page-title .heading {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            position: relative;
        }
        .tf-page-title .heading::after {
            content: '';
            display: block;
            width: 48px;
            height: 3px;
            background: var(--accent);
            margin: 10px auto 0;
            border-radius: 2px;
        }

        /* ========== DESCRIPTION LIST STYLES ========== */
        .text-muted.description ul { list-style: none; padding-left: 0; margin: 1em 0; }
        .text-muted.description ol { list-style: decimal !important; padding-left: 1em; margin: 1em 0; }
        .text-muted.description ul li {
            position: relative; padding-left: 1em; margin-bottom: 0.5em; line-height: 1.6;
        }
        .text-muted.description ul li::before {
            content: ''; position: absolute; left: 0; top: 0.65em;
            width: 6px; height: 6px; background: var(--brand); border-radius: 50%;
        }

        /* ========== PRODUCT MEDIA LAYOUT ========== */
        .tf-product-media-wrap { position: relative; }

        /* ---- DESKTOP: vertical thumbs ---- */
        @media (min-width: 1200px) {
            .tf-product-media-wrap.sticky-top { top: 90px; }
            .thumbs-slider { display: flex; flex-direction: row; gap: 12px; align-items: flex-start; }

            .swiper.tf-product-media-thumbs {
                width: 82px; flex-shrink: 0; height: 520px; overflow: hidden;
            }
            .swiper.tf-product-media-thumbs .swiper-slide {
                height: 80px !important; width: 80px !important;
                cursor: pointer; border: 2px solid var(--border);
                border-radius: 10px; overflow: hidden; transition: border-color 0.2s, transform 0.2s;
            }
            .swiper.tf-product-media-thumbs .swiper-slide:hover { transform: scale(1.04); }
            .swiper.tf-product-media-thumbs .swiper-slide-thumb-active { border-color: var(--brand) !important; }
            .swiper.tf-product-media-thumbs .item img,
            .swiper.tf-product-media-thumbs .item video {
                width: 100%; height: 100%; object-fit: cover; display: block;
            }

            .swiper.tf-product-media-main { flex: 1; min-width: 0; border-radius: var(--radius); overflow: hidden; }
            .swiper.tf-product-media-main .swiper-slide { height: 520px; }
            .swiper.tf-product-media-main .swiper-slide .item { display: block; height: 100%; }
            .swiper.tf-product-media-main .swiper-slide .item img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .swiper.tf-product-media-main .swiper-slide video,
            .swiper.tf-product-media-main .swiper-slide iframe { width: 100%; height: 520px; object-fit: cover; }
        }

        /* ---- TABLET ---- */
        @media (min-width: 576px) and (max-width: 1199px) {
            .tf-product-media-wrap.sticky-top { position: relative !important; top: auto !important; }
            .thumbs-slider { display: flex; flex-direction: column; gap: 10px; }
            .swiper.tf-product-media-main { order: 1; width: 100%; border-radius: var(--radius); overflow: hidden; }
            .swiper.tf-product-media-thumbs {
                order: 2; width: 100% !important; height: 84px !important; overflow: hidden !important;
            }
            .swiper.tf-product-media-thumbs .swiper-wrapper {
                display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important;
                align-items: center !important; height: 80px !important; transform: translate3d(0,0,0) !important;
            }
            .swiper.tf-product-media-thumbs .swiper-slide {
                width: 76px !important; height: 76px !important; min-width: 76px !important;
                flex-shrink: 0 !important; margin-right: 8px !important;
                border: 2px solid var(--border); border-radius: 10px; overflow: hidden;
                cursor: pointer; transition: border-color 0.2s;
            }
            .swiper.tf-product-media-thumbs .swiper-slide-thumb-active { border-color: var(--brand) !important; }
            .swiper.tf-product-media-thumbs .item,
            .swiper.tf-product-media-thumbs .item img,
            .swiper.tf-product-media-thumbs .item video { display: block; width: 100%; height: 100%; object-fit: cover; }
            .swiper.tf-product-media-main .swiper-slide { height: 440px; }
            .swiper.tf-product-media-main .swiper-slide .item { display: block; height: 100%; }
            .swiper.tf-product-media-main .swiper-slide .item img { width: 100%; height: 100%; object-fit: cover; }
            .swiper.tf-product-media-main .swiper-slide video,
            .swiper.tf-product-media-main .swiper-slide iframe { width: 100%; height: 440px; }
        }

        /* ---- MOBILE ---- */
        @media (max-width: 575px) {
            .tf-product-media-wrap.sticky-top { position: relative !important; top: auto !important; }
            .thumbs-slider { display: flex; flex-direction: column; gap: 8px; }
            .swiper.tf-product-media-main { order: 1; width: 100%; border-radius: 10px; overflow: hidden; }
            .swiper.tf-product-media-thumbs {
                order: 2; width: 100% !important; height: 70px !important; overflow: hidden !important;
            }
            .swiper.tf-product-media-thumbs .swiper-wrapper {
                display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important;
                align-items: center !important; height: 66px !important; transform: translate3d(0,0,0) !important;
            }
            .swiper.tf-product-media-thumbs .swiper-slide {
                width: 60px !important; height: 60px !important; min-width: 60px !important;
                flex-shrink: 0 !important; margin-right: 6px !important;
                border: 2px solid var(--border); border-radius: 8px; overflow: hidden;
                cursor: pointer; transition: border-color 0.2s;
            }
            .swiper.tf-product-media-thumbs .swiper-slide-thumb-active { border-color: var(--brand) !important; }
            .swiper.tf-product-media-thumbs .item,
            .swiper.tf-product-media-thumbs .item img,
            .swiper.tf-product-media-thumbs .item video { display: block; width: 100%; height: 100%; object-fit: cover; }
            .swiper.tf-product-media-main .swiper-slide { height: 340px; }
            .swiper.tf-product-media-main .swiper-slide .item { display: block; height: 100%; }
            .swiper.tf-product-media-main .swiper-slide .item img { width: 100%; height: 100%; object-fit: cover; }
            .swiper.tf-product-media-main .swiper-slide video,
            .swiper.tf-product-media-main .swiper-slide iframe { width: 100%; height: 280px; }
        }

        /* ========== MAIN PRODUCT CARD ========== */
        .product-detail-card {
            background: var(--card);
            border-radius: 20px;
            box-shadow: var(--shadow);
            padding: 32px;
            margin-top: 0;
        }

        /* ========== PRODUCT TITLE ========== */
        .tf-product-info-title h5 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 700;
            color: var(--dark);
            line-height: 1.3;
            margin-bottom: 0;
        }

        /* ========== PRICE ========== */
        .tf-product-info-price {
            display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
            padding: 12px 0;
        }
        .price-on-sale.text_black {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 700; color: var(--dark);
        }
        .compare-at-price {
            font-size: 16px; color: var(--muted);
            text-decoration: line-through;
        }
        .discount-percentage {
            background: linear-gradient(135deg, #e63946, #c1121f);
            color: #fff; font-size: 13px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px; letter-spacing: 0.5px;
        }

        /* ========== TAG LINE ========== */
        .tf-product-info-list > div > h6.fw-6 {
            font-size: 15px; color: var(--mid); font-weight: 400;
            border-left: 3px solid var(--brand);
            padding-left: 10px; margin: 4px 0 12px;
        }

        /* ========== STOCK BADGE ========== */
        .tf-product-info-liveview {
            background: #fff3cd; border: 1px solid #ffc107;
            border-radius: 8px; padding: 8px 14px;
            display: inline-flex; align-items: center; gap: 8px; margin-bottom: 8px;
        }
        .tf-product-info-liveview .liveview-count {
            font-size: 13px; font-weight: 600; color: #856404;
        }

        /* ========== VARIANT PICKER ========== */
        .variant-picker-item { margin-bottom: 16px; }
        .variant-picker-label {
            font-size: 13px; font-weight: 600; color: var(--mid);
            text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px;
        }
        .variant-picker-label-value { color: var(--brand); text-transform: none; letter-spacing: 0; }
        .variant-picker-values { display: flex; flex-wrap: wrap; gap: 8px; }

        /* Hide native radio */
        input[type="radio"].property-value { display: none; }

        /* Size / text variant pill */
        .style-text {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 7px 18px; border-radius: 8px;
            border: 2px solid var(--border); background: var(--bg);
            font-size: 14px; font-weight: 500; color: var(--dark);
            cursor: pointer; transition: all 0.18s ease; min-height: 40px;
            user-select: none;
        }
        .style-text:hover { border-color: var(--brand); color: var(--brand); }
        input[type="radio"].property-value:checked + .style-text {
            border-color: var(--brand) !important;
            background: var(--brand) !important;
            color: #fff !important;
        }

        /* Color swatch dot — FIXED: always show the colored circle */
        .color-dot {
            display: inline-block;
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(0,0,0,0.15);
            flex-shrink: 0;
            vertical-align: middle;
        }
        .style-text .color-dot { margin-right: 0; }

        /* Color variant pill sizing */
        .style-text[data-is-color="true"] { gap: 7px; padding: 5px 14px; }

        /* ========== QUANTITY ========== */
        .tf-product-info-quantity { margin: 20px 0; }
        .quantity-title.fw-6 {
            font-size: 13px; font-weight: 600; color: var(--mid);
            text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px;
        }
        .wg-quantity {
            display: inline-flex; align-items: center;
            border: 2px solid var(--border); border-radius: 10px; overflow: hidden; background: var(--bg);
        }
        .btn-quantity {
            width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 300; cursor: pointer; color: var(--dark);
            transition: background 0.15s;
            user-select: none;
        }
        .btn-quantity:hover { background: var(--brand-pale); color: var(--brand); }
        .quantity-product {
            width: 52px; text-align: center; border: none; background: transparent;
            font-size: 16px; font-weight: 600; color: var(--dark);
        }

        /* ========== ADD TO CART BUTTON ========== */
        .tf-product-info-buy-button form { display: flex; gap: 10px; align-items: stretch; margin-bottom: 20px; }
        .btn-add-to-cart {
            flex: 1;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-light) 100%);
            color: #fff !important; border: none; border-radius: 12px;
            padding: 14px 24px; font-size: 15px; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            transition: all 0.22s ease; box-shadow: 0 4px 16px rgba(45,106,79,0.35);
            text-decoration: none;
        }
        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(45,106,79,0.45);
            color: #fff !important;
        }

        /* Wishlist button */
        .tf-product-btn-wishlist.box-icon {
            width: 52px; height: 52px; border-radius: 12px;
            border: 2px solid var(--border); background: var(--bg);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; position: relative; flex-shrink: 0;
        }
        .tf-product-btn-wishlist.box-icon:hover,
        .tf-product-btn-wishlist.box-icon.active { border-color: #e63946; background: #fff0f1; }
        .tf-product-btn-wishlist.box-icon .icon-heart { color: #e63946; font-size: 20px; }
        .tf-product-btn-wishlist.box-icon .icon-delete { display: none; font-size: 20px; color: #e63946; }
        .tf-product-btn-wishlist.box-icon.active .icon-heart { display: none; }
        .tf-product-btn-wishlist.box-icon.active .icon-delete { display: block; }
        .tf-product-btn-wishlist.box-icon .tooltip {
            position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%);
            background: var(--dark); color: #fff; font-size: 12px; white-space: nowrap;
            padding: 4px 10px; border-radius: 6px; opacity: 0; pointer-events: none; transition: opacity 0.2s;
        }
        .tf-product-btn-wishlist.box-icon:hover .tooltip { opacity: 1; }

        /* ========== DELIVERY / RETURN ========== */
        .tf-product-info-delivery-return {
            background: var(--bg); border-radius: 12px;
            padding: 16px 20px; margin-bottom: 20px; border: 1px solid var(--border);
        }
        .tf-product-delivery {
            display: flex; align-items: flex-start; gap: 12px;
        }
        .tf-product-delivery .icon { font-size: 22px; color: var(--brand); flex-shrink: 0; margin-top: 2px; }
        .tf-product-delivery p { font-size: 13px; color: var(--mid); line-height: 1.5; margin: 0; }
        .tf-product-delivery p .fw-7 { font-weight: 700; color: var(--dark); }

        /* ========== TRUST / PAYMENTS ========== */
        .tf-product-info-trust-seal {
            display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
            background: var(--brand-pale); border-radius: 12px; padding: 14px 20px;
        }
        .tf-product-trust-mess { display: flex; align-items: center; gap: 8px; }
        .tf-product-trust-mess i { font-size: 22px; color: var(--brand); }
        .tf-product-trust-mess p { font-size: 13px; font-weight: 600; color: var(--brand); margin: 0; line-height: 1.3; }
        .tf-payment { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .tf-payment img { height: 26px; width: auto; filter: saturate(0) brightness(0.6); opacity: 0.7; transition: all 0.2s; }
        .tf-payment img:hover { filter: none; opacity: 1; }

        /* ========== SECTION DIVIDER ========== */
        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 0 auto;
        }

        /* ========== DESCRIPTION / HIGHLIGHTS / INFO CARDS ========== */
        .info-card {
            background: var(--card); border-radius: var(--radius);
            border: 1px solid var(--border); padding: 28px;
            height: 100%; transition: box-shadow 0.25s, transform 0.25s;
            box-shadow: var(--shadow);
        }
        .info-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-3px); }
        .info-card h5 {
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 700; color: var(--dark);
            margin-bottom: 16px; padding-bottom: 12px;
            border-bottom: 2px solid var(--brand-pale);
        }
        .info-card .text-muted { font-size: 14px; line-height: 1.7; color: var(--mid); }
        .info-card .list-unstyled li { padding: 8px 0; border-bottom: 1px solid var(--border); }
        .info-card .list-unstyled li:last-child { border-bottom: none; }
        .info-card .list-unstyled li .prop-key {
            font-weight: 700; color: var(--brand); font-size: 13px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .info-card .list-unstyled li .prop-val { color: var(--mid); font-size: 14px; }

        /* ========== SECTION HEADINGS ========== */
        .section-heading {
            font-family: 'Playfair Display', serif;
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 700; color: var(--dark);
            text-align: center; margin-bottom: 8px;
        }
        .section-subline {
            width: 40px; height: 3px; background: var(--brand);
            border-radius: 2px; margin: 0 auto 32px;
        }

        /* ========== REVIEWS ========== */
        .review-section-wrap {
            background: var(--card); border-radius: var(--radius);
            border: 1px solid var(--border); padding: 28px;
            box-shadow: var(--shadow);
        }
        .review-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700; color: var(--dark);
            margin-bottom: 20px; padding-bottom: 14px;
            border-bottom: 2px solid var(--brand-pale);
        }
        .tab-reviews-heading .top {
            display: flex; gap: 32px; align-items: flex-start; flex-wrap: wrap; margin-bottom: 24px;
        }
        .tab-reviews-heading .number {
            font-family: 'Playfair Display', serif;
            font-size: 56px; font-weight: 700; line-height: 1; color: var(--dark);
        }
        .tab-reviews-heading .list-star { display: flex; gap: 4px; margin: 6px 0; }
        .tab-reviews-heading .list-star i { color: #f4a261; font-size: 16px; }
        .tab-reviews-heading .list-star i[style*="gray"] { color: var(--border) !important; }

        .rating-score { flex: 1; min-width: 220px; }
        .rating-score .item {
            display: flex; align-items: center; gap: 8px; margin-bottom: 6px;
        }
        .rating-score .item .number-1 { font-size: 13px; font-weight: 600; color: var(--mid); width: 12px; text-align: right; }
        .rating-score .item .number-2 { font-size: 12px; color: var(--muted); width: 24px; }
        .rating-score .item .icon-star { color: #f4a261; font-size: 12px; }
        .rating-score .item .line-bg {
            flex: 1; height: 7px; background: var(--bg); border-radius: 4px; overflow: hidden;
        }
        .rating-score .item .line-bg div { height: 100%; background: linear-gradient(90deg, var(--brand), var(--brand-light)); border-radius: 4px; transition: width 0.6s ease; }

        /* Review cards */
        .review-item-card {
            background: var(--bg); border-radius: 12px;
            padding: 18px; margin-bottom: 14px; border: 1px solid var(--border);
        }
        .review-item-card .user { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .review-item-card .user .image img {
            width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border);
        }
        .review-item-card .user h6 { font-size: 15px; font-weight: 600; color: var(--dark); margin: 0 0 2px; }
        .review-item-card .user .day { font-size: 12px; color: var(--muted); }
        .review-item-card p.text_black-2 { font-size: 14px; color: var(--mid); line-height: 1.6; margin: 0; }

        /* Write Review button */
        .btn-write-review, .btn-cancel-review {
            border: 2px solid var(--brand); color: var(--brand);
            background: transparent; border-radius: 8px;
            padding: 8px 18px; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s; margin-left: 8px;
        }
        .btn-write-review:hover { background: var(--brand); color: #fff; }

        /* Write Review Form */
        .form-write-review { margin-top: 24px; display: none; }
        .form-write-review .heading { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
        .form-write-review .heading h5 { font-size: 17px; font-weight: 700; color: var(--dark); margin: 0; }
        .box-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
        .box-field label.label { font-size: 13px; font-weight: 600; color: var(--mid); text-transform: uppercase; letter-spacing: 0.5px; }
        .box-field input, .box-field textarea {
            border: 2px solid var(--border); border-radius: 10px;
            padding: 10px 14px; font-size: 14px; font-family: 'DM Sans', sans-serif;
            color: var(--dark); background: var(--bg);
            transition: border-color 0.2s; outline: none;
        }
        .box-field input:focus, .box-field textarea:focus { border-color: var(--brand); background: #fff; }
        .button-submit { margin-top: 8px; }
        .tf-btn.btn-fill {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-light) 100%);
            color: #fff; border: none; border-radius: 10px;
            padding: 12px 28px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(45,106,79,0.3);
        }
        .tf-btn.btn-fill:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(45,106,79,0.4); }

        /* Star rating form */
        .list-rating-check { display: flex; flex-direction: row-reverse; gap: 4px; }
        .list-rating-check input { display: none; }
        .list-rating-check label {
            font-size: 28px; cursor: pointer; color: var(--border); transition: color 0.15s;
        }
        .list-rating-check label::before { content: '★'; }
        .list-rating-check input:checked ~ label,
        .list-rating-check label:hover,
        .list-rating-check label:hover ~ label { color: #f4a261; }

        /* ========== SWIPER NAV ARROWS ========== */
        .thumbs-next, .thumbs-prev {
            background: rgba(255,255,255,0.9) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            color: var(--dark) !important;
            border-radius: 50%; width: 36px !important; height: 36px !important;
        }
        .thumbs-next::after, .thumbs-prev::after { font-size: 14px !important; font-weight: 700; }

        /* ========== PEOPLE ALSO BOUGHT / RECENTLY VIEWED ========== */
        @media (max-width: 575px) {
            .col-6 { padding-left: 6px; padding-right: 6px; }
        }

        /* ========== RESPONSIVE TWEAKS ========== */
        @media (max-width: 767px) {
            .product-detail-card { padding: 16px; }
            .tf-product-info-buy-button .btn-add-to-cart { font-size: 14px; padding: 12px 16px; }
            .tf-product-info-trust-seal { flex-direction: column; align-items: flex-start; gap: 10px; }
            .info-card { padding: 18px; }
            .tab-reviews-heading .top { flex-direction: column; gap: 16px; }
        }

        /* ========== VIDEO SECTION ========== */
        @media (max-width: 767px) {
            #gallery-swiper-started .swiper-slide { height: 240px !important; }
            #gallery-swiper-started iframe, #gallery-swiper-started video { height: 240px !important; }
        }

        /* ========== IMAGE PREVIEW GRID ========== */
        .image-preview-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .image-preview-grid img { width: 72px; height: 72px; object-fit: cover; border-radius: 8px; border: 2px solid var(--border); }

        /* Flat spacing overrides */
        .flat-spacing-4 { padding: 48px 0; }
        .flat-spacing-8 { padding: 48px 0; }
        .flat-spacing-10 { padding: 48px 0; }
    </style>

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Your Products Is Here</div>
        </div>
    </div>

    <section class="flat-spacing-4 pt_0 mt-4">
        <div class="tf-main-product">
            <div class="container">
                <div class="product-detail-card">
                    <div class="row g-4">
                        <!-- ===== MEDIA COLUMN ===== -->
                        <div class="col-md-6">
                            <div class="tf-product-media-wrap sticky-top">
                                <div class="thumbs-slider">
                                    <!-- Thumbnail Swiper -->
                                    <div class="swiper tf-product-media-thumbs" id="thumbs-swiper">
                                        <div class="swiper-wrapper">
                                            @if ($product_images->isEmpty())
                                                <div class="swiper-slide">
                                                    <div class="item">
                                                        <img class="lazyload" src="{{ $product->getImage() }}"
                                                            data-src="{{ $product->getImage() }}" alt="img-product">
                                                    </div>
                                                </div>
                                            @else
                                                @foreach ($product_images as $product_image)
                                                    <div class="swiper-slide">
                                                        <div class="item">
                                                            <img class="lazyload"
                                                                src="{{ asset(Storage::url($product_image->image)) }}"
                                                                data-src="{{ asset(Storage::url($product_image->image)) }}"
                                                                alt="img-product">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if ($product->video_type === 'video' && $product->video)
                                                <div class="swiper-slide video-thumb-slide">
                                                    <div class="item">
                                                        <video playsinline autoplay preload="metadata" muted loop
                                                            src="{{ asset(Storage::url($product->video)) }}"
                                                            style="width:100%;height:100%;object-fit:cover;display:block;"></video>
                                                    </div>
                                                </div>
                                            @elseif ($product->video_type === 'youtube_link' && $product->youtube_link)
                                                @php
                                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]+)/', $product->youtube_link, $matches);
                                                    $youtube_id = $matches[1] ?? null;
                                                    $thumbnail = $youtube_id ? "https://img.youtube.com/vi/$youtube_id/0.jpg" : null;
                                                @endphp
                                                @if ($thumbnail)
                                                    <div class="swiper-slide video-thumb-slide">
                                                        <div class="item">
                                                            <img src="{{ $thumbnail }}" alt="YouTube Thumbnail"
                                                                style="width:100%;height:100%;object-fit:cover;display:block;" />
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Main Image Swiper -->
                                    <div class="swiper tf-product-media-main" id="main-swiper">
                                        <div class="swiper-wrapper">
                                            @if ($product_images->isEmpty())
                                                <div class="swiper-slide" data-color="beige">
                                                    <a href="{{ $product->getImage() }}" class="item"
                                                        data-pswp-width="770" data-pswp-height="1075">
                                                        <img class="lazyload" src="{{ $product->getImage() }}"
                                                            data-zoom="{{ $product->getImage() }}"
                                                            data-src="{{ $product->getImage() }}" alt="">
                                                    </a>
                                                </div>
                                            @else
                                                @foreach ($product_images as $product_image)
                                                    <div class="swiper-slide" data-color="beige">
                                                        <a href="{{ asset(Storage::url($product_image->image)) }}" class="item"
                                                            data-pswp-width="770" data-pswp-height="1075">
                                                            <img class="lazyload"
                                                                src="{{ asset(Storage::url($product_image->image)) }}"
                                                                data-zoom="{{ asset(Storage::url($product_image->image)) }}"
                                                                data-src="{{ asset(Storage::url($product_image->image)) }}" alt="">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if ($product->video_type === 'video' && $product->video)
                                                <div class="swiper-slide video-main-slide">
                                                    <div class="item">
                                                        <video playsinline autoplay preload="metadata" muted controls loop
                                                            src="{{ asset(Storage::url($product->video)) }}"></video>
                                                    </div>
                                                </div>
                                            @elseif ($product->video_type === 'youtube_link' && $product->youtube_link)
                                                @php
                                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]+)/', $product->youtube_link, $matches);
                                                    $youtube_id = $matches[1] ?? null;
                                                    $embed_link = $youtube_id ? "https://www.youtube.com/embed/$youtube_id" : null;
                                                @endphp
                                                @if ($embed_link)
                                                    <div class="swiper-slide video-main-slide">
                                                        <div class="item">
                                                            <iframe width="100%" height="100%"
                                                                src="{{ $embed_link }}"
                                                                title="YouTube video" frameborder="0"
                                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                        <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== INFO COLUMN ===== -->
                        <div class="col-md-6">
                            <div class="tf-product-info-wrap">
                                <div class="tf-product-info-list other-image-zoom">

                                    <!-- Title -->
                                    <div class="tf-product-info-title mb-2">
                                        <h5>{{ $product->name }}</h5>
                                    </div>

                                    <!-- Price -->
                                    @php $initial_price = $product->getPrice(); @endphp
                                    <div class="tf-product-info-price">
                                        <div class="price-on-sale text_black" id="sellingPrice">
                                            {{ $initial_price ? toCurrency($initial_price->selling_price) : '' }}
                                        </div>
                                        <div class="compare-at-price" id="actualPrice">
                                            {{ $initial_price && $initial_price->actual_price > $initial_price->selling_price ? toCurrency($initial_price->actual_price) : '' }}
                                        </div>
                                        @if ($initial_price && $initial_price->discount_percentage > 0)
                                            <div class="discount-percentage" id="discountPercentage">
                                                <span>{{ round($initial_price->discount_percentage) }}</span>% OFF
                                            </div>
                                        @else
                                            <div class="discount-percentage" id="discountPercentage"></div>
                                        @endif
                                    </div>

                                    <!-- Tagline -->
                                    <div>
                                        <h6 class="fw-6">{{ $product->tag_line }}</h6>
                                    </div>

                                    <!-- Stock -->
                                    <div class="tf-product-info-liveview"
                                        {!! $initial_price && $initial_price->stock > 1 ? 'style="display:none;"' : '' !!}>
                                        @if ($initial_price)
                                            @if ($initial_price->stock == 0)
                                                <span>⚠️</span>
                                                <div class="liveview-count" id="productStock">Out of Stock</div>
                                            @elseif ($initial_price->stock == 1)
                                                <span>🔥</span>
                                                <div class="liveview-count" id="productStock">Only 1 item left in stock!</div>
                                            @else
                                                <div class="liveview-count" id="productStock"></div>
                                            @endif
                                        @else
                                            <div class="liveview-count" id="productStock"></div>
                                        @endif
                                        <p class="fw-6" id="stockStatus"></p>
                                    </div>

                                    <!-- Variant Picker -->
                                    <div class="tf-product-info-variant-picker">
                                        @foreach ($product_property_labels as $property_label)
                                            @php
                                                $product_property_values = getProductPropertyValues($product->id, $property_label);
                                            @endphp
                                            @if ($product_property_values->isNotEmpty())
                                                <div class="variant-picker-item">
                                                    <div class="variant-picker-label">
                                                        {{ $property_label }}:
                                                        <span class="fw-6 variant-picker-label-value"></span>
                                                    </div>
                                                    <div class="variant-picker-values">
                                                        @foreach ($product_property_values as $product_property_value)
                                                            <input type="radio" class="property-value"
                                                                name="{{ $property_label }}"
                                                                id="{{ $product_property_value->id }}"
                                                                value="{{ $product_property_value->propertyValue->name }}"
                                                                {{ $product_property_value->is_primary === 'YES' ? 'checked' : '' }}
                                                                data-property-value-id="{{ $product_property_value->property_value_id }}"
                                                                data-image-property="{{ $product->primary_property_id == $product_property_value->property_id ? 'YES' : 'NO' }}">

                                                            @if ($product_property_value->property->is_color == 'YES')
                                                                {{-- COLOR VARIANT: show dot + name --}}
                                                                <label class="style-text"
                                                                    data-is-color="true"
                                                                    style="gap:8px; padding: 5px 14px;"
                                                                    for="{{ $product_property_value->id }}"
                                                                    data-value="{{ $product_property_value->propertyValue->name }}">
                                                                    <span class="color-dot"
                                                                        style="background:{{ $product_property_value->color_code ?? '#ccc' }}; border-color: rgba(0,0,0,0.2);"></span>
                                                                    <span>{{ $product_property_value->propertyValue->name }}</span>
                                                                </label>
                                                            @else
                                                                {{-- SIZE / TEXT VARIANT --}}
                                                                <label class="style-text"
                                                                    for="{{ $product_property_value->id }}"
                                                                    data-value="{{ $product_property_value->propertyValue->name }}">
                                                                    {{ $product_property_value->propertyValue->name }}
                                                                </label>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Quantity -->
                                    <div class="tf-product-info-quantity">
                                        <div class="quantity-title fw-6">Quantity</div>
                                        <div class="wg-quantity">
                                            <span class="btn-quantity btn-decrease">−</span>
                                            <input type="text" class="quantity-product" name="quantity" value="1" id="quantity">
                                            <span class="btn-quantity btn-increase">+</span>
                                        </div>
                                    </div>

                                    <!-- Add to Cart -->
                                    <div class="tf-product-info-buy-button">
                                        <form>
                                            <a href="javascript:void(0);"
                                                class="btn-add-to-cart add-to-cart-btn"
                                                data-id="{{ $product->id }}">
                                                <span class="cart-btn-text">Add to cart —&nbsp;</span>
                                                <span class="tf-qty-price total-price" id="totalAmount">
                                                    {{ $initial_price ? toCurrency($initial_price->selling_price) : '' }}
                                                </span>
                                            </a>
                                            <a href="javascript:void(0);"
                                                class="tf-product-btn-wishlist hover-tooltip box-icon bg_white btn-icon-action tf-btn-loading product-wishlist {{ $product->is_wishlisted ? 'active' : '' }}"
                                                data-id="{{ $product->id }}">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip" id="wishlist-tooltip-{{ $product->id }}">
                                                    {{ $product->is_wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                                                </span>
                                                <span class="icon icon-delete"></span>
                                            </a>
                                        </form>
                                    </div>

                                    <!-- Delivery & Return -->
                                    <div class="tf-product-info-delivery-return">
                                        <div class="row g-3">
                                            <div class="col-12 col-xl-6">
                                                <div class="tf-product-delivery">
                                                    <div class="icon"><i class="icon-delivery-time"></i></div>
                                                    <p>Estimated delivery:<br><span class="fw-7">10–15 days</span></p>
                                                </div>
                                            </div>
                                            <div class="col-12 col-xl-6">
                                                <div class="tf-product-delivery mb-0">
                                                    <div class="icon"><i class="icon-return-order"></i></div>
                                                    <p>Return within <span class="fw-7">07 days</span> of purchase. Duties & taxes non-refundable.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Trust Seal -->
                                    <div class="tf-product-info-trust-seal">
                                        <div class="tf-product-trust-mess">
                                            <i class="icon-safe"></i>
                                            <p class="fw-6">Guarantee Safe<br>Checkout</p>
                                        </div>
                                        <div class="tf-payment">
                                            <img src="/frontend/images/payments/visa.png" alt="Visa">
                                            <img src="/frontend/images/payments/img-1.png" alt="">
                                            <img src="/frontend/images/payments/img-2.png" alt="">
                                            <img src="/frontend/images/payments/img-3.png" alt="">
                                            <img src="/frontend/images/payments/img-4.png" alt="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== VIDEO SECTION ===== -->
    @if ($product->video_type && ($product->video || $product->youtube_link))
        <section class="flat-spacing-8 pb_0">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tf-product-media-wrap">
                            <div class="thumbs-slider">
                                <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started">
                                    <div class="swiper-wrapper">
                                        @if ($product->video_type === 'video' && $product->video)
                                            <div class="swiper-slide">
                                                <video class="w-100" style="max-height:520px;object-fit:cover;border-radius:var(--radius);" controls>
                                                    <source src="{{ asset(Storage::url($product->video)) }}" type="video/mp4">
                                                </video>
                                            </div>
                                        @elseif ($product->video_type === 'youtube_link' && $product->youtube_link)
                                            @php $videoID = getYoutubeEmbedUrl($product->youtube_link); @endphp
                                            @if ($videoID)
                                                <div class="swiper-slide">
                                                    <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--radius);">
                                                        <iframe style="position:absolute;top:0;left:0;width:100%;height:100%;"
                                                            src="https://www.youtube.com/embed/{{ $videoID }}?rel=0&showinfo=0"
                                                            frameborder="0"
                                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- ===== DESCRIPTION / HIGHLIGHTS / INFO + REVIEWS ===== -->
    <section class="flat-spacing-10">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column gap-4">

                        <!-- Cards Row -->
                        <div class="row g-4">
                            <div class="col-12 col-md-4">
                                <div class="info-card">
                                    <h5>Description</h5>
                                    <div class="text-muted description">{!! $product->description !!}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="info-card">
                                    <h5>Highlights</h5>
                                    <div class="text-muted description">{!! $product->highlights !!}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="info-card">
                                    <h5>Additional Information</h5>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($product_property_labels as $property_label)
                                            @php
                                                $values = getProductPropertyValues($product->id, $property_label)
                                                    ->map(fn($v) => $v->propertyValue->name)
                                                    ->implode(', ');
                                            @endphp
                                            <li>
                                                <span class="prop-key">{{ $property_label }}:</span>
                                                <span class="prop-val"> {{ $values }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews -->
                        <div class="review-section-wrap">
                            <div class="review-section-title">Customer Reviews</div>
                            <div class="tab-reviews write-cancel-review-wrap">
                                <div class="tab-reviews-heading">
                                    <div class="top">
                                        <div class="text-center">
                                            <h1 class="number fw-6">{{ $product_review->average_rating }}</h1>
                                            <div class="list-star">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($product_review->average_rating))
                                                        <i class="icon icon-star"></i>
                                                    @elseif ($i - 0.5 <= $product_review->average_rating)
                                                        <i class="icon icon-star-half"></i>
                                                    @else
                                                        <i class="icon icon-star" style="color:var(--border);"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p style="font-size:13px;color:var(--muted);">({{ $product_review->total_reviews }} Ratings)</p>
                                        </div>
                                        <div class="rating-score">
                                            @foreach ([5 => 'five', 4 => 'four', 3 => 'three', 2 => 'two', 1 => 'one'] as $star => $word)
                                                <div class="item">
                                                    <div class="number-1">{{ $star }}</div>
                                                    <i class="icon icon-star"></i>
                                                    <div class="line-bg">
                                                        <div style="width:{{ $product_review->total_reviews > 0 ? ($product_review->{$word.'_star_count'} / $product_review->total_reviews) * 100 : 0 }}%;"></div>
                                                    </div>
                                                    <div class="number-2">{{ $product_review->{$word.'_star_count'} }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if (isOrderedProduct($product->id))
                                            <div style="align-self:flex-start;">
                                                <button class="btn-cancel-review tf-btn btn-comment-review" style="display:none;">Cancel</button>
                                                <button class="btn-write-review tf-btn btn-comment-review">✏️ Write a Review</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Existing Reviews -->
                                <div class="reply-comment cancel-review-wrap mt-3">
                                    <div class="reply-comment-wrap">
                                        @if ($product->reviews->isNotEmpty())
                                            @foreach ($product->reviews as $review)
                                                <div class="review-item-card">
                                                    <div class="user">
                                                        <div class="image">
                                                            <img src="/frontend/images/item/user.png" alt="user">
                                                        </div>
                                                        <div>
                                                            <h6><a href="#" class="link" style="color:var(--dark);text-decoration:none;">{{ $review->title }}</a></h6>
                                                            <div class="day">{{ $review->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    </div>
                                                    <p class="text_black-2">{{ $review->description }}</p>
                                                    @if ($review->photos)
                                                        <div class="review-images mt-2 d-flex flex-wrap gap-2">
                                                            @foreach ($review->photos as $photo)
                                                                <a href="{{ asset('storage/'.$photo) }}" target="_blank">
                                                                    <img src="{{ asset('storage/'.$photo) }}" width="80" height="80"
                                                                        style="object-fit:cover;border-radius:8px;border:2px solid var(--border);">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <p style="color:var(--muted);font-size:14px;text-align:center;padding:20px 0;">No reviews yet. Be the first to review!</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Write Review Form -->
                                <form class="form-write-review write-review-wrap"
                                    action="{{ route('frontend.reviews.store') }}" method="POST"
                                    id="reviewForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="heading">
                                        <h5>Write a Review</h5>
                                        <div class="list-rating-check">
                                            <input type="radio" id="star5" name="rating" value="5" checked />
                                            <label for="star5" title="5 stars"></label>
                                            <input type="radio" id="star4" name="rating" value="4" />
                                            <label for="star4" title="4 stars"></label>
                                            <input type="radio" id="star3" name="rating" value="3" />
                                            <label for="star3" title="3 stars"></label>
                                            <input type="radio" id="star2" name="rating" value="2" />
                                            <label for="star2" title="2 stars"></label>
                                            <input type="radio" id="star1" name="rating" value="1" />
                                            <label for="star1" title="1 star"></label>
                                        </div>
                                    </div>
                                    <div class="form-content">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <fieldset class="box-field">
                                                    <label class="label">Review Title</label>
                                                    <input type="text" placeholder="Give your review a title" name="title">
                                                    <span class="text-danger" id="title_error"></span>
                                                </fieldset>
                                            </div>
                                            <div class="col-12">
                                                <fieldset class="box-field">
                                                    <label class="label">Review Description</label>
                                                    <textarea rows="4" name="description" placeholder="Share your experience with this product..."></textarea>
                                                    <span class="text-danger" id="description_error"></span>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <fieldset class="box-field">
                                                    <label class="label">Upload Photos</label>
                                                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                                    <div id="imagePreview" class="image-preview-grid"></div>
                                                    <span class="text-danger" id="photos_error"></span>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-submit mt-3">
                                        <button class="tf-btn btn-fill" type="submit" id="submitReviewBtn">
                                            <span id="submitReviewBtnText" style="min-width:120px;display:inline-block;">Submit Review</span>
                                            <span id="submitReviewBtnLoader" class="d-none" style="min-width:120px;display:inline-block;">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PEOPLE ALSO BOUGHT ===== -->
    @if (isset($people_also_bought) && $people_also_bought->isNotEmpty())
        <section class="flat-spacing-10 pb-0">
            <div class="container">
                <div class="section-heading">People Also Bought</div>
                <div class="section-subline"></div>
                <div class="row g-3">
                    @foreach ($people_also_bought as $p)
                        <div class="col-lg-3 col-md-4 col-6">
                            @include('Frontend.components.product-card', ['product' => $p])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ===== RECENTLY VIEWED ===== -->
    @if (isset($recently_viewed) && $recently_viewed->isNotEmpty())
        <section class="flat-spacing-10">
            <div class="container">
                <div class="section-heading">Recently Viewed</div>
                <div class="section-subline"></div>
                <div class="row g-3">
                    @foreach ($recently_viewed as $p)
                        <div class="col-lg-3 col-md-4 col-6">
                            @include('Frontend.components.product-card', ['product' => $p])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <script>
        $(document).ready(function() {
            // ---- Init Swipers on load ----
            initSwipers();

            // ---- Update label on radio change ----
            updateVariantLabels();
            $('.property-value').on('change', function() {
                updateVariantLabels();
                let selectedPropertyValues = getSelectedPropertyValues();
                getProductPrice(selectedPropertyValues);
                getProductImages(selectedPropertyValues);
            });

            // Initial price + images
            let selectedPropertyValues = getSelectedPropertyValues();
            getProductPrice(selectedPropertyValues);
            getProductImages(selectedPropertyValues);

            // ---- Quantity Controls ----
            $(document).on('click', '.btn-decrease', function() {
                let qty = parseInt($('#quantity').val()) || 1;
                if (qty > 1) {
                    $('#quantity').val(qty - 1);
                    updateTotalPrice();
                }
            });
            $(document).on('click', '.btn-increase', function() {
                let qty = parseInt($('#quantity').val()) || 1;
                $('#quantity').val(qty + 1);
                updateTotalPrice();
            });

            // ---- Review Form Toggle ----
            $(document).on('click', '.btn-write-review', function() {
                $('.form-write-review').slideToggle(250);
                $('.btn-cancel-review').toggle();
            });
            $(document).on('click', '.btn-cancel-review', function() {
                $('.form-write-review').slideUp(250);
                $(this).hide();
            });

            // ---- Image Preview ----
            $('input[name="photos[]"]').on('change', function() {
                $('#imagePreview').html('');
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $('#imagePreview').append('<img src="' + e.target.result + '" alt="preview">');
                    };
                    reader.readAsDataURL(file);
                });
            });

            // ---- Review Submit ----
            $('#reviewForm').submit(function(e) {
                e.preventDefault();
                $('#title_error, #description_error, #photos_error').html('');
                $('#submitReviewBtnText').addClass('d-none');
                $('#submitReviewBtnLoader').removeClass('d-none');
                $('#submitReviewBtn').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false, cache: false, processData: false,
                    success: function(response) {
                        $('#submitReviewBtnText').removeClass('d-none');
                        $('#submitReviewBtnLoader').addClass('d-none');
                        $('#submitReviewBtn').prop('disabled', false);
                        if (response.status == 'success') {
                            toastr.success(response.message, '', { showMethod: "slideDown", timeOut: 1500, closeButton: true });
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        $('#submitReviewBtnText').removeClass('d-none');
                        $('#submitReviewBtnLoader').addClass('d-none');
                        $('#submitReviewBtn').prop('disabled', false);
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '_error').html(value);
                        });
                    }
                });
            });
        });

        /* ---- Helpers ---- */

        function updateVariantLabels() {
            $('.variant-picker-item').each(function() {
                let checked = $(this).find('.property-value:checked');
                let label = checked.next('label');
                let val = label.data('value') || checked.val() || '';
                $(this).find('.variant-picker-label-value').text(val);
            });
        }

        function updateTotalPrice() {
            let selling = $('#sellingPrice').text().trim();
            // Extract numeric value
            let num = parseFloat(selling.replace(/[^0-9.]/g, ''));
            let qty = parseInt($('#quantity').val()) || 1;
            // Re-display in same currency format (simple)
            // Ideally format via toCurrency helper; here we keep it readable
            $('#totalAmount').text($('#sellingPrice').text().replace(/[\d,.]+/, (num * qty).toLocaleString('en-GB', {minimumFractionDigits: 0})));
        }

        function getSelectedPropertyValues() {
            let propertyValues = [];
            $('.property-value:checked').each(function() {
                propertyValues.push({
                    property_value: $(this).val(),
                    property_value_id: $(this).data('property-value-id'),
                    is_image_property: $(this).data('image-property') === "YES" ? 'YES' : 'NO',
                });
            });
            return propertyValues;
        }

        function getProductPrice(propertyValues) {
            $.ajax({
                url: "{{ route('frontend.products.price', ['product' => $product->route_key]) }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', property_values: propertyValues },
                success: function(response) {
                    if (response.product_price) {
                        $('#sellingPrice').text(response.product_price.selling_price);
                        $('#actualPrice').text(response.product_price.actual_price);
                        $('#totalAmount').text(response.product_price.selling_price);

                        const stock = response.product_price.stock;
                        if (stock === 0) {
                            $('#productStock').text('Out of Stock');
                            $('.tf-product-info-liveview').show();
                        } else if (stock === 1) {
                            $('#productStock').text('Only 1 item left in stock!');
                            $('.tf-product-info-liveview').show();
                        } else {
                            $('.tf-product-info-liveview').hide();
                        }

                        const discount = Math.round(response.product_price.discount_percentage);
                        $('#discountPercentage').html(discount > 0 ? '<span>' + discount + '</span>% OFF' : '');
                    }
                },
                error: function(err) { console.error(err); }
            });
        }

        let thumbsSwiper, mainSwiper;

        function initSwipers() {
            // Destroy previous instances cleanly
            if (thumbsSwiper && !thumbsSwiper.destroyed) thumbsSwiper.destroy(true, true);
            if (mainSwiper && !mainSwiper.destroyed) mainSwiper.destroy(true, true);

            const w = window.innerWidth;
            const isDesktop = w >= 1200;

            thumbsSwiper = new Swiper('#thumbs-swiper', {
                spaceBetween: 8,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                direction: isDesktop ? 'vertical' : 'horizontal',
                slidesPerView: isDesktop ? 'auto' : (w >= 576 ? 5 : 4),
            });

            mainSwiper = new Swiper('#main-swiper', {
                spaceBetween: 0,
                observer: true,
                observeParents: true,
                navigation: {
                    nextEl: '.thumbs-next',
                    prevEl: '.thumbs-prev',
                },
                thumbs: {
                    swiper: thumbsSwiper,
                },
            });
        }

        // Re-init on window resize (debounced)
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(initSwipers, 250);
        });

        function getProductImages(propertyValues) {
            $.ajax({
                url: "{{ route('frontend.products.images', ['product' => $product->route_key]) }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', property_values: propertyValues },
                success: function(response) {
                    if (response.product_images && response.product_images.length > 0) {
                        updateSwipersWithImages(response.product_images);
                    }
                },
                error: function(err) { console.error(err); }
            });
        }

        function updateSwipersWithImages(images) {
            let thumbsHtml = '';
            let mainHtml = '';

            images.forEach(function(img) {
                thumbsHtml += `<div class="swiper-slide"><div class="item"><img class="lazyload" src="${img}" data-src="${img}" alt="product-img"></div></div>`;
                mainHtml   += `<div class="swiper-slide"><a href="${img}" class="item" data-pswp-width="770" data-pswp-height="1075"><img class="lazyload" src="${img}" data-zoom="${img}" data-src="${img}" alt="product-img"></a></div>`;
            });

            // Preserve video slides
            const videoThumbs = $('#thumbs-swiper .swiper-wrapper .video-thumb-slide').clone();
            const videoMains  = $('#main-swiper  .swiper-wrapper .video-main-slide').clone();

            $('#thumbs-swiper .swiper-wrapper').html(thumbsHtml).append(videoThumbs);
            $('#main-swiper  .swiper-wrapper').html(mainHtml).append(videoMains);

            initSwipers();
        }
    </script>
@endsection