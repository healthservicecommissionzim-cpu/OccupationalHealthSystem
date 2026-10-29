<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Executive Recommendation · <?php echo e($incident->reference_number); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1180px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .back:hover { background: #f8fafb; }
        
        /* Alert */
        .alert { display: flex; gap: 14px; padding: 17px 20px; background: #fff8e7; border: 1px solid #f6d48b; border-left: 4px solid #f59e0b; border-radius: 10px; margin-bottom: 22px; }
        .alerticon { width: 34px; height: 34px; border-radius: 9px; background: #fff0c9; color: #c67a00; display: grid; place-items: center; flex-shrink: 0; }
        .alert strong { display: block; color: #624300; font-size: 12px; margin-bottom: 4px; }
        .alert p { margin: 0; color: #7d6332; font-size: 11px; line-height: 1.6; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .body { padding: 23px; }
        
        /* Form Elements */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #8a95a5; font-size: 9px; font-weight: 750; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 8px; }
        .form-group label .required { color: #dc2626; }
        .form-group input[type="text"], 
        .form-group input[type="date"], 
        .form-group textarea { width: 100%; padding: 11px 14px; border: 1px solid #e1e7ee; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; background: #f8fafb; transition: all 0.2s; }
        .form-group input[type="text"]:focus, 
        .form-group input[type="date"]:focus, 
        .form-group textarea:focus { outline: none; border-color: #079b6d; background: white; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .char-count { font-size: 11px; color: #8a95a5; margin-top: 6px; }
        .char-count.valid { color: #079b6d; font-weight: 600; }
        .char-count.invalid { color: #dc2626; }
        
        /* Actions */
        .actions { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .buttons { display: flex; gap: 9px; }
        .btn { border-radius: 8px; padding: 10px 17px; font-size: 10px; font-weight: 700; border: 1px solid #d9e0e7; background: white; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #263149; display: flex; align-items: center; gap: 6px; }
        .btn:hover { background: #f8fafb; }
        .primary { background: #079b6d; color: white; border-color: #079b6d; box-shadow: 0 5px 13px rgba(7, 155, 109, .18); flex: 1; justify-content: center; }
        .primary:hover:not(:disabled) { background: #067c58; }
        .primary:disabled { opacity: 0.6; cursor: not-allowed; }
        
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loading { opacity: 0.7; pointer-events: none; }
        .loading i { animation: spin 1s linear infinite; }
        
        /* Responsive */
        @media (max-width: 900px) { 
            .details { grid-template-columns: 1fr; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; }
            .actions { flex-direction: column; }
            .buttons { width: 100%; flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                <div>
                    <h1 class="title">Executive Recommendation</h1>
                    <div class="sub">Compose your clinical recommendation letter</div>
                    <span class="case">CASE: <?php echo e($incident->reference_number); ?></span>
                </div>
            </div>
            <a href="<?php echo e(route('executive.review_case', $incident)); ?>" class="back"><i class="fa-solid fa-arrow-left"></i> &nbsp; Back</a>
        </div>

        <!-- Alert -->
        <div class="alert">
            <div class="alerticon"><i class="fa-solid fa-info-circle"></i></div>
            <div>
                <strong>Executive Recommendation:</strong>
                <p>Complete all form fields below to compose your official recommendation letter for this case.</p>
            </div>
        </div>

        <?php if(!isset($incident)): ?>
            <div class="card">
                <div class="body">
                    <div style="color: #dc2626; font-weight: 600;">
                        <i class="fa-solid fa-exclamation-circle"></i> Error: Incident data not found.
                    </div>
                </div>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('executive.submit_recommendation', $incident)); ?>" id="recommendationForm" onsubmit="return validateForm()">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="audit_verified" value="1">
                <input type="hidden" name="service_connection_notes" value="<?php echo e($serviceConnectionNotes ?? ''); ?>">

                <!-- From Section -->
                <div class="card">
                    <div class="chead">
                        <div class="ctitle"><i class="fa-solid fa-user"></i> &nbsp; From (Your Details)</div>
                    </div>
                    <div class="body">
                        <div class="form-group">
                            <label for="from_name">Your Name <span class="required">*</span></label>
                            <input type="text" id="from_name" name="from_name" autocomplete="off" placeholder="Your full name" required>
                        </div>

                        <div class="form-group">
                            <label for="from_title">Your Title <span class="required">*</span></label>
                            <input type="text" id="from_title" name="from_title" autocomplete="off" placeholder="e.g., District Medical Officer" required>
                        </div>

                        <div class="form-group">
                            <label for="from_department">Your Department/Facility <span class="required">*</span></label>
                            <input type="text" id="from_department" name="from_department" autocomplete="off" placeholder="e.g., Health Service Commission" required>
                        </div>

                        <div class="form-group">
                            <label for="letter_date">Letter Date <span class="required">*</span></label>
                            <input type="date" id="letter_date" name="letter_date" autocomplete="off" required readonly value="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                    </div>
                </div>

                <!-- To Section -->
                <div class="card">
                    <div class="chead">
                        <div class="ctitle"><i class="fa-solid fa-envelope"></i> &nbsp; To (Recipient)</div>
                    </div>
                    <div class="body">
                        <div class="form-group">
                            <label for="to_name">Recipient Name <span class="required">*</span></label>
                            <input type="text" id="to_name" name="to_name" autocomplete="off" placeholder="Recipient's full name" required>
                        </div>

                        <div class="form-group">
                            <label for="to_title">Recipient Title <span class="required">*</span></label>
                            <input type="text" id="to_title" name="to_title" autocomplete="off" placeholder="e.g., Provincial Medical Director" required>
                        </div>

                        <div class="form-group">
                            <label for="to_address">Address <span class="required">*</span></label>
                            <textarea id="to_address" name="to_address" autocomplete="off" placeholder="Recipient's mailing address" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Letter Subject -->
                <div class="card">
                    <div class="chead">
                        <div class="ctitle"><i class="fa-solid fa-tag"></i> &nbsp; Subject</div>
                    </div>
                    <div class="body">
                        <div class="form-group">
                            <label for="subject">Subject/Reference <span class="required">*</span></label>
                            <input type="text" id="subject" name="subject" autocomplete="off" placeholder="e.g., Clinical Review and Recommendation" required value="Clinical Review and Recommendation - Case <?php echo e($incident->reference_number); ?>">
                        </div>
                    </div>
                </div>

                <!-- Recommendation Letter -->
                <div class="card">
                    <div class="chead">
                        <div class="ctitle"><i class="fa-solid fa-file-lines"></i> &nbsp; Recommendation Letter</div>
                    </div>
                    <div class="body">
                        <div class="form-group">
                            <label for="recommendation_text">Letter Content <span class="required">*</span></label>
                            <textarea id="recommendation_text" name="recommendation_text" autocomplete="off" maxlength="3000" placeholder="<?php echo e($placeholderText ?? 'Enter your clinical recommendation letter...'); ?>" rows="10" required oninput="updateCharCount()"></textarea>
                            <div class="char-count" id="charCount">0 / 3000 characters</div>
                            <div class="char-count invalid" id="charWarning">Minimum 50 characters required</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="actions">
                    <a href="<?php echo e(route('executive.review_case', $incident)); ?>" class="btn">
                        <i class="fa-solid fa-arrow-left"></i> Back to Review
                    </a>
                    <button type="submit" id="submitBtn" class="btn primary" disabled>
                        <i class="fa-solid fa-paper-plane" id="submitBtnIcon"></i> 
                        <span id="submitBtnText">Submit & Escalate</span>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function updateCharCount() {
            const textarea = document.getElementById('recommendation_text');
            const charCount = document.getElementById('charCount');
            const charWarning = document.getElementById('charWarning');
            const submitBtn = document.getElementById('submitBtn');

            const length = textarea.value.length;
            const max = 3000;
            const min = 50;

            charCount.textContent = length + ' / ' + max + ' characters';

            if (length < min) {
                charWarning.className = 'char-count invalid';
                charWarning.textContent = (min - length) + ' more characters required';
                submitBtn.disabled = true;
            } else {
                charWarning.className = 'char-count valid';
                charWarning.textContent = '✓ Valid – Ready to submit';
                submitBtn.disabled = false;
            }
        }

        function validateForm() {
            const textarea = document.getElementById('recommendation_text');
            if (textarea.value.length < 50) {
                alert('Please write at least 50 characters in the letter.');
                return false;
            }
            
            const btn = document.getElementById('submitBtn');
            const icon = document.getElementById('submitBtnIcon');
            const text = document.getElementById('submitBtnText');
            
            btn.classList.add('loading');
            btn.disabled = true;
            icon.className = 'fa-solid fa-spinner';
            text.textContent = 'Submitting...';
            
            return true;
        }

        // Initialize character count
        updateCharCount();
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\executive_recommendation.blade.php ENDPATH**/ ?>