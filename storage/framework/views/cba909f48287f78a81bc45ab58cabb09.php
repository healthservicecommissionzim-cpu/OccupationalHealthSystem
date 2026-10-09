<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Access Denied · HSC-OHS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
            --slate-50: #FBFBFA;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --white: #ffffff;
            --red-500: #ef4444;
            --amber-500: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--brand-light) 0%, #f0f2f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container { max-width: 600px; width: 100%; }

        .error-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            width: 60px;
            height: 60px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .error-icon i {
            font-size: 2rem;
            color: var(--red-500);
        }

        h1 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
        }

        .error-message {
            font-size: 0.9rem;
            color: var(--slate-600);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.55rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--brand);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        .btn-secondary {
            background: var(--slate-100);
            color: var(--slate-700);
            border: 1px solid var(--slate-200);
        }

        .btn-secondary:hover {
            background: var(--slate-50);
        }

        .footer-text {
            font-size: 0.75rem;
            color: var(--slate-500);
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--slate-200);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card">
            <div class="error-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <h1>Unable to Access This Form</h1>
            
            <p class="error-message">
                We're unable to process your request at this time. This could happen if the link has expired or has already been used. Please contact your administrator or get support below.
            </p>

            <div class="action-buttons">
                <a href="<?php echo e(route('support.create_ticket')); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-headset"></i> Contact IT Support
                </a>
                <a href="<?php echo e(route('welcome')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-home"></i> Go Home
                </a>
            </div>

            <div class="footer-text">
                <p>If you believe this is an error, please contact IT Support with details about this issue.</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\witness_access_denied.blade.php ENDPATH**/ ?>