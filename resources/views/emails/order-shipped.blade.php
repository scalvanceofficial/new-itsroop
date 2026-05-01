<!DOCTYPE html>
<html>

<head>
    <title>Your Order #{{ $data['order_number'] }} Has Been Shipped 📦</title>
</head>

<body>
    <h4>Hello {{ $data['customer_name'] }},</h4>

    <p>Good news! Your order <strong>#{{ $data['order_number'] }}</strong> has been 
        <strong>shipped</strong> and is on its way to you. 📦</p>

    <p>We’ll notify you once it’s out for delivery. Get ready to receive your order soon! 😊</p>

    <h3>🧾 Order Summary:</h3>
    <ul>
        @foreach ($data['order_products'] as $order_product)
            <li>
                <strong>{{ $order_product->product->name }}</strong> – 
                {{ $order_product->quantity }} x {{ toCurrency($order_product->price) }}
            </li>
        @endforeach
    </ul>

    <p><strong>Total Amount:</strong> {{ toCurrency($data['total_amount']) }}</p>

    <p>If you have any questions or need assistance, our support team is always here to help.</p>

    <p>🛍️ While you wait, explore the latest trends in clothing, shoes, watches, and more!</p>

    <p>
        <a href="{{ url('/') }}"
           style="background:#000; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;">
           Shop More
        </a>
    </p>

    <p>Thank you for shopping with <strong>Itsroop</strong>! ❤️</p>

    <p>Best regards,<br>
        The Itsroop Team</p>
</body>

</html>