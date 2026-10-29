<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>HSC-OHS | Witness Statement Submission</title>
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
            --green-500: #22c55e;
            --amber-500: #f59e0b;
            --blue-500: #3b82f6;
            --radius: 12px;
            --radius-sm: 6px;
            --shadow-card: 0 8px 30px -8px rgba(0, 0, 0, 0.08), 0 2px 12px -4px rgba(0, 0, 0, 0.02);
            --shadow-focus: 0 0 0 3px rgba(29, 69, 51, 0.25);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 1.5rem 1rem;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .container { max-width: 900px; width: 100%; margin: 2rem auto 0; }

        .form-header {
            background: transparent;
            border-radius: 0;
            padding: 1.5rem 2rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 0;
        }

        .form-header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
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
            margin-bottom: 0.2rem;
        }

        .form-header p {
            font-size: 0.8rem;
            color: var(--slate-500);
        }

        .form-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.75rem 2rem;
            box-shadow: var(--shadow-card);
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
            border-left: 4px solid;
            font-size: 0.82rem;
        }

        .alert-info {
            border-left-color: var(--blue-500);
            background: #eff6ff;
            color: #1e40af;
        }

        .alert-error {
            border-left-color: var(--red-500);
            background: #fef2f2;
            color: #991b1b;
        }

        .alert i { margin-top: 2px; }

        .summary-section {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-sm);
            padding: 1.25rem;
            margin-bottom: 1.75rem;
        }

        .summary-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--brand);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .summary-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-900);
        }

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
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--brand);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i { font-size: 1.1rem; width: 1.2rem; text-align: center; }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .form-group label .required {
            color: var(--red-500);
            font-weight: 700;
        }

        .form-group textarea {
            padding: 0.9rem;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.85rem;
            color: var(--slate-900);
            background: var(--white);
            transition: all var(--transition);
            width: 100%;
            min-height: 200px;
            resize: vertical;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: var(--shadow-focus);
        }

        .char-count {
            font-size: 0.7rem;
            color: var(--slate-500);
            margin-top: 0.35rem;
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

        .btn-primary:hover:not(:disabled) {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--slate-100);
            justify-content: center;
            flex-wrap: wrap;
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
            .summary-grid { grid-template-columns: 1fr; }
            .btn { width: 100%; justify-content: center; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <header class="form-header">
                <div class="form-header-content">
                    <div class="form-header-icon">
                        <img src="<?php echo e(asset('images/hsc-logo.png')); ?>" alt="HSC-OHS Logo">
                    </div>
                    <div>
                        <h1>Witness Statement</h1>
                        <p>Please provide your account of the incident</p>
                    </div>
                </div>
            </header>

            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                <div>
                    <strong>Witness Statement Form</strong>
                    <p style="margin-top: 0.3rem;">Please provide a detailed account of what you witnessed. Your statement is important for the investigation.</p>
                </div>
            </div>

            <!-- Incident Summary -->
            <div class="summary-section">
                <div class="summary-title">
                    <i class="fa-solid fa-file-lines"></i>
                    Incident Information
                </div>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span class="summary-label">Worker Name</span>
                        <span class="summary-value"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Incident Date</span>
                        <span class="summary-value"><?php echo e($incident->incident_date->format('d M Y')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Witness Statement Form -->
            <form method="POST" action="<?php echo e(route('incidents.witness_submit', ['token' => $witnessToken->token])); ?>" id="witnessForm">
                <?php echo csrf_field(); ?>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Your Statement
                    </div>

                    <div class="form-group">
                        <label for="statement">
                            Witness Statement <span class="required">*</span>
                        </label>
                        <textarea 
                            id="statement" 
                            name="statement" 
                            placeholder="Please describe what you witnessed, including details about the incident, injuries, and any actions taken..."
                            required
                            onchange="updateCharCount()"
                            oninput="updateCharCount()"
                        ><?php echo e(old('statement')); ?></textarea>
                        <div class="char-count">
                            <span id="charCount">0</span> / 5000 characters
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-check"></i> Submit Statement
                    </button>
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert alert-error" style="margin-top: 1rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>
                            <strong>Validation Error</strong>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </form>

            <footer class="form-footer">
                <span>&copy; <?php echo e(date('Y')); ?> HSC-OHS. All rights reserved.</span>
            </footer>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function updateCharCount() {
            const statement = document.getElementById('statement');
            const charCount = document.getElementById('charCount');
            charCount.textContent = statement.value.length;
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateCharCount();
        });

        document.getElementById('witnessForm').addEventListener('submit', function(e) {
            const statement = document.getElementById('statement').value.trim();
            if (!statement) {
                e.preventDefault();
                alert('Please provide a witness statement.');
                return false;
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\witness_statement.blade.php ENDPATH**/ ?>