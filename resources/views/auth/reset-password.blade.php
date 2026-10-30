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
    <title>Reset Password | HSC-OHS</title>
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            margin: 0;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: clamp(1.25rem, 4vw, 2rem);
            box-shadow: var(--shadow-card);
            width: 100%;
            max-width: 440px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand), #2d5a47, var(--brand));
            background-size: 200% 100%;
            animation: shimmer 4s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%,100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .brand-header img {
            height: clamp(32px, 8vw, 40px);
            width: auto;
        }

        .brand-title {
            font-size: clamp(0.75rem, 3vw, 1rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--slate-900);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .brand-title span {
            color: var(--brand);
        }

        .page-title {
            font-size: clamp(1.2rem, 5vw, 1.5rem);
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.5rem;
            text-align: center;
            letter-spacing: -0.01em;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .page-description {
            font-size: clamp(0.8rem, 2.5vw, 0.95rem);
            color: var(--slate-500);
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .status-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--green-success-text);
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            padding: clamp(0.5rem, 2vw, 0.75rem) clamp(0.7rem, 2vw, 0.9rem);
            background: var(--green-success-bg);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--brand);
            margin-bottom: 1.25rem;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.5;
        }

        .status-message i {
            color: var(--brand);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            color: var(--red-error-text);
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            padding: clamp(0.5rem, 2vw, 0.75rem) clamp(0.7rem, 2vw, 0.9rem);
            background: var(--red-error-bg);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--red-500);
            margin-bottom: 1.25rem;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.5;
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

        .alert-error strong {
            display: block;
            margin-bottom: 0.3rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: clamp(0.7rem, 2vw, 0.85rem);
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.35rem;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
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
            flex-shrink: 0;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: clamp(0.5rem, 2vw, 0.7rem) clamp(0.6rem, 2vw, 0.9rem) clamp(0.5rem, 2vw, 0.7rem) 2.5rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-size: clamp(0.85rem, 2vw, 1rem);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--white);
            color: var(--slate-900);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
            min-height: 44px;
            display: flex;
            align-items: center;
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
            font-size: clamp(0.7rem, 2vw, 0.85rem);
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            animation: slideDown 0.2s ease;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.4;
        }

        .error-text i {
            font-size: 0.65rem;
            flex-shrink: 0;
        }

        .input-wrapper.valid input {
            border-color: var(--green-500) !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12) !important;
        }

        .input-wrapper.valid .input-icon {
            color: var(--green-500);
        }

        .btn-submit {
            background: var(--brand);
            color: var(--white);
            padding: clamp(0.5rem, 2vw, 0.7rem) 1.6rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: clamp(0.8rem, 2vw, 0.95rem);
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .btn-submit:hover:not(:disabled) {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .footer-text {
            text-align: center;
            margin-top: 1.25rem;
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            color: var(--slate-500);
            padding-top: 1rem;
            border-top: 1px solid var(--slate-100);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.5;
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

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--slate-400);
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
        }

        .password-toggle:hover {
            color: var(--slate-600);
        }

        .password-toggle:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
            border-radius: 4px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ═══════════════════════════════════════RESPONSIVE═══════════════════════════════════════ */
        @media (max-width: 768px) {
            header {
                padding: clamp(0.75rem, 2vw, 1rem) clamp(1%, 3vw, 2%);
            }

            nav {
                display: none;
            }

            .nav-buttons {
                display: none;
            }

            main {
                padding: clamp(1rem, 3vw, 1.5rem) clamp(0.5rem, 2vw, 1rem);
            }

            footer {
                padding: clamp(1.25rem, 3vw, 1.5rem) clamp(1%, 3vw, 2%);
            }
        }

        @media (max-width: 500px) {
            header {
                padding: 0.75rem 1%;
            }

            .logo img {
                height: 35px;
            }

            .logo-text {
                font-size: 0.75rem;
            }

            main {
                padding: 0.75rem 0.25rem;
            }

            footer {
                padding: 1rem 1rem;
            }

            body {
                padding-top: 0;
            }

            .card {
                padding: clamp(1rem, 3vw, 1.5rem);
                border-radius: 10px;
                margin: 0 0.25rem;
            }

            .brand-header {
                margin-bottom: 1rem;
            }

            .brand-header img {
                height: 28px;
            }

            .brand-title {
                font-size: 0.7rem;
            }

            .page-title {
                font-size: 1.1rem;
                margin-bottom: 0.4rem;
            }

            .page-description {
                font-size: 0.8rem;
                margin-bottom: 1.2rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .status-message {
                font-size: 0.7rem;
                padding: 0.5rem 0.6rem;
            }

            .alert-error {
                font-size: 0.7rem;
                padding: 0.5rem 0.6rem;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="card" role="main" aria-label="Reset password panel">
        <!-- BRAND -->
        <div class="brand-header">
            <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC Logo" />
            <div class="brand-title">HEALTH SERVICE <span>COMMISSION</span></div>
        </div>

        <h1 class="page-title">Set New Password</h1>
        <p class="page-description">
            Enter your new password below to regain access to your account.
        </p>

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
        <form method="POST" action="{{ route('password.store') }}" novalidate>
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email (read-only, pre-filled from reset link) -->
            <div class="form-group">
                <label for="email">Email address</label>
                <div class="input-wrapper @error('email') error @enderror">
                    <input
                        id="email"
                        type="email"
                            name="email"
                            value="{{ old('email', request()->email) }}"
                            required
                            readonly
                            autocomplete="email"
                            style="background-color: #f8fafc; cursor: not-allowed;"
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

                <!-- Password -->
                <div class="form-group">
                    <label for="password">New password</label>
                    <div class="input-wrapper @error('password') error @enderror">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autofocus
                            autocomplete="new-password"
                            placeholder="••••••••••••"
                            aria-describedby="password-error"
                        />
                        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                        <button type="button" class="password-toggle" id="toggle-password" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-text" id="password-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <div class="input-wrapper @error('password_confirmation') error @enderror">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••••••"
                            aria-describedby="password-confirm-error"
                        />
                        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                        <button type="button" class="password-toggle" id="toggle-confirm" aria-label="Toggle confirm password visibility">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="error-text" id="password-confirm-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="fa-regular fa-check-circle"></i> Reset Password
                </button>
            </form>

            <div class="footer-text">
                Remember your password? <a href="{{ route('login') }}">Log in</a>
                <a href="{{ route('welcome') }}"> | Home</a>
            </div>
        </div>
    </body>
</html>

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
                    const inputs = form.querySelectorAll('input[type="password"], input[type="email"]');
                    inputs.forEach(input => input.value = '');
                }
                sessionStorage.clear();
                localStorage.removeItem('resetPasswordFormData');
            });

            window.addEventListener('load', function() {
                const form = document.querySelector('form');
                if (form) {
                    form.setAttribute('autocomplete', 'off');
                    form.reset();
                    const inputs = form.querySelectorAll('input[type="password"], input[type="email"]');
                    inputs.forEach(input => {
                        input.value = '';
                        input.setAttribute('autocomplete', 'off');
                    });
                }
            });

            // Form submit handler
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    sessionStorage.clear();
                    localStorage.removeItem('resetPasswordFormData');
                });
            }

            // Focus password on load (email is read-only)
            document.addEventListener('DOMContentLoaded', () => {
                const passwordInput = document.getElementById('password');
                if (passwordInput) {
                    setTimeout(() => passwordInput.focus(), 100);
                }
            });

            // ─── PASSWORD TOGGLE ────────────────────
            function setupPasswordToggle() {
                const togglePassword = document.getElementById('toggle-password');
                const passwordInput = document.getElementById('password');
                
                if (togglePassword && passwordInput) {
                    togglePassword.addEventListener('click', function(e) {
                        e.preventDefault();
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
                        this.innerHTML = type === 'password' 
                            ? '<i class="fa-regular fa-eye"></i>' 
                            : '<i class="fa-regular fa-eye-slash"></i>';
                    });
                }

                const toggleConfirm = document.getElementById('toggle-confirm');
                const confirmInput = document.getElementById('password_confirmation');
                
                if (toggleConfirm && confirmInput) {
                    toggleConfirm.addEventListener('click', function(e) {
                        e.preventDefault();
                        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        confirmInput.setAttribute('type', type);
                        this.innerHTML = type === 'password' 
                            ? '<i class="fa-regular fa-eye"></i>' 
                            : '<i class="fa-regular fa-eye-slash"></i>';
                    });
                }
            }

            // ─── REAL-TIME FORM VALIDATION ────────────────────
            function setupFormValidation() {
                const form = document.querySelector('form');
                if (!form) return;

                const emailInput = form.querySelector('input[type="email"]');
                const passwordInput = form.querySelector('input[name="password"]');
                const confirmInput = form.querySelector('input[name="password_confirmation"]');

                // Clear error when user starts typing
                [emailInput, passwordInput, confirmInput].forEach(input => {
                    if (input) {
                        input.addEventListener('input', function() {
                            clearError(this);
                        });
                        input.addEventListener('blur', function() {
                            validateField(this);
                        });
                        input.addEventListener('focus', function() {
                            clearError(this);
                        });
                    }
                });
            }

            function clearError(input) {
                const wrapper = input.closest('.input-wrapper');
                const errorText = wrapper.parentElement.querySelector('.error-text');

                if (wrapper) {
                    wrapper.classList.remove('error', 'valid');
                }

                if (errorText) {
                    errorText.style.opacity = '0';
                    setTimeout(() => {
                        if (errorText.parentElement) {
                            errorText.remove();
                        }
                    }, 300);
                }
            }

            function validateField(input) {
                const wrapper = input.closest('.input-wrapper');
                const value = input.value.trim();
                const type = input.getAttribute('type');

                if (!value) {
                    showError(input, 'This field is required');
                    return false;
                }

                if (type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        showError(input, 'Please enter a valid email address');
                        return false;
                    }
                }

                if (type === 'password' && value.length < 8) {
                    showError(input, 'Password must be at least 8 characters');
                    return false;
                }

                // Valid
                if (wrapper) {
                    wrapper.classList.remove('error');
                    wrapper.classList.add('valid');
                }
                return true;
            }

            function showError(input, message) {
                const wrapper = input.closest('.input-wrapper');
                const parent = wrapper.parentElement;

                if (wrapper) {
                    wrapper.classList.remove('valid');
                    wrapper.classList.add('error');
                }

                // Remove existing error if any
                const existingError = parent.querySelector('.error-text');
                if (existingError) {
                    existingError.remove();
                }

                // Create and show new error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-text';
                errorDiv.setAttribute('role', 'alert');
                errorDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${message}`;
                parent.appendChild(errorDiv);
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', () => {
                setupPasswordToggle();
                setupFormValidation();
            });
        })();
    </script>
</body>
</html>
