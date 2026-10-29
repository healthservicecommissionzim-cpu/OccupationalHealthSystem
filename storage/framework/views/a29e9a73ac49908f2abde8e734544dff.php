

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-[20px] font-bold text-navy">Document Verification</h1>
                <p class="text-[12px] text-slate-500 mt-1"><?php echo e($incident->reference_number); ?> • Verify compliance with HSB Circular No. 3 of 2017</p>
            </div>
            <a href="<?php echo e(route('incidents.hr_dossier', $incident)); ?>" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dossier
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Flag Toggle Section -->
        <div class="bg-white border border-slate-200 rounded-lg p-5 mb-6">
            <h2 class="text-[14px] font-bold text-navy mb-4">Incident Classification Flags</h2>
            <p class="text-[11px] text-slate-600 mb-4">Toggle these flags to activate conditional mandatory documents:</p>
            
            <form id="flagsForm" method="POST" action="<?php echo e(route('incidents.update_flags', $incident)); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                
                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded border border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <input type="hidden" name="is_workplace_fatality" value="0">
                        <input type="checkbox" name="is_workplace_fatality" value="1" <?php echo e($incident->is_workplace_fatality ? 'checked' : ''); ?> class="w-4 h-4">
                        <span class="text-[12px] font-semibold text-slate-700">
                            <i class="fa-solid fa-skull mr-2 text-red-600"></i>Workplace Fatality
                        </span>
                    </label>
                    <span class="text-[11px] text-slate-500">Activates: Death Certificate, Heir/Guardian Certificate</span>
                </div>

                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded border border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <input type="hidden" name="is_road_traffic_accident" value="0">
                        <input type="checkbox" name="is_road_traffic_accident" value="1" <?php echo e($incident->is_road_traffic_accident ? 'checked' : ''); ?> class="w-4 h-4">
                        <span class="text-[12px] font-semibold text-slate-700">
                            <i class="fa-solid fa-car-crash mr-2 text-orange-600"></i>Road Traffic Accident
                        </span>
                    </label>
                    <span class="text-[11px] text-slate-500">Activates: Detailed Police Report</span>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-[12px] font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fa-solid fa-sync-alt mr-1"></i>Update Classification
                </button>
            </form>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-[12px] text-emerald-900">
                    <i class="fa-solid fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

                </p>
            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <p class="text-[12px] text-amber-900">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i><?php echo e(session('warning')); ?>

                </p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-[12px] text-red-900">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

                </p>
            </div>
        <?php endif; ?>
        <!-- Uploaded Files Section -->
        <?php if($attachments && count($attachments) > 0): ?>
        <div class="mb-8">
            <h2 class="text-[14px] font-bold text-navy mb-4">Uploaded Attachments</h2>
            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-700">File Name</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-700">Type</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-slate-700">Uploaded</th>
                            <th class="px-4 py-3 text-center text-[11px] font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="px-4 py-3 text-[12px] font-medium text-slate-700">
                                <i class="fa-solid fa-file mr-2"></i><?php echo e($attachment->filename ?? $attachment->original_filename); ?>

                            </td>
                            <td class="px-4 py-3 text-[12px] text-slate-600"><?php echo e($attachment->document_type ?? 'Document'); ?></td>
                            <td class="px-4 py-3 text-[12px] text-slate-600"><?php echo e($attachment->created_at->diffForHumans()); ?></td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <button type="button" class="view-doc-btn px-3 py-1.5 text-blue-600 hover:text-blue-700 text-[11px] font-semibold rounded hover:bg-blue-50 transition-colors" data-file="<?php echo e(route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment])); ?>" data-filename="<?php echo e($attachment->filename ?? $attachment->original_filename); ?>">
                                        <i class="fa-solid fa-eye mr-1"></i>View
                                    </button>
                                    <a href="<?php echo e(route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment])); ?>" download class="px-3 py-1.5 text-emerald-600 hover:text-emerald-700 text-[11px] font-semibold rounded hover:bg-emerald-50 transition-colors">
                                        <i class="fa-solid fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <form id="complianceForm" method="POST" action="<?php echo e(route('incidents.submit_compliance', $incident)); ?>">
            <?php echo csrf_field(); ?>

            <!-- Mandatory Documents Section -->
            <div class="mb-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <p class="text-[12px] text-red-900 font-semibold mb-1">
                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>Mandatory Documents Required
                    </p>
                    <p class="text-[11px] text-red-800">All <?php echo e(count($mandatoryDocuments) + count($mandatoryConditional)); ?> mandatory document<?php echo e(count($mandatoryDocuments) + count($mandatoryConditional) > 1 ? 's' : ''); ?> must be marked as ACCEPTED. The "Finalize Compliance & Escalate" button remains disabled until all are accepted.</p>
                </div>

                <div class="space-y-3">
                    <?php $docCount = 0; ?>
                    <?php $__currentLoopData = $mandatoryDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $docCount++; ?>
                    <div class="bg-white border border-slate-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-red-100 rounded-full text-[11px] font-bold text-red-700">
                                        <?php echo e($docCount); ?>

                                    </span>
                                    <h3 class="text-[13px] font-semibold text-navy"><?php echo e($label); ?></h3>
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-bold">MANDATORY</span>
                                </div>
                                <p class="text-[11px] text-slate-500">Required per HSB Circular No. 3 of 2017</p>

                                <!-- Special handling for bank statement fields -->
                                <?php if($key === 'bank_statements'): ?>
                                <div class="mt-3 space-y-2">
                                    <div class="text-[11px]">
                                        <label class="flex items-center gap-2 mb-1">
                                            <input type="checkbox" class="bank-stmt-check" data-type="nostro">
                                            <span class="text-slate-700">Nostro Account Statement</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" class="bank-stmt-check" data-type="zig">
                                            <span class="text-slate-700">ZiG Account Statement</span>
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <!-- Accept Button -->
                                <button type="button" class="accept-btn px-4 py-2 bg-emerald-600 text-white rounded-lg text-[11px] font-semibold hover:bg-emerald-700 transition-colors whitespace-nowrap" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>" data-mandatory="true">
                                    <i class="fa-solid fa-check mr-1"></i>Accept
                                </button>

                                <!-- Reject Button -->
                                <button type="button" class="reject-btn px-4 py-2 bg-red-600 text-white rounded-lg text-[11px] font-semibold hover:bg-red-700 transition-colors whitespace-nowrap" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>" data-mandatory="true">
                                    <i class="fa-solid fa-times mr-1"></i>Reject
                                </button>
                            </div>
                        </div>

                        <!-- Status Indicator -->
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <div class="status-indicator" data-key="<?php echo e($key); ?>">
                                <span class="text-[11px] text-slate-500">Status: <span class="font-semibold text-slate-700 status-text">Pending</span></span>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="compliance_decisions[<?php echo e($key); ?>][status]" class="status-input" value="">
                        <input type="hidden" name="compliance_decisions[<?php echo e($key); ?>][reason]" class="reason-input" value="">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Conditional Mandatory Documents (only if activated) -->
                    <?php if(count($mandatoryConditional) > 0): ?>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 my-4">
                        <p class="text-[12px] text-orange-900 font-semibold">
                            <i class="fa-solid fa-flag mr-2"></i>Activated Conditional Mandatory Documents
                        </p>
                        <?php if($incident->is_workplace_fatality): ?>
                            <p class="text-[11px] text-orange-800 mt-1"><i class="fa-solid fa-skull"></i> Workplace Fatality - Death-related documents required</p>
                        <?php endif; ?>
                        <?php if($incident->is_road_traffic_accident): ?>
                            <p class="text-[11px] text-orange-800 mt-1"><i class="fa-solid fa-car-crash"></i> Road Traffic Accident - Police report required</p>
                        <?php endif; ?>
                    </div>
                    <?php $__currentLoopData = $mandatoryConditional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $docCount++; ?>
                    <div class="bg-white border border-orange-300 rounded-lg p-5 hover:shadow-md transition-shadow border-l-4 border-l-orange-500">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-orange-100 rounded-full text-[11px] font-bold text-orange-700">
                                        <?php echo e($docCount); ?>

                                    </span>
                                    <h3 class="text-[13px] font-semibold text-navy"><?php echo e($label); ?></h3>
                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded text-[10px] font-bold">CONDITIONAL MANDATORY</span>
                                </div>
                                <p class="text-[11px] text-slate-500">Required due to incident classification</p>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <!-- Accept Button -->
                                <button type="button" class="accept-btn px-4 py-2 bg-emerald-600 text-white rounded-lg text-[11px] font-semibold hover:bg-emerald-700 transition-colors whitespace-nowrap" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>" data-mandatory="true">
                                    <i class="fa-solid fa-check mr-1"></i>Accept
                                </button>

                                <!-- Reject Button -->
                                <button type="button" class="reject-btn px-4 py-2 bg-red-600 text-white rounded-lg text-[11px] font-semibold hover:bg-red-700 transition-colors whitespace-nowrap" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>" data-mandatory="true">
                                    <i class="fa-solid fa-times mr-1"></i>Reject
                                </button>
                            </div>
                        </div>

                        <!-- Status Indicator -->
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <div class="status-indicator" data-key="<?php echo e($key); ?>">
                                <span class="text-[11px] text-slate-500">Status: <span class="font-semibold text-slate-700 status-text">Pending</span></span>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="compliance_decisions[<?php echo e($key); ?>][status]" class="status-input" value="">
                        <input type="hidden" name="compliance_decisions[<?php echo e($key); ?>][reason]" class="reason-input" value="">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Optional/Conditional Documents Section -->
            <div class="mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-[12px] text-blue-900 font-semibold">
                        <i class="fa-solid fa-info-circle mr-2"></i>Optional/Conditional Documents
                    </p>
                    <p class="text-[11px] text-blue-800">These documents are reviewed as supporting evidence. Conditional documents only appear here if not activated by incident flags.</p>
                </div>

                <div class="space-y-3">
                    <?php 
                        // Show conditional documents that are NOT mandatory
                        $optionalConditional = array_diff_key($conditionalDocuments, $mandatoryConditional);
                    ?>
                    
                    <?php if(count($optionalConditional) > 0): ?>
                        <?php $__currentLoopData = $optionalConditional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-slate-200 rounded-lg p-5 hover:shadow-md transition-shadow opacity-75">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fa-solid fa-file text-slate-400"></i>
                                        <h3 class="text-[13px] font-semibold text-slate-700"><?php echo e($label); ?></h3>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-medium">CONDITIONAL</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500">May be required based on incident classification</p>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button type="button" class="accept-opt-btn px-4 py-2 bg-slate-300 text-slate-700 rounded-lg text-[11px] font-semibold hover:bg-slate-400 transition-colors" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>">
                                        <i class="fa-solid fa-check mr-1"></i>Accept
                                    </button>
                                    <button type="button" class="reject-opt-btn px-4 py-2 bg-slate-300 text-slate-700 rounded-lg text-[11px] font-semibold hover:bg-slate-400 transition-colors" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>">
                                        <i class="fa-solid fa-times mr-1"></i>Reject
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-slate-100">
                                <div class="opt-status-indicator" data-key="<?php echo e($key); ?>">
                                    <span class="text-[11px] text-slate-500">Status: <span class="font-semibold text-slate-700 opt-status-text">Not Reviewed</span></span>
                                </div>
                            </div>

                            <input type="hidden" name="optional_decisions[<?php echo e($key); ?>][status]" class="opt-status-input" value="">
                            <input type="hidden" name="optional_decisions[<?php echo e($key); ?>][reason]" class="opt-reason-input" value="">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <!-- Additional Optional Documents -->
                    <?php $__currentLoopData = $optionalDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white border border-slate-200 rounded-lg p-5 hover:shadow-md transition-shadow opacity-75">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-file text-slate-400"></i>
                                    <h3 class="text-[13px] font-semibold text-slate-700"><?php echo e($label); ?></h3>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-medium">OPTIONAL</span>
                                </div>
                                <p class="text-[11px] text-slate-500">Supporting documentation</p>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" class="accept-opt-btn px-4 py-2 bg-slate-300 text-slate-700 rounded-lg text-[11px] font-semibold hover:bg-slate-400 transition-colors" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>">
                                    <i class="fa-solid fa-check mr-1"></i>Accept
                                </button>
                                <button type="button" class="reject-opt-btn px-4 py-2 bg-slate-300 text-slate-700 rounded-lg text-[11px] font-semibold hover:bg-slate-400 transition-colors" data-key="<?php echo e($key); ?>" data-label="<?php echo e($label); ?>">
                                    <i class="fa-solid fa-times mr-1"></i>Reject
                                </button>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <div class="opt-status-indicator" data-key="<?php echo e($key); ?>">
                                <span class="text-[11px] text-slate-500">Status: <span class="font-semibold text-slate-700 opt-status-text">Not Reviewed</span></span>
                            </div>
                        </div>

                        <input type="hidden" name="optional_decisions[<?php echo e($key); ?>][status]" class="opt-status-input" value="">
                        <input type="hidden" name="optional_decisions[<?php echo e($key); ?>][reason]" class="opt-reason-input" value="">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="button" id="proceedBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg text-[13px] font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2 opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-arrow-right"></i>Proceed to Administrative Dispatch
                </button>
                <a href="<?php echo e(route('incidents.hr_dossier', $incident)); ?>" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg text-[13px] font-semibold hover:bg-slate-50 transition-colors">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectionModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4">
        <h2 class="text-[16px] font-bold text-navy mb-2">Correction Required</h2>
        <p class="text-[12px] text-slate-600 mb-4">Please provide a reason for returning: <span id="rejectingLabel" class="font-semibold"></span></p>

        <form id="rejectionForm">
            <textarea id="rejectionReason" class="w-full border border-slate-300 rounded-lg p-3 text-[12px] focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe what corrections are needed..." rows="4" maxlength="500"></textarea>
            <p class="text-[10px] text-slate-500 mt-1"><span id="charCount">0</span>/500 characters</p>

            <div class="flex gap-2 mt-4">
                <button type="button" id="confirmRejectBtn" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-[12px] font-semibold hover:bg-red-700">
                    Confirm Rejection
                </button>
                <button type="button" id="cancelRejectBtn" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-[12px] font-semibold hover:bg-slate-50">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Document Viewer Modal -->
