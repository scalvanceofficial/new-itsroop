@extends('layouts.frontend')
@section('title')
    News | BaItsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title bg_green-1 bg_green-1">
        <div class="container-full">
            <div class="heading text-center text_white-2">News</div>
            <p class="text-center text-2 mt_5 text-white"> Stay informed with the latest updates, stories, and breakthroughs
                in sustainable living and bamboo innovation.
            </p>
        </div>
    </div>
    <!-- /page-title -->

    @if ($news->isNotEmpty())
        <div class="blog-grid-main">
            <div class="container">
                <div class="row">
                    @foreach ($news as $news)
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="blog-article-item">
                                <div class="article-thumb">
                                    <a href="{{ route('frontend.news.details', $news->slug) }}">
                                        <img class="lazyload" data-src="{{ asset('storage/' . $news->thumbnail_image) }}"
                                            src="{{ asset('storage/' . $news->thumbnail_image) }}"
                                            alt="{{ $news->title }}">
                                    </a>
                                </div>
                                <div class="article-content">
                                    <div class="article-title">
                                        <a href="{{ route('frontend.news.details', $news->slug) }}">
                                            {{ $news->title }}
                                        </a>
                                    </div>
                                    <div class="article-btn">
                                        <a href="{{ route('frontend.news.details', $news->slug) }}"
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
                    <h4 class="text_green-1">No news posts found!</h4>
                    <p class="mt-3">We're working on bringing you the latest updates. Check back soon for news on
                        sustainability and bamboo innovations.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
