<!-- resources/views/emails/contact.blade.php -->

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Contact Form Submission</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      margin-top: 50px;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #007bff;
      color: white;
      border-radius: 10px 10px 0 0;
    }

    .card-body {
      padding: 30px;
    }

    .footer {
      margin-top: 50px;
      text-align: center;
      color: #666;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        <h2>Contact Form Submission</h2>
      </div>
      <div class="card-body">
        <p><strong>Name:</strong> {{ $name }}</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Contact No:</strong> {{ $contact_no }}</p>
        <p><strong>Topic:</strong> {{ $topic }}</p>
        <p><strong>Message:</strong></p>
        <p>{{ $user_message }}</p>
      </div>
    </div>
  </div>


</body>

</html>
