<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 + React Dynamic Error Pages & Telemetry Suite</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #020617 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
            color: #f8fafc;
        }

        .container {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            padding: 45px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            max-width: 860px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #ffffff;
            margin-bottom: 10px;
            font-size: 2.3rem;
            font-weight: 800;
        }

        .subtitle {
            color: #94a3b8;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .demo-section {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px;
            border-radius: 16px;
            margin: 25px 0;
        }

        .demo-section h2 {
            color: #38bdf8;
            margin-bottom: 8px;
            font-size: 1.3rem;
        }

        .error-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
        }

        .error-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .error-404 {
            background: #dc2626;
            color: white;
        }

        .error-403 {
            background: #d97706;
            color: white;
        }

        .error-500 {
            background: #e11d48;
            color: white;
        }

        .error-dash {
            background: #2563eb;
            color: white;
        }

        .error-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
        }

        .features {
            text-align: left;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px;
            border-radius: 16px;
            margin-top: 25px;
        }

        .features h3 {
            color: #38bdf8;
            margin-bottom: 15px;
            font-size: 1.15rem;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
        }

        .feature-item {
            padding: 10px 14px;
            background: rgba(30, 41, 59, 0.6);
            border-radius: 10px;
            font-size: 0.88rem;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚀 Laravel 12 + React 18</h1>
        <p class="subtitle">Dynamic Error Pages, Mini-Games & Telemetry Security Radar</p>

        <div class="demo-section">
            <h2>Interactive Error Pages Experience</h2>
            <p style="color: #94a3b8; font-size: 0.9rem;">Test dynamic React error components, play mini-games, switch themes, and submit incident tickets:</p>

            <div class="error-links">
                <a href="{{ route('test.404') }}" class="error-link error-404" target="_blank">
                    <i class="fa-solid fa-compass"></i> 404 + Space Jumper Game
                </a>
                <a href="{{ route('test.403') }}" class="error-link error-403" target="_blank">
                    <i class="fa-solid fa-lock"></i> 403 + Cyber Sentinel
                </a>
                <a href="{{ route('test.500') }}" class="error-link error-500" target="_blank">
                    <i class="fa-solid fa-server"></i> 500 + Server Snake Game
                </a>
                <a href="{{ route('error.dashboard') }}" class="error-link error-dash">
                    <i class="fa-solid fa-radar"></i> Error Radar Dashboard
                </a>
            </div>

            <p style="margin-top: 18px; color: #64748b; font-size: 0.85rem;">
                Try a random missing route: <a href="/explore/unknown-product-slug" target="_blank" style="color: #38bdf8;">/explore/unknown-product-slug</a>
            </p>
        </div>

        <div class="features">
            <h3>⚡ Enhanced Production Capabilities:</h3>
            <div class="feature-grid">
                <div class="feature-item">
                    <span>🕹️</span> <strong>In-Browser Mini-Games</strong> (Dino Runner & Retro Snake)
                </div>
                <div class="feature-item">
                    <span>🎨</span> <strong>Interactive Theme Studio</strong> (Clean, Cyberpunk & Glass)
                </div>
                <div class="feature-item">
                    <span>🛸</span> <strong>Animated Vector Mascots</strong> (Astronaut, Sentinel, Server)
                </div>
                <div class="feature-item">
                    <span>🚨</span> <strong>1-Click Incident Ticket Modal</strong> (Auto-captures diagnostics)
                </div>
                <div class="feature-item">
                    <span>🛡️</span> <strong>Malicious Bot Scanner Radar</strong> (Flag probes & 1-click IP ban)
                </div>
                <div class="feature-item">
                    <span>⚡</span> <strong>Real-Time Live Telemetry</strong> (Dynamic toast stream & Chart.js)
                </div>
            </div>
        </div>

        <p style="margin-top: 25px; color: #64748b; font-size: 0.82rem;">
            Enterprise-grade error experience engineered with Laravel 12 &amp; React 18.
        </p>
    </div>
</body>

</html>
