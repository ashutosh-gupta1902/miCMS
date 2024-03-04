<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Our Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .welcome-message {
            margin-top: 50px;
        }

        .login-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #FF2D20;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/cms-logo.png') }}" alt="Logo" width="150">
        <div class="welcome-message">
            <h1>Welcome to Our Website</h1>
            <p>Enjoy exploring our services and features.</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('admin.login') }}" class="login-button">Admin Login</a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('app.login') }}" class="login-button">User Login</a>
            </div>
        </div>
    </div>
</body>

</html>