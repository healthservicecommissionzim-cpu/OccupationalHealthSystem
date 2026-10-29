<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IT Support Ticket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #079b6d;
            --brand-deep: #067c58;
            --brand-soft: #e9f8f3;
            --ink: #101a31;
            --ink-soft: #4e596c;
            --muted: #718096;
            --line: #e1e7ee;
            --surface: #ffffff;
            --canvas: #f5f7fa;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            
            --shadow: 0 8px 25px rgba(16,26,49,.06);
            --radius: 12px;
            --radius-sm: 8px;
            --radius-md: 10px;
            --radius-lg: 14px;
            
            --ease: 0.2s ease;
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
            font-family: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            background: var(--canvas);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--ink);
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 12px;
        }

        .header-left {
            display: flex;
            gap: 12px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--brand-soft);
            color: var(--brand);
            display: grid;
            place-items: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .header-left h3 {
            font-size: 20px;
            font-weight: 750;
            color: var(--ink);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .header-left p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            color: var(--ink-soft);
            font-weight: 650;
            font-size: 11px;
            text-decoration: none;
            transition: all var(--ease);
            cursor: pointer;
            min-height: 40px;
            white-space: nowrap;
        }

        .header-btn:hover {
            background: var(--brand);
            color: white;
            border-color: var(--brand);
            transform: translateY(-1px);
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 24px;
            transition: box-shadow var(--ease);
        }

        .progress-wrapper {
            margin-bottom: 24px;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            font-weight: 750;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .progress-labels span.active {
            color: var(--brand);
        }

        .progress-bar {
            height: 4px;
            background: var(--line);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar .fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--brand), var(--brand-deep));
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group label .required {
            color: var(--danger);
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-family: inherit;
            font-weight: 500;
            background: var(--surface);
            transition: all var(--ease);
            color: var(--ink);
            min-height: 40px;
            line-height: 1.5;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1);
        }

        .form-control.error {
            border-color: var(--danger);
            background: #fef2f2;
        }

        .form-control.error:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
            padding: 10px 12px;
        }

        .char-counter {
            text-align: right;
            font-size: 11px;
            color: var(--muted);
            margin-top: 6px;
            font-weight: 600;
        }

        .char-counter.limit {
            color: var(--warning);
        }

        .char-counter.danger {
            color: var(--danger);
        }

        .field-error {
            display: none;
            font-size: 11px;
            color: var(--danger);
            margin-top: 6px;
            font-weight: 600;
        }

        .field-error.show {
            display: block;
        }

        .form-group select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23718096' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
            padding-right: 36px;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 650;
            cursor: pointer;
            transition: all var(--ease);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: inherit;
            min-height: 40px;
            flex: 1;
            text-decoration: none;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            color: white;
            box-shadow: 0 4px 16px rgba(7, 155, 109, 0.25);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(7, 155, 109, 0.35);
        }

        .btn-secondary {
            background: var(--line);
            color: var(--ink-soft);
            border: 1px solid var(--line);
        }

        .btn-secondary:hover:not(:disabled) {
            background: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .btn.loading .spinner {
            display: inline-block;
        }

        .btn.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .toast {
            position: fixed;
            top: 16px;
            right: 16px;
            padding: 14px 16px;
            border-radius: 10px;
            background: var(--surface);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 13px;
            transform: translateY(-120%);
            transition: transform var(--ease);
            z-index: 999;
            max-width: 400px;
            border-left: 4px solid transparent;
        }

        .toast.show {
            transform: translateY(0);
        }

        .toast.success {
            border-left-color: var(--success);
        }

        .toast.error {
            border-left-color: var(--danger);
        }

        .toast i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .toast.success i {
            color: var(--success);
        }

        .toast.error i {
            color: var(--danger);
        }

        @media (max-width: 640px) {
            body {
                padding: 16px;
            }

            .card {
                padding: 18px;
            }

            .header {
                flex-direction: column;
                gap: 12px;
            }

            .header-right {
                width: 100%;
                justify-content: flex-start;
            }

            .header-btn {
                flex: 1;
                min-width: 120px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .toast {
                top: 12px;
                right: 12px;
                left: 12px;
                max-width: none;
            }
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <div class="header-left">
                <div class="header-icon"><i class="fa-solid fa-headset"></i></div>
                <div>
                    <h3>IT Support Ticket</h3>
                    <p>Report an issue or request assistance</p>
                </div>
            </div>
            <div class="header-right">
                <a href="<?php echo e(route('support.my_tickets')); ?>" class="header-btn" title="View my support tickets">
                    <i class="fa-solid fa-list"></i> My Tickets
                </a>
                <a href="javascript:history.back()" class="header-btn" title="Go back to previous page">
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
</html><?php /**PATH D:\OccupationalHealth System\resources\views\support\create_ticket.blade.php ENDPATH**/ ?>