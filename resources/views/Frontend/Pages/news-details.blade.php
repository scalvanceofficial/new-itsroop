@extends('layouts.frontend')

@section('title')
    {{ $news->title }} | Itsroop
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
                            <div class="title">{{ $news->title }}</div>
                            <div class="image">
                                <img class="lazyload" data-src="{{ asset('storage/' . $news->main_image) }}"
                                    src="{{ asset('storage/' . $news->main_image) }}" alt="{{ $news->title }}">
                            </div>
                        </div>
                        <div class="desc">
                            <p class="text">
                                {!! $news->description_1 !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
