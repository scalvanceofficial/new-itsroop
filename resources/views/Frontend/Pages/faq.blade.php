@extends('layouts.frontend')
@section('title')
    FAQ | Itsroop
@endsection
@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">FAQ</div>
        </div>
    </div>
    <!-- /page-title -->
    <section class="flat-spacing-11">
        <div class="container">
            <div class="tf-accordion-wrap d-flex justify-content-between">
                <div class="content">
                    <h5 class="mb_24">Shopping Information</h5>
                    <div class="flat-accordion style-default has-btns-arrow mb_60">
                        <div class="flat-toggle active">
                            <div class="toggle-title active">What are your shipping options and times?</div>
                            <div class="toggle-content">
                                <p>We offer Standard Delivery (2-3 business days) which is FREE for orders over £150 or £4.95 otherwise. We also offer Overnight Delivery (£14.95) and Nominated Day Delivery (£9.95).</p>
                            </div>
                        </div>
                        <div class="flat-toggle">
                            <div class="toggle-title">Do you offer international shipping?</div>
                            <div class="toggle-content">
                                <p>Currently, our specified rates and free shipping offers are focused on the UK. For international inquiries, please contact us at info@itsroop.com.</p>
                            </div>
                        </div>
                        <div class="flat-toggle">
                            <div class="toggle-title">Are your products authentic?</div>
                            <div class="toggle-content">
                                <p>Yes, all items sold are 100% authentic, sourced directly from trusted suppliers and luxury resellers.</p>
                            </div>
                        </div>
                    </div>
                    <h5 class="mb_24">Payment Information</h5>
                    <div class="flat-accordion style-default has-btns-arrow mb_60">
                        <div class="flat-toggle">
                            <div class="toggle-title">What payment methods do you accept?</div>
                            <div class="toggle-content">
                                <p>We accept secure payments through SumUp, including VISA, Mastercard, Maestro, American Express, and more.</p>
                            </div>
                        </div>
                        <div class="flat-toggle">
                            <div class="toggle-title">Is my payment information secure?</div>
                            <div class="toggle-content">
                                <p>Yes, we use encrypted payment processing and a secure SSL website. We do NOT store your full card information.</p>
                            </div>
                        </div>
                    </div>
                    <h5 class="mb_24">Order Returns</h5>
                    <div class="flat-accordion style-default has-btns-arrow">
                        <div class="flat-toggle">
                            <div class="toggle-title">What is your return policy?</div>
                            <div class="toggle-content">
                                <p>We offer a free-of-charge 7-day return policy within the UK. Items must be unused, in original packaging, with tags intact.</p>
                            </div>
                        </div>
                        <div class="flat-toggle">
                            <div class="toggle-title">How do I start a return?</div>
                            <div class="toggle-content">
                                <p>To initiate a return, email us at info@itsroop.com with your order number and reason for return. We will provide a prepaid return label.</p>
                            </div>
                        </div>
                        <div class="flat-toggle">
                            <div class="toggle-title">When will I receive my refund?</div>
                            <div class="toggle-content">
                                <p>Refunds are typically processed to your original payment method within 3–7 working days after we receive and inspect the returned item.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
