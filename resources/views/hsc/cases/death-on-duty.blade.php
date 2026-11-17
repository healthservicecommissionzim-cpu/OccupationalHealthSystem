<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Death on Duty Cases - HSC OHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 30px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        h1 {
            font-size: 32px;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 18px;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        p {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .back-button i {
            font-size: 16px;
        }

        .feature-badge {
            display: inline-block;
            padding: 8px 20px;
            background: #f0f4ff;
            border: 2px dashed #667eea;
            border-radius: 50px;
            color: #667eea;
            font-size: 13px;
            font-weight: 600;
            margin-top: 20px;
        }

        @media (max-width: 640px) {
            .container {
                padding: 40px 25px;
            }

            h1 {
                font-size: 26px;
            }

            .subtitle {
                font-size: 16px;
            }

            p {
                font-size: 14px;
            }

            .icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fa-solid fa-heart-crack"></i>
        </div>
        
        <div class="subtitle">Death on Duty Cases</div>
        
        <h1>Coming Soon</h1>
        
        <p>
            We're working on a dedicated module to manage and track Death on Duty cases with enhanced reporting, 
            documentation workflows, and compliance tracking features.
        </p>

        <div class="feature-badge">
            <i class="fa-solid fa-hammer"></i> Under Development
        </div>

        <div style="margin-top: 40px;">
            <a href="{{ route('dashboards.hsc_conditions') }}" class="back-button">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