<div id="documentViewerModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-slate-50 border-b border-slate-200 p-4 flex items-center justify-between sticky top-0">
            <h2 class="text-[16px] font-bold text-navy" id="docViewerTitle">Document Viewer</h2>
            <button type="button" id="closeViewerBtn" class="text-slate-600 hover:text-slate-900 text-2xl">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <!-- PDF Viewer -->
            <div id="pdfViewer" class="hidden">
                <iframe id="pdfFrame" class="w-full" style="height: 500px; border: 1px solid #ccc; border-radius: 4px;"></iframe>
            </div>

            <!-- Image Viewer -->
            <div id="imageViewer" class="hidden text-center">
                <img id="imageFrame" src="" alt="Document" class="max-w-full max-h-[500px] mx-auto rounded-lg shadow-sm">
            </div>

            <!-- Document Preview unavailable -->
            <div id="unsupportedViewer" class="hidden text-center py-12">
                <i class="fa-solid fa-file text-6xl text-slate-300 mb-4 block"></i>
                <p class="text-[14px] text-slate-600 mb-4">Preview not available for this file type.</p>
                <a id="downloadBtn" href="#" download class="px-4 py-2 bg-blue-600 text-white rounded-lg text-[12px] font-semibold hover:bg-blue-700">
                    <i class="fa-solid fa-download mr-2"></i>Download File
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    let currentRejectKey = null;
    let currentRejectLabel = null;
    let complianceStatus = {};
    const totalMandatoryDocuments = <?php echo e(count($mandatoryDocuments) + count($mandatoryConditional)); ?>;

    // Initialize all mandatory documents as pending
    <?php $__currentLoopData = $mandatoryDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        complianceStatus['<?php echo e($key); ?>'] = 'PENDING';
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__currentLoopData = $mandatoryConditional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        complianceStatus['<?php echo e($key); ?>'] = 'PENDING';
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    document.addEventListener('DOMContentLoaded', function() {
        // Document viewer functionality
        const viewerModal = document.getElementById('documentViewerModal');
        const closeViewerBtn = document.getElementById('closeViewerBtn');
        const viewDocBtns = document.querySelectorAll('.view-doc-btn');

        viewDocBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const fileUrl = this.dataset.file;
                const fileName = this.dataset.filename;
                openDocumentViewer(fileUrl, fileName);
            });
        });

        closeViewerBtn.addEventListener('click', function() {
            viewerModal.classList.add('hidden');
            document.getElementById('pdfViewer').classList.add('hidden');
            document.getElementById('imageViewer').classList.add('hidden');
            document.getElementById('unsupportedViewer').classList.add('hidden');
        });

        // Close modal on background click
        viewerModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                document.getElementById('pdfViewer').classList.add('hidden');
                document.getElementById('imageViewer').classList.add('hidden');
                document.getElementById('unsupportedViewer').classList.add('hidden');
            }
        });

        // Handle flags form submission
        const flagsForm = document.getElementById('flagsForm');
        if (flagsForm) {
            flagsForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading indicator
                const submitBtn = flagsForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Updating...';
                
                // Submit form
                fetch(flagsForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(new FormData(flagsForm)),
                }).then(response => {
                    // Reload the page to reflect updated documents
                    window.location.reload();
                }).catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    alert('An error occurred while updating the classification.');
                });
            });
        }

        // Accept buttons for mandatory documents - with event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.accept-btn')) {
                console.log('Accept button clicked via delegation!');
                e.preventDefault();
                e.stopPropagation();
                
                const btn = e.target.closest('.accept-btn');
                const key = btn.dataset.key;
                const label = btn.dataset.label;
                
                console.log('Processing accept:', { key, label });
                
                if (key) {
                    complianceStatus[key] = 'ACCEPTED';
                    updateDocumentUI(key, 'ACCEPTED');
                    updateFinalizeButton();
                    console.log('Document accepted:', key, 'Status:', complianceStatus);
                }
            }
        }, true);

        // Reject buttons for mandatory documents - with event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.reject-btn')) {
                console.log('Reject button clicked via delegation!');
                e.preventDefault();
                e.stopPropagation();
                
                const btn = e.target.closest('.reject-btn');
                const key = btn.dataset.key;
                const label = btn.dataset.label;
                
                console.log('Processing reject:', { key, label });
                
                if (key) {
                    currentRejectKey = key;
                    currentRejectLabel = label;
                    const rejectingLabel = document.getElementById('rejectingLabel');
                    if (rejectingLabel) rejectingLabel.textContent = label;
                    
                    const rejectionReason = document.getElementById('rejectionReason');
                    if (rejectionReason) rejectionReason.value = '';
                    
                    const charCount = document.getElementById('charCount');
                    if (charCount) charCount.textContent = '0';
                    
                    const modal = document.getElementById('rejectionModal');
                    if (modal) modal.classList.remove('hidden');
                }
            }
        }, true);

        // Character count for rejection reason
        document.getElementById('rejectionReason').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Confirm rejection
        document.getElementById('confirmRejectBtn').addEventListener('click', function() {
            const reason = document.getElementById('rejectionReason').value.trim();
            
            if (!reason) {
                alert('Please provide a reason for rejection');
                return;
            }

            complianceStatus[currentRejectKey] = 'REJECTED';
            updateDocumentUI(currentRejectKey, 'REJECTED', reason);
            document.getElementById('rejectionModal').classList.add('hidden');
            updateFinalizeButton();
        });

        // Cancel rejection
        document.getElementById('cancelRejectBtn').addEventListener('click', function() {
            document.getElementById('rejectionModal').classList.add('hidden');
            currentRejectKey = null;
            currentRejectLabel = null;
        });

        // Handle form submission
        document.getElementById('complianceForm').addEventListener('submit', function(e) {
            const allAccepted = Object.values(complianceStatus).every(status => status === 'ACCEPTED');
            if (!allAccepted) {
                e.preventDefault();
                alert('All mandatory documents must be marked as Accepted before finalizing.');
                return;
            }
        });

        // Initialize finalize button state
        updateFinalizeButton();

        // Handle proceed button click
        const proceedBtn = document.getElementById('proceedBtn');
        if (proceedBtn) {
            proceedBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const allAccepted = Object.values(complianceStatus).every(status => status === 'ACCEPTED');
                console.log('Button clicked - All accepted:', allAccepted, 'Status:', complianceStatus);
                
                if (!allAccepted) {
                    alert('All mandatory documents must be marked as Accepted before proceeding to administrative dispatch.');
                    return false;
                }
                
                // All documents accepted, navigate to administrative dispatch
                console.log('Navigating to administrative dispatch...');
                window.location.href = '<?php echo e(route("incidents.administrative_dispatch", $incident)); ?>';
            });
        }
    });

    function openDocumentViewer(fileUrl, fileName) {
        const viewerModal = document.getElementById('documentViewerModal');
        const docViewerTitle = document.getElementById('docViewerTitle');
        const pdfViewer = document.getElementById('pdfViewer');
        const imageViewer = document.getElementById('imageViewer');
        const unsupportedViewer = document.getElementById('unsupportedViewer');
        
        // Hide all viewers
        pdfViewer.classList.add('hidden');
        imageViewer.classList.add('hidden');
        unsupportedViewer.classList.add('hidden');

        docViewerTitle.textContent = fileName;

        // Determine file type from extension
        const ext = fileName.split('.').pop().toLowerCase();
        const pdfExtensions = ['pdf'];
        const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        if (pdfExtensions.includes(ext)) {
            // PDF viewer
            document.getElementById('pdfFrame').src = fileUrl;
            pdfViewer.classList.remove('hidden');
        } else if (imageExtensions.includes(ext)) {
            // Image viewer
            document.getElementById('imageFrame').src = fileUrl;
            imageViewer.classList.remove('hidden');
        } else {
            // Unsupported type
            document.getElementById('downloadBtn').href = fileUrl;
            unsupportedViewer.classList.remove('hidden');
        }

        viewerModal.classList.remove('hidden');
    }

    function updateDocumentUI(key, status, reason = null) {
        console.log('updateDocumentUI called:', { key, status, reason });
        
        const statusInput = document.querySelector(`input[name="compliance_decisions[${key}][status]"]`);
        if (!statusInput) {
            console.error('Status input not found for key:', key);
            return;
        }
        
        const form = statusInput.closest('.bg-white');
        if (!form) {
            console.error('Form container not found for key:', key);
            return;
        }
        
        const reasonInput = form.querySelector('.reason-input');
        const statusIndicator = form.querySelector('.status-indicator');

        if (statusInput) statusInput.value = status;
        if (reason && reasonInput) reasonInput.value = reason;

        // Update visual feedback
        if (statusIndicator) {
            if (status === 'ACCEPTED') {
                statusIndicator.innerHTML = '<span class="text-[11px] text-emerald-700 font-semibold"><i class="fa-solid fa-check mr-1"></i>Accepted</span>';
                statusIndicator.className = 'status-indicator p-2 bg-emerald-50 rounded border border-emerald-200';
            } else if (status === 'REJECTED') {
                statusIndicator.innerHTML = `<div class="text-[11px] text-red-700 font-semibold"><i class="fa-solid fa-times mr-1"></i>Rejected</div><div class="text-[10px] text-red-600 mt-1">Reason: ${reason}</div>`;
                statusIndicator.className = 'status-indicator p-2 bg-red-50 rounded border border-red-200';
            }
        }
        
        console.log('Document UI updated successfully for key:', key);
    }

    function updateFinalizeButton() {
        const allAccepted = Object.values(complianceStatus).every(status => status === 'ACCEPTED');
        const proceedBtn = document.getElementById('proceedBtn');
        console.log('Button update check:', 'Total docs:', Object.keys(complianceStatus).length, 'Status:', complianceStatus, 'All accepted:', allAccepted);
        if (proceedBtn) {
            if (allAccepted) {
                // Enable button - all documents accepted
                proceedBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                proceedBtn.classList.add('hover:bg-blue-700', 'cursor-pointer');
                proceedBtn.style.pointerEvents = 'auto';
                console.log('Button ENABLED - Ready to proceed');
            } else {
                // Disable button - waiting for document acceptance
                proceedBtn.classList.add('opacity-50', 'cursor-not-allowed');
                proceedBtn.classList.remove('hover:bg-blue-700', 'cursor-pointer');
                proceedBtn.style.pointerEvents = 'none';
                console.log('Button remains DISABLED - waiting for all documents to be accepted');
            }
        }
    }

    function validateAllMandatory() {
        const allAccepted = Object.values(complianceStatus).every(status => status === 'ACCEPTED');
        if (!allAccepted) {
            alert('All mandatory documents must be marked as Accepted before proceeding.');
            return false;
        }
        return true;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\incidents\hr_document_verification.blade.php ENDPATH**/ ?>