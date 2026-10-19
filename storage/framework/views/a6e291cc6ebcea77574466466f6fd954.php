<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>HSC-OHS | First Aid Access - Token Verification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
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
            --radius: 12px;
            --radius-sm: 6px;
            --shadow-card: 0 8px 30px -8px rgba(0, 0, 0, 0.08), 0 2px 12px -4px rgba(0, 0, 0, 0.02);
            --shadow-focus: 0 0 0 3px var(--brand-glow);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f2f5 0%, #e5e7eb 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }

        .container { width: 100%; max-width: 440px; }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border-top: 5px solid var(--brand);
            overflow: hidden;
        }

        .header {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .icon {
            width: 40px;
            height: 40px;
            background: var(--brand-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--brand);
            font-size: 1.2rem;
        }

        h1 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.15rem;
        }

        .subtitle {
            font-size: 0.75rem;
            color: var(--slate-500);
        }

        .back-link {
            text-decoration: none;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            background: var(--brand);
            transition: all var(--transition);
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.15);
        }

        .back-link:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.25);
        }

        .back-link:active {
            transform: translateY(0);
        }

        .body {
            padding: 1.5rem 2rem;
        }

        .info-text {
            font-size: 0.75rem;
            color: var(--slate-600);
            line-height: 1.5;
            margin-bottom: 1.25rem;
            padding: 0.75rem 0.9rem;
            background: var(--brand-light);
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-sm);
        }

        .info-text strong {
            color: var(--brand);
            font-weight: 700;
        }

        .alert {
            padding: 0.65rem 0.9rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            border-left: 4px solid var(--red-500);
            background: #fef2f2;
            color: #991b1b;
            font-size: 0.75rem;
        }

        .alert i { 
            color: var(--red-500); 
            margin-top: 1px;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.5rem;
        }

        .token-input-wrapper {
            display: flex;
            gap: 0.6rem;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .token-box {
            width: 45px;
            height: 50px;
            font-size: 1.3rem;
            font-weight: 800;
            text-align: center;
            border: 2px solid var(--slate-200);
            border-radius: 8px;
            font-family: monospace;
            letter-spacing: 0.1em;
            background: var(--white);
            transition: all var(--transition);
        }

        .token-box:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: var(--shadow-focus);
            transform: scale(1.03);
        }

        .token-box:hover {
            border-color: var(--brand);
        }

        .token-box.filled {
            border-color: var(--brand);
            background: var(--brand-light);
        }

        .token-box.complete {
            border-color: var(--green-500);
            background: var(--brand-light);
        }

        .loading {
            display: none;
            text-align: center;
            color: var(--slate-500);
            font-size: 0.75rem;
            margin-top: 0.75rem;
        }

        .loading.active {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid var(--slate-200);
            border-top-color: var(--brand);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .back-link-footer {
            text-align: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--slate-100);
        }

        .back-link-footer a {
            color: var(--brand);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .back-link-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container { max-width: 100%; }
            .card { border-radius: 8px; }
            .header { padding: 1rem 1.5rem; }
            .body { padding: 1.25rem 1.5rem; }
            .token-box { width: 40px; height: 45px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="header-content">
                    <div class="icon">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h1>Token Verification</h1>
                        <p class="subtitle">Enter your 4-digit code</p>
                    </div>
                </div>
                <a href="<?php echo e(route('welcome')); ?>" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Home
                </a>
            </div>

            <div class="body">
                <div class="info-text">
                    <strong>Enter the 4-digit token</strong> you received to access the first aid recording form.
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><?php echo e($error); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div><?php echo e(session('error')); ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('first_aid.verify_token')); ?>" novalidate id="tokenForm">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <div class="token-input-wrapper">
                            <input type="text" class="token-box" id="digit1" maxlength="1" inputmode="numeric" pattern="[0-9]" required autofocus>
                            <input type="text" class="token-box" id="digit2" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="token-box" id="digit3" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="token-box" id="digit4" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                        </div>
                        <input type="hidden" id="token" name="token">
                    </div>

                    <div class="loading" id="loading">
                        <div class="spinner"></div>
                        <span>Verifying token...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Get all digit inputs
        const digits = ['digit1', 'digit2', 'digit3', 'digit4'];
        const form = document.getElementById('tokenForm');
        const tokenInput = document.getElementById('token');
        const loading = document.getElementById('loading');

        digits.forEach((id, index) => {
            const input = document.getElementById(id);

            input.addEventListener('input', function(e) {
                // Allow only digits
                e.target.value = e.target.value.replace(/[^0-9]/g, '');

                // Add filled class
                if (e.target.value) {
                    e.target.classList.add('filled');
                } else {
                    e.target.classList.remove('filled');
                }

                // Move to next digit
                if (e.target.value && index < digits.length - 1) {
                    document.getElementById(digits[index + 1]).focus();
                }

                // Check if all digits filled
                checkAndSubmit();
            });

            input.addEventListener('keydown', function(e) {
                // Backspace: move to previous
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    document.getElementById(digits[index - 1]).focus();
                }
                
                // Arrow left: move to previous
                if (e.key === 'ArrowLeft' && index > 0) {
                    document.getElementById(digits[index - 1]).focus();
                }
                
                // Arrow right: move to next
                if (e.key === 'ArrowRight' && index < digits.length - 1) {
                    document.getElementById(digits[index + 1]).focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                const pastedDigits = pastedText.replace(/[^0-9]/g, '').slice(0, 4);

                // Fill boxes with pasted digits
                for (let i = 0; i < pastedDigits.length && i < 4; i++) {
                    document.getElementById(digits[i]).value = pastedDigits[i];
                    document.getElementById(digits[i]).classList.add('filled');
                }

                // Focus on last filled or next empty
                if (pastedDigits.length === 4) {
                    checkAndSubmit();
                } else {
                    document.getElementById(digits[pastedDigits.length]).focus();
                }
            });
        });

        function checkAndSubmit() {
            const allFilled = digits.every(id => document.getElementById(id).value);

            if (allFilled) {
                // Combine all digits
                const token = digits.map(id => document.getElementById(id).value).join('');
                tokenInput.value = token;

                // Mark boxes as complete
                digits.forEach(id => {
                    document.getElementById(id).classList.add('complete');
                });

                // Show loading
                loading.classList.add('active');

                // Auto-submit after short delay
                setTimeout(() => {
                    form.submit();
                }, 600);
            }
        }

        // Focus first input on load
        document.getElementById('digit1').focus();
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/first_aid/token_verification.blade.php ENDPATH**/ ?>