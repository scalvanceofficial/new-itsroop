@extends('layouts.frontend')
@section('title')
    Home | Articles
@endsection
@section('content')
    <!-- /page-title -->
    <!-- main-page -->
    <style>
        .mobile-justify-wrapper p {
            text-align: justify;
        }
    </style>

    <section class="flat-spacing-4 pb-1">
        <div class="container">
            <div class="tf-grid-layout md-col-12 tf-img-with-text">
                <div class="tf-content-wrap wow fadeInUp" data-wow-delay="0s">
                    <div class="main-heading">
                        Bamboo Handcrafted Lanterns aren’t just about lighting — they’re about adding warmth, charm, and
                        style to your space. Here’s how you can use them in beautiful, practical, and creative ways:
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-1">
        <div class="container">
            <div class="tf-grid-layout md-col-12 tf-img-with-text">
                <div class="tf-content-wrap wow fadeInUp {{ is_mobile() ? 'mobile-justify-wrapper' : '' }}"
                    data-wow-delay="0s">
                    <div class="heading text_green-1">Bright Ideas: Creative Ways to Use Bamboo Lanterns</div>

                    <p class="description text_green-2 fw-6 mt_20">1. Hang Them High, Let the Vibes Fly:</p>
                    <p>Suspend lanterns from ceilings, balconies, or tree branches using ropes or chains. Whether it's your
                        patio, balcony, or garden — they instantly create a cozy, rustic mood.</p>

                    <p class="description text_green-2 fw-6 mt_20">2. Make It the Center of Attention:</p>
                    <p>Use one as a centerpiece on your dining or coffee table. Pop in a candle or LED light, and decorate
                        around it with flowers, pebbles, or greenery for that wow factor.</p>

                    <p class="description text_green-2 fw-6 mt_20">3. Add a Glow to Your Walls:</p>
                    <p>Mount your bamboo lantern on a stylish wall bracket to create an eye-catching lighting feature in
                        your hallway, living area, or entrance.</p>

                    <p class="description text_green-2 fw-6 mt_20">4. Light the Path with Magic:</p>
                    <p>Line up lanterns along your garden walkway or driveway. As the sun sets, your path transforms into a
                        softly glowing trail of charm.</p>

                    <p class="description text_green-2 fw-6 mt_20">5. Style with the Seasons:</p>
                    <p>Give your lantern a festive touch! Add fairy lights for Diwali or Christmas, fresh flowers for
                        spring, or dried leaves for autumn — and change the vibe with the season.</p>

                    <p class="description text_green-2 fw-6 mt_20">6. Cozy Up Indoors:</p>
                    <p>Place it in a bedroom corner or near your reading nook for a soft, ambient glow that adds warmth and
                        personality to any room.</p>

                    <p class="description text_green-2 fw-6 mt_20">7. Elevate Your Events:</p>
                    <p>Perfect for weddings, festive decor, or intimate gatherings — these lanterns add elegance, tradition,
                        and eco-conscious beauty to every celebration.</p>

                    <p class="description text_green-2 fw-6 mt_20">8. Balcony Goals, Achieved:</p>
                    <p>Turn your balcony into a peaceful escape. Just add a few bamboo lanterns, a comfy chair, and you’ve
                        got the perfect spot to unwind.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="flat-spacing-4 pt-1">
        <div class="container">
            <div class="tf-grid-layout md-col-12 tf-img-with-text">
                <div class="tf-image-wrap radius-10 wow fadeInUp" data-wow-delay="0s">
                </div>
                <div class="tf-content-wrap wow fadeInUp" data-wow-delay="0s">
                    <span class="sub-heading text-uppercase fw-7 text_green-1">Crafted with love, designed for charm –
                        bamboo lanterns that do more than light up your space… they transform it.</span>

                </div>
            </div>
        </div>
    </section>
@endsection
