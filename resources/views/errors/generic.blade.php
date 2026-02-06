@php
    $code = $exception->getStatusCode();
    $message = $exception->getMessage() ?: 'An error occurred';
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Error {{ $code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
        }
        .error-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: inline-block;
            margin-top: 100px;
        }
        h1 {
            color: #dc3545;
            font-size: 72px;
            margin: 0;
        }
        p {
            color: #666;
            margin: 20px 0;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>{{ $code }}</h1>
        <p>{{ $message }}</p>
        <a href="/">Go Home</a>
    </div>
</body>
</html>