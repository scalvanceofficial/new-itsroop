<!DOCTYPE html>
<html>

<head>
  <title>New Contact Enquiry – Itsroop</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      color: #333;
      line-height: 1.6;
      background-color: #f9f9f9;
      padding: 20px;
    }

    .container {
      max-width: 700px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    h2 {
      color: #4CAF50;
      margin-bottom: 10px;
    }

    p {
      margin-bottom: 20px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }

    td,
    th {
      border: 1px solid #ddd;
      padding: 12px;
    }

    th {
      background-color: #f0f0f0;
      text-align: left;
    }

    .footer {
      margin-top: 30px;
      font-size: 14px;
      color: #777;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>📩 New Contact Enquiry Received</h2>

    <p>Hello Team,</p>

    <p>A new contact enquiry has been submitted on <strong>Itsroop</strong>. Please find the details below:</p>

    <table>
      <tr>
        <th>Name</th>
        <td>{{ $data['name'] }}</td>
      </tr>
      <tr>
        <th>Email Address</th>
        <td>{{ $data['email'] }}</td>
      </tr>
      <tr>
        <th>Phone Number</th>
        <td>{{ $data['mobile'] }}</td>
      </tr>
      <tr>
        <th>Message</th>
        <td>
          {{ $data['message'] }}
        </td>
      </tr>
    </table>

    <p class="footer">
      Thank you for staying connected with us.<br>
      — Technicul Cloud Team
    </p>
  </div>
</body>

</html>
