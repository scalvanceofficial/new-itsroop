<!DOCTYPE html>
<html>

<head>
    <title>Refund Processed for Return – Order #{{ $data['order_number'] }}</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">
    <h4>Thank You, {{ $data['customer_name'] }}! 🙏</h4>

    <p>We’re pleased to inform you that your return has been received and your refund has been 
        <strong>successfully processed</strong>. 🎉</p>

    <h3>🔁 Returned Product:</h3>
    <ul>
        <li><strong>Product:</strong> {{ $data['product_name'] }}</li>
        <li><strong>Quantity:</strong> {{ $data['quantity'] }}</li>
        <li><strong>Refund Amount:</strong> {{ toCurrency($data['refund_amount']) }}</li>
    </ul>

    <p>The refunded amount will be credited to your original payment method within 5–7 business days, depending on your bank or payment provider.</p>

    <p>
        <a href="{{ url('/') }}"
           style="background:#000; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;">
           Shop Again
        </a>
    </p>

    <p>If you have any questions or need assistance, our support team is always here to help.</p>

    <p>We appreciate your trust in <strong>Itsroop</strong> and hope to serve you again soon! ❤️</p>

    <p>Best regards,<br>
        The Itsroop Team</p>
</body>

</html>