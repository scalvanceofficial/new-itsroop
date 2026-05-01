@extends('layouts.frontend')
@section('title')
    Terms & Conditions | Itsroop
@endsection
@section('content')
   
      <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Terms & Conditions</div>
        </div>
    </div>
    <style>
        .tf-main-area-page p {
            font-size: 1rem;
            color: #333333;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
    </style>
    <!-- /page-title -->
    <!-- main-page -->
    <section class="flat-spacing-25">
        <div class="container">
            <div class="tf-main-area-page">
                <h4>TERMS & CONDITIONS</h4>
                <p>Last updated: November 2025</p>

                <h6>1. About Us</h6>
                <p>Welcome to ITSROOP LTD. We operate the website itsroop.co.uk, an independent luxury reseller providing authentic designer products.</p>
                <p><strong>Please Note:</strong> We are not affiliated, associated, authorised, endorsed by, or in any way officially connected with any luxury brand or fashion house. All trademarks belong to their respective owners.</p>

                <h6>2. Acceptance of Terms</h6>
                <p>By accessing or placing an order on our website, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use our website.</p>

                <h6>3. Eligibility</h6>
                <ul>
                    <li>18 years or older</li>
                    <li>Capable of entering a legally binding agreement</li>
                    <li>Providing accurate and truthful information when ordering</li>
                </ul>

                <h6>4. Products & Descriptions</h6>
                <p>We aim to provide accurate product descriptions, including colour, condition, pricing, and availability. However:</p>
                <ul>
                    <li>Colours may vary due to screen settings</li>
                    <li>Products may sell out at any time</li>
                    <li>Errors may occur; we reserve the right to correct them</li>
                </ul>
                <p>All items sold are 100% authentic, sourced directly from trusted suppliers.</p>

                <h6>5. Pricing & Payment</h6>
                <p>All prices are listed in GBP (£) and include applicable UK taxes unless stated otherwise.</p>
                <p>We accept payments through: SumUp</p>
                <p>Payment must be received before dispatch. If a pricing error occurs, we may cancel or amend the order after notifying you.</p>

                <h6>6. Order Confirmation</h6>
                <p>When you place an order, you will receive an email acknowledgement. This is not order acceptance. Your order is accepted once we dispatch your products.</p>

                <h6>7. Shipping & Delivery</h6>
                <p>We offer:</p>
                <ul>
                    <li>Free UK shipping over £60</li>
                    <li>Standard delivery within 2–5 working days</li>
                    <li>Tracking information for all orders</li>
                </ul>
                <p>Shipping times may vary due to courier delays or peak seasons.</p>

                <h6>8. Returns & Refunds</h6>
                <p>Our Returns & Refund Policy applies to all purchases. Please read it here: <a href="{{ route('frontend.return-and-exchange') }}">Returns & Refund Policy</a></p>

                <h6>9. Intellectual Property</h6>
                <p>All text, images, graphics, and content are owned by or licensed to ITSROOP LTD. You may not reuse or copy without permission.</p>

                <h6>10. Prohibited Activities</h6>
                <ul>
                    <li>Using our site for unlawful purposes</li>
                    <li>Attempting unauthorised access</li>
                    <li>Scraping, copying, or misusing product data</li>
                    <li>Misrepresentation of authenticity claims</li>
                </ul>

                <h6>11. Limitation of Liability</h6>
                <p>To the fullest extent permitted by UK law, we are not liable for:</p>
                <ul>
                    <li>Loss of profits</li>
                    <li>Indirect or consequential damages</li>
                    <li>Courier delays</li>
                </ul>
                <p>Your statutory rights remain unaffected.</p>

                <h6>12. Governing Law</h6>
                <p>These Terms & Conditions are governed by UK law. Any disputes will be handled in UK courts.</p>

                <h6>13. Contact Us</h6>
                <p>For any questions, reach us at: <a href="mailto:info@itsroop.com">info@itsroop.com</a></p>
            </div>
        </div>
    </section>
    <!-- /main-page -->
@endsection
