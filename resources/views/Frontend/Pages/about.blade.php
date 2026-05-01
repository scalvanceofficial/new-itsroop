@extends('layouts.frontend')
@section('title')
    About | Itsroop
@endsection
@section('content')
    <!-- flat-title -->
    <section class="flat-spacing-9">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="flat-title my-0">
                        <span class="title text-center">Welcome to Its Roop – Your Independent Source for Luxury!
                        </span>
                        <p class="sub-title text_black-2 text-center" style="text-align: justify !important;">
                            At Its Roop, we are dedicated to providing our customers with 100% authentic designer products. As an independent luxury reseller, our mission is to make high-end fashion accessible while ensuring the highest standards of quality and authenticity.
                            Our journey began with a passion for luxury fashion and a commitment to transparency. We source our products directly from trusted suppliers, ensuring that every item in our collection is genuine and meets our rigorous standards.
                            We take pride in our independence, which allows us to offer a curated selection of products that reflect modern style and timeless elegance. By choosing Its Roop, you are supporting a business that values authenticity, quality, and exceptional customer service.
                            Join us in our mission to bring the world of luxury to your doorstep, one authentic piece at a time.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="flat-spacing-9 d-none d-md-block">
        <div class="container">
            <div class="row justify-content-center">
                <img src="/frontend/images/about/about.png" alt="about">
            </div>
        </div>
    </section>

    <section class="flat-spacing-9 d-block d-md-none">
        <div class="container">
            <div class="row justify-content-center">
                <img src="/frontend/images/about/mobile/1.png" alt="about">
            </div>
        </div>
        <div class="container mt-3">
            <div class="row justify-content-center">
                <img src="/frontend/images/about/mobile/2.png" alt="about">
            </div>
        </div>
        <div class="container mt-3">
            <div class="row justify-content-center">
                <img src="/frontend/images/about/mobile/3.png" alt="about">
            </div>
        </div>
    </section>


    <!-- /flat-title -->
    <div class="container">
        <div class="line"></div>
    </div>
    <!-- image-text -->
    {{-- <section class="flat-spacing-23 flat-image-text-section">
        <div class="container">
            <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
                <div class="tf-image-wrap">
                    <img class="lazyload w-100" data-src="/frontend/images/item/mission.jpg"
                        src="/frontend/images/item/mission.jpg" alt="collection-img">
                </div>
                <div class="tf-content-wrap px-0 d-flex justify-content-center w-100">
                    <div>
                        <div class="heading">Our Mission: Promoting Sustainability with Bamboo</div>
                        <div class="text">
                            <p><strong>To promote sustainable, biodegradable alternatives to plastic.</strong><br> We are
                                dedicated to reducing plastic waste by offering eco-friendly bamboo products that help
                                create a cleaner and greener planet.</p>
                            <p><strong>To generate employment for skilled artisans and support Indian
                                    craftsmanship.</strong><br> Our mission extends beyond sustainability; we empower local
                                artisans, preserve traditional craftsmanship, and provide them with opportunities to
                                showcase their skills.</p>
                            <p><strong>To bring stylish, durable, and functional bamboo products to modern
                                    homes.</strong><br> We believe in combining aesthetics with sustainability, ensuring
                                that our products enhance everyday living while promoting an eco-conscious lifestyle.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="flat-spacing-23 flat-image-text-section">
        <div class="container">
            <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
                <div class="tf-content-wrap px-0 d-flex justify-content-center w-100">
                    <div>
                        <div class="heading">Our Vision: Creating a Sustainable Future Together</div>
                        <div class="text"> To lead the sustainable revolution by offering high-quality bamboo and cane
                            products that blend durability with aesthetics, making eco-friendly living a conscious choice
                            for every household. <p><strong>Our Commitment to a Greener Future</strong><br> As we grow, our
                                mission remains clear: to innovate with biodegradable materials, expand our range of
                                sustainable products, and build a global community that shares our passion for an
                                eco-friendly tomorrow.</p>
                            <p><strong>Join the Movement</strong><br> Every bamboo product we create is a step closer to a
                                more sustainable future. Together, we are not just changing the way we live, but shaping the
                                world for future generations. By choosing Bamboo Street, you are part of the solution,
                                contributing to a better planet, one product at a time.</p>
                        </div>
                    </div>
                </div>
                <div class="tf-image-wrap">
                    <img class="lazyload w-100" data-src="/frontend/images/item/mission.jpg"
                        src="/frontend/images/item/mission.jpg" alt="collection-img">
                </div>
            </div>
        </div>
    </section>
    <section class="flat-spacing-23 flat-image-text-section">
        <div class="container">
            <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
                <div class="tf-image-wrap">
                    <img class="lazyload w-100" data-src="/frontend/images/item/mission.jpg"
                        src="/frontend/images/item/mission.jpg" alt="collection-img">
                </div>
                <div class="tf-content-wrap px-0 d-flex justify-content-center w-100">
                    <div>
                        <div class="heading">Our Tagline – Good for You, Better for Earth</div>
                        <div class="text">
                            <strong>Embracing Sustainability with Every Choice</strong>
                            <p>We don’t just sell products; we offer a lifestyle that is good for your home, your
                                family, and our planet. Our tagline reflects our commitment to making sustainable
                                choices effortless and elegant.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="flat-spacing-23 flat-image-text-section">
        <div class="container">
            <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
                <div class="tf-content-wrap px-0 d-flex justify-content-center w-100">
                    <div>
                        <div class="heading">Our Initiative – Save Humans with Bamboo Street</div>
                        <div class="text">
                            <strong>Preserving Nature, Protecting the Future</strong>
                            <p>Bamboo isn’t just eco-friendly; it’s a powerful solution to environmental
                                challenges. Our initiative is to inspire people to choose natural, biodegradable
                                products that reduce waste and create a better future for generations to come.
                            </p>
                        </div>

                    </div>
                </div>
                <div class="tf-image-wrap">
                    <img class="lazyload w-100" data-src="/frontend/images/item/mission.jpg"
                        src="/frontend/images/item/mission.jpg" alt="collection-img">
                </div>
            </div>
        </div>
    </section> --}}
    <!-- /image-text -->

    <!-- iconbox -->
    <section>
        <div class="container mb-5">
            <div class="bg_grey-2 radius-10 flat-wrap-iconbox">
                <div class="flat-title lg">
                    <span class="title fw-5 text_primary">Sustainability is Our Priority</span>
                    <div>
                        <p class="sub-title text_black-2  {{ is_mobile() ? 'justify-text' : '' }}">We are committed to
                            providing eco-friendly, handcrafted bamboo
                            products that blend quality with sustainability.</p>
                        <p class="sub-title text_black-2  {{ is_mobile() ? 'justify-text' : '' }}">Our artisans craft each
                            piece with care, ensuring durability
                            while preserving nature.</p>
                    </div>
                </div>
                <div class="flat-iconbox-v3 lg">
                    <div class="wrap-carousel wrap-mobile">
                        <div dir="ltr" class="swiper tf-sw-mobile" data-preview="1" data-space="15">
                            <div class="swiper-wrapper wrap-iconbox lg">
                                <div class="swiper-slide">
                                    <div class="tf-icon-box text-center">
                                        <div class="icon">
                                            <i class="icon-materials text_primary"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title text_primary">Eco-Friendly Materials</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="tf-icon-box text-center">
                                        <div class="icon">
                                            <i class="icon-design text_primary"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title text_primary">Ethical Craftsmanship</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="tf-icon-box text-center">
                                        <div class="icon">
                                            <i class="icon-sizes text_primary"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title text_primary">Durable & Versatile</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sw-dots style-2 sw-pagination-mb justify-content-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /iconbox -->
@endsection
