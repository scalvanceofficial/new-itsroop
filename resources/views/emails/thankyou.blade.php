<!DOCTYPE html>
<html>

<head>
  <title>Thank You – Itsroop</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      color: #333;
      padding: 20px;
    }

    .container {
      max-width: 650px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h1 {
      color: #4CAF50;
    }

    p {
      line-height: 1.6;
      margin: 15px 0;
    }

    .button {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 25px;
      background-color: #4CAF50;
      color: #fff !important;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    .footer {
      margin-top: 40px;
      font-size: 14px;
      color: #777;
    }
  </style>
</head>

<body>
  <div class="container">
    <h4>Thank You, {{ $data['name'] }}!</h4>

    <p>We’ve received your enquiry and our team is already on it! 🙌</p>

    <p>A Itsroop representative will connect with you shortly to assist with your query.</p>

    <p>In the meantime, feel free to explore more of our products.</p>

    <a href="{{ url('/') }}" class="button">Explore Itsroop</a>

    <div class="footer">
      <p>Thanks again for reaching out to us!<br>
        Warm regards,<br>
        <strong>The Itsroop Team</strong>
      </p>
    </div>
  </div>
</body>

</html>
