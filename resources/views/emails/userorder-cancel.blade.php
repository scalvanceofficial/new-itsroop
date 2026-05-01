<!DOCTYPE html>
<html>

<head>
    <title>Your Order #{{ $data['order_number'] }} Has Been Cancelled ❌</title>
</head>

<body>
    <h4>Hello {{ $data['customer_name'] }},</h4>

    <p>We regret to inform you that your order <strong>#{{ $data['order_number'] }}</strong> has been 
        <strong>cancelled</strong>. 😞</p>

    <p>If this was unexpected or you need any assistance, please don’t hesitate to contact our support team — we’re here to help. 🤝</p>

    <h3>🧾 <strong>Order Summary:</strong></h3>
    <ul>
        @foreach ($data['order_products'] as $order_product)
            <li>
                <strong>{{ $order_product->product->name }}</strong> – 
                {{ $order_product->quantity }} x {{ toCurrency($order_product->price) }}
            </li>
        @endforeach
    </ul>

    <p><strong>Total Amount:</strong> {{ toCurrency($data['total_amount']) }}</p>

    <p>🛍️ Explore the latest trends in clothing, shoes, watches, and more on Itsroop.</p>

    <p>
        <a href="{{ url('/') }}"
           style="background:#000; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;">
           Shop Now
        </a>
    </p>

    <p>Thank you for choosing <strong>Itsroop</strong>. We hope to serve you again soon! ❤️</p>

    <p>Best regards,<br>
        The Itsroop Team</p>
</body>

</html>