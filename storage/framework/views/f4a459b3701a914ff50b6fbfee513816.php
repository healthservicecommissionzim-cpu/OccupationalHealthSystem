<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Register - HSC-OHS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-900: #0f172a;
            --white: #ffffff;
            --red-500: #ef4444;
            --green-500: #22c55e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1.5rem;
        }

        .register-container {
            max-width: 600px;
            width: 100%;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(29, 69, 51, 0.12);
            border-top: 5px solid var(--brand);
            padding: 2rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .hsc-logo {
            height: 48px;
            margin-bottom: 0.6rem;
        }

        .form-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--slate-900);
        }

        .alert {
            padding: 0.7rem 0.9rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            display: flex;
            gap: 0.5rem;
            border-left: 4px solid var(--red-500);
            background: #fef2f2;
            color: #991b1b;
            font-size: 0.82rem;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.35rem;
        }

        .form-group .required {
            color: var(--red-500);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--slate-900);
            background: var(--white);
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23334155'%3E%3Cpath d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1.1rem;
            padding-right: 2.2rem;
        }

        .form-group input:disabled,
        .form-group select:disabled {
            background-color: var(--slate-100);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .error-text {
            color: var(--red-500);
            font-size: 0.7rem;
            margin-top: 0.2rem;
        }

        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .form-section:last-of-type {
            border-bottom: none;
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

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .custom-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        .custom-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-top: 2px;
        }

        .custom-checkbox label {
            flex: 1;
            font-size: 0.75rem;
            color: var(--brand);
            cursor: pointer;
            margin: 0;
        }

        .btn-primary {
            width: 100%;
            padding: 0.7rem;
            background: var(--brand);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: var(--brand-dark);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
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
            to { transform: rotate(360deg); }
        }

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
        }

        footer {
            background: var(--brand);
            color: rgba(255, 255, 255, 0.9);
            padding: 2rem 4%;
            text-align: center;
        }

        footer p {
            font-size: 0.8rem;
            margin: 0.5rem 0;
        }

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
            padding: 0.25rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Select2 custom styling */
        .select2-container--default .select2-selection--single {
            height: auto !important;
            padding: 0.5rem 0.75rem !important;
            border: 1.5px solid var(--slate-200) !important;
            border-radius: 6px !important;
            background: var(--white) !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            color: var(--slate-900) !important;
            font-size: 0.95rem !important;
            line-height: 1.5 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.5rem !important;
        }

        .select2-dropdown {
            border: 1.5px solid var(--brand) !important;
            border-radius: 6px !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--slate-200) !important;
            border-radius: 6px !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.95rem !important;
        }

        .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.95rem !important;
        }

        .select2-results__option--highlighted {
            background-color: rgba(29, 69, 51, 0.1) !important;
            color: var(--brand) !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
</head>
<body>
    <main>
        <div class="register-container">
            <div class="form-header">
                <img src="/images/hsc-logo.png" alt="HSC-OHS Logo" class="hsc-logo">
                <h1>Create Account</h1>
                <hr>
            </div>

            <?php if($errors->any()): ?>
            <div class="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register.store')); ?>" id="registerForm">
                <?php echo csrf_field(); ?>

                <!-- Personal Details -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-regular fa-user"></i> Personal Details
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" required placeholder="e.g. John">
                        </div>
                        <div class="form-group">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" required placeholder="e.g. Doe">
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="you@example.com">
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone_number" value="<?php echo e(old('phone_number')); ?>" required placeholder="+263 77 123 4567">
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Department <span class="required">*</span></label>
                            <select name="department_id" id="department_id" required>
                                <option value="">— Select Department —</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id') == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Designation <span class="required">*</span></label>
                            <select name="designation_id" id="designation_id" required>
                                <option value="">— Select Designation —</option>
                                <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($desig->id); ?>" data-name="<?php echo e($desig->name); ?>" <?php echo e(old('designation_id') == $desig->id ? 'selected' : ''); ?>><?php echo e($desig->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Organization Details -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-regular fa-briefcase"></i> Organization Details
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Organization <span class="required">*</span></label>
                            <select name="organization_id" id="organization_id" required>
                                <option value="">— Select Organization —</option>
                                <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($org->id); ?>" data-requires-location="<?php echo e($org->requires_location ? '1' : '0'); ?>" <?php echo e(old('organization_id') == $org->id ? 'selected' : ''); ?>><?php echo e($org->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Province <span class="required" id="province_req">*</span></label>
                            <select name="province_id" id="province_id" disabled>
                                <option value="">— Select Province —</option>
                                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prov->id); ?>" <?php echo e(old('province_id') == $prov->id ? 'selected' : ''); ?>><?php echo e($prov->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>District <span class="required" id="district_req">*</span></label>
                            <select name="district_id" id="district_id" disabled>
                                <option value="">— Select District —</option>
                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dist->id); ?>" data-province="<?php echo e($dist->province_id); ?>" <?php echo e(old('district_id') == $dist->id ? 'selected' : ''); ?>><?php echo e($dist->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Facility Type <span class="required" id="facility_type_req">*</span></label>
                            <select name="facility_type_id" id="facility_type_id" disabled>
                                <option value="">— Select Facility Type —</option>
                                <?php $__currentLoopData = $facilityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ft->id); ?>" <?php echo e(old('facility_type_id') == $ft->id ? 'selected' : ''); ?>><?php echo e($ft->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Facility/Hospital <span class="required" id="facility_req">*</span></label>
                            <select name="facility_id" id="facility_id" disabled>
                                <option value="">— Select Facility —</option>
                                <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($fac->id); ?>" data-district="<?php echo e($fac->district_id); ?>" data-type="<?php echo e($fac->facility_type_id); ?>" <?php echo e(old('facility_id') == $fac->id ? 'selected' : ''); ?>><?php echo e($fac->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-regular fa-lock"></i> Security
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Password <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="password" name="password" id="password" required minlength="8" placeholder="••••••••">
                                <button type="button" class="toggle-password" data-target="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label>Confirm Password <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••">
                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consent -->
                <div class="custom-checkbox">
                    <input type="checkbox" name="consent" id="consent" value="1" required>
                    <label for="consent">
                        I consent to the collection and processing of my personal information. <span class="required">*</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">
                    <span class="spinner"></span>
                    <span class="btn-text"><i class="fa-regular fa-user-plus"></i> Create Account</span>
                </button>
            </form>

            <div class="form-footer">
                Already have an account? <a href="<?php echo e(route('login')); ?>">Sign in</a>
                <a href="<?php echo e(route('welcome')); ?>"> | Home</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Health Service Commission. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // SVG icon fallback for Font Awesome
        (function() {
            const svgIcons = {
                'fa-eye': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>',
                'fa-eye-slash': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>'
            };

            function replaceFAIcons() {
                const icons = document.querySelectorAll('i[class*="fa-"]');
                icons.forEach(icon => {
                    const hasContent = icon.textContent.trim().length > 0 || icon.querySelector('svg');
                    if (!hasContent) {
                        const classes = Array.from(icon.classList);
                        for (const cls of classes) {
                            if (svgIcons[cls]) {
                                icon.innerHTML = svgIcons[cls];
                                icon.style.display = 'inline-flex';
                                icon.style.alignItems = 'center';
                                icon.style.justifyContent = 'center';
                                break;
                            }
                        }
                    }
                });
            }

            window.addEventListener('DOMContentLoaded', replaceFAIcons);
        })();

        // Initialize Select2 on searchable dropdowns ONLY (Department, Designation, District, Facility)
        $(document).ready(function() {
            $('#department_id, #designation_id, #district_id, #facility_id').select2({
                placeholder: function() {
                    return $(this).find('option:first').text();
                },
                allowClear: true,
                width: '100%'
            });
        });

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.onclick = function() {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            };
        });

        // Cascading dropdowns - organization enables province
        $('#organization_id').on('change', function() {
            const selected = $(this).find(':selected');
            const requiresLocation = selected.data('requiresLocation') === 1;
            const province = $('#province_id');
            
            if (requiresLocation) {
                province.prop('disabled', false).prop('required', true);
            } else {
                province.prop('disabled', true).prop('required', false).val('');
            }
            
            // Reset downstream
            $('#district_id').val('').prop('disabled', true);
            $('#facility_type_id').val('').prop('disabled', true);
            $('#facility_id').val('').trigger('change').prop('disabled', true);
        });

        // Province enables district
        $('#province_id').on('change', function() {
            const provinceId = $(this).val();
            const districtSelect = $('#district_id');
            
            if (provinceId) {
                // Filter districts by province
                districtSelect.find('option').each(function() {
                    const opt = $(this);
                    if (opt.val() === '' || opt.data('province') == provinceId) {
                        opt.prop('disabled', false).show();
                    } else {
                        opt.prop('disabled', true).hide();
                    }
                });
                
                // Enable district and refresh Select2
                districtSelect.prop('disabled', false).prop('required', true).val('').trigger('change');
            } else {
                districtSelect.prop('disabled', true).val('').trigger('change');
            }
            
            $('#facility_type_id').prop('disabled', false);
            $('#facility_id').val('').trigger('change').prop('disabled', true);
        });

        // District + Facility Type enable Facility
        function updateFacilities() {
            const districtId = $('#district_id').val();
            const facilityTypeId = $('#facility_type_id').val();
            const facilitySelect = $('#facility_id');
            
            if (districtId && facilityTypeId) {
                facilitySelect.prop('disabled', false).prop('required', true);
                
                facilitySelect.find('option').each(function() {
                    const opt = $(this);
                    if (opt.val() === '' || (opt.data('district') == districtId && opt.data('type') == facilityTypeId)) {
                        opt.show();
                    } else {
                        opt.hide();
                    }
                });
                
                facilitySelect.trigger('change');
            } else {
                facilitySelect.prop('disabled', true).val('').trigger('change');
            }
        }

        $('#district_id, #facility_type_id').on('change', updateFacilities);

        // Form submission with loading state
        document.getElementById('registerForm').onsubmit = function() {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        };

        // Success modal
        <?php if(session('success')): ?>
        (function() {
            const modal = document.createElement('div');
            modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:9999;';
            modal.innerHTML = `
                <div style="background:white;border-radius:12px;padding:40px;max-width:450px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.4);">
                    <div style="width:80px;height:80px;margin:0 auto 20px;background:linear-gradient(135deg,#22c55e,#16a34a);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-check" style="font-size:40px;color:white;"></i>
                    </div>
                    <h2 style="font-size:24px;font-weight:700;color:#0f172a;margin:0 0 12px 0;">Success!</h2>
                    <p style="color:#475569;margin:0 0 24px 0;font-size:14px;"><?php echo e(session('success')); ?></p>
                    <div style="background:#f0fdf4;border-radius:10px;padding:20px;margin-bottom:24px;">
                        <p style="color:#4b7c59;font-size:12px;margin:0 0 8px 0;">Redirecting in</p>
                        <p style="font-size:32px;font-weight:700;color:#1D4533;margin:0;" id="countdown">5</p>
                    </div>
                    <button onclick="location.href='<?php echo e(route('login')); ?>'" style="width:100%;padding:12px;background:#1D4533;color:white;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
                        <i class="fa-solid fa-sign-in"></i> Go to Login
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            
            let count = 5;
            const interval = setInterval(() => {
                count--;
                document.getElementById('countdown').textContent = count;
                if (count <= 0) {
                    clearInterval(interval);
                    location.href = '<?php echo e(route('login')); ?>';
                }
            }, 1000);
        })();
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/auth/register.blade.php ENDPATH**/ ?>