

<?php $__env->startSection('page_title', 'Document Verification · HSC'); ?>

<?php $__env->startSection('header_title', 'Document Verification'); ?>
<?php $__env->startSection('header_subtitle', 'Verify document completeness and upload status'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-file-check text-brand"></i> DOCUMENT VERIFICATION
    </h2>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Documents</p>
        <p class="text-3xl font-bold text-slate-900 mt-1"><?php echo e($documentStats['total_documents']); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Verified</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1"><?php echo e($documentStats['verified_documents']); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Pending</p>
        <p class="text-3xl font-bold text-amber-600 mt-1"><?php echo e($documentStats['pending_documents']); ?></p>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Cases with Attachments</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Case Reference</th>
                    <th class="text-center py-2 px-3">Document Count</th>
                    <th class="text-center py-2 px-3">Verified</th>
                    <th class="text-center py-2 px-3">Pending</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $incidentsWithAttachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $verified = $incident->attachments->where('upload_status', 'VERIFIED')->count();
                        $pending = $incident->attachments->where('upload_status', 'PENDING')->count();
                    ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand"><?php echo e($incident->reference_number); ?></td>
                        <td class="py-2 px-3 text-center font-bold"><?php echo e($incident->attachments->count()); ?></td>
                        <td class="py-2 px-3 text-center text-emerald-600 font-bold"><?php echo e($verified); ?></td>
                        <td class="py-2 px-3 text-center text-amber-600 font-bold"><?php echo e($pending); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No documents found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\quality\audit-documents.blade.php ENDPATH**/ ?>