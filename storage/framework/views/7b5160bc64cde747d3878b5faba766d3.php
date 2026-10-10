<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IT Support Ticket</title>
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
            
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-weight-light: 300;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;
            --font-weight-extrabold: 800;
            
            --radius: 12px;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 8px 32px rgba(29, 69, 51, 0.12);
            --shadow-lg: 0 16px 48px rgba(29, 69, 51, 0.16);
            --shadow-focus: 0 0 0 3px var(--brand-glow);
            
            --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-family);
            font-size: 1rem;
            line-height: 1.6;
            background: linear-gradient(145deg, var(--slate-50) 0%, var(--slate-100) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-md);
            color: var(--slate-900);
        }

        .container {
            max-width: 550px;
            width: 100%;
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--spacing-lg);
            gap: var(--spacing-sm);
        }

        .header-right {
            display: flex;
            gap: var(--spacing-sm);
            align-items: center;
        }

        .header-left h3 {
            font-size: 1.25rem;
            font-weight: var(--font-weight-extrabold);
            color: var(--slate-900);
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .header-left h3 i {
            color: var(--brand);
            background: rgba(29, 69, 51, 0.12);
            padding: 0.4rem;
            border-radius: 14px;
            font-size: 1.4rem;
        }

        .header-left p {
            font-size: 0.875rem;
            color: var(--slate-500);
            margin-top: 0.1rem;
            font-weight: var(--font-weight-medium);
        }

        .header-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.9rem;
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 40px;
            color: var(--slate-800);
            font-weight: var(--font-weight-semibold);
            font-size: 0.75rem;
            text-decoration: none;
            transition: all var(--transition-base);
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
            min-height: 44px;
            padding: 0.5rem 0.9rem;
            display: inline-flex;
            align-items: center;
        }

        .header-back:hover {
            background: var(--brand);
            color: var(--white);
            border-color: var(--brand);
            transform: translateX(-2px);
            box-shadow: 0 8px 20px rgba(29, 69, 51, 0.15);
        }

        .header-back:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .header-back i {
            transition: transform var(--transition-base);
        }
        .header-back:hover i {
            transform: translateX(-3px);
        }

        .card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: 1.5rem;
            transition: box-shadow var(--transition-base);
        }

        .progress-wrapper {
            margin-bottom: var(--spacing-lg);
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
            font-weight: var(--font-weight-semibold);
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.2rem;
        }

        .progress-labels span.active {
            color: var(--brand);
        }

        .progress-bar {
            height: 4px;
            background: var(--slate-200);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar .fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--brand), #2d6b4f);
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: var(--font-weight-semibold);
            color: var(--slate-800);
            margin-bottom: 0.5rem;
        }

        .form-group label .required {
            color: var(--red-500);
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.4rem;
            border: 2px solid var(--slate-200);
            border-radius: var(--radius);
            font-size: 0.95rem;
            font-family: var(--font-family);
            font-weight: var(--font-weight-normal);
            background: #fafbfc;
            transition: all var(--transition-base);
            color: var(--slate-900);
            min-height: 44px;
            line-height: 1.5;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand);
            background: var(--white);
            box-shadow: var(--shadow-focus);
        }

        .form-control.error {
            border-color: var(--red-500);
            background: #fef2f2;
        }

        .form-control.error:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .form-control::placeholder {
            color: var(--slate-400);
            font-weight: var(--font-weight-normal);
        }

        .form-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color var(--transition-base);
            margin-top: 0.75rem;
        }

        .form-group:focus-within .form-icon {
            color: var(--brand);
        }

        textarea.form-control {
            padding-left: 1rem;
            resize: vertical;
            min-height: 120px;
            padding-top: 0.75rem;
        }

        textarea.form-control ~ .form-icon {
            display: none;
        }

        .char-counter {
            text-align: right;
            font-size: 0.75rem;
            color: var(--slate-400);
            margin-top: 0.25rem;
            font-weight: var(--font-weight-medium);
        }

        .char-counter.limit {
            color: #f59e0b;
        }
        .char-counter.danger {
            color: var(--red-500);
        }

        .field-error {
            display: none;
            font-size: 0.75rem;
            color: var(--red-500);
            margin-top: 0.25rem;
            font-weight: var(--font-weight-medium);
        }

        .field-error.show {
            display: block;
        }

        .form-group select.form-control {
            padding-left: 2.4rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--slate-200);
        }

        .btn {
            padding: 0.75rem 1.2rem;
            border: none;
            border-radius: var(--radius);
            font-size: 0.95rem;
            font-weight: var(--font-weight-semibold);
            cursor: pointer;
            transition: all var(--transition-base);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: var(--font-family);
            min-height: 44px;
            width: 100%;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), #2d6b4f);
            color: var(--white);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.25);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(29, 69, 51, 0.35);
        }

        .btn-primary:focus {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .btn-primary .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: var(--white);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .btn-primary.loading .spinner {
            display: inline-block;
        }

        .btn-primary.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-secondary {
            background: var(--slate-100);
            color: var(--slate-600);
            border: 2px solid var(--slate-200);
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--slate-200);
            transform: translateY(-2px);
        }

        .btn-secondary:focus {
            outline: 2px solid var(--slate-600);
            outline-offset: 2px;
        }

        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            left: 1rem;
            padding: 1rem;
            border-radius: 14px;
            background: var(--white);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: var(--font-weight-medium);
            font-size: 0.95rem;
            transform: translateY(-120%);
            transition: transform var(--transition-base);
            z-index: 999;
            max-width: 100%;
            border-left: 5px solid transparent;
        }

        .toast.show {
            transform: translateY(0);
        }

        .toast.success {
            border-left-color: var(--green-500);
        }
        .toast.error {
            border-left-color: var(--red-500);
        }

        .toast i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .toast.success i {
            color: var(--green-500);
        }
        .toast.error i {
            color: var(--red-500);
        }

        /* Mobile first: Single column layout */
        @media (max-width: 500px) {
            body {
                padding: var(--spacing-md);
            }

            .container {
                width: 100%;
            }

            .card {
                padding: 1.25rem;
                border-radius: 20px;
            }

            .header {
                flex-direction: column;
                align-items: stretch;
                gap: var(--spacing-sm);
                margin-bottom: var(--spacing-lg);
            }

            .header-left h3 {
                font-size: 1.25rem;
            }

            .header-left h3 i {
                font-size: 1.2rem;
                padding: 0.3rem;
            }

            .header-right {
                align-self: flex-start;
                flex-wrap: wrap;
            }

            .header-back {
                font-size: 0.75rem;
                min-height: 44px;
            }

            .actions {
                flex-direction: column;
                gap: var(--spacing-md);
            }

            .btn {
                width: 100%;
                font-size: 0.9rem;
                min-height: 44px;
                padding: 0.65rem 1rem;
            }

            .form-group {
                margin-bottom: var(--spacing-lg);
            }

            .form-control {
                padding: 0.65rem 1rem 0.65rem 2.2rem;
                font-size: 0.9rem;
                min-height: 44px;
            }

            .form-icon {
                left: 0.65rem;
                font-size: 0.85rem;
            }

            textarea.form-control {
                min-height: 100px;
                padding-left: 1rem;
            }

            .toast {
                top: 1rem;
                right: var(--spacing-sm);
                left: var(--spacing-sm);
                max-width: none;
                font-size: 0.85rem;
            }
        }

        /* Tablet and up */
        @media (min-width: 501px) {
            body {
                padding: var(--spacing-lg);
                align-items: flex-start;
                padding-top: var(--spacing-xl);
            }

            .card {
                padding: 2rem;
                border-radius: 24px;
            }

            .header {
                margin-bottom: var(--spacing-xl);
            }

            .header-left h3 {
                font-size: 1.5rem;
            }

            .form-group {
                margin-bottom: var(--spacing-lg);
            }

            .form-control {
                padding: 0.75rem 1rem 0.75rem 2.4rem;
                font-size: 0.95rem;
                min-height: 44px;
            }

            .actions {
                flex-direction: row;
                gap: var(--spacing-md);
            }

            .btn {
                flex: 1;
                width: auto;
            }

            .toast {
                top: 1.5rem;
                right: 1.5rem;
                left: auto;
                max-width: 400px;
            }
        }

        /* Desktop */
        @media (min-width: 769px) {
            body {
                align-items: center;
                justify-content: center;
                padding: var(--spacing-xl);
            }

            .card {
                padding: 2rem;
            }

            .form-control {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <div class="header-left">
                <h3>
                    <i class="fa-solid fa-headset"></i>
                    IT Support Ticket
                </h3>
                <p>Report an issue or request assistance</p>
            </div>
            <div class="header-right">
                <a href="<?php echo e(route('support.my_tickets')); ?>" class="header-back" title="View my support tickets">
                    <i class="fa-solid fa-list"></i> My Tickets
                </a>
                <a href="javascript:history.back()" class="header-back" title="Go back to previous page">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card" id="mainCard">

            <div class="progress-wrapper">
                <div class="progress-labels">
                    <span class="active" id="stepLabel1">Details</span>
                    <span id="stepLabel2">Submit</span>
                    <span id="stepLabel3">Done</span>
                </div>
                <div class="progress-bar">
                    <div class="fill" id="progressFill" style="width:0%;"></div>
                </div>
            </div>

            <form id="ticketForm" method="POST" action="<?php echo e(route('support.store_ticket')); ?>" novalidate>
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="title">Issue Title <span class="required">*</span></label>
                    <i class="fa-regular fa-pen-to-square form-icon"></i>
                    <input type="text" id="title" name="title" class="form-control" required placeholder="e.g. VPN connection drops" autofocus aria-describedby="titleError">
                    <div class="field-error" id="titleError" role="alert">Please enter a title (at least 3 characters).</div>
                </div>

                <div class="form-group">
                    <label for="category">Category <span class="required">*</span></label>
                    <i class="fa-regular fa-folder form-icon"></i>
                    <select id="category" name="category" class="form-control" required aria-describedby="categoryError">
                        <option value="">— Select Category —</option>
                        <option value="system_issue">System Issue</option>
                        <option value="feature_request">Feature Request</option>
                        <option value="bug_report">Bug Report</option>
                        <option value="account_issue">Account Issue</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="field-error" id="categoryError" role="alert">Please select a category.</div>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" class="form-control" required placeholder="Describe the issue in detail…" aria-describedby="descriptionError charCounter"></textarea>
                    <div class="char-counter" id="charCounter">0 / 2000</div>
                    <div class="field-error" id="descriptionError" role="alert">Please provide at least 10 characters.</div>
                </div>

                <div class="actions">
                    <a href="javascript:history.back()" class="btn btn-secondary" title="Cancel and go back">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" title="Submit support ticket">
                        <span class="spinner"></span>
                        <span class="btn-text">
                            <i class="fa-regular fa-paper-plane"></i> Submit
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast" id="toast" role="alert" aria-live="polite" aria-atomic="true">
        <i class="fa-regular fa-circle-check"></i>
        <span id="toastMessage">Success</span>
    </div>

    <script>
        (function() {
            'use strict';

            const form = document.getElementById('ticketForm');
            const submitBtn = document.getElementById('submitBtn');
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMessage');

            const title = document.getElementById('title');
            const category = document.getElementById('category');
            const description = document.getElementById('description');
            const charCounter = document.getElementById('charCounter');
            const mainCard = document.getElementById('mainCard');

            const fill = document.getElementById('progressFill');
            const stepLabels = [
                document.getElementById('stepLabel1'),
                document.getElementById('stepLabel2'),
                document.getElementById('stepLabel3')
            ];

            // Handle mobile keyboard - ensure focused inputs are not hidden
            const handleInputFocus = (input) => {
                setTimeout(() => {
                    // Check if input is in viewport
                    const rect = input.getBoundingClientRect();
                    if (rect.bottom > window.innerHeight) {
                        // Scroll to center the input in viewport
                        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 100);
            };

            title.addEventListener('focus', function() {
                handleInputFocus(this);
            });

            category.addEventListener('focus', function() {
                handleInputFocus(this);
            });

            description.addEventListener('focus', function() {
                handleInputFocus(this);
            });

            function updateProgress() {
                const hasTitle = title.value.trim().length >= 3;
                const hasCat = category.value !== '';
                const hasDesc = description.value.trim().length >= 10;
                let progress = 0;
                if (hasTitle) progress += 33;
                if (hasCat) progress += 33;
                if (hasDesc) progress += 34;
                fill.style.width = progress + '%';

                stepLabels.forEach((el, idx) => {
                    el.classList.remove('active');
                    if (idx === 0 && hasTitle) el.classList.add('active');
                    else if (idx === 1 && hasTitle && hasCat) el.classList.add('active');
                    else if (idx === 2 && hasTitle && hasCat && hasDesc) el.classList.add('active');
                });
            }

            title.addEventListener('input', updateProgress);
            category.addEventListener('change', updateProgress);
            description.addEventListener('input', function() {
                updateProgress();
                const len = this.value.length;
                charCounter.textContent = len + ' / 2000';
                charCounter.classList.remove('limit', 'danger');
                if (len > 1800) charCounter.classList.add('limit');
                if (len > 1900) charCounter.classList.add('danger');
            });

            function showError(field, errorId) {
                field.classList.add('error');
                const errorMsg = document.getElementById(errorId);
                errorMsg.classList.add('show');
                // Announce error to screen readers
                errorMsg.setAttribute('aria-live', 'assertive');
            }

            function hideError(field, errorId) {
                field.classList.remove('error');
                const errorMsg = document.getElementById(errorId);
                errorMsg.classList.remove('show');
                errorMsg.removeAttribute('aria-live');
            }

            title.addEventListener('blur', function() {
                if (this.value.trim().length < 3) showError(this, 'titleError');
                else hideError(this, 'titleError');
            });

            category.addEventListener('blur', function() {
                if (this.value === '') showError(this, 'categoryError');
                else hideError(this, 'categoryError');
            });

            description.addEventListener('blur', function() {
                if (this.value.trim().length < 10) showError(this, 'descriptionError');
                else hideError(this, 'descriptionError');
            });

            function showToast(message, type = 'success') {
                toast.className = 'toast ' + type;
                toastMsg.textContent = message;
                
                // Ensure toast is not hidden by keyboard on mobile
                if (window.innerHeight < 600) {
                    toast.style.position = 'fixed';
                    toast.style.top = '1rem';
                }
                
                toast.classList.add('show');
                clearTimeout(toast._timer);
                toast._timer = setTimeout(() => toast.classList.remove('show'), 3500);
            }

            function scrollToFirstError() {
                const firstError = document.querySelector('.form-control.error');
                if (firstError) {
                    firstError.focus({ preventScroll: false });
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                let valid = true;

                // Clear previous errors first
                hideError(title, 'titleError');
                hideError(category, 'categoryError');
                hideError(description, 'descriptionError');

                if (title.value.trim().length < 3) {
                    showError(title, 'titleError');
                    valid = false;
                }

                if (category.value === '') {
                    showError(category, 'categoryError');
                    valid = false;
                }

                if (description.value.trim().length < 10) {
                    showError(description, 'descriptionError');
                    valid = false;
                }

                if (!valid) {
                    scrollToFirstError();
                    return;
                }

                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                const formData = new FormData(form);

                fetch("<?php echo e(route('support.store_ticket')); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                        if (data.success) {
                            showToast(data.message || 'Ticket submitted successfully!', 'success');
                            form.reset();
                            charCounter.textContent = '0 / 2000';
                            fill.style.width = '0%';
                            stepLabels.forEach(el => el.classList.remove('active'));
                            stepLabels[0].classList.add('active');
                            
                            // Clear any error states
                            hideError(title, 'titleError');
                            hideError(category, 'categoryError');
                            hideError(description, 'descriptionError');
                            
                            setTimeout(() => {
                                history.back();
                            }, 1800);
                        } else {
                            showToast(data.message || 'Something went wrong. Please try again.', 'error');
                        }
                    })
                    .catch((error) => {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                        console.error('Error:', error);
                        showToast('Network error. Please try again.', 'error');
                    });
            });

            updateProgress();
        })();
    </script>

</body>
</html><?php /**PATH D:\OccupationalHealth System\resources\views/support/create_ticket.blade.php ENDPATH**/ ?>