<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>HSC-OHS | Record First Aid Administered</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-900: #0f172a;
            --white: #ffffff;
            --red-500: #ef4444;
            --green-500: #22c55e;
            --radius-sm: 6px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 1.5rem 1rem;
        }

        .container { max-width: 640px; width: 100%; margin: 2rem auto 0; }

        .form-header {
            background: transparent;
            padding: 1.5rem 2rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 0;
        }

        .form-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
        }

        .back-home-link {
            text-decoration: none;
            color: var(--slate-500);
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
        }

        .back-home-link:hover {
            background: var(--slate-100);
            color: var(--brand);
        }

        .form-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.75rem 2rem;
            box-shadow: 0 8px 30px -8px rgba(0, 0, 0, 0.08);
            border-top: 5px solid var(--brand);
            margin-top: -1.5rem;
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

        .alert ul { margin: 0.25rem 0 0 1.25rem; list-style: disc; }

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

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.25rem;
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

        .form-group label .required { color: var(--red-500); font-weight: 700; }

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
            width: 100%;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.25);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.5;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23334155'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 640px) {
            .form-grid.half { grid-template-columns: 1fr 1fr; }
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

        .btn {
            padding: 0.55rem 1.4rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
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
            background: #0f2818;
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

        .info-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-sm);
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--slate-700);
            display: flex;
            gap: 0.75rem;
        }

        .info-box i {
            color: var(--brand);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .success-message {
            display: none;
            padding: 1rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            color: #1b5e20;
            text-align: center;
            font-weight: 600;
            animation: slideDown 0.3s ease;
        }

        .success-message.show {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .success-message i {
            color: var(--green-500);
            font-size: 1.2rem;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <header class="form-header">
                <div>
                    <h1>Record First Aid Administered</h1>
                </div>
                <a href="<?php echo e(route('welcome')); ?>" class="back-home-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </header>

            <?php if($errors->any()): ?>
                <div class="alert">
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

            <div class="success-message" id="successMessage">
                <i class="fa-solid fa-check-circle"></i>
                <span>First aid record submitted successfully</span>
            </div>

            <form method="POST" action="<?php echo e(route('first_aid.store')); ?>" novalidate id="firstAidForm">
                <?php echo csrf_field(); ?>

                <!-- Link to Incident Section -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-link"></i>
                        Linked Case
                    </div>

                    <div class="form-group">
                        <label>Linked to Incident:</label>
                        <input type="hidden" name="incident_id" value="<?php echo e($incident->id); ?>">
                        <input type="hidden" name="token" value="<?php echo e($token); ?>">
                        <div style="padding: 0.75rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; color: var(--brand); font-weight: 600;">
                            <i class="fa-solid fa-check-circle"></i> <?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?>

                        </div>
                    </div>
                </section>

                <!-- Nurse Information Section -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-user-nurse"></i>
                        Nurse Information
                    </div>

                    <div class="form-grid half">
                        <div class="form-group">
                            <label for="nurse_name">Full Name <span class="required">*</span></label>
                            <input type="text" id="nurse_name" name="nurse_name" required placeholder="Full name" value="<?php echo e(old('nurse_name')); ?>" />
                            <?php $__errorArgs = ['nurse_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="nurse_license_number">License Number / EC Number <span class="required">*</span></label>
                            <input type="text" id="nurse_license_number" name="nurse_license_number" required placeholder="Nursing license or EC number" value="<?php echo e(old('nurse_license_number')); ?>" />
                            <?php $__errorArgs = ['nurse_license_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </section>

                <!-- Injury Description Section -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-bandage"></i>
                        Injury Information
                    </div>

                    <div class="form-group">
                        <label for="injury_description">Description of the Injury <span class="required">*</span></label>
                        <textarea id="injury_description" name="injury_description" required minlength="10" maxlength="1000" placeholder="Describe the injury in detail..."><?php echo e(old('injury_description')); ?></textarea>
                        <div class="hint-text">Describe the nature, location, and severity of the injury</div>
                        <?php $__errorArgs = ['injury_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </section>

                <!-- First Aid Details Section -->
                <section class="form-section">
                    <div class="section-title">
                        <i class="fa-solid fa-first-aid"></i>
                        First Aid Details
                    </div>

                    <div class="form-group">
                        <label for="first_aid_description">Description of First Aid Administered <span class="required">*</span></label>
                        <textarea id="first_aid_description" name="first_aid_description" required minlength="20" maxlength="2000" placeholder="Describe the first aid treatment provided, procedures followed, and patient response..."><?php echo e(old('first_aid_description')); ?></textarea>
                        <div class="hint-text">Provide detailed information about what treatment was given and how the patient responded</div>
                        <?php $__errorArgs = ['first_aid_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-grid half">
                        <div class="form-group">
                            <label for="time_administered">Time First Aid Given <span class="required">*</span></label>
                            <input type="datetime-local" id="time_administered" name="time_administered" required value="<?php echo e(old('time_administered')); ?>" />
                            <?php $__errorArgs = ['time_administered'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="severity_level">Injury Severity <span class="required">*</span></label>
                            <select id="severity_level" name="severity_level" required>
                                <option value="">-- Select --</option>
                                <option value="minor" <?php echo e(old('severity_level') == 'minor' ? 'selected' : ''); ?>>Minor (Minor cuts, bruises)</option>
                                <option value="moderate" <?php echo e(old('severity_level') == 'moderate' ? 'selected' : ''); ?>>Moderate (Sprains, moderate bleeding)</option>
                                <option value="severe" <?php echo e(old('severity_level') == 'severe' ? 'selected' : ''); ?>>Severe (Fractures, severe bleeding)</option>
                            </select>
                            <?php $__errorArgs = ['severity_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="location">Location Where First Aid Was Given <span class="required">*</span></label>
                        <input type="text" id="location" name="location" required placeholder="e.g., Hospital Emergency Room, On-site Clinic" value="<?php echo e(old('location')); ?>" />
                        <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea id="notes" name="notes" maxlength="1000" placeholder="Any additional information about the treatment..."><?php echo e(old('notes')); ?></textarea>
                        <div class="hint-text">Optional: Any relevant information not covered above</div>
                    </div>
                </section>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div>
                        <a href="<?php echo e(route('welcome')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fa-solid fa-check"></i> Submit First Aid Record
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('firstAidForm');
        const submitBtn = document.getElementById('submitBtn');
        const successMessage = document.getElementById('successMessage');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(new FormData(form))
                });

                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                const submitResponse = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                if (submitResponse.ok) {
                    // Show success message
                    successMessage.classList.add('show');

                    // Clear form
                    form.reset();

                    // Scroll to success message
                    successMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                    // Hide success message after 5 seconds
                    setTimeout(() => {
                        successMessage.classList.remove('show');
                    }, 5000);
                } else {
                    const error = await submitResponse.json();
                    if (error.errors) {
                        // Show validation errors
                        Object.keys(error.errors).forEach(field => {
                            const errorText = error.errors[field][0];
                            showFieldError(field, errorText);
                        });
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while submitting the form');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Submit First Aid Record';
            }
        });

        function showFieldError(fieldName, message) {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                // Find parent form-group
                const formGroup = input.closest('.form-group');
                if (formGroup) {
                    // Remove existing error message
                    const existingError = formGroup.querySelector('.error-text');
                    if (existingError) {
                        existingError.remove();
                    }

                    // Add new error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-text';
                    errorDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${message}`;
                    formGroup.appendChild(errorDiv);
                }
            }
        }
    </script>

</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\first_aid\create.blade.php ENDPATH**/ ?>