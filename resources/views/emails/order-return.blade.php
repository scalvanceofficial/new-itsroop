<!DOCTYPE html>
<html>
<head>
    <title>Order Return Initiated</title>
</head>
<body>
    <h2>Hello {{ $data['customer_name'] }},</h2>
    <p>We have received your return request for Order #{{ $data['order_number'] }}.</p>
    <p><strong>Product:</strong> {{ $data['product_name'] }}</p>
    <p><strong>Quantity:</strong> {{ $data['quantity'] }}</p>
    <p><strong>Reason/Remark:</strong> {{ $data['remark'] }}</p>
    <p>Our team will review your request and get back to you shortly.</p>
    <p>Thank you for shopping with us!</p>
</body>
</html>
