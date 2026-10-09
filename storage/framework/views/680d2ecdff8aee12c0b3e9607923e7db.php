

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-[20px] font-bold text-navy">Administrative Dispatch Interface</h1>
                <p class="text-[12px] text-slate-500 mt-1">Final compliance stage - Physical delivery to Department of Social Services</p>
            </div>
            <a href="<?php echo e(route('incidents.document_verification', $incident)); ?>" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Document Verification
            </a>
        </div>
    </div>

    <!-- Statutory Compliance Countdown -->
    <div class="bg-slate-50 border-b border-slate-200 px-6 py-3">
        <div class="max-w-7xl mx-auto">
            <?php
                $colorCode = $incident->getComplianceColorCode();
                $daysRemaining = $incident->getDaysRemainingUntilExpiry();
                $expiryDate = $incident->getExpiryDate();
                $monthsSince = $incident->getMonthsSinceIncident();
            ?>

            <?php if($incident->isClaimExpired()): ?>
                <div class="bg-red-50 border border-red-300 rounded-lg p-3 flex items-center gap-3">
                    <i class="fa-solid fa-ban text-red-600 text-2xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[12px] font-bold text-red-900">CLAIM EXPIRED</p>
                        <p class="text-[11px] text-red-800">This case is beyond the 12-month statutory window and cannot be submitted.</p>
                    </div>
                </div>
            <?php elseif($incident->isInCriticalRiskZone()): ?>
                <div class="bg-red-50 border border-red-300 rounded-lg p-3 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-2xl flex-shrink-0 animate-pulse"></i>
                    <div>
                        <p class="text-[12px] font-bold text-red-900">🔴 CRITICAL: <?php echo e($daysRemaining); ?> DAYS REMAINING</p>
                        <p class="text-[11px] text-red-800">Must be delivered to Social Services by <strong><?php echo e($expiryDate->format('M d, Y')); ?></strong> to avoid statutory rejection.</p>
                    </div>
                </div>
            <?php elseif($incident->isInWarningZone()): ?>
                <div class="bg-amber-50 border border-amber-300 rounded-lg p-3 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-amber-600 text-2xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[12px] font-bold text-amber-900">🟡 WARNING: <?php echo e($daysRemaining); ?> DAYS LEFT</p>
                        <p class="text-[11px] text-amber-800">Plan for immediate delivery to Social Services. Expiry: <?php echo e($expiryDate->format('M d, Y')); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-emerald-50 border border-emerald-300 rounded-lg p-3 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-2xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[12px] font-bold text-emerald-900">🟢 COMPLIANT: <?php echo e($daysRemaining); ?> DAYS REMAINING</p>
                        <p class="text-[11px] text-emerald-800">Within safe submission window. Expiry: <?php echo e($expiryDate->format('M d, Y')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Messages -->
        <?php if(session('success')): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-[12px] text-emerald-900"><i class="fa-solid fa-check-circle mr-2"></i><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-[12px] text-red-900"><i class="fa-solid fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Incident Summary -->
                <div class="bg-white border border-slate-200 rounded-lg p-5">
                    <h2 class="text-[14px] font-bold text-navy mb-4">Case Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-[12px] text-slate-600">Reference Number:</span>
                            <span class="text-[12px] font-semibold text-slate-900"><?php echo e($incident->reference_number); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[12px] text-slate-600">Employee:</span>
                            <span class="text-[12px] font-semibold text-slate-900"><?php echo e($incident->employee?->name ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[12px] text-slate-600">Facility:</span>
                            <span class="text-[12px] font-semibold text-slate-900"><?php echo e($incident->facility?->name ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[12px] text-slate-600">Incident Date:</span>
                            <span class="text-[12px] font-semibold text-slate-900"><?php echo e($incident->incident_date->format('M d, Y')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- HR Compliance Notes -->
                <div class="bg-white border border-slate-200 rounded-lg p-5">
                    <h2 class="text-[14px] font-bold text-navy mb-3">HR Compliance Notes & Findings</h2>
                    <p class="text-[11px] text-slate-500 mb-4">
                        <i class="fa-solid fa-info-circle mr-1"></i>Administrative summary required. Minimum 50 characters, maximum 2,000 characters.
                    </p>
                    
                    <?php if($incident->hr_compliance_notes): ?>
                        <!-- Read-Only Mode: Notes Already Saved -->
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 space-y-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-lock text-emerald-600"></i>
                                <p class="text-[11px] font-semibold text-emerald-700">Notes Finalized & Locked</p>
                            </div>
                            <p class="text-[12px] text-slate-700 leading-relaxed"><?php echo e($incident->hr_compliance_notes); ?></p>
                            <div class="pt-2 border-t border-emerald-200 text-[10px] text-emerald-700 space-y-1">
                                <p><strong>Saved:</strong> <?php echo e($incident->hr_compliance_notes_saved_at?->format('M d, Y H:i') ?? 'N/A'); ?></p>
                                <p><strong>By Officer:</strong> <?php echo e(auth()->user()->name ?? 'System'); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Edit Mode: Notes Not Yet Saved -->
                        <form method="POST" action="<?php echo e(route('incidents.save_compliance_notes', $incident)); ?>" class="space-y-4" id="complianceForm">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Textarea Field -->
                            <div>
                                <textarea 
                                    id="complianceNotes" 
                                    name="hr_compliance_notes" 
                                    class="w-full border-2 border-slate-300 rounded-lg p-4 text-[13px] font-family-sans focus:outline-none focus:border-blue-500 transition-colors resize-none"
                                    placeholder="Enter your HR compliance notes and administrative findings here. Include your assessment of the case, document review status, and any additional observations..."
                                    rows="7"
                                    maxlength="2000"
                                    required></textarea>
                            </div>

                            <!-- Character Counter with Dynamic Color -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span id="charCounter" class="text-[13px] font-bold px-3 py-1 rounded-full bg-red-100 text-red-700" style="min-width: 60px;">
                                        <span id="charCount">0</span>/2000
                                    </span>
                                    <span id="counterStatus" class="text-[11px] text-red-600 font-medium">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>Minimum 50 characters required
                                    </span>
                                </div>
                            </div>

                            <!-- Validation Message -->
                            <div id="validationMessage" class="p-3 rounded-lg bg-red-50 border border-red-200 hidden">
                                <p class="text-[11px] text-red-700">
                                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                                    <span id="validationText">Enter at least 50 characters</span>
                                </p>
                            </div>

                            <?php $__errorArgs = ['hr_compliance_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="p-3 rounded-lg bg-red-50 border border-red-200">
                                    <p class="text-[11px] text-red-700">
                                        <i class="fa-solid fa-exclamation-circle mr-2"></i><?php echo e($message); ?>

                                    </p>
                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                            <!-- Submit Button - Initially Disabled -->
                            <button 
                                id="saveNotesBtn"
                                type="submit" 
                                class="w-full px-4 py-3 rounded-lg text-[13px] font-bold transition-all duration-200 flex items-center justify-center gap-2"
                                style="background-color: #d1d5db; color: #6b7280; cursor: not-allowed;"
                                disabled>
                                <i class="fa-solid fa-save"></i>Save Compliance Notes
                            </button>
                            <p class="text-[10px] text-slate-500 text-center">Complete validation to enable saving</p>
                        </form>
                    <?php endif; ?>
                </div>


            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Progress Checklist -->
                <div class="bg-white border border-slate-200 rounded-lg p-5">
                    <h2 class="text-[14px] font-bold text-navy mb-4">Completion Checklist</h2>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <?php if($incident->hr_compliance_notes): ?>
                                <i class="fa-solid fa-check-circle text-emerald-600 text-lg flex-shrink-0"></i>
                                <span class="text-[12px] text-emerald-700 font-medium">Compliance Notes Saved</span>
                            <?php else: ?>
                                <i class="fa-solid fa-circle text-slate-300 text-lg flex-shrink-0"></i>
                                <span class="text-[12px] text-slate-600">Compliance Notes Pending</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Download Master PDF Dossier -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-5">
                    <h3 class="text-[13px] font-bold text-blue-900 mb-3">
                        <i class="fa-solid fa-file-pdf mr-2"></i>Master Documents
                    </h3>
                    <a href="<?php echo e(route('incidents.download_case_dossier', $incident)); ?>" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-[12px] font-semibold hover:bg-blue-700 inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download mr-1"></i>Download Complete Case Dossier
                    </a>
                </div>

                <!-- Final Submission Button -->
                <div class="bg-white border border-slate-200 rounded-lg p-5">
                    <?php if($incident->hr_compliance_notes): ?>
                        <form method="POST" action="<?php echo e(route('incidents.finalize_submission', $incident)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg text-[13px] font-bold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-double"></i>Finalize HR Review
                            </button>
                            <p class="text-[10px] text-slate-500 text-center mt-2">Case will be escalated to executive officers automatically.</p>
                        </form>
                    <?php else: ?>
                        <button type="button" disabled class="w-full px-4 py-3 bg-slate-300 text-slate-600 rounded-lg text-[13px] font-bold cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock"></i>Finalize HR Review (Locked)
                        </button>
                        <p class="text-[10px] text-slate-500 text-center mt-2">Complete compliance notes to unlock finalization.</p>
                    <?php endif; ?>
                </div>


            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded');
    
    const textarea = document.getElementById('complianceNotes');
    const charCount = document.getElementById('charCount');
    const charCounter = document.getElementById('charCounter');
    const counterStatus = document.getElementById('counterStatus');
    const validationMessage = document.getElementById('validationMessage');
    const validationText = document.getElementById('validationText');
    const saveNotesBtn = document.getElementById('saveNotesBtn');
    const complianceForm = document.getElementById('complianceForm');
    
    console.log('textarea:', textarea);
    console.log('charCount:', charCount);
    console.log('charCounter:', charCounter);
    console.log('counterStatus:', counterStatus);
    console.log('validationMessage:', validationMessage);
    console.log('validationText:', validationText);
    console.log('saveNotesBtn:', saveNotesBtn);
    console.log('complianceForm:', complianceForm);
    
    // If notes already saved, exit early
    if (!textarea || !complianceForm) {
        console.log('Textarea or form not found, exiting');
        return;
    }

    // Function to update validation state
    function updateValidation() {
        const length = textarea.value.length;
        const trimmedLength = textarea.value.trim().length;
        
        console.log('updateValidation called:', { length, trimmedLength });
        
        // Update character count display
        charCount.textContent = length;
        
        // Determine validation state
        let isValid = false;
        
        if (trimmedLength < 50) {
            console.log('State: RED (under 50)');
            charCounter.style.backgroundColor = '#fee2e2';
            charCounter.style.color = '#991b1b';
            counterStatus.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i>Minimum 50 characters required';
            counterStatus.style.color = '#991b1b';
            validationMessage.classList.remove('hidden');
            validationText.textContent = `Only ${trimmedLength} of 50 characters minimum`;
            isValid = false;
        } else if (trimmedLength > 2000) {
            console.log('State: RED (over 2000)');
            charCounter.style.backgroundColor = '#fee2e2';
            charCounter.style.color = '#991b1b';
            counterStatus.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i>Exceeds 2,000 character limit';
            counterStatus.style.color = '#991b1b';
            validationMessage.classList.remove('hidden');
            validationText.textContent = `Exceeds maximum by ${trimmedLength - 2000} characters`;
            isValid = false;
        } else {
            console.log('State: GREEN (valid)');
            charCounter.style.backgroundColor = '#d1fae5';
            charCounter.style.color = '#065f46';
            counterStatus.innerHTML = '<i class="fa-solid fa-check-circle mr-1"></i>Valid entry';
            counterStatus.style.color = '#065f46';
            validationMessage.classList.add('hidden');
            isValid = true;
        }
        
        // Update button state
        saveNotesBtn.disabled = !isValid;
        saveNotesBtn.style.backgroundColor = isValid ? '#2563eb' : '#d1d5db';
        saveNotesBtn.style.color = isValid ? 'white' : '#6b7280';
        saveNotesBtn.style.cursor = isValid ? 'pointer' : 'not-allowed';
        
        console.log('Button disabled:', saveNotesBtn.disabled);
    }

    // Update character count and validation in real-time
    textarea.addEventListener('input', function(e) {
        console.log('input event fired');
        updateValidation();
    });

    textarea.addEventListener('keyup', function(e) {
        console.log('keyup event fired');
        updateValidation();
    });

    // Initial validation check
    console.log('Running initial validation');
    updateValidation();

    // Prevent form submission if validation fails
    complianceForm.addEventListener('submit', function(e) {
        const trimmedLength = textarea.value.trim().length;
        console.log('Form submit attempt:', { trimmedLength });
        if (trimmedLength < 50 || trimmedLength > 2000) {
            e.preventDefault();
            alert('Please enter between 50 and 2,000 characters.');
            return false;
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\incidents\administrative_dispatch.blade.php ENDPATH**/ ?>