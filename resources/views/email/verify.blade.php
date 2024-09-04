<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .email-body {
            padding: 20px;
        }

        .email-footer {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            {{-- <img src="{{ asset('images/e-consul-logo.svg') }}" alt="Logo"> <!-- Adjust path as needed --> --}}
            <h1>Welcome to Our Service!</h1>
        </div>
        <div class="email-body">
            <p>Thank you for updating your profile. To verify your new email address, please use the OTP code below:</p>
            <p style="font-size: 24px; font-weight: bold;">{{$code}}</p>
            <p>If you did not make these changes, please contact us immediately at contact@greencard.ge</p>
        </div>
        <div class="email-footer">
            &copy; {{date("Y")}} Green Card. All rights reserved.
        </div>
    </div>
</body>

</html>
