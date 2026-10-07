<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HSC-OHS | Statement Submitted</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --brand-light: #f0fdf4;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-500: #64748b;
            --slate-900: #0f172a;
            --white: #ffffff;
            --green-500: #22c55e;
            --radius: 12px;
            --shadow-card: 0 8px 30px -8px rgba(0, 0, 0, 0.08), 0 2px 12px -4px rgba(0, 0, 0, 0.02);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 1.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container { max-width: 600px; width: 100%; }

        .success-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 3rem 2rem;
            box-shadow: var(--shadow-card);
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: var(--green-500);
        }

        .success-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
        }

        .success-message {
            font-size: 0.95rem;
            color: var(--slate-500);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .footer-text {
            font-size: 0.8rem;
            color: var(--slate-500);
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--slate-200);
        }

        @media (max-width: 640px) {
            .success-card { padding: 2rem 1.5rem; }
            .success-title { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h1 class="success-title">Statement Submitted Successfully</h1>
            <p class="success-message">
                Thank you for providing your witness statement. Your account has been recorded and will be used in the incident investigation.
            </p>

            <p class="success-message">
                Your statement has been securely recorded. The incident investigation team will review your account along with other witness statements and evidence.
            </p>

            <p class="footer-text">
                &copy; {{ date('Y') }} HSC-OHS. All rights reserved.<br>
                If you have any questions, please contact the Human Resources Department.
            </p>
        </div>
    </div>
</body>
</html>
