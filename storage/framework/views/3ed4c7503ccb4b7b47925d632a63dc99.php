<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="robots" content="noarchive" />
    <title>Login - HSC-OHS</title>
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

        /* ═══════════════════════════════════════HEADER════════════════════════════════════════ */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem 4%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo img {
            height: 55px;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1rem;
            color: var(--brand);
            letter-spacing: -0.02em;
        }

        nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: var(--slate-600);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--brand);
        }

        .nav-buttons {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--brand);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.2);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
        }

        /* ═══════════════════════════════════════MAIN LAYOUT════════════════════════════════════════ */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        /* ═══════════════════════════════════════FOOTER════════════════════════════════════════ */
        footer {
            background: var(--brand);
            color: rgba(255, 255, 255, 0.9);
            padding: 2rem 4%;
            text-align: center;
        }

        footer h3 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        footer p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin: 0.5rem 0;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 2rem;
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
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.25rem;
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
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.6rem 0.9rem 0.6rem 2.5rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
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

        /* Password toggle button */
        .toggle-password-btn {
            position: absolute;
            right: 0.6rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--slate-400);
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
            transition: color var(--transition);
        }

        .toggle-password-btn:hover {
            color: var(--slate-600);
        }

        .toggle-password-btn:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Remember me checkbox */
        .form-group.checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group.checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--brand);
        }

        .form-group.checkbox label {
            margin-bottom: 0;
            cursor: pointer;
            font-size: 0.82rem;
            color: var(--slate-600);
            font-weight: 500;
        }

        .form-group.checkbox input[type="checkbox"]:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .btn-submit {
            background: var(--brand);
            color: var(--white);
            padding: 0.6rem 1.6rem;
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

        .forgot-password-link {
            text-align: right;
            margin-bottom: 1.5rem;
        }

        .forgot-password-link a {
            color: var(--slate-600) !important;
            text-decoration: none !important;
            font-weight: 500;
            font-size: 0.82rem;
            transition: color var(--transition);
        }

        .forgot-password-link a:hover {
            color: var(--brand) !important;
            text-decoration: none !important;
        }

        .forgot-password-link a:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        /* Form Actions Row */
        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .form-actions .form-group.checkbox {
            margin-bottom: 0;
        }

        .form-actions .forgot-password-link {
            margin-bottom: 0;
            text-align: right;
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
        
        /* Tablet (501px-768px) - Increased padding, optimized spacing */
        @media (min-width: 501px) and (max-width: 768px) {
            header {
                padding: 1rem 3%;
            }

            nav {
                display: none;
            }

            .nav-buttons {
                display: none;
            }

            main {
                padding: 2rem 1.5rem;
            }

            footer {
                padding: 1.75rem 3%;
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
            input[type="password"],
            input[type="text"] {
                font-size: 0.9rem;
                padding: 0.6rem 0.9rem 0.6rem 2.5rem;
                min-height: 44px;
            }

            .input-wrapper .input-icon {
                left: 0.75rem;
                font-size: 0.85rem;
            }

            .toggle-password-btn {
                right: 0.6rem;
                font-size: 0.9rem;
            }

            .form-group.checkbox {
                margin-bottom: 1.5rem;
            }

            .forgot-password-link {
                margin-bottom: 1.5rem;
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

        /* Mobile (320px-500px) - Tight spacing, single column, smaller fonts */
        @media (max-width: 500px) {
            header {
                padding: 1rem 2%;
            }

            .logo img {
                height: 40px;
            }

            .logo-text {
                font-size: 0.9rem;
            }

            nav {
                display: none;
            }

            .nav-buttons {
                display: none;
            }

            main {
                padding: 1rem 0.5rem;
            }

            footer {
                padding: 1.25rem 1.5rem;
            }

            body {
                padding-top: 0;
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

            .alert-error {
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
            input[type="password"],
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

            .toggle-password-btn {
                right: 0.4rem;
                font-size: 0.85rem;
                min-width: 40px;
                min-height: 40px;
                margin: -2px;
            }

            .form-group.checkbox {
                margin-bottom: 1rem;
                gap: 0.4rem;
            }

            .form-group.checkbox label {
                font-size: 0.78rem;
            }

            .forgot-password-link {
                margin-bottom: 1.1rem;
            }

            .forgot-password-link a {
                font-size: 0.78rem;
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

        /* Desktop (769px+) - Centered card with appropriate max-width */
        @media (min-width: 769px) {
            main {
                padding: 3rem 1.5rem;
            }

            .card {
                max-width: 440px;
            }
        }

        /* Ultra-small phones (320px) - Extra tight constraints */
        @media (max-width: 340px) {
            .card {
                padding: 1rem;
                border-radius: 10px;
            }

            .brand-header {
                margin-bottom: 1rem;
            }

            .brand-header img {
                height: 28px;
            }

            .brand-title {
                font-size: 0.8rem;
            }

            .page-title {
                font-size: 1rem;
                margin-bottom: 0.3rem;
            }

            .page-description {
                font-size: 0.75rem;
                margin-bottom: 1rem;
            }

            input[type="email"],
            input[type="password"],
            input[type="text"] {
                font-size: 0.8rem;
                padding: 0.45rem 0.65rem 0.45rem 2rem;
            }

            .form-group {
                margin-bottom: 0.75rem;
            }

            .btn-submit {
                padding: 0.5rem 1.2rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>


    <!-- Main Content -->
    <main>
        <div class="card" role="main" aria-label="Login panel">
            <!-- BRAND -->
            <div class="brand-header">
                <img src="<?php echo e(asset('images/hsc-logo.png')); ?>" alt="HSC Logo" />
                <div class="brand-title">HEALTH SERVICE <span>COMMISSION</span></div>
            </div>

            <h1 class="page-title">Log In</h1>
            <p class="page-description">
                Enter your credentials to access your account securely.
            </p>

            <!-- Errors -->
            <?php if($errors->any()): ?>
                <div class="alert-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form method="POST" action="<?php echo e(route('login.store')); ?>" novalidate>
                <?php echo csrf_field(); ?>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email address</label>
                    <div class="input-wrapper <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="<?php echo e(old('email')); ?>"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="you@example.com"
                            aria-describedby="email-error"
                        />
                        <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-text" id="email-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            aria-describedby="password-error"
                        />
                        <span class="input-icon"><i class="fa-regular fa-lock"></i></span>
                        <button
                            type="button"
                            class="toggle-password-btn"
                            id="toggle-password"
                            aria-label="Toggle password visibility"
                            aria-pressed="false"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-text" id="password-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Remember Me & Forgot Password Row -->
                <div class="form-actions">
                    <div class="form-group checkbox">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            <?php echo e(old('remember') ? 'checked' : ''); ?>

                        />
                        <label for="remember">Remember me</label>
                    </div>

                    <a href="<?php echo e(route('password.request')); ?>" class="forgot-password-link">Forgot your password?</a>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> Log In
                </button>
            </form>

            <div class="footer-text">
                Don't have an account? <a href="<?php echo e(route('register')); ?>">Register here</a>
                <a href="<?php echo e(route('welcome')); ?>"> | Home</a>
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
                    const inputs = form.querySelectorAll('input[type="email"], input[type="password"]');
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
                    const passwordInput = form.querySelector('input[type="password"]');
                    if (passwordInput) {
                        passwordInput.setAttribute('autocomplete', 'current-password');
                    }
                }
            });

            // Password visibility toggle
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleBtn.setAttribute('aria-pressed', !isPassword);
                    toggleBtn.innerHTML = isPassword ? '<i class="fa-regular fa-eye-slash"></i>' : '<i class="fa-regular fa-eye"></i>';
                });
            }

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
<?php /**PATH D:\OccupationalHealth System\resources\views\auth\login.blade.php ENDPATH**/ ?>