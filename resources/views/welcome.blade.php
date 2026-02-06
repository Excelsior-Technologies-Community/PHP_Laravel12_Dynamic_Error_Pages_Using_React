<!DOCTYPE html>
<html>
<head>
    <title>Laravel + React Error Pages Demo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.2rem;
        }
        .demo-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
        }
        h2 {
            color: #444;
            margin-bottom: 20px;
        }
        .error-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        .error-link {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 150px;
        }
        .error-404 {
            background: #dc3545;
            color: white;
        }
        .error-403 {
            background: #ffc107;
            color: #333;
        }
        .error-500 {
            background: #17a2b8;
            color: white;
        }
        .error-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .features {
            text-align: left;
            background: #f0f7ff;
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
        }
        .features h3 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .features ul {
            list-style: none;
            padding-left: 0;
        }
        .features li {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .features li:last-child {
            border-bottom: none;
        }
        .github-link {
            display: inline-block;
            margin-top: 30px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Laravel 12 + React</h1>
        <p class="subtitle">Dynamic Error Pages Demo</p>
        
        <div class="demo-section">
            <h2>Test Error Pages</h2>
            <p>Click the buttons below to see React-powered error pages:</p>
            
            <div class="error-links">
                <a href="{{ route('test.404') }}" class="error-link error-404">
                    404 Error
                </a>
                <a href="{{ route('test.403') }}" class="error-link error-403">
                    403 Error
                </a>
                <a href="{{ route('test.500') }}" class="error-link error-500">
                    500 Error
                </a>
            </div>
            
            <p style="margin-top: 20px; color: #666;">
                Or visit a non-existent page: <a href="/non-existent-page" style="color: #007bff;">/non-existent-page</a>
            </p>
        </div>
        
        <div class="features">
            <h3>✨ Features:</h3>
            <ul>
                <li>✅ React-powered error pages (404, 403, 500)</li>
                <li>✅ Fully dynamic with React components</li>
                <li>✅ Custom styling for each error type</li>
                <li>✅ Interactive buttons and hover effects</li>
                <li>✅ Responsive design</li>
                <li>✅ Laravel's exception handler integration</li>
                <li>✅ Vite for fast development</li>
                <li>✅ Clean separation of concerns</li>
            </ul>
        </div>
        
        <p style="margin-top: 30px; color: #888; font-size: 0.9rem;">
            This demo shows how to replace Laravel's default error pages with dynamic React components.
        </p>
    </div>
</body>
</html>