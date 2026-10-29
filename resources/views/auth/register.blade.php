<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="robots" content="noarchive" />
    <title>Register - HSC-OHS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ─── Variables (matching incident form) ─── */
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
        }

        .register-container {
            max-width: 520px;
            width: 100%;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border-top: 5px solid var(--brand);
            padding: 1.5rem 1.75rem 1.25rem;
        }

        /* ─── Header ─── */
        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .hsc-logo {
            height: 48px;
            margin-bottom: 0.6rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .form-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
        }

        .form-header p {
            font-size: 0.8rem;
            color: var(--slate-500);
            margin-top: 0.1rem;
        }

        /* ─── Alerts ─── */
        .alert {
            padding: 0.7rem 0.9rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            border-left: 4px solid var(--red-500);
            background: #fef2f2;
            color: #991b1b;
            font-size: 0.82rem;
            animation: slideDown 0.3s ease;
        }

        .alert i {
            flex-shrink: 0;
            margin-top: 2px;
            color: var(--red-500);
            font-size: 0.95rem;
        }

        .alert.success {
            border-left-color: var(--green-500);
            background: #f0fdf4;
            color: #166534;
        }

        .alert.success i {
            color: var(--green-500);
        }

        .alert ul {
            margin: 0;
            padding-left: 1.25rem;
            list-style-type: disc;
        }

        .alert ul li+li {
            margin-top: 0.1rem;
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

        /* ─── Form Groups ─── */
        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.25rem;
        }

        .form-group label .required {
            color: var(--red-500);
            font-weight: 700;
        }

        .form-group label .helper {
            font-weight: 400;
            color: var(--slate-400);
            font-size: 0.65rem;
            margin-left: 0.3rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.82rem;
            color: var(--slate-900);
            background: var(--white);
            transition: all var(--transition);
            -webkit-appearance: none;
            appearance: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: var(--shadow-focus);
        }

        .form-group input:hover,
        .form-group select:hover {
            border-color: var(--slate-300);
        }

        .form-group input::placeholder {
            color: var(--slate-400);
        }

        .form-group select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23334155'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1.1rem;
            padding-right: 2.2rem;
        }

        /* ─── Disabled State ─── */
        .form-group input:disabled,
        .form-group select:disabled {
            background-color: var(--slate-100);
            border-color: var(--slate-200);
            color: var(--slate-400);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .form-group select:disabled {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* ─── Consent Checkbox ─── */
        .consent-group {
            margin-bottom: 1.25rem;
        }

        .consent-label {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
            user-select: none;
        }

        .consent-label input[type="checkbox"] {
            width: 20px;
            height: 20px;
            min-width: 20px;
            margin-top: 0.05rem;
            cursor: pointer;
            accent-color: var(--brand);
            border: 2px solid var(--slate-300);
            border-radius: 4px;
            transition: all var(--transition);
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: var(--white);
            position: relative;
        }

        .consent-label input[type="checkbox"]:hover {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }

        .consent-label input[type="checkbox"]:checked {
            background-color: var(--brand);
            border-color: var(--brand);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3E%3Cpath d='M13.78 4.22a.75.75 0 010 1.06l-7.25 7.25a.75.75 0 11-1.06-1.06L12.72 4.22a.75.75 0 011.06 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 14px;
        }

        .consent-label input[type="checkbox"]:focus {
            outline: none;
            box-shadow: 0 0 0 3px var(--brand-glow);
        }

        .consent-text {
            font-size: 0.8rem;
            color: var(--slate-600);
            line-height: 1.5;
            flex: 1;
        }

        /* ─── Success Modal ─── */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .success-modal {
            background: white;
            border-radius: var(--radius);
            max-width: 450px;
            width: 90%;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s backwards;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon i {
            font-size: 40px;
            color: white;
        }

        .success-modal h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--slate-900);
            margin-bottom: 0.75rem;
        }

        .success-modal p {
            font-size: 0.95rem;
            color: var(--slate-600);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .modal-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--brand);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all var(--transition);
        }

        .modal-btn:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        /* ─── Button ─── */
        .btn-primary {
            width: 100%;
            padding: 0.6rem;
            background: var(--brand);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.2);
            margin-top: 0.25rem;
            position: relative;
        }

        .btn-primary:hover:not(:disabled)::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--red-500);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
            animation: tooltipFade 0.2s ease;
        }

        .btn-primary:hover:not(:disabled)::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: var(--red-500);
            z-index: 1000;
            pointer-events: none;
            animation: tooltipFade 0.2s ease;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        .btn-primary:active:not(:disabled) {
            transform: scale(0.98);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-primary:disabled:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--red-500);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
            animation: tooltipFade 0.2s ease;
        }

        .btn-primary:disabled:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: var(--red-500);
            z-index: 1000;
            pointer-events: none;
            animation: tooltipFade 0.2s ease;
        }

        @keyframes tooltipFade {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(4px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
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

        /* ─── Footer ─── */
        .form-footer {
            margin-top: 1.25rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--slate-500);
        }

        .form-footer a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
            transition: color var(--transition);
        }

        .form-footer a:hover {
            color: var(--brand-dark);
            text-decoration: underline;
        }

        /* ─── Password toggle (optional) ─── */
        .input-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 0.7rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--slate-400);
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0.25rem;
            transition: color var(--transition);
        }

        .toggle-password:hover {
            color: var(--slate-600);
        }

        .toggle-password:focus {
            outline: none;
        }

        /* ─── Validation states ─── */
        .form-group input.is-valid,
        .form-group select.is-valid {
            border-color: var(--green-500);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2322c55e'%3E%3Cpath fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1rem;
            padding-right: 2.2rem;
        }

        .form-group input.is-invalid,
        .form-group select.is-invalid {
            border-color: var(--red-500);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23ef4444'%3E%3Cpath fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1rem;
            padding-right: 2.2rem;
        }

        .error-text {
            color: var(--red-500);
            font-size: 0.68rem;
            margin-top: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.2rem;
            animation: slideDown 0.2s ease;
        }

        .error-text i {
            font-size: 0.6rem;
        }

        /* ─── Form Sections ─── */
        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 1rem;
        }

        .form-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-500);
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            font-size: 0.85rem;
            color: var(--brand);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }

        .form-row .form-group {
            margin-bottom: 0;
        }

        /* ─── Responsive ─── */
        @media (max-width: 480px) {
            .register-container {
                padding: 1.25rem 1rem;
            }
            .form-header h1 {
                font-size: 1.2rem;
            }
            .hsc-logo {
                height: 40px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* ─── Organization Selection Modal ─── */
        .org-selection-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .org-selection-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .org-selection-modal {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.25rem 1rem;
            max-width: 280px;
            width: 85%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-align: center;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .org-selection-header h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 0.3rem;
            letter-spacing: -0.02em;
        }

        .org-selection-header p {
            font-size: 0.7rem;
            color: var(--slate-500);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .org-selection-options {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .org-selection-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0.85rem;
            border: 2px solid var(--slate-200);
            border-radius: 6px;
            background: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            font-family: inherit;
            text-align: left;
        }

        .org-selection-btn:hover {
            border-color: var(--brand);
            box-shadow: 0 0 0 2px rgba(29, 69, 51, 0.1);
            transform: translateY(-1px);
        }

        .org-selection-btn:active {
            transform: translateY(0);
        }

        .org-selection-logo {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            margin-right: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .org-selection-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .org-selection-text {
            flex: 1;
        }

        .org-selection-name {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--slate-900);
            line-height: 1.2;
        }

        .org-selection-desc {
            font-size: 0.65rem;
            color: var(--slate-500);
            line-height: 1.2;
        }

        .org-selection-arrow {
            font-size: 0.8rem;
            color: var(--slate-400);
            flex-shrink: 0;
            margin-left: 0.4rem;
            transition: all 0.3s ease;
        }

        .org-selection-btn:hover .org-selection-arrow {
            color: var(--brand);
            transform: translateX(2px);
        }

        /* ─── Hide organization section for HSC ─── */
        #organization-section.hidden {
            display: none;
        }

        /* ─── Select2 Custom Styling ─── */
        .select2-container--default .select2-selection--single {
            height: auto !important;
            padding: 0.5rem 0.75rem !important;
            border: 1.5px solid var(--slate-200) !important;
            border-radius: var(--radius-sm) !important;
            background: var(--white) !important;
            transition: all var(--transition) !important;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: var(--slate-300) !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--brand) !important;
            box-shadow: var(--shadow-focus) !important;
            outline: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            color: var(--slate-900) !important;
            line-height: 1.5 !important;
            font-size: 0.82rem !important;
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

        /* Dropdown styles */
        .select2-dropdown {
            border: 1.5px solid var(--brand) !important;
            border-radius: var(--radius-sm) !important;
            box-shadow: var(--shadow-card) !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--slate-200) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.82rem !important;
            transition: all var(--transition) !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--brand) !important;
            outline: none !important;
            box-shadow: var(--shadow-focus) !important;
        }

        .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.82rem !important;
            color: var(--slate-900) !important;
        }

        .select2-results__option--highlighted {
            background-color: var(--brand-light) !important;
            color: var(--brand) !important;
        }

        .select2-results__option--selected {
            background-color: var(--brand) !important;
            color: var(--white) !important;
        }

        .select2-container--disabled .select2-selection--single {
            background-color: var(--slate-100) !important;
            border-color: var(--slate-200) !important;
            opacity: 0.6 !important;
            cursor: not-allowed !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
</head>
<body>

    <div class="register-container">

        <!-- ─── Header ─── -->
        <div class="form-header">
            <img src="/images/hsc-logo.png" alt="HSC-OHS Logo" class="hsc-logo" />
            <h1>Create Account</h1><br/><hr/>
        </div>

        <!-- ─── Errors ─── -->
        @if ($errors->any())
            <div class="alert" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
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

        <!-- ─── Organization Selection Modal ─── -->
        <div class="org-selection-overlay active" id="orgSelectionOverlay">
            <div class="org-selection-modal">
                <div class="org-selection-header">
                    <h2>Select Organization</h2>
                    <p>Choose your organization to continue</p>
                </div>

                <div class="org-selection-options">
                    <!-- Health Service Commission -->
                    <button type="button" class="org-selection-btn hsc" onclick="selectOrganization('hsc')">
                        <div class="org-selection-logo">
                            <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC" />
                        </div>
                        <div class="org-selection-text">
                            <div class="org-selection-name">Health Service Commission</div>
                        </div>
                        <div class="org-selection-arrow">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </button>

                    <!-- Ministry of Health -->
                    <button type="button" class="org-selection-btn moh" onclick="selectOrganization('moh')">
                        <div class="org-selection-logo">
                            <img src="{{ asset('images/mohcc-logo.png') }}" alt="MOH" />
                        </div>
                        <div class="org-selection-text">
                            <div class="org-selection-name">Ministry of Health and Child Care</div>
                        </div>
                        <div class="org-selection-arrow">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- ─── Form ─── -->
        <form method="POST" action="{{ route('register.store') }}" novalidate id="registerForm">
            @csrf
            
            <!-- Hidden field for organization type -->
            <input type="hidden" name="organization_type" value="" id="organization-type">

            <!-- ─── Personal Details Section ─── -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fa-regular fa-user"></i> Personal Details
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required"></span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. John" autofocus autocomplete="off" />
                        @error('first_name')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required"></span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Doe" autocomplete="off" />
                        @error('last_name')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row full">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required"></span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" autocomplete="off" />
                        @error('email')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row full">
                    <div class="form-group">
                        <label for="phone_number">Phone Number <span class="required"></span></label>
                        <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required placeholder="+263 77 123 4567" autocomplete="off" />
                        @error('phone_number')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="department_id">Department <span class="required"></span></label>
                        <select id="department_id" name="department_id" required>
                            <option value="">— Select Department —</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation_id">Designation <span class="required"></span></label>
                        <select id="designation_id" name="designation_id" required>
                            <option value="">— Select Designation —</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}" data-name="{{ $designation->name }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('designation_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ─── Organization Details Section (Hidden for HSC) ─── -->
            <div class="form-section" id="organization-section">
                <div class="form-section-title">
                    <i class="fa-regular fa-briefcase"></i> Organization Details
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="organization_id">Organization</label>
                        <select id="organization_id" name="organization_id">
                            <option value="">— Select Organization —</option>
                            @foreach($organizations as $organization)
                                <option value="{{ $organization->id }}" data-requires-location="{{ $organization->requires_location ? 'true' : 'false' }}" title="requires_location: {{ $organization->requires_location }}">
                                    {{ $organization->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="facility_type_id">Facility Type</label>
                        <select id="facility_type_id" name="facility_type_id" onchange="updateByFacilityType()">
                            <option value="">— Select Facility Type —</option>
                            @foreach($facilityTypes as $facilityType)
                                <option value="{{ $facilityType->id }}" {{ old('facility_type_id') == $facilityType->id ? 'selected' : '' }}>
                                    {{ $facilityType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('facility_type_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="province_id">Province</label>
                        <select id="province_id" name="province_id" onchange="updateDistricts()">
                            <option value="">— Select Province —</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="district_id">District</label>
                        <select id="district_id" name="district_id" onchange="updateFacilities()">
                            <option value="">— Select District —</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" data-province="{{ $district->province_id }}" data-facility-type="{{ $district->facility_type_id ?? 'all' }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="facility_id">Facility/Hospital Name</label>
                        <select id="facility_id" name="facility_id">
                            <option value="">— Select Facility/Hospital —</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" data-district="{{ $facility->district_id }}" data-facility-type="{{ $facility->facility_type_id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('facility_id')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ─── Security Section ─── -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fa-regular fa-lock"></i> Security
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span class="required"></span> <span class="helper">min. 8 characters</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" required placeholder="••••••••" minlength="8" autocomplete="off" />
                            <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="required"></span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" autocomplete="off" />
                            <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="password_confirmation">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ─── Consent Section ─── -->
            <div class="form-section">
                <div class="form-group consent-group">
                    <label class="consent-label">
                        <input type="checkbox" id="consent_checkbox" name="consent" required />
                        <span class="consent-text">
                            I agree to supply my personal and professional information for the purpose of accessing the Health Service Commission System and confirm that the information provided is accurate and complete.
                        </span>
                    </label>
                    @error('consent')
                        <div class="error-text"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn" disabled>
                <span class="spinner" aria-hidden="true"></span>
                <span class="btn-text"><i class="fa-regular fa-user-plus" aria-hidden="true"></i> Create Account</span>
            </button>
        </form>

        <!-- ─── Footer ─── -->
        <div class="form-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
            <a href="{{ route('welcome') }}"> | Home</a>
        </div>
          
    </div>

    <!-- ─── Success Modal ─── -->
    @if (session('success'))
    <div class="modal-overlay active" id="successModal">
        <div class="success-modal">
            <div class="success-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2>Registration Successful!</h2>
            <p>{{ session('success') }}</p>
            <button type="button" class="modal-btn" onclick="closeSuccessModal()">
                <i class="fa-solid fa-arrow-right"></i> Go to Login
            </button>
        </div>
    </div>
    @endif

    <!-- Select2 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- ─── CORRECTED JAVASCRIPT ─── -->
    <script>
        // ─── Success Modal Close Function ───
        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.remove('active');
                // Redirect to login page after closing
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeSuccessModal();
                    }
                });
            }
        });

        // Aggressive cache prevention - run before anything else
        (function() {
            'use strict';

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
                const form = document.querySelector('#registerForm');
                if (form) {
                    form.reset();
                }
            });

            // Clear data when leaving the page
            window.addEventListener('pagehide', function() {
                const form = document.querySelector('#registerForm');
                if (form) {
                    form.reset();
                    // Clear visible input values only, NOT hidden CSRF tokens
                    const visibleInputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"], textarea, select');
                    visibleInputs.forEach(input => {
                        input.value = '';
                    });
                }
                // Clear all storage
                sessionStorage.clear();
                localStorage.removeItem('registerFormData');
            });

            // Prevent form autofill from browser cache
            window.addEventListener('load', function() {
                const form = document.querySelector('#registerForm');
                if (form) {
                    // Disable browser autocomplete on the entire form
                    form.setAttribute('autocomplete', 'off');
                    
                    // Reset all fields explicitly
                    form.reset();
                    
                    // Clear visible input values only, NOT hidden CSRF tokens
                    const visibleInputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"], textarea, select');
                    visibleInputs.forEach(input => {
                        input.value = '';
                        input.setAttribute('autocomplete', 'off');
                    });
                }
            });
        })();

        // ── Toggle Password Visibility ──
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            });
        });

        // ─── SIMPLIFIED: All Organization Details fields enabled and optional ───
        window.updateLocationFields = function() {
            const facilitySelect = document.getElementById('facility_id');
            const facilityTypeSelect = document.getElementById('facility_type_id');
            const provinceSelect = document.getElementById('province_id');
            const districtSelect = document.getElementById('district_id');

            // Enable all fields and make them optional
            const enableField = (field) => {
                if (!field) return;
                field.disabled = false;
                field.removeAttribute('required');
            };

            enableField(provinceSelect);
            enableField(districtSelect);
            enableField(facilityTypeSelect);
            enableField(facilitySelect);
        };

        // ── Update Districts - just enable, don't filter ──
        window.updateDistricts = function() {
            const districtSelect = document.getElementById('district_id');
            // Simply enable the district dropdown without filtering
            districtSelect.disabled = false;
        };

        // ── Update Facilities based on District & Facility Type ──
        window.updateFacilities = function() {
            const districtSelect = document.getElementById('district_id');
            const facilityTypeSelect = document.getElementById('facility_type_id');
            const provinceSelect = document.getElementById('province_id');
            const facilitySelect = document.getElementById('facility_id');
            
            const selectedDistrictId = districtSelect.value;
            const selectedFacilityTypeId = facilityTypeSelect.value;
            const selectedProvinceId = provinceSelect.value;

            console.log('updateFacilities called:', {
                selectedDistrictId,
                selectedFacilityTypeId,
                selectedProvinceId
            });

            // Store all original facility options (before clearing)
            const allFacilityOptions = Array.from(facilitySelect.options).map(opt => ({
                value: opt.value,
                text: opt.text,
                district: opt.getAttribute('data-district'),
                facilityType: opt.getAttribute('data-facility-type')
            }));

            // Clear all options except placeholder
            facilitySelect.innerHTML = '<option value="">— Select Facility/Hospital —</option>';

            // Filter and add back matching facilities
            let matchCount = 0;
            allFacilityOptions.forEach(optData => {
                if (optData.value === '') return; // Skip placeholder

                let shouldShow = false;

                // District-level facility: must match selected district
                if (selectedDistrictId) {
                    const districtMatches = optData.district === selectedDistrictId;
                    const typeMatches = !selectedFacilityTypeId || optData.facilityType === selectedFacilityTypeId;
                    shouldShow = districtMatches && typeMatches;
                }
                // Provincial facility: match by facility type only (no district filter)
                else if (selectedFacilityTypeId && !selectedDistrictId) {
                    const typeMatches = optData.facilityType === selectedFacilityTypeId;
                    shouldShow = typeMatches;
                }

                if (shouldShow) {
                    matchCount++;
                    const newOption = document.createElement('option');
                    newOption.value = optData.value;
                    newOption.text = optData.text;
                    newOption.setAttribute('data-district', optData.district);
                    newOption.setAttribute('data-facility-type', optData.facilityType);
                    facilitySelect.appendChild(newOption);
                }
            });

            console.log('Facilities added:', matchCount);

            // Reset facility selection
            facilitySelect.value = '';
            facilitySelect.disabled = matchCount === 0;

            // Update Select2 if initialized
            if (typeof $ !== 'undefined' && $.fn.select2) {
                try {
                    $('#facility_id').select2('destroy');
                    $('#facility_id').select2({
                        placeholder: '— Select Facility/Hospital —',
                        allowClear: true,
                        width: '100%',
                        minimumInputLength: 0,
                        matcher: function(params, data) {
                            if ($.trim(params.term) === '') return data;
                            if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                                return data;
                            }
                            return null;
                        }
                    });
                } catch(e) {
                    console.error('Select2 error for facility:', e);
                }
            }
        };

        // ── Facility Type change (re‑filter facilities) ──
        window.updateByFacilityType = function() {
            const facilityTypeSelect = document.getElementById('facility_type_id');
            const provinceSelect = document.getElementById('province_id');
            const districtSelect = document.getElementById('district_id');
            const facilitySelect = document.getElementById('facility_id');
            
            const selectedFacilityTypeOption = facilityTypeSelect.options[facilityTypeSelect.selectedIndex];
            const facilityTypeName = selectedFacilityTypeOption.text || '';
            
            // District-level facilities: lock Province & District, allow Facility selection
            const districtFacilities = ['Primary Clinic', 'Poly-Clinic', 'Rural Health Centre', 'District Hospital'];
            const isDistrictFacility = districtFacilities.some(type => facilityTypeName.includes(type));
            
            // Provincial/General facilities: allow Province selection, lock District, allow Facility selection
            const provincialFacilities = ['General Hospital', 'Provincial Hospital', 'Central Hospital'];
            const isProvincialFacility = provincialFacilities.some(type => facilityTypeName.includes(type));
            
            // Update Province field
            if (isDistrictFacility) {
                provinceSelect.disabled = true;
                provinceSelect.removeAttribute('required');
                provinceSelect.value = '';
            } else if (isProvincialFacility) {
                provinceSelect.disabled = false;
                provinceSelect.setAttribute('required', 'required');
            } else {
                provinceSelect.disabled = false;
                provinceSelect.setAttribute('required', 'required');
            }
            
            // Update District field - ALWAYS disabled for provincial facilities (including Central Hospital)
            if (isDistrictFacility || isProvincialFacility) {
                districtSelect.disabled = true;
                districtSelect.removeAttribute('required');
                districtSelect.value = '';
            } else {
                districtSelect.disabled = false;
                districtSelect.setAttribute('required', 'required');
            }
            
            // Always allow Facility selection
            facilitySelect.disabled = false;
            
            // Update visual indicators
            updateLockIndicators();
            updateFacilities();
        };

        // ── Update lock indicators on labels ──
        window.updateLockIndicators = function() {
            const provinceLabel = document.querySelector('label[for="province_id"]');
            const districtLabel = document.querySelector('label[for="district_id"]');
            const facilityLabel = document.querySelector('label[for="facility_id"]');
            
            const provinceSelect = document.getElementById('province_id');
            const districtSelect = document.getElementById('district_id');
            const facilitySelect = document.getElementById('facility_id');
            
            // Remove existing lock indicators
            document.querySelectorAll('.lock-indicator').forEach(el => el.remove());
            
            // Add lock icons for disabled fields
            if (provinceSelect.disabled && provinceLabel) {
                const lockIcon = document.createElement('span');
                lockIcon.className = 'lock-indicator';
                lockIcon.innerHTML = ' <i class="fa-solid fa-lock" style="color: var(--slate-400); font-size: 0.7rem; margin-left: 0.3rem;"></i>';
                provinceLabel.appendChild(lockIcon);
            }
            
            if (districtSelect.disabled && districtLabel) {
                const lockIcon = document.createElement('span');
                lockIcon.className = 'lock-indicator';
                lockIcon.innerHTML = ' <i class="fa-solid fa-lock" style="color: var(--slate-400); font-size: 0.7rem; margin-left: 0.3rem;"></i>';
                districtLabel.appendChild(lockIcon);
            }
        };

        // ── Designation dependencies (PMD / DMO / HR) ──
        window.updateDesignationDependencies = function() {
            const designationSelect = document.getElementById('designation_id');
            const facilityTypeSelect = document.getElementById('facility_type_id');
            
            // Get the selected designation name
            const designationName = designationSelect.options[designationSelect.selectedIndex].textContent.trim().toLowerCase();
            
            // If Chief Medical Officer, auto-select Central Hospital facility type
            if (designationName.includes('chief medical officer')) {
                // Find the "Central Hospital" option
                let centralHospitalFound = false;
                Array.from(facilityTypeSelect.options).forEach(option => {
                    if (option.text.includes('Central Hospital')) {
                        facilityTypeSelect.value = option.value;
                        centralHospitalFound = true;
                    }
                });
                
                if (centralHospitalFound) {
                    // Trigger the facility type change to update facilities
                    updateByFacilityType();
                }
            }
            
            // Call updateLocationFields which handles all designation types
            updateLocationFields();
        };
        
        // ── Inline validation ──
        const form = document.getElementById('registerForm');
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(field => {
            field.addEventListener('blur', function() { validateField(this); });
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') || this.classList.contains('is-valid')) {
                    validateField(this);
                }
            });
            field.addEventListener('change', function() {
                if (this.tagName === 'SELECT') validateField(this);
            });
        });

        function validateField(field) {
            const isValid = field.checkValidity();
            field.classList.remove('is-valid', 'is-invalid');
            if (field.value.trim() !== '') {
                field.classList.add(isValid ? 'is-valid' : 'is-invalid');
            }
        }

        // ── Consent Checkbox Handler ──
        const consentCheckbox = document.getElementById('consent_checkbox');
        const submitBtn = document.getElementById('submitBtn');

        function updateSubmitButtonState() {
            if (consentCheckbox.checked) {
                submitBtn.disabled = false;
                submitBtn.removeAttribute('data-tooltip');
                submitBtn.style.position = 'relative';
            } else {
                submitBtn.disabled = true;
                submitBtn.setAttribute('data-tooltip', 'Please agree to the consent before creating an account');
                submitBtn.style.position = 'relative';
            }
        }

        if (consentCheckbox) {
            consentCheckbox.addEventListener('change', updateSubmitButtonState);
            // Set initial state
            updateSubmitButtonState();
        }

        // Prevent form submission if consent not checked
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!consentCheckbox.checked) {
                    e.preventDefault();
                    alert('Please agree to the consent to create an account');
                    return false;
                }
            });
        }

        // ── Submit loading ──
        form.addEventListener('submit', function(e) {
            // Clear all storage immediately
            sessionStorage.clear();
            localStorage.removeItem('registerFormData');
            
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            
            // Clear visible form data only, NOT hidden CSRF token
            setTimeout(() => {
                const visibleInputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"], textarea, select');
                visibleInputs.forEach(input => {
                    input.value = '';
                });
            }, 100);
        });

        // ── DOM Ready ──
        document.addEventListener('DOMContentLoaded', function() {

            // Add event listeners for dropdowns
            document.getElementById('organization_id').addEventListener('change', updateLocationFields);
            document.getElementById('designation_id').addEventListener('change', updateDesignationDependencies);

            // Initialize Select2 on facility dropdown with search and filtering
            $('#facility_id').select2({
                placeholder: '— Select Facility/Hospital —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    // If there are no search terms, return the original option
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    // Disable options that don't match the district/facility type filters
                    if (data.element && data.element.disabled) {
                        return null;
                    }

                    // Do a case-insensitive search of the term in the option text
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }

                    return null;
                },
                templateResult: function(option) {
                    // Hide disabled options from the dropdown
                    if (option.disabled && option.id !== '') {
                        return null;
                    }
                    return option.text;
                }
            });

            // Initialize Select2 for Department and Designation dropdowns with search
            $('#department_id').select2({
                placeholder: '— Select Department —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            $('#designation_id').select2({
                placeholder: '— Select Designation —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            // Initialize Select2 for District dropdown (searchable)
            $('#district_id').select2({
                placeholder: '— Select District —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            // Initialize Select2 for Province dropdown (searchable)
            $('#province_id').select2({
                placeholder: '— Select Province —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            // Initialize Select2 for Organization dropdown (searchable)
            $('#organization_id').select2({
                placeholder: '— Select Organization —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            // Initialize Select2 for Facility Type dropdown (searchable)
            $('#facility_type_id').select2({
                placeholder: '— Select Facility Type —',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    if ((data.text || '').toLowerCase().indexOf((params.term || '').toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            // Trigger facility filtering on page load if values are pre-selected
            if ($('#district_id').val() || $('#facility_type_id').val()) {
                updateFacilities();
            }

            // Handle Select2 change events
            $('#department_id').on('select2:select select2:unselect', function() {
                updateLocationFields();
            });

            $('#designation_id').on('select2:select select2:unselect', function() {
                updateDesignationDependencies();
            });

            // If there's a success message, clear the form
            const successAlert = document.querySelector('.alert.success');
            if (successAlert) {
                form.reset();
                // Re-disable location fields
                updateLocationFields();
            }

            // Initial validation classes
            inputs.forEach(field => {
                if (field.value && field.value.trim() !== '') {
                    validateField(field);
                }
            });

            // Initialise location fields based on current organization selection
            updateLocationFields();

            // Set up change listeners for cascading
            document.getElementById('organization_id').addEventListener('change', function() {
                updateLocationFields();
            });

            document.getElementById('province_id').addEventListener('change', function() {
                updateDistricts();
                document.getElementById('facility_id').value = '';
                updateFacilities();
            });

            document.getElementById('district_id').addEventListener('change', function() {
                document.getElementById('facility_id').value = '';
                updateFacilities();
            });

            document.getElementById('facility_type_id').addEventListener('change', function() {
                updateFacilities();
            });

            // Also trigger on page load in case old values are present
            updateDistricts();
            if (document.getElementById('district_id').value) {
                updateFacilities();
            }
        });

        // ─── Organization Selection Handler ───
        window.selectOrganization = function(org) {
            const overlay = document.getElementById('orgSelectionOverlay');
            const orgTypeInput = document.getElementById('organization-type');
            const orgSection = document.getElementById('organization-section');
            
            // Set the organization type
            orgTypeInput.value = org;
            
            // Hide the selection overlay
            overlay.classList.remove('active');
            
            // Show/hide organization section based on selection
            if (org === 'hsc') {
                orgSection.classList.add('hidden');
            } else {
                orgSection.classList.remove('hidden');
            }
            
            // Clear any validation errors for hidden organization section when HSC is selected
            if (org === 'hsc') {
                // Clear values in organization-specific fields to avoid validation errors
                document.getElementById('organization_id').value = '';
                document.getElementById('facility_type_id').value = '';
                document.getElementById('province_id').value = '';
                document.getElementById('district_id').value = '';
                document.getElementById('facility_id').value = '';
            }
            
            // Focus on first form field
            document.getElementById('first_name').focus();
        };

        // Show organization selection modal on page load
        document.addEventListener('DOMContentLoaded', function() {
            const orgTypeValue = document.getElementById('organization-type').value;
            // If organization type is default or not properly selected, show modal
            if (!orgTypeValue || orgTypeValue === '') {
                const overlay = document.getElementById('orgSelectionOverlay');
                if (overlay) {
                    overlay.classList.add('active');
                }
            }
        });

        // Prevent form submission without organization selection
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const orgType = document.getElementById('organization-type').value;
            if (!orgType || (orgType !== 'hsc' && orgType !== 'moh')) {
                e.preventDefault();
                alert('Please select your organization first');
                return false;
            }
        });
    </script>
</body>
</html>