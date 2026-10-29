

<?php $__env->startSection('title', 'Case Review - ' . $incident->reference_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="<?php echo e(route('medical-board.dashboard')); ?>" class="text-blue-600 hover:text-blue-700 font-medium mb-6 inline-block">
        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
    </a>

    <div class="grid grid-cols-3 gap-6">
        <!-- Left Column: Case Details -->
        <div class="col-span-2 space-y-6">
            <!-- Case Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800"><?php echo e($incident->reference_number); ?></h1>
                        <p class="text-slate-600 mt-1"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></p>
                    </div>
                    <span class="inline-block px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-lg">
                        <i class="fas fa-alert-circle mr-2"></i><?php echo e($incident->status); ?>

                    </span>
                </div>

                <!-- Case Information -->
                <div class="grid grid-cols-2 gap-4 border-t border-slate-200 pt-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-600 uppercase">Incident Date</p>
                        <p class="text-sm font-medium text-slate-800 mt-1"><?php echo e($incident->incident_date?->format('M d, Y')); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 uppercase">Facility</p>
                        <p class="text-sm font-medium text-slate-800 mt-1"><?php echo e($incident->facility?->name); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 uppercase">District</p>
                        <p class="text-sm font-medium text-slate-800 mt-1"><?php echo e($incident->district?->name); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 uppercase">Incident Type</p>
                        <p class="text-sm font-medium text-slate-800 mt-1"><?php echo e($incident->incidentType?->name); ?></p>
                    </div>
                </div>
            </div>

            <!-- Leave Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-800 mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Leave Information
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-xs font-semibold text-slate-600 uppercase">Leave Start Date</p>
                        <p class="text-lg font-bold text-blue-700 mt-2"><?php echo e($incident->sick_leave_start_date?->format('M d, Y')); ?></p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg">
                        <p class="text-xs font-semibold text-slate-600 uppercase">Days Elapsed</p>
                        <p class="text-lg font-bold text-slate-800 mt-2"><?php echo e($incident->days_elapsed); ?> days</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg">
                        <p class="text-xs font-semibold text-slate-600 uppercase">Payment Stage</p>
                        <p class="text-sm font-semibold text-slate-700 mt-2">
                            <?php if($incident->leave_payment_stage === 'FULL_PAY'): ?>
                                <span class="text-green-700">Full Pay (Days 1-90)</span>
                            <?php elseif($incident->leave_payment_stage === 'HALF_PAY'): ?>
                                <span class="text-orange-700">Half Pay (Days 91-180)</span>
                            <?php else: ?>
                                <span class="text-red-700">Medical Discharge Eligible (181+)</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg">
                        <p class="text-xs font-semibold text-slate-600 uppercase">Medical Certificate</p>
                        <p class="text-sm font-semibold text-slate-700 mt-2">
                            <?php if($incident->medical_certificate_provided): ?>
                                <span class="text-green-700"><i class="fas fa-check mr-1"></i>Provided</span>
                            <?php else: ?>
                                <span class="text-red-700"><i class="fas fa-times mr-1"></i>Not Provided</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if($incident->expected_recovery_date): ?>
                    <div class="bg-slate-50 p-4 rounded-lg col-span-2">
                        <p class="text-xs font-semibold text-slate-600 uppercase">Expected Recovery Date</p>
                        <p class="text-sm font-medium text-slate-700 mt-2"><?php echo e($incident->expected_recovery_date?->format('M d, Y')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Audit Trail -->
            <?php if (isset($component)) { $__componentOriginal1117cb3188798a7802cd146fb5310e0e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1117cb3188798a7802cd146fb5310e0e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.audit-trail-display','data' => ['incident' => $incident]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('audit-trail-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['incident' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($incident)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1117cb3188798a7802cd146fb5310e0e)): ?>
<?php $attributes = $__attributesOriginal1117cb3188798a7802cd146fb5310e0e; ?>
<?php unset($__attributesOriginal1117cb3188798a7802cd146fb5310e0e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1117cb3188798a7802cd146fb5310e0e)): ?>
<?php $component = $__componentOriginal1117cb3188798a7802cd146fb5310e0e; ?>
<?php unset($__componentOriginal1117cb3188798a7802cd146fb5310e0e); ?>
<?php endif; ?>
        </div>

        <!-- Right Column: Decision Form -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                <h2 class="text-lg font-semibold text-slate-800 mb-6">
                    <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>
                    Record Decision
                </h2>

                <form id="decisionForm" class="space-y-4">
                    <?php echo csrf_field(); ?>

                    <!-- Decision Options -->
                    <div class="space-y-3">
                        <label class="flex items-start space-x-3 p-3 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                            <input type="radio" name="decision" value="APPROVE_RETURN_TO_WORK" class="mt-1" required>
                            <div>
                                <p class="font-medium text-slate-800">Approve Return to Work</p>
                                <p class="text-xs text-slate-600">Worker cleared to return to normal duties</p>
                            </div>
                        </label>

                        <label class="flex items-start space-x-3 p-3 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                            <input type="radio" name="decision" value="RECOMMEND_EXTENDED_LEAVE" class="mt-1" required>
                            <div>
                                <p class="font-medium text-slate-800">Recommend Extended Leave</p>
                                <p class="text-xs text-slate-600">Continue sick leave for recovery period</p>
                            </div>
                        </label>

                        <label class="flex items-start space-x-3 p-3 border-2 border-red-200 rounded-lg cursor-pointer hover:border-red-400 transition-colors">
                            <input type="radio" name="decision" value="RECOMMEND_MEDICAL_DISCHARGE" class="mt-1" required>
                            <div>
                                <p class="font-medium text-red-800">Recommend Medical Discharge</p>
                                <p class="text-xs text-red-600">Initiate medical discharge procedures</p>
                            </div>
                        </label>
                    </div>

                    <!-- Decision Reason -->
                    <div class="border-t border-slate-200 pt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Decision Reason
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea name="decision_reason" 
                            placeholder="Provide detailed medical justification for this decision..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="4"
                            required
                            minlength="10"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>
                        Submit Decision
                    </button>

                    <!-- Loading State -->
                    <div id="loadingState" class="hidden text-center py-4">
                        <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                        <p class="text-slate-600 text-sm mt-2">Processing decision...</p>
                    </div>
                </form>

                <!-- Alert Messages -->
                <div id="successAlert" class="hidden mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-green-800">Decision recorded successfully</p>
                </div>

                <div id="errorAlert" class="hidden mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-red-800" id="errorMessage"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('decisionForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    document.querySelector('button[type="submit"]').disabled = true;
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('successAlert').classList.add('hidden');
    document.getElementById('errorAlert').classList.add('hidden');

    try {
        const response = await fetch(`/api/medical-board/incidents/<?php echo e($incident->id); ?>/decision`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Failed to submit decision');
        }

        document.getElementById('successAlert').classList.remove('hidden');
        setTimeout(() => {
            window.location.href = '<?php echo e(route("medical-board.dashboard")); ?>';
        }, 2000);

    } catch (error) {
        document.getElementById('errorMessage').textContent = error.message;
        document.getElementById('errorAlert').classList.remove('hidden');
    } finally {
        document.querySelector('button[type="submit"]').disabled = false;
        document.getElementById('loadingState').classList.add('hidden');
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\medical-board\review.blade.php ENDPATH**/ ?>