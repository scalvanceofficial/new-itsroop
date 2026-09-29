<!DOCTYPE html>

<html>

<head> <title>Welcome to Itsroop – Discover Your Style</title> </head>

<body style="margin:0; padding:0; background-color:#f7f7f7; font-family:Arial, Helvetica, sans-serif; color:#333;">

<div style="max-width:600px; margin:30px auto; background:#ffffff; padding:40px;">

    <h2 style="margin:0 0 20px; color:#222; font-size:24px;">
        Welcome, {{ $data['name'] }}!
    </h2>

    <p style="font-size:15px; line-height:1.7; margin-bottom:20px;">
        Thank you for joining <strong>Itsroop</strong>. We're excited to have you as part of our community.
    </p>

    <p style="font-size:15px; line-height:1.7; margin-bottom:20px;">
        At Itsroop, we bring you a carefully selected collection of clothing designed to help you look and feel your best. 
        From everyday essentials to stylish pieces, we're here to make finding your next favorite outfit simple and enjoyable.
    </p>

    <p style="font-size:15px; line-height:1.7; margin-bottom:25px;">
        Explore our latest collection and discover styles that fit your personality and everyday lifestyle.
    </p>

    <p style="margin:30px 0;">
        <a href="{{ url('/') }}"
            style="display:inline-block; background:#222222; color:#ffffff; padding:13px 28px; text-decoration:none; border-radius:4px; font-size:14px; font-weight:bold;">
            Shop Now
        </a>
    </p>

    <p style="font-size:15px; line-height:1.7; margin-bottom:20px;">
        If you have any questions about our products, your order, or your shopping experience, our support team is always happy to assist you.
    </p>

    <p style="font-size:15px; line-height:1.7; margin-bottom:25px;">
        Thank you for choosing <strong>Itsroop</strong>. We look forward to helping you find styles you'll love.
    </p>

    <p style="font-size:15px; line-height:1.7; margin:0;">
        Warm regards,<br>
        <strong>The Itsroop Team</strong>
    </p>

</div>

</body>

</html>