<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error {{ $code }} - {{ config('app.name', 'Laravel') }}</title>
    @vite('resources/js/app.jsx')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8f9fa;
        }
        #app {
            min-height: 100vh;
        }
        a, button {
            transition: all 0.3s ease;
        }
        a:hover, button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div id="app" data-page="{{ $code }}"></div>
</body>
</html>