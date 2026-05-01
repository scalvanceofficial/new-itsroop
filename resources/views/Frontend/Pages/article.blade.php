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
                    <div class="main-heading {{ is_mobile() ? 'justify-text' : '' }}">
                        Hey parents! We know mealtime with kids can be a mix of fun, mess, and sometimes... chaos. That’s
                        why our bamboo fiber plates are designed to make your life easier — and your little one’s experience
                        more joyful.
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
                    <div class="heading text_green-1">Here’s how you can use them in multiple creative ways:</div>

                    <p class="description text_green-2 fw-6 mt_20 ">1. Everyday Mealtime:</p>
                    <p>These plates are lightweight, sturdy, and perfectly
                        sized for tiny hands. No more heavy plates slipping and falling!</p>
                    <p class="description text_green-2 fw-6 mt_20 ">2. Snack Time
                        Made Fun:</p>
                    <p>
                        The divided sections help you serve different snacks without
                        mixing them — ideal for fussy eaters who
                        want everything separate!</p>

                    <p class="description text_green-2 fw-6 mt_20">3. Art & Craft Helper:</p>
                    <p>Done with lunch? Use the same plate to hold crayons, beads, or paint while your child gets creative.
                        Easy to clean and super handy!</p>

                    <p class="description text_green-2 fw-6 mt_20">4. On-the-Go Friendly:</p>
                    <p>Whether it’s a picnic, park day, or a long drive, these plates travel well. Lightweight, reusable,
                        and hassle-free.</p>

                    <p class="description text_green-2 fw-6 mt_20">5. Learning at the Table:</p>
                    <p>Use the compartments to talk about food groups, teach portion sizes, or play fun games with colorful
                        meals.</p>

                    <p class="description text_green-2 fw-6 mt_20"> 6. Perfect for gifting:</p>
                    <p>Hosting a kids’ birthday or playdate? Ditch the plastic gifts and use these eco-friendly plates that
                        look cute and care for the planet. Great gift, happy kid, happy planet.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-1">
        <div class="container">
            <div class="tf-grid-layout md-col-12 tf-img-with-text">
                <div class="tf-content-wrap wow fadeInUp {{ is_mobile() ? 'mobile-justify-wrapper' : '' }}"
                    data-wow-delay="0s">
                    <div class="heading text_green-1">How to Make Mealtime Healthier & More Enjoyable for Your Child:</div>
                    <div class="tf-content-wrap wow fadeInUp" data-wow-delay="0s">
                        <span class="sub-heading text-uppercase fw-7 text_green-1">We believe that small changes at the
                            table can build lifelong healthy habits. Here are a few simple tips every parent can try:
                    </div>
                    <p class="description text_green-2 fw-6 mt_20">1. Stick to a Routine::</p>
                    <p>Set meal and snack times — kids feel more secure and eat better with a routine.</p>

                    <p class="description text_green-2 fw-6 mt_20">2. Keep the Table Distraction-Free:</p>
                    <p>Turn off TVs and put away phones. Let your child focus on food and family time.</p>

                    <p class="description text_green-2 fw-6 mt_20">3. Serve a Colorful, Balanced Plate:</p>
                    <p>Mix fruits, veggies, grains, and proteins. The more color, the more nutrients!</p>

                    <p class="description text_green-2 fw-6 mt_20">4. Let Them Try on Their Own:</p>
                    <p>Yes, it might get messy — but self-feeding builds confidence and motor skills.</p>

                    <p class="description text_green-2 fw-6 mt_20">5. Dealing with Picky Eating? Be Patient:</p>
                    <p>Don’t force. Introduce new foods slowly, and always offer a familiar favorite with it.</p>

                    <p class="description text_green-2 fw-6 mt_20"> 6. Make Food Fun:</p>
                    <p>Use cutters for fun shapes, arrange faces on the plate, or let them “build” their own snack.</p>

                    <p class="description text_green-2 fw-6 mt_20"> 7.
                        Teach Table Manners Gently:</p>
                    <p>Help them understand the importance of sitting at the table and not munching all day.</p>

                    <p class="description text_green-2 fw-6 mt_20"> 8. Lead by Example:</p>
                    <p>Kids copy you. If they see you eating a variety of foods, they’ll be more likely to try them too.</p>

                    <p class="description text_green-2 fw-6 mt_20"> 9. Stay Calm & Positive:</p>
                    <p>Avoid bribing or pressuring. Keep mealtime happy and stress-free.</p>

                    <p class="description text_green-2 fw-6 mt_20"> 10.Keep Them Hydrated:</p>
                    <p>Offer water throughout the day — and avoid sugary drinks during meals.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
