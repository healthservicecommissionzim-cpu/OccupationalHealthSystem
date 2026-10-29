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
    <title>HSC-OHS | Report Incident</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Mobile-First Responsive Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/responsive-mobile-first.css') }}" />
    <style>
        /* ─── Design Variables ─── */
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
            --amber-500: #f59e0b;
            --radius: 12px;
            --radius-sm: 6px;
            --shadow-card: 0 8px 30px -8px rgba(0, 0, 0, 0.08), 0 2px 12px -4px rgba(0, 0, 0, 0.02);
            --shadow-focus: 0 0 0 3px var(--brand-glow);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        
        @media (min-width: 768px) {
            body {
                padding: 1.5rem 1rem;
            }
        }

        .container { 
            max-width: 640px; 
            width: 100%; 
            margin: 2rem auto 0; 
        }
        
        /* Let responsive stylesheet handle base sizing */
        @media (max-width: 640px) {
            .container {
                margin: 1rem auto 0;
            }
        }

        .form-header {
            background: transparent;
            border-radius: 0;
            padding: 1.5rem 2rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            box-shadow: none;
            border-bottom: none;
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .form-header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 0;
        }

        .form-header-icon {
            width: 48px;
            height: 48px;
            background: var(--brand-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .form-header-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .form-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
            margin-bottom: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .form-header p {
            font-size: 0.8rem;
            color: var(--slate-500);
            display: none;
        }

        .back-home-link {
            text-decoration: none;
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            white-space: nowrap;
            padding: 0.55rem 1.4rem;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
            background: var(--brand);
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.2);
            border: none;
            flex-shrink: 0;
        }

        .back-home-link:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        @media (max-width: 640px) {
            .form-header {
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1rem;
                flex-wrap: nowrap;
            }

            .form-header-content {
                flex: 1;
                min-width: 0;
                gap: 0.5rem;
            }

            .form-header-icon {
                width: 32px;
                height: 32px;
            }

            .form-header h1 {
                font-size: 0.75rem;
                line-height: 1.2;
            }

            .back-home-link {
                padding: 0.35rem 0.6rem;
                font-size: 0.6rem;
                flex-shrink: 0;
            }
        }

        @media (max-width: 480px) {
            .form-header {
                padding: 0.6rem 0.75rem;
                gap: 0.4rem;
            }

            .form-header-icon {
                width: 28px;
                height: 28px;
            }

            .form-header h1 {
                font-size: 0.65rem;
            }

            .back-home-link {
                padding: 0.3rem 0.5rem;
                font-size: 0.55rem;
            }
        }

        .form-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.75rem 2rem;
            box-shadow: 0 8px 30px -8px rgba(0, 0, 0, 0.08), 0 2px 12px -4px rgba(0, 0, 0, 0.02);
            border-top: 5px solid var(--brand);
            margin-top: -1.5rem;
            position: relative;
            z-index: 1;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            border-left: 4px solid var(--red-500);
            background: #fef2f2;
            color: #991b1b;
            font-size: 0.82rem;
        }

        .alert i { color: var(--red-500); margin-top: 2px; }

        .alert ul {
            margin: 0.25rem 0 0 1.25rem;
            padding: 0;
            list-style: disc;
        }

        .alert ul li+li { margin-top: 0.1rem; }

        .alert-success {
            border-left-color: var(--green-500);
            background: #f0fdf4;
            color: #15803d;
        }

        .alert-success i { color: var(--green-500); }

        .alert-success .success-content {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .alert-success .success-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .alert-success .download-link {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.8rem;
            background: rgba(29, 69, 51, 0.1);
            border-radius: var(--radius-sm);
            transition: all var(--transition);
        }

        .alert-success .download-link:hover {
            background: rgba(29, 69, 51, 0.2);
            transform: translateX(2px);
        }

        .alert-success .close-alert {
            margin-left: auto;
            cursor: pointer;
            color: #15803d;
            opacity: 0.7;
            transition: opacity var(--transition);
        }

        .alert-success .close-alert:hover {
            opacity: 1;
        }

        /* ─── Error Alert ─── */
        .alert-danger {
            border-left-color: var(--red-500);
            background: #fef2f2;
            color: #991b1b;
        }
        .alert-danger i { color: var(--red-500); }
        .alert-danger .close-alert {
            margin-left: auto;
            cursor: pointer;
            color: #991b1b;
            opacity: 0.7;
            transition: opacity var(--transition);
        }
        .alert-danger .close-alert:hover { opacity: 1; }

        .form-section {
            margin-bottom: 1.75rem;
            padding-bottom: 1.75rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--brand);
            margin-bottom: 1.25rem;
        }

        .section-title i { font-size: 1.1rem; width: 1.2rem; text-align: center; }

        .section-title .badge {
            margin-left: auto;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: var(--brand-light);
            color: var(--brand);
            padding: 0.15rem 0.6rem;
            border-radius: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 640px) {
            .form-grid.half { grid-template-columns: 1fr 1fr; }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .form-group label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .form-group label .required {
            color: var(--red-500);
            font-weight: 700;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 0.55rem 0.85rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.85rem;
            color: var(--slate-900);
            background: var(--white);
            transition: all var(--transition);
            width: 100%;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: var(--shadow-focus);
        }

        .form-group input:disabled,
        .form-group select:disabled {
            background-color: var(--slate-100);
            color: var(--slate-400);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
            line-height: 1.5;
        }

        .form-group select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23334155'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }

        .form-group select:disabled {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        .form-group .error-text {
            color: var(--red-500);
            font-size: 0.7rem;
            margin-top: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-group .hint-text {
            font-size: 0.68rem;
            color: var(--slate-400);
            margin-top: 0.25rem;
        }

        .form-group .char-counter {
            font-size: 0.65rem;
            color: var(--slate-400);
            text-align: right;
            margin-top: 0.2rem;
        }

        .form-group .char-counter.warning { color: var(--amber-500); }
        .form-group .char-counter.danger { color: var(--red-500); }

        .form-group .lock-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.65rem;
            color: var(--amber-500);
            margin-top: 0.2rem;
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--slate-100);
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .form-actions .left,
        .form-actions .right {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.55rem 1.4rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--brand);
            color: var(--white);
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.2);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        .btn-secondary {
            background: var(--slate-100);
            color: var(--slate-700);
            border: 1px solid var(--slate-200);
        }

        .btn-secondary:hover {
            background: var(--slate-200);
        }

        .form-footer {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--slate-100);
            text-align: center;
            font-size: 0.7rem;
            color: var(--slate-400);
        }

        @media (max-width: 640px) {
            .form-header { padding: 1.25rem 1.5rem 1rem; }
            .form-card { padding: 1.25rem 1.5rem; }
            .form-actions { flex-direction: column-reverse; }
            .btn { width: 100%; justify-content: center; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ─── Select2 Custom Styling ─── */
        .select2-container--default .select2-selection--single {
            border: 1.5px solid var(--slate-300);
            height: auto !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: var(--radius-sm);
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: var(--slate-400);
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--brand) !important;
            box-shadow: var(--shadow-focus) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            color: var(--slate-900) !important;
            font-size: 0.875rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--slate-400) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.5rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--slate-700) transparent transparent transparent !important;
            border-width: 6px 5px 0 5px !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent var(--slate-700) transparent !important;
            border-width: 0 5px 6px 5px !important;
        }

        .select2-dropdown {
            border: 1.5px solid var(--brand) !important;
            border-radius: var(--radius-sm) !important;
            margin-top: 4px;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--slate-200) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.5rem 0.75rem;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--brand) !important;
            outline: none !important;
        }

        .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
        }

        .select2-results__option--highlighted {
            background-color: var(--brand-light) !important;
            color: var(--brand) !important;
        }

        .select2-results__option--selected {
            background-color: var(--brand) !important;
            color: var(--white) !important;
        }

        .select2-container {
            width: 100% !important;
        }

        /* ─── Toast Notifications ─── */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: var(--white);
            border-radius: var(--radius-sm);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-left: 4px solid var(--brand);
            animation: slideInRight 0.3s ease;
            pointer-events: auto;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--slate-900);
        }

        .toast.success {
            border-left-color: var(--green-500);
        }

        .toast.success i {
            color: var(--green-500);
        }

        .toast.error {
            border-left-color: var(--red-500);
        }

        .toast.error i {
            color: var(--red-500);
        }

        .toast.warning {
            border-left-color: var(--amber-500);
        }

        .toast.warning i {
            color: var(--amber-500);
        }

        .toast i {
            font-size: 1rem;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast.fade-out {
            animation: slideOutRight 0.3s ease;
        }

        /* ─── Loading Spinner ─── */
        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: var(--white);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn:disabled .spinner {
            margin-right: 0.25rem;
        }

        /* ─── Custom Checkbox with Visible Checkmark ─── */
        .custom-checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
            background: var(--brand-light);
            border: 2px solid var(--brand);
            border-radius: var(--radius-sm);
            transition: all var(--transition);
        }

        /* Hide default checkbox */
        .custom-checkbox-wrapper input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Create custom checkbox - only this is clickable */
        .custom-checkbox-wrapper .checkmark {
            width: 24px;
            height: 24px;
            background-color: var(--white);
            border: 2px solid var(--brand);
            border-radius: 4px;
            flex-shrink: 0;
            position: relative;
            transition: all var(--transition);
            cursor: pointer;
            display: block;
        }

        /* On hover - only checkbox */
        .custom-checkbox-wrapper .checkmark:hover {
            background-color: rgba(29, 69, 51, 0.05);
            border-color: var(--brand-dark);
        }

        /* When checked, change background */
        .custom-checkbox-wrapper input[type="checkbox"]:checked + .checkmark {
            background-color: var(--brand);
            border-color: var(--brand);
        }

        /* Create the checkmark/tick (hidden when not checked) */
        .custom-checkbox-wrapper .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .custom-checkbox-wrapper input[type="checkbox"]:checked + .checkmark:after {
            display: block;
        }

        /* Style the checkmark/tick */
        .custom-checkbox-wrapper .checkmark:after {
            left: 7px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox-wrapper .checkbox-label {
            flex: 1;
            margin: 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--brand);
            line-height: 1.5;
            cursor: default;
            user-select: none;
        }

        .custom-checkbox-wrapper .checkbox-label .required {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.7rem;
            color: var(--red-500);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <header class="form-header">
                <div class="form-header-content">
                    <div class="form-header-icon">
                        <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC-OHS Logo">
                    </div>
                    <h1>Report Occupational Incident</h1>
                </div>
                <a href="{{ route('welcome') }}" class="back-home-link">
                    Back to Home
                </a>
            </header>

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert" role="alert">
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

            {{-- Success flash --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert" id="successAlert">
                    <i class="fa-solid fa-circle-check"></i>
                    <div class="success-content">
                        <div>
                            <strong>Success!</strong>
                            <p>{{ session('success') }}</p>
                            @if(session('referenceNumber'))
                                <p style="font-size: 0.9rem; color: var(--slate-600); margin-top: 0.5rem;">
                                    <strong>Reference Number:</strong> {{ session('referenceNumber') }}
                                </p>
                            @endif
                            @if(session('first_aid_token'))
                                <div style="background: rgba(29, 69, 51, 0.08); padding: 0.75rem; border-radius: 6px; margin-top: 0.75rem; border-left: 3px solid var(--brand);">
                                    <p style="font-size: 0.85rem; color: var(--slate-600); margin: 0 0 0.5rem 0;">
                                        <strong>First Aid Code (give to nurse within 7 days):</strong>
                                    </p>
                                    <p style="font-size: 1.2rem; font-weight: 700; color: var(--brand); letter-spacing: 2px; text-align: center; font-family: 'Courier New', monospace; margin: 0;">
                                        {{ session('first_aid_token') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="success-actions">
                            <a href="{{ route('incidents.downloadPdf', ['referenceNumber' => session('referenceNumber')]) }}" class="download-link" download>
                                <i class="fa-solid fa-download"></i> Download Report
                            </a>
                            <a href="{{ route('welcome') }}" class="download-link" style="background: rgba(29, 69, 51, 0.05);">
                                <i class="fa-solid fa-home"></i> Back to Home
                            </a>
                        </div>
                    </div>
                    <i class="fa-solid fa-times close-alert" onclick="document.getElementById('successAlert').style.display='none';" style="cursor: pointer;"></i>
                </div>
            @endif

            {{-- Error flash --}}
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <div style="flex:1;">
                        <strong>Error!</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                    <i class="fa-solid fa-times close-alert" onclick="this.parentElement.style.display='none';"></i>
                </div>
            @endif

            <form method="POST" action="{{ route('incidents.store') }}" novalidate id="incidentForm" enctype="multipart/form-data">
                @csrf

                <!-- ─── SECTION 1: Injured Person Information ─── -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-user"></i>
                        Injured Person Information
                        <span class="badge">Step 1 of 4</span>
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name <span class="required"></span></label>
                        <input type="text" id="first_name" name="first_name" required placeholder="First name" value="{{ old('first_name') }}" autocomplete="off" />
                        @error('first_name')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="surname">Surname <span class="required"></span></label>
                        <input type="text" id="surname" name="surname" required placeholder="Surname" value="{{ old('surname') }}" autocomplete="off" />
                        @error('surname')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="designation">Designation <span class="required"></span></label>
                        <select id="designation" name="designation" required>
                            <option value="">— Select Designation —</option>
                            @foreach ($designations as $des)
                                <option value="{{ $des->id }}" {{ old('designation') == $des->id ? 'selected' : '' }}>{{ $des->name }}</option>
                            @endforeach
                        </select>
                        @error('designation')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="department">Department <span class="required"></span></label>
                        <select id="department" name="department" required>
                            <option value="">— Select Department —</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="id_number">ID Number <span class="required"></span></label>
                        <input type="text" id="id_number" name="id_number" required placeholder="e.g., 12-345678-Z67" value="{{ old('id_number') }}" autocomplete="off" />
                        @error('id_number')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="ec_number">EC Number <span class="required"></span></label>
                        <input type="text" id="ec_number" name="ec_number" required placeholder="Employee/EC Number" value="{{ old('ec_number') }}" autocomplete="off" />
                        @error('ec_number')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="home_address">Home Address <span class="required"></span></label>
                        <textarea id="home_address" name="home_address" required placeholder="Street address, suburb, city..." autocomplete="off">{{ old('home_address') }}</textarea>
                        @error('home_address')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number <span class="required"></span></label>
                        <input type="tel" id="phone_number" name="phone_number" required placeholder="e.g., +263 712 345 678" value="{{ old('phone_number') }}" autocomplete="off" />
                        @error('phone_number')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required"></span></label>
                        <input type="email" id="email" name="email" required placeholder="e.g., name@example.com" value="{{ old('email') }}" autocomplete="off" />
                        @error('email')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>
                </section>

                <!-- ─── SECTION 2: Incident Details ─── -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-clock"></i>
                        Incident Details
                        <span class="badge">Step 2 of 4</span>
                    </div>

                    <div class="form-grid half">
                        <div class="form-group">
                            <label for="incident_date">Date of Incident <span class="required"></span></label>
                            <input type="date" id="incident_date" name="incident_date" required value="{{ old('incident_date') }}" autocomplete="off" />
                            @error('incident_date')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="incident_time">Time of Incident <span class="required"></span></label>
                            <input type="time" id="incident_time" name="incident_time" required value="{{ old('incident_time') }}" autocomplete="off" />
                            @error('incident_time')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="incident_type">Incident Type <span class="required"></span></label>
                        <select id="incident_type" name="incident_type_id" required>
                            <option value="">— Select Incident Type —</option>
                            @foreach ($incidentTypes as $type)
                                <option value="{{ $type->id }}" {{ old('incident_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('incident_type_id')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="incident_description">Description of How the Incident Occurred <span class="required"></span><span class="helper" style="display:block;font-weight:400;color:var(--slate-400);font-size:0.65rem;margin-top:0.2rem;">Min. 20 characters</span></label>
                        <textarea id="incident_description" name="incident_description" required minlength="20" maxlength="2000" placeholder="Provide a detailed description of what happened, including the sequence of events..." autocomplete="off">{{ old('incident_description') }}</textarea>
                        <div class="hint-text">Be as specific as possible — include actions leading up to the incident, equipment involved, and any contributing factors.</div>
                        <div class="char-counter" id="desc-counter">0 / 2000</div>
                        @error('incident_description')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>
                </section>

                <!-- ─── SECTION 3: Location & Facility Information ─── -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-location-dot"></i>
                        Location &amp; Facility Information
                        <span class="badge">Step 3 of 4</span>
                    </div>

                    <div class="form-group">
                        <label for="facility_type">Facility Type <span class="required"></span></label>
                        <select id="facility_type" name="facility_type" required onchange="handleFacilityTypeChange()">
                            <option value="">— Select Facility Type —</option>
                        </select>
                        @error('facility_type')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="province">Province <span class="required"></span></label>
                        <select id="province" name="province" required onchange="updateDistricts()">
                            <option value="">— Select Province —</option>
                        </select>
                        @error('province')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="district">
                            District <span class="required"></span>
                            <span id="districtLockIndicator" class="lock-indicator" style="display:none;">
                                <i class="fa-solid fa-lock"></i> Locked
                            </span>
                        </label>
                        <select id="district" name="district" required onchange="updateFacilities()">
                            <option value="">— Select District —</option>
                        </select>
                        @error('district')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="facility">Health Facility <span class="required"></span></label>
                        <select id="facility" name="facility" required>
                            <option value="">— Select Facility —</option>
                        </select>
                        @error('facility')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                    </div>
                </section>

                <!-- ─── SECTION 4: Supervisor Information ─── -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-user-tie"></i>
                        Immediate Supervisor Information
                        <span class="badge">Step 4 of 5</span>
                    </div>

                    <div class="form-grid half">
                        <div class="form-group">
                            <label for="supervisor_designation">Supervisor Designation <span class="required"></span></label>
                            <select id="supervisor_designation" name="supervisor_designation" required>
                                <option value="">— Select Supervisor Designation —</option>
                            </select>
                            @error('supervisor_designation')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="supervisor_name">Supervisor Name <span class="required"></span></label>
                            <select id="supervisor_name" name="supervisor_user_id" required>
                                <option value="">— Select Supervisor —</option>
                            </select>
                            @error('supervisor_user_id')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <!-- ─── SECTION 5: Witness Information ─── -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-users"></i>
                        Witness Information
                        <span class="badge">Step 5 of 5</span>
                    </div>

                    <div style="background: var(--brand-light); border: 1.5px solid var(--brand); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1.25rem;">
                        <p style="font-size: 0.8rem; color: var(--brand); line-height: 1.6; margin: 0;">
                            <i class="fa-solid fa-info-circle"></i> <strong>Please provide details for witnesses who observed this incident.</strong> Witnesses will receive an SMS with a secure link to submit their statements.
                        </p>
                    </div>

                    <div id="witnessesContainer">
                        <!-- Witness 1 -->
                        <div class="witness-group" data-witness-index="1">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                <h4 style="font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin: 0;">
                                    <i class="fa-solid fa-user"></i> Witness 1
                                </h4>
                            </div>

                            <div class="form-grid half">
                                <div class="form-group">
                                    <label for="witness_1_name">Full Name <span class="required"></span></label>
                                    <input type="text" id="witness_1_name" name="witnesses[0][name]" placeholder="Enter witness full name" value="{{ old('witnesses.0.name') }}" autocomplete="off" required />
                                    @error('witnesses.0.name')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="witness_1_phone">Phone Number <span class="required"></span></label>
                                    <input type="tel" id="witness_1_phone" name="witnesses[0][phone]" placeholder="+263 712 345 678 or 0712 345 678" value="{{ old('witnesses.0.phone') }}" autocomplete="off" required />
                                    <div class="hint-text">Format: +263 or 07xx</div>
                                    @error('witnesses.0.phone')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Witness 2 -->
                        <div class="witness-group" data-witness-index="2" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200);">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                <h4 style="font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin: 0;">
                                    <i class="fa-solid fa-user"></i> Witness 2 <span style="font-weight: 400; color: var(--slate-400); font-size: 0.75rem;">(Optional)</span>
                                </h4>
                            </div>

                            <div class="form-grid half">
                                <div class="form-group">
                                    <label for="witness_2_name">Full Name</label>
                                    <input type="text" id="witness_2_name" name="witnesses[1][name]" placeholder="Enter witness full name" value="{{ old('witnesses.1.name') }}" autocomplete="off" />
                                    @error('witnesses.1.name')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="witness_2_phone">Phone Number</label>
                                    <input type="tel" id="witness_2_phone" name="witnesses[1][phone]" placeholder="+263 712 345 678 or 0712 345 678" value="{{ old('witnesses.1.phone') }}" autocomplete="off" />
                                    <div class="hint-text">Format: +263 or 07xx</div>
                                    @error('witnesses.1.phone')<div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="witnessCounter" style="margin-top: 1rem; padding: 0.75rem; background: var(--slate-50); border-radius: var(--radius-sm); text-align: center; font-size: 0.8rem; color: var(--slate-600);">
                        <i class="fa-solid fa-users"></i> <strong><span id="witnessCount">0</span> / 2</strong> witnesses added
                    </div>
                </section>

                <!-- ─── SECTION 6: Consent & Digital Signature ─── -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-shield-halved"></i>
                        Consent & Authorization
                        <span class="badge">Acknowledgement</span>
                    </div>

                    <div style="background: #f9fafb; border: 1.5px solid var(--slate-200); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.25rem;">
                        <p style="font-size: 0.8rem; color: var(--slate-600); line-height: 1.6; margin: 0;">
                            By submitting this incident report, I confirm that:
                        </p>
                        <ul style="font-size: 0.8rem; color: var(--slate-600); line-height: 1.6; margin: 0.75rem 0 0 1.25rem; padding: 0;">
                            <li>The information provided is true, accurate, and complete</li>
                            <li>I consent to the collection and processing of my personal information</li>
                            <li>This submission serves as my digital signature authorizing this report</li>
                        </ul>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <div class="custom-checkbox-wrapper">
                            <input type="checkbox" id="consent_all" name="consent_all" value="1" {{ old('consent_all') ? 'checked' : '' }} required />
                            <label for="consent_all" class="checkmark"></label>
                            <span class="checkbox-label">
                                I agree and authorize the submission of this incident report
                                <span class="required">Required</span>
                            </span>
                        </div>
                        @error('consent_all')<div class="error-text" style="margin-top: 0.5rem;"><i class="fa-solid fa-circle-exclamation"></i> You must consent to submit this report</div>@enderror
                    </div>
                </section>

                <!-- ─── Form Actions ─── -->
                <div class="form-actions">
                    <div class="left">
                        <a href="{{ route('welcome') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                    <div class="right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Submit Report
                        </button>
                    </div>
                </div>
            </form>

            <footer class="form-footer">
                <span>&copy; {{ date('Y') }} HSC-OHS. All rights reserved.</span>
            </footer>
        </div>
    </div>

    <script>
        // Aggressive cache prevention
        // Replace the URL in history to prevent back button from returning to this form
        if (window.history.replaceState) {
            window.history.replaceState({noBackButton: true}, null, window.location.href);
        }

        // Prevent browser cache from restoring form data
        window.addEventListener('pageshow', function(event) {
            // Force reload if page was loaded from bfcache (back/forward cache)
            if (event.persisted) {
                window.location.reload();
            }
            
            // Always reset form to ensure no data is shown
            const form = document.querySelector('#incidentForm');
            if (form) {
                form.reset();
            }
        });

        // Clear data when leaving the page
        window.addEventListener('pagehide', function() {
            const form = document.querySelector('#incidentForm');
            if (form) {
                form.reset();
                // Clear only visible input values (not hidden CSRF token)
                const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="date"], input[type="time"], textarea, select');
                inputs.forEach(input => {
                    input.value = '';
                });
            }
            // Clear all storage
            sessionStorage.clear();
            localStorage.removeItem('incidentFormData');
        });

        // Prevent form autofill from browser cache (but not if success message is showing)
        window.addEventListener('load', function() {
            // Check if success alert is displayed
            const successAlert = document.querySelector('#successAlert');
            
            // Only clear form if there's no success message
            if (!successAlert || successAlert.style.display === 'none') {
                const form = document.querySelector('#incidentForm');
                if (form) {
                    // Disable browser autocomplete on the entire form
                    form.setAttribute('autocomplete', 'off');
                    
                    // Reset all fields explicitly
                    form.reset();
                    
                    // Clear only visible input values (not hidden CSRF token)
                    const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="date"], input[type="time"], textarea, select');
                    inputs.forEach(input => {
                        input.value = '';
                        input.setAttribute('autocomplete', 'off');
                    });
                }
            }
        });

        // Facility types from database
        const facilityTypesData = {!! json_encode($facilityTypes->pluck('name', 'id')) !!};
        const provincesData = {!! json_encode($provinces->pluck('name', 'id')) !!};
        const districtsData = {!! json_encode($districts->mapWithKeys(function($d) { return [$d->id => ['name' => $d->name, 'province_id' => $d->province_id]]; })) !!};
        const facilitiesData = {!! json_encode($facilities->mapWithKeys(function($f) { return [$f->id => ['name' => $f->name, 'district_id' => $f->district_id, 'province_id' => $f->province_id, 'facility_type_id' => $f->facility_type_id]]; })) !!};
        
        // Supervisors mapping
        const supervisorsByDept = {!! $supervisorsByDeptJson !!};
        const supervisorsByDeptAndFacility = {!! $supervisorsByDeptAndFacilityJson !!};
        const supervisorsByDesignation = {!! json_encode($supervisorsByDesignation) !!};
        const departmentsData = {!! json_encode($departments->pluck('name', 'id')) !!};
        const designations = {!! json_encode($designations->pluck('name', 'id')) !!};

        // Central Hospital and Provincial Hospital IDs for freezing district
        const freezeFacilityTypes = ['Central Hospital', 'Provincial Hospital'];

        function handleFacilityTypeChange() {
            const facilityTypeSelect = document.getElementById('facility_type');
            const selectedId = facilityTypeSelect.value;
            const selectedType = facilityTypesData[selectedId] || '';
            const districtLockIndicator = document.getElementById('districtLockIndicator');
            const districtSelect = document.getElementById('district');
            const provinceSelect = document.getElementById('province');

            // Reset district lock indicator
            districtLockIndicator.style.display = 'none';
            districtSelect.disabled = false;

            if (selectedId && freezeFacilityTypes.includes(selectedType)) {
                // For Central and Provincial Hospitals, disable district selection
                districtSelect.disabled = true;
                districtLockIndicator.style.display = 'inline-flex';
                // If province is already selected, update facilities
                if (provinceSelect.value) {
                    updateFacilities();
                }
            } else {
                // For non-Central hospitals, clear facilities and require district selection
                document.getElementById('facility').innerHTML = '<option value="">— Select Facility —</option>';
            }
        }

        function updateDistricts() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const facilityTypeSelect = document.getElementById('facility_type');
            const selectedProvince = parseInt(provinceSelect.value);
            const selectedFacilityType = parseInt(facilityTypeSelect.value);
            const facilityTypeName = facilityTypesData[selectedFacilityType] || '';
            const isCentralHospital = freezeFacilityTypes.includes(facilityTypeName);

            districtSelect.innerHTML = '<option value="">— Select District —</option>';
            document.getElementById('facility').innerHTML = '<option value="">— Select Facility —</option>';

            if (selectedProvince) {
                // If not a Central/Provincial Hospital, populate districts
                if (!isCentralHospital) {
                    const districts = [];
                    Object.entries(districtsData).forEach(([districtId, district]) => {
                        if (parseInt(district.province_id) === selectedProvince) {
                            districts.push({ id: districtId, name: district.name });
                        }
                    });

                    // Sort alphabetically by name
                    districts.sort((a, b) => a.name.localeCompare(b.name));

                    districts.forEach(district => {
                        const opt = document.createElement('option');
                        opt.value = district.id;
                        opt.textContent = district.name;
                        districtSelect.appendChild(opt);
                    });
                } else {
                    // For Central/Provincial Hospitals, directly update facilities by province and facility type
                    if (selectedFacilityType) {
                        updateFacilities();
                    }
                }
            }
        }

        function updateFacilities() {
            const districtSelect = document.getElementById('district');
            const facilityTypeSelect = document.getElementById('facility_type');
            const facilitySelect = document.getElementById('facility');
            const provinceSelect = document.getElementById('province');
            const selectedDistrict = parseInt(districtSelect.value);
            const selectedProvince = parseInt(provinceSelect.value);
            const selectedFacilityType = parseInt(facilityTypeSelect.value);

            facilitySelect.innerHTML = '<option value="">— Select Facility —</option>';

            // Get the selected facility type name
            const facilityTypeName = facilityTypesData[selectedFacilityType] || '';
            const isCentralHospital = freezeFacilityTypes.includes(facilityTypeName);

            const facilities = [];
            Object.entries(facilitiesData).forEach(([id, facility]) => {
                // If Central Hospital or Provincial Hospital, filter by facility type and province only
                if (isCentralHospital) {
                    if (parseInt(facility.facility_type_id) === selectedFacilityType && 
                        parseInt(facility.province_id) === selectedProvince) {
                        facilities.push({id, name: facility.name});
                    }
                } 
                // Otherwise, filter by district
                else if (selectedDistrict && parseInt(facility.district_id) === selectedDistrict) {
                    facilities.push({id, name: facility.name});
                }
            });

            facilities.sort((a, b) => a.name.localeCompare(b.name));

            facilities.forEach(fac => {
                const opt = document.createElement('option');
                opt.value = fac.id;
                opt.textContent = fac.name;
                facilitySelect.appendChild(opt);
            });
        }

        function updateSupervisorDesignations() {
            const departmentSelect = document.getElementById('department');
            const facilitySelect = document.getElementById('facility');
            const supervisorSelect = document.getElementById('supervisor_designation');
            const supervisorNameSelect = document.getElementById('supervisor_name');
            const selectedDeptId = departmentSelect.value;
            const selectedFacilityId = facilitySelect.value;

            supervisorSelect.innerHTML = '<option value="">— Select Supervisor Designation —</option>';
            supervisorNameSelect.innerHTML = '<option value="">— Select Supervisor —</option>';

            // Debug: Log the data
            console.log('Selected Dept ID:', selectedDeptId, 'Facility ID:', selectedFacilityId);
            console.log('Available supervisors by dept+facility:', supervisorsByDeptAndFacility);
            console.log('Available supervisors by dept only:', supervisorsByDept);
            console.log('Designations lookup:', designations);

            if (selectedDeptId) {
                let designationIds = [];
                
                // If facility is selected, use combined dept+facility lookup
                if (selectedFacilityId) {
                    const compositeKey = selectedDeptId + '_' + selectedFacilityId;
                    designationIds = supervisorsByDeptAndFacility[compositeKey] || [];
                    console.log('Using dept+facility filter. Key:', compositeKey, 'Found:', designationIds);
                } else {
                    // If no facility selected yet, use department-only lookup as fallback
                    designationIds = supervisorsByDept[selectedDeptId] || supervisorsByDept[String(selectedDeptId)] || [];
                    console.log('Using dept-only filter. Found:', designationIds);
                }
                
                if (designationIds && Array.isArray(designationIds) && designationIds.length > 0) {
                    console.log('Adding', designationIds.length, 'designation(s)');
                    designationIds.forEach(designationId => {
                        if (designationId) { // Skip null IDs
                            const designationName = designations[designationId] || 'Unknown';
                            console.log(`Adding designation: ${designationId} = ${designationName}`);
                            const opt = document.createElement('option');
                            opt.value = designationId;
                            opt.textContent = designationName;
                            supervisorSelect.appendChild(opt);
                        }
                    });
                } else {
                    console.warn(`No supervisors found for department ID: ${selectedDeptId}, facility ID: ${selectedFacilityId}`);
                }
            }
        }

        function updateSupervisorNames() {
            const departmentSelect = document.getElementById('department');
            const facilitySelect = document.getElementById('facility');
            const supervisorDesignationSelect = document.getElementById('supervisor_designation');
            const supervisorNameSelect = document.getElementById('supervisor_name');
            const selectedDesignationId = supervisorDesignationSelect.value;
            const selectedDeptId = departmentSelect.value;
            const selectedFacilityId = facilitySelect.value;

            supervisorNameSelect.innerHTML = '<option value="">— Select Supervisor —</option>';

            console.log('updateSupervisorNames - Designation:', selectedDesignationId, 'Dept:', selectedDeptId, 'Facility:', selectedFacilityId);

            if (selectedDesignationId && supervisorsByDesignation[selectedDesignationId]) {
                const allSupervisors = supervisorsByDesignation[selectedDesignationId];
                console.log('All supervisors for designation:', allSupervisors);
                
                // Filter supervisors by department AND facility (if facility is selected)
                const filteredSupervisors = allSupervisors.filter(supervisor => {
                    const deptMatch = !selectedDeptId || String(supervisor.department_id) === String(selectedDeptId);
                    const facilityMatch = !selectedFacilityId || String(supervisor.facility_id) === String(selectedFacilityId);
                    return deptMatch && facilityMatch;
                });

                console.log('Filtered supervisors:', filteredSupervisors);

                filteredSupervisors.forEach(supervisor => {
                    const opt = document.createElement('option');
                    opt.value = supervisor.id;
                    opt.textContent = supervisor.name;
                    supervisorNameSelect.appendChild(opt);
                });
            }
        }

        // Initialize facility types on page load
        document.addEventListener('DOMContentLoaded', function() {
            const facilityTypeSelect = document.getElementById('facility_type');
            const provinceSelect = document.getElementById('province');
            const departmentSelect = document.getElementById('department');
            const textarea = document.getElementById('incident_description');
            const counter = document.getElementById('desc-counter');
            const form = document.getElementById('incidentForm');

            // Populate facility types
            Object.entries(facilityTypesData).forEach(([id, name]) => {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                facilityTypeSelect.appendChild(opt);
            });

            // Populate all provinces on page load
            Object.entries(provincesData).forEach(([id, name]) => {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                provinceSelect.appendChild(opt);
            });

            // Add change listeners
            facilityTypeSelect.addEventListener('change', handleFacilityTypeChange);
            document.getElementById('province').addEventListener('change', updateDistricts);
            document.getElementById('district').addEventListener('change', updateFacilities);
            document.getElementById('facility').addEventListener('change', updateSupervisorDesignations);
            // Note: Department change listener is now handled by Select2 in the $(document).ready block below

            // Add change listener to supervisor designation
            const supervisorDesignationSelect = document.getElementById('supervisor_designation');
            supervisorDesignationSelect.addEventListener('change', updateSupervisorNames);

            // Clear form on submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Clear all storage immediately
                    sessionStorage.clear();
                    localStorage.removeItem('incidentFormData');
                    
                    // Clear form data after a delay (after form submission completes)
                    setTimeout(() => {
                        form.reset();
                        const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="date"], input[type="time"], textarea, select');
                        inputs.forEach(input => {
                            input.value = '';
                        });
                    }, 100);
                });
            }

            // Character counter
            if (textarea && counter) {
                const max = 2000;
                function update() {
                    const len = textarea.value.length;
                    counter.textContent = len + ' / ' + max;
                    counter.classList.remove('warning', 'danger');
                    if (len > max * 0.85) counter.classList.add('warning');
                    if (len > max * 0.95) counter.classList.add('danger');
                }
                textarea.addEventListener('input', update);
                update();
            }

            // Restore old values if present
            const oldFacilityType = '{{ old("facility_type") }}';
            const oldProvince = '{{ old("province") }}';
            const oldDistrict = '{{ old("district") }}';
            const oldFacility = '{{ old("facility") }}';

            if (oldFacilityType) {
                facilityTypeSelect.value = oldFacilityType;
                handleFacilityTypeChange();
                if (oldProvince) {
                    document.getElementById('province').value = oldProvince;
                    updateDistricts();
                    if (oldDistrict) {
                        document.getElementById('district').value = oldDistrict;
                        updateFacilities();
                        if (oldFacility) {
                            document.getElementById('facility').value = oldFacility;
                        }
                    }
                }
            }

            // Witness validation and counter
            updateWitnessCounter();
            attachWitnessValidation();
        });

        // Witness counter update function
        function updateWitnessCounter() {
            const witness1Name = document.getElementById('witness_1_name')?.value.trim();
            const witness1Phone = document.getElementById('witness_1_phone')?.value.trim();
            const witness2Name = document.getElementById('witness_2_name')?.value.trim();
            const witness2Phone = document.getElementById('witness_2_phone')?.value.trim();
            
            let count = 0;
            
            // Count witness 1 if both fields are filled
            if (witness1Name && witness1Phone) {
                count++;
            }
            
            // Count witness 2 if both fields are filled
            if (witness2Name && witness2Phone) {
                count++;
            }
            
            const counterElement = document.getElementById('witnessCount');
            if (counterElement) {
                counterElement.textContent = count;
            }
        }

        // Witness field validation with inline error display
        function validateWitnessField(event) {
            const input = event.target;
            const value = input.value.trim();
            
            // Remove any existing error messages
            const existingError = input.parentElement.querySelector('.error-text.client-validation');
            if (existingError) {
                existingError.remove();
            }
            
            // Reset border color
            input.style.borderColor = 'var(--slate-200)';
            
            // Phone number validation
            if (input.type === 'tel' && value) {
                // Zimbabwe phone format: +263 or 07xx
                const phoneRegex = /^(\+263|0)[0-9]{9}$/;
                const cleanedPhone = value.replace(/\s+/g, '');
                
                if (!phoneRegex.test(cleanedPhone)) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-text client-validation';
                    errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Invalid phone format. Use +263 or 07xx format';
                    input.parentElement.appendChild(errorDiv);
                    input.style.borderColor = 'var(--red-500)';
                    return false;
                }
            }
            
            // Name validation
            if (input.type === 'text' && input.id.includes('witness') && input.id.includes('name') && value) {
                if (value.length < 3) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-text client-validation';
                    errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Name must be at least 3 characters';
                    input.parentElement.appendChild(errorDiv);
                    input.style.borderColor = 'var(--red-500)';
                    return false;
                }
            }
            
            updateWitnessCounter();
            return true;
        }

        // Real-time validation for witness fields
        function attachWitnessValidation() {
            const witness1Name = document.getElementById('witness_1_name');
            const witness1Phone = document.getElementById('witness_1_phone');
            const witness2Name = document.getElementById('witness_2_name');
            const witness2Phone = document.getElementById('witness_2_phone');
            
            const fields = [witness1Name, witness1Phone, witness2Name, witness2Phone];
            
            fields.forEach(field => {
                if (field) {
                    // Validate on blur
                    field.addEventListener('blur', validateWitnessField);
                    
                    // Also validate on input for real-time feedback
                    field.addEventListener('input', function() {
                        // Clear error on input
                        const existingError = this.parentElement.querySelector('.error-text.client-validation');
                        if (existingError) {
                            existingError.remove();
                            this.style.borderColor = 'var(--slate-200)';
                        }
                        updateWitnessCounter();
                    });
                }
            });
        }

        // Toast notification system
        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toastContainer') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            container.appendChild(toast);
            
            // Auto-remove after duration
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => toast.remove(), 300);
            }, duration);
            
            return toast;
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
            return container;
        }

        // Form validation before submit
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('incidentForm');
            
            if (form) {
                form.addEventListener('submit', function(event) {
                    const witness1Name = document.getElementById('witness_1_name')?.value.trim();
                    const witness1Phone = document.getElementById('witness_1_phone')?.value.trim();
                    const witness2Name = document.getElementById('witness_2_name')?.value.trim();
                    const witness2Phone = document.getElementById('witness_2_phone')?.value.trim();
                    
                    // Validate at least one complete witness
                    if (!witness1Name || !witness1Phone) {
                        event.preventDefault();
                        showToast('At least one witness is required. Please provide Witness 1 details.', 'error');
                        document.getElementById('witness_1_name').focus();
                        return false;
                    }
                    
                    // Validate witness 2 if partially filled
                    if ((witness2Name && !witness2Phone) || (!witness2Name && witness2Phone)) {
                        event.preventDefault();
                        showToast('Witness 2 is incomplete. Please provide both name and phone, or leave both empty.', 'error');
                        if (!witness2Name) {
                            document.getElementById('witness_2_name').focus();
                        } else {
                            document.getElementById('witness_2_phone').focus();
                        }
                        return false;
                    }
                    
                    // Validate phone formats
                    const phoneRegex = /^(\+263|0)[0-9]{9}$/;
                    
                    if (witness1Phone) {
                        const cleaned1 = witness1Phone.replace(/\s+/g, '');
                        if (!phoneRegex.test(cleaned1)) {
                            event.preventDefault();
                            showToast('Witness 1 phone number format is invalid. Use +263 or 07xx format.', 'error');
                            document.getElementById('witness_1_phone').focus();
                            return false;
                        }
                    }
                    
                    if (witness2Phone) {
                        const cleaned2 = witness2Phone.replace(/\s+/g, '');
                        if (!phoneRegex.test(cleaned2)) {
                            event.preventDefault();
                            showToast('Witness 2 phone number format is invalid. Use +263 or 07xx format.', 'error');
                            document.getElementById('witness_2_phone').focus();
                            return false;
                        }
                    }
                    
                    // All validation passed - show loading state
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner"></span> Submitting...';
                    });
                    
                    showToast('Submitting incident report with witness details...', 'success');
                });
            }
        });
    </script>
    
    <!-- Select2 JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Initialize Select2 for Department and Designation dropdowns
        $(document).ready(function() {
            // Custom matcher function for case-insensitive search
            var customMatcher = function(params, data) {
                if ($.trim(params.term) === '') return data;
                if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                    return data;
                }
                return null;
            };

            $('#department').select2({
                placeholder: '— Select Department —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#designation').select2({
                placeholder: '— Select Designation —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#incident_type').select2({
                placeholder: '— Select Incident Type —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher,
                language: {
                    noResults: function() {
                        return 'No incident types found';
                    }
                }
            });

            $('#facility_type').select2({
                placeholder: '— Select Facility Type —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#province').select2({
                placeholder: '— Select Province —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#district').select2({
                placeholder: '— Select District —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#facility').select2({
                placeholder: '— Select Facility —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#supervisor_designation').select2({
                placeholder: '— Select Supervisor Designation —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            $('#supervisor_name').select2({
                placeholder: '— Select Supervisor —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: customMatcher
            });

            // Trigger updateSupervisorDesignations when department Select2 changes
            $('#department').on('select2:select select2:unselect', function() {
                console.log('Select2 change detected on department');
                updateSupervisorDesignations();
            });

            // Also handle the native change event as fallback
            $('#department').on('change', function() {
                console.log('Native change detected on department');
                updateSupervisorDesignations();
            });

            // Handle Select2 changes for facility type, province, and district
            $('#facility_type').on('select2:select select2:unselect', function() {
                handleFacilityTypeChange();
            });

            $('#province').on('select2:select select2:unselect', function() {
                updateDistricts();
            });

            $('#district').on('select2:select select2:unselect', function() {
                updateFacilities();
            });

            $('#facility').on('select2:select select2:unselect', function() {
                updateSupervisorDesignations();
            });
        });
    </script>
</body>
</html>