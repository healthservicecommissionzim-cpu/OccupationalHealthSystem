

<?php $__env->startSection('title', 'View Staff Rosters'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    Staff Rosters
                </h1>
                <p class="text-slate-600 mt-2">
                    View rotational staff, locum doctors, and nursing students across facilities
                    <?php if($user->hasRole('district_hr')): ?>
                        <span class="text-sm">(<?php echo e($user->district->name ?? 'Your District'); ?>)</span>
                    <?php elseif($user->hasRole('hospital_hr')): ?>
                        <span class="text-sm">(<?php echo e($user->facility->name ?? 'Your Facility'); ?>)</span>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <a href="<?php echo e(url()->previous()); ?>" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Read-Only Notice -->
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
        <i class="fas fa-info-circle mr-2"></i>
        <span class="font-medium">Read-Only Access:</span> 
        You have view-only access to roster information. Contact your Super Admin to modify roster data.
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="<?php echo e(route('roster.view')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search Staff</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Staff name or email..." 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Staff Type</label>
                    <select name="staff_type" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="permanent" <?php echo e(request('staff_type') === 'permanent' ? 'selected' : ''); ?>>Permanent</option>
                        <option value="locum" <?php echo e(request('staff_type') === 'locum' ? 'selected' : ''); ?>>Locum</option>
                        <option value="student" <?php echo e(request('staff_type') === 'student' ? 'selected' : ''); ?>>Student</option>
                        <option value="rotational" <?php echo e(request('staff_type') === 'rotational' ? 'selected' : ''); ?>>Rotational</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Primary Facility</label>
                    <select name="facility_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->id); ?>" <?php echo e(request('facility_id') == $facility->id ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="active" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="1" <?php echo e(request('active') === '1' ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo e(request('active') === '0' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('roster.view')); ?>" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <?php if($rosters->total() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Total Staff</p>
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($rosters->total()); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Active Rosters</p>
                        <p class="text-2xl font-bold text-green-600">
                            <?php echo e($rosters->where('active', true)->count()); ?>

                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Rotational Staff</p>
                        <p class="text-2xl font-bold text-purple-600">
                            <?php echo e($rosters->where('staff_type', 'rotational')->count()); ?>

                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Total Assignments</p>
                        <p class="text-2xl font-bold text-orange-600">
                            <?php echo e($rosters->sum(function($roster) { return $roster->assignments->count(); })); ?>

                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Rosters Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Staff Member</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Staff Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Primary Facility</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Start Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">End Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Current Assignments</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php $__empty_1 = true; $__currentLoopData = $rosters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium"><?php echo e($roster->user->name); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($roster->user->email); ?></div>
                            <?php if($roster->user->phone): ?>
                                <div class="text-xs text-slate-500">
                                    <i class="fas fa-phone mr-1"></i><?php echo e($roster->user->phone); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full uppercase
                                <?php if($roster->staff_type === 'permanent'): ?> bg-blue-100 text-blue-800
                                <?php elseif($roster->staff_type === 'locum'): ?> bg-purple-100 text-purple-800
                                <?php elseif($roster->staff_type === 'student'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-orange-100 text-orange-800
                                <?php endif; ?>">
                                <?php echo e($roster->staff_type); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium"><?php echo e($roster->primaryFacility->name); ?></div>
                            <?php if($roster->primaryFacility->district): ?>
                                <div class="text-xs text-slate-500"><?php echo e($roster->primaryFacility->district->name); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <?php echo e($roster->start_date->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <?php echo e($roster->end_date ? $roster->end_date->format('M d, Y') : '—'); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if($roster->active): ?>
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            <?php else: ?>
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php
                                $activeAssignments = $roster->assignments->where('status', 'active');
                                $totalAssignments = $roster->assignments->count();
                            ?>
                            <?php if($activeAssignments->count() > 0): ?>
                                <button onclick="toggleAssignments(<?php echo e($roster->id); ?>)" 
                                    class="text-blue-600 hover:text-blue-700 font-medium cursor-pointer">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <?php echo e($activeAssignments->count()); ?> active
                                    <?php if($totalAssignments > $activeAssignments->count()): ?>
                                        <span class="text-slate-500">(<?php echo e($totalAssignments); ?> total)</span>
                                    <?php endif; ?>
                                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                </button>
                            <?php elseif($totalAssignments > 0): ?>
                                <span class="text-slate-500">
                                    <i class="fas fa-history mr-1"></i>
                                    <?php echo e($totalAssignments); ?> historical
                                </span>
                            <?php else: ?>
                                <span class="text-slate-400">No assignments</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- Expandable Assignments Row -->
                    <tr id="assignments-<?php echo e($roster->id); ?>" class="hidden bg-slate-50">
                        <td colspan="7" class="px-6 py-4">
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-clipboard-list mr-2"></i>Current Assignments
                                </h4>
                                <?php if($roster->assignments->count() > 0): ?>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $roster->assignments->sortByDesc('assignment_start_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="bg-white rounded-lg p-4 border border-slate-200">
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Facility</p>
                                                        <p class="text-sm font-medium text-slate-800">
                                                            <?php echo e($assignment->facility->name); ?>

                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Assignment Period</p>
                                                        <p class="text-sm text-slate-700">
                                                            <?php echo e($assignment->assignment_start_date->format('M d, Y')); ?> 
                                                            <i class="fas fa-arrow-right text-xs mx-1"></i> 
                                                            <?php echo e($assignment->assignment_end_date->format('M d, Y')); ?>

                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Assignment Type</p>
                                                        <p class="text-sm text-slate-700">
                                                            <span class="capitalize"><?php echo e(str_replace('_', ' ', $assignment->assignment_type)); ?></span>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Status</p>
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                                            <?php if($assignment->status === 'active'): ?> bg-green-100 text-green-800
                                                            <?php elseif($assignment->status === 'completed'): ?> bg-slate-100 text-slate-700
                                                            <?php else: ?> bg-red-100 text-red-800
                                                            <?php endif; ?>">
                                                            <?php echo e(ucfirst($assignment->status)); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <?php if($assignment->notes): ?>
                                                    <div class="mt-3 pt-3 border-t border-slate-200">
                                                        <p class="text-xs text-slate-500 mb-1">Notes</p>
                                                        <p class="text-sm text-slate-700"><?php echo e($assignment->notes); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-sm text-slate-500">No assignments found for this staff member.</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-600">
                            <i class="fas fa-inbox text-4xl opacity-50 mb-3 block"></i>
                            <p class="font-medium">No rosters found</p>
                            <p class="text-sm text-slate-500 mt-1">Try adjusting your filters or search criteria</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($rosters->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($rosters->links()); ?>

        </div>
    <?php endif; ?>
</div>

<script>
function toggleAssignments(rosterId) {
    const row = document.getElementById(`assignments-${rosterId}`);
    const icon = event.target.closest('button').querySelector('.fa-chevron-down, .fa-chevron-up');
    
    if (row.classList.contains('hidden')) {
        row.classList.remove('hidden');
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    } else {
        row.classList.add('hidden');
        if (icon) {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\roster\view.blade.php ENDPATH**/ ?>