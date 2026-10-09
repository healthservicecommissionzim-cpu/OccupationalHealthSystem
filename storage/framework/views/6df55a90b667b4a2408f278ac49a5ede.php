

<?php $__env->startSection('title', 'Leave Management Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                    Leave Management Dashboard
                </h1>
                <p class="text-slate-600 mt-2">Monitor sick leave cases and HSB 3/2017 compliance</p>
            </div>
            <a href="<?php echo e(route('dashboards.hr')); ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to HR Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Active</p>
                    <p class="text-2xl font-bold text-slate-800 mt-2"><?php echo e($stats['total_active']); ?></p>
                </div>
                <i class="fas fa-users text-blue-500 text-3xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Full Pay (0-90d)</p>
                    <p class="text-2xl font-bold text-green-700 mt-2"><?php echo e($stats['full_pay_count']); ?></p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Half Pay (91-180d)</p>
                    <p class="text-2xl font-bold text-orange-700 mt-2"><?php echo e($stats['half_pay_count']); ?></p>
                </div>
                <i class="fas fa-exclamation-circle text-orange-500 text-3xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Discharge Eligible</p>
                    <p class="text-2xl font-bold text-red-700 mt-2"><?php echo e($stats['discharge_eligible_count']); ?></p>
                </div>
                <i class="fas fa-alert-circle text-red-500 text-3xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Approaching 90d</p>
                    <p class="text-2xl font-bold text-amber-700 mt-2"><?php echo e($stats['approaching_90']); ?></p>
                </div>
                <i class="fas fa-hourglass-end text-amber-500 text-3xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="<?php echo e(route('leave.dashboard')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Reference or worker name..." 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Facility</label>
                    <select name="facility_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Facilities</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">District</label>
                    <select name="district_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Districts</option>
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="<?php echo e(route('leave.dashboard')); ?>" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Cases Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Reference</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Worker</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Facility</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Days Elapsed</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Payment Stage</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $daysElapsed = $incident->days_elapsed ?? 0;
                        $rowClass = '';
                        if ($daysElapsed >= 180) $rowClass = 'bg-red-50';
                        elseif ($daysElapsed >= 90) $rowClass = 'bg-yellow-50';
                    ?>
                    <tr class="<?php echo e($rowClass); ?> hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-800"><?php echo e($incident->reference_number); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-700"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-700"><?php echo e($incident->facility?->name); ?></td>
                        <td class="px-6 py-4 text-center text-sm font-semibold text-slate-800">
                            <?php echo e($daysElapsed); ?> days
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if($incident->leave_payment_stage === 'FULL_PAY'): ?>
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Full Pay</span>
                            <?php elseif($incident->leave_payment_stage === 'HALF_PAY'): ?>
                                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">Half Pay</span>
                            <?php else: ?>
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if($incident->status === 'REFERRED_TO_MEDICAL_BOARD'): ?>
                                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">For Review</span>
                            <?php elseif($incident->status === 'MEDICAL_DISCHARGE_ELIGIBLE'): ?>
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Discharge Eligible</span>
                            <?php else: ?>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Active</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <a href="<?php echo e(route('incidents.show', $incident->id)); ?>" 
                                class="text-blue-600 hover:text-blue-700 font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-600">
                            <i class="fas fa-inbox text-3xl opacity-50 mb-3 block"></i>
                            No active leave cases found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($incidents->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($incidents->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\leave\dashboard.blade.php ENDPATH**/ ?>