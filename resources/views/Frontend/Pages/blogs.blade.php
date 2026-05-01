@extends('layouts.frontend')
@section('title')
    Blog | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title bg_green-1 bg_green-1">
        <div class="container-full">
            <div class="heading text-center text_white-2">Blogs</div>
            <p class="text-center text-2 mt_5 text-white">Explore insightful blogs on sustainable living, eco-friendly
                choices, and the
                beauty of bamboo craftsmanship.</p>
        </div>
    </div>
    <!-- /page-title -->

    @if ($blogs->isNotEmpty())
        <div class="blog-grid-main">
            <div class="container">
                <div class="row">
                    @foreach ($blogs as $blog)
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="blog-article-item">
                                <div class="article-thumb">
                                    <a href="{{ route('frontend.blogs.details', $blog->slug) }}">
                                        <img class="lazyload" data-src="{{ asset('storage/' . $blog->thumbnail_image) }}"
                                            src="{{ asset('storage/' . $blog->thumbnail_image) }}"
                                            alt="{{ $blog->title }}">
                                    </a>
                                </div>
                                <div class="article-content">
                                    <div class="article-title">
                                        <a href="{{ route('frontend.blogs.details', $blog->slug) }}">
                                            {{ $blog->title }}
                                        </a>
                                    </div>
                                    <div class="article-btn">
                                        <a href="{{ route('frontend.blogs.details', $blog->slug) }}"
                                            class="tf-btn btn-line fw-6">
                                            Read more <i class="icon icon-arrow1-top-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="flat-spacing-2">
            <div class="container">
                <div class="text-center py-5">
                    <h4 class="text_green-1">No blog posts found!</h4>
                    <p class="mt-3">We’re still planting some content seeds. Come back soon for fresh insights.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
