

<?php $__env->startSection('cos-content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Executive Dashboard</h2>
            <p class="text-gray-600 mt-1">Case metrics, SLA status, and performance overview</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_data')): ?>
            <form method="GET" action="<?php echo e(route('cos.export')); ?>" class="flex gap-2">
                <select name="format" class="px-3 py-2 border rounded">
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-download"></i> Export
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" class="flex gap-4 flex-wrap">
            <div class="flex-1 min-w-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">Case Type</label>
                <select name="case_type" class="w-full px-3 py-2 border rounded" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="injury" <?php echo e(request('case_type') === 'injury' ? 'selected' : ''); ?>>Injury</option>
                    <option value="illness" <?php echo e(request('case_type') === 'illness' ? 'selected' : ''); ?>>Illness</option>
                    <option value="hazard_exposure" <?php echo e(request('case_type') === 'hazard_exposure' ? 'selected' : ''); ?>>Hazard Exposure</option>
                    <option value="mental_health" <?php echo e(request('case_type') === 'mental_health' ? 'selected' : ''); ?>>Mental Health</option>
                </select>
            </div>

            <div class="flex-1 min-w-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                <select name="province_id" class="w-full px-3 py-2 border rounded" onchange="this.form.submit()">
                    <option value="">All Provinces</option>
                    <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($province->id); ?>" <?php echo e(request('province_id') == $province->id ? 'selected' : ''); ?>>
                            <?php echo e($province->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="flex-1 min-w-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">Facility</label>
                <select name="facility_id" class="w-full px-3 py-2 border rounded" onchange="this.form.submit()">
                    <option value="">All Facilities</option>
                    <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($facility->id); ?>" <?php echo e(request('facility_id') == $facility->id ? 'selected' : ''); ?>>
                            <?php echo e($facility->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <?php if(request()->anyFilled(['case_type', 'province_id', 'facility_id'])): ?>
                <div class="flex items-end">
                    <a href="<?php echo e(route('cos.index')); ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <?php $__currentLoopData = $metrics['by_stage']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="metric-card">
                <div class="metric-value text-blue-600"><?php echo e($data['count']); ?></div>
                <div class="metric-label"><?php echo e($data['stage']); ?></div>
                <div class="text-xs text-gray-500 mt-2"><?php echo e($data['percentage']); ?>% of total</div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Total Open Cases -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Total Open Cases</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($metrics['total_open']); ?></p>
            </div>
            <div class="text-4xl text-blue-200">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
    </div>

    <!-- TAT Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $tatMetrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $tat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($tat['data_available']): ?>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-900 mb-3"><?php echo e($tat['stage']); ?> TAT</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Average:</span>
                            <span class="font-semibold"><?php echo e($tat['average_days']); ?> days</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Min:</span>
                            <span><?php echo e($tat['min_hours']); ?> hrs</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Max:</span>
                            <span><?php echo e($tat['max_hours']); ?> hrs</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sample:</span>
                            <span><?php echo e($tat['sample_size']); ?> cases</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- SLA Breaches -->
    <?php if(count($breaches) > 0): ?>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold text-red-600 mb-4">
                <i class="fas fa-exclamation-circle"></i> SLA Breaches (<?php echo e(count($breaches)); ?>)
            </h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $breaches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breach): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="breach-alert">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-gray-900"><?php echo e($breach['reference']); ?></h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    <?php echo e($breach['worker_name']); ?> at <?php echo e($breach['facility']); ?>

                                </p>
                                <p class="text-sm text-red-600 font-semibold mt-1">
                                    <?php echo e($breach['days_overdue']); ?> days overdue (<?php echo e($breach['hours_at_stage']); ?> hours at <?php echo e($breach['current_stage']); ?>)
                                </p>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_reminder')): ?>
                                <form method="POST" action="<?php echo e(route('cos.case.send_nudge', $breach['case_id'])); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                                        <i class="fas fa-bell"></i> Send Nudge
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-green-50 border border-green-200 p-4 rounded text-center">
            <p class="text-green-700"><i class="fas fa-check-circle"></i> No SLA breaches detected</p>
        </div>
    <?php endif; ?>

    <!-- Performance Trends -->
    <?php if($trends): ?>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Performance Trends (Last 30 Days)</h3>
            <div class="text-center text-gray-500">
                <p>Chart visualization would display TAT trends over time</p>
                <p class="text-sm mt-2">Detailed trend data available via API: <?php echo e(count($trends['trends'])); ?> data points</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\executive\index.blade.php ENDPATH**/ ?>