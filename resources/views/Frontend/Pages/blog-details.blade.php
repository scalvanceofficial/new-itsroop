@extends('layouts.frontend')

@section('title')
  {{ $blog->title }} | Itsroop
@endsection

@section('content')
  <style>
    p {
      font-size: 1rem;
    }
  </style>

  <!-- blog-detail -->
  <div class="blog-detail">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="blog-detail-main">
            <div class="blog-detail-main-heading">
              <div class="title">{{ $blog->title }}</div>
              <div class="image">
                <img class="lazyload" data-src="{{ asset('storage/' . $blog->main_image) }}" src="{{ asset('storage/' . $blog->main_image) }}" alt="{{ $blog->title }}">
              </div>
            </div>
            <blockquote>
              <div class="icon">
                <img src="/frontend/images/item/quote.svg" alt="">
              </div>
              <div class="{{ is_mobile() ? 'justify-text' : '' }}">
                {!! $blog->description_1 !!}
              </div>
            </blockquote>

            <div class="desc {{ is_mobile() ? 'justify-text' : '' }}">
              {!! $blog->description_2 !!}
            </div>
            <div class="bot d-flex justify-content-between flex-wrap align-items-center">
              <div class="shop-cta text-md-end text-start">
                <h5 class="shop-title mb-1" style="font-weight: 600; font-size: 16px;">
                  Ready to switch to better choices? <br>
                  Shop now on Amazon – because every small step matters.
                </h5>

                <a href="https://www.amazon.in/s?k=Bamboo+Street&ref=bl_dp_s_web_0" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1" target="_blank"
                  style="font-weight: 500; padding: 6px 12px; font-size: 0.85rem; border-radius: 20px;">
                  <i class="fas fa-shopping-cart"></i>
                  <span>Shop Now</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
