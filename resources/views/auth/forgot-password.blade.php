<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="robots" content="noarchive" />
    <title>Forgot Password - HSC-OHS</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
            --brand-glow: rgba(29, 69, 51, 0.25);
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
            --green-500: #22c55e;
            --red-error-bg: #fef2f2;
            --red-error-text: #991b1b;
            --green-success-bg: #ecfdf5;
            --green-success-text: #065f46;
            --radius: 14px;
            --radius-sm: 8px;
            --shadow-card: 0 20px 60px -12px rgba(0, 0, 0, 0.08), 0 4px 24px -6px rgba(0, 0, 0, 0.02);
            --shadow-focus: 0 0 0 3px var(--brand-glow);
            --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════════MAIN LAYOUT════════════════════════════════════════ */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(29, 69, 51, 0.12), 0 2px 8px rgba(29, 69, 51, 0.08);
            width: 100%;
            max-width: 600px;
            position: relative;
            overflow: hidden;
            border-top: 5px solid var(--brand);
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .brand-header img {
            height: 40px;
            width: auto;
        }

        .brand-title {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--slate-900);
        }

        .brand-title span {
            color: var(--brand);
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .page-description {
            font-size: 0.85rem;
            color: var(--slate-500);
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .alert-success {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            color: var(--green-success-text);
            font-size: 0.8rem;
            padding: 0.6rem 0.9rem;
            background: var(--green-success-bg);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--green-500);
            margin-bottom: 1.25rem;
        }

        .alert-success i {
            color: var(--green-500);
            font-size: 0.85rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            color: var(--red-error-text);
            font-size: 0.8rem;
            padding: 0.6rem 0.9rem;
            background: var(--red-error-bg);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--red-500);
            margin-bottom: 1.25rem;
        }

        .alert-error i {
            color: var(--red-500);
            font-size: 0.85rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .alert-error li {
            margin-top: 0.3rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.35rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 0.85rem;
            pointer-events: none;
        }

        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 0.65rem 0.9rem 0.65rem 2.5rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            font-family: inherit;
            background: var(--white);
            color: var(--slate-900);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
            min-height: 44px;
        }

        input:focus {
            border-color: var(--brand);
            box-shadow: var(--shadow-focus);
        }

        input::placeholder {
            color: var(--slate-400);
            font-size: 0.85rem;
        }

        .input-wrapper.error input {
            border-color: var(--red-500);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }

        .error-text {
            color: var(--red-500);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            animation: slideDown 0.2s ease;
        }

        .error-text i {
            font-size: 0.7rem;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-submit {
            background: var(--brand);
            color: var(--white);
            padding: 0.65rem 1.6rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.25);
            width: 100%;
            min-height: 44px;
        }

        .btn-submit:hover:not(:disabled) {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
        }

        .btn-submit:active:not(:disabled) {
            transform: scale(0.98);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .footer-text {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.82rem;
            color: var(--slate-500);
            padding-top: 1rem;
            border-top: 1px solid var(--slate-100);
        }

        .footer-text a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
            transition: color var(--transition);
        }

        .footer-text a:hover {
            color: var(--brand-dark);
            text-decoration: underline;
        }

        .footer-text a:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        /* ═══════════════════════════════════════RESPONSIVE═══════════════════════════════════════ */
        
        /* Mobile (320px-500px) */
        @media (max-width: 500px) {
            main {
                padding: 1rem 0.5rem;
            }

            .card {
                padding: 1.25rem;
                border-radius: 12px;
                max-width: 100%;
            }

            .brand-header {
                margin-bottom: 1.25rem;
            }

            .brand-header img {
                height: 32px;
            }

            .brand-title {
                font-size: 0.85rem;
            }

            .page-title {
                font-size: 1.1rem;
                margin-bottom: 0.35rem;
            }

            .page-description {
                font-size: 0.8rem;
                margin-bottom: 1.25rem;
            }

            .alert-error, .alert-success {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
                margin-bottom: 1rem;
            }

            .form-group {
                margin-bottom: 0.9rem;
            }

            .form-group label {
                font-size: 0.75rem;
                margin-bottom: 0.2rem;
            }

            input[type="email"],
            input[type="text"] {
                font-size: 0.85rem;
                padding: 0.5rem 0.75rem 0.5rem 2.25rem;
                min-height: 44px;
                border-radius: 6px;
            }

            .input-wrapper .input-icon {
                left: 0.6rem;
                font-size: 0.8rem;
            }

            .btn-submit {
                padding: 0.55rem 1.4rem;
                font-size: 0.8rem;
                min-height: 44px;
                border-radius: 6px;
            }

            .error-text {
                font-size: 0.7rem;
            }

            .footer-text {
                margin-top: 1rem;
                font-size: 0.75rem;
                padding-top: 0.75rem;
            }
        }

        /* Tablet (501px-768px) */
        @media (min-width: 501px) and (max-width: 768px) {
            main {
                padding: 2rem 1.5rem;
            }

            .card {
                padding: 1.75rem 1.5rem;
                max-width: 480px;
            }

            .brand-header {
                margin-bottom: 1.75rem;
            }

            .brand-header img {
                height: 36px;
            }

            .brand-title {
                font-size: 0.95rem;
            }

            .page-title {
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .page-description {
                font-size: 0.9rem;
                margin-bottom: 1.75rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-group label {
                font-size: 0.82rem;
            }

            input[type="email"],
            input[type="text"] {
                font-size: 0.9rem;
                padding: 0.6rem 0.9rem 0.6rem 2.5rem;
                min-height: 44px;
            }

            .input-wrapper .input-icon {
                left: 0.75rem;
                font-size: 0.85rem;
            }

            .btn-submit {
                padding: 0.65rem 1.8rem;
                font-size: 0.88rem;
                min-height: 44px;
            }

            .footer-text {
                margin-top: 1.5rem;
                font-size: 0.85rem;
            }
        }

        /* Desktop (769px+) */
        @media (min-width: 769px) {
            main {
                padding: 3rem 1.5rem;
            }

            .card {
                max-width: 600px;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <div class="card" role="main" aria-label="Forgot password panel">
            <!-- BRAND -->
            <div class="brand-header">
                <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC Logo" />
                <div class="brand-title">HEALTH SERVICE <span>COMMISSION</span></div>
            </div>

            <h1 class="page-title">Reset Password</h1>
            <p class="page-description">
                Enter your email address and we'll send you a link to reset your password.
            </p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert-success" role="status">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Errors -->
            @if ($errors->any())
                <div class="alert-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email address</label>
                    <div class="input-wrapper @error('email') error @enderror">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="you@example.com"
                            aria-describedby="email-error"
                        />
                        <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                    </div>
                    @error('email')
                        <div class="error-text" id="email-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="fa-regular fa-paper-plane"></i> Send Password Reset Link
                </button>
            </form>

            <div class="footer-text">
                Remember your password? <a href="{{ route('login') }}">Log in</a>
                <a href="{{ route('welcome') }}"> | Home</a>
            </div>
        </div>
    </main>

    <!-- JAVASCRIPT -->
    <script>
        (function() {
            'use strict';

            // Aggressive cache prevention
            if (window.history.replaceState) {
                window.history.replaceState({noBackButton: true}, null, window.location.href);
            }

            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    window.location.reload();
                }
                const form = document.querySelector('form');
                if (form) form.reset();
            });

            window.addEventListener('pagehide', function() {
                const form = document.querySelector('form');
                if (form) {
                    form.reset();
                    const inputs = form.querySelectorAll('input[type="email"]');
                    inputs.forEach(input => input.value = '');
                }
                sessionStorage.clear();
            });

            window.addEventListener('load', function() {
                const form = document.querySelector('form');
                if (form) {
                    form.setAttribute('autocomplete', 'off');
                    const emailInput = form.querySelector('input[type="email"]');
                    if (emailInput) {
                        emailInput.setAttribute('autocomplete', 'email');
                    }
                }
            });

            // Focus email on load
            document.addEventListener('DOMContentLoaded', () => {
                const emailInput = document.getElementById('email');
                if (emailInput) {
                    setTimeout(() => emailInput.focus(), 100);
                }
            });
        })();
    </script>
</body>
</html>
