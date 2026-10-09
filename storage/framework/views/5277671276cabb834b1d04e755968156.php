

<?php $__env->startSection('title', 'Compliance - DMO Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-8">
    <!-- Header with Date Range Filter -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Compliance</h1>
            <p class="text-gray-600 mt-1">Statutory deadline tracking and reporting</p>
        </div>
        
        <form method="GET" class="flex gap-2">
            <select name="date_range" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                <option value="all" <?php echo e($dateRange === 'all' ? 'selected' : ''); ?>>All Time</option>
                <option value="7days" <?php echo e($dateRange === '7days' ? 'selected' : ''); ?>>Last 7 Days</option>
                <option value="30days" <?php echo e($dateRange === '30days' ? 'selected' : ''); ?>>Last 30 Days</option>
                <option value="90days" <?php echo e($dateRange === '90days' ? 'selected' : ''); ?>>Last 90 Days</option>
            </select>
        </form>
    </div>

    <!-- Compliance KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Statutory Deadlines Met -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Deadlines Met</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($statistics['statutory_deadline_met']); ?></p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-green-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        <!-- At Risk -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">At Risk</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($statistics['statutory_deadline_at_risk']); ?></p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-yellow-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        <!-- First Medical Report -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Medical Reports</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($statistics['first_medical_report_submitted']); ?></p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-blue-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Final Report Clearance -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Closed Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($statistics['final_report_clearance']); ?></p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-purple-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Compliance Progress -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Compliance Status</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Statutory Compliance</span>
                        <?php
                            $total = $statistics['statutory_deadline_met'] + $statistics['statutory_deadline_at_risk'];
                            $percentage = $total > 0 ? round(($statistics['statutory_deadline_met'] / $total) * 100) : 0;
                        ?>
                        <span class="text-sm font-semibold text-gray-900"><?php echo e($percentage); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-emerald-600 h-3 rounded-full transition-all" style="width: <?php echo e($percentage); ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Medical Report Submission</span>
                        <?php
                            $reportPercentage = $statistics['first_medical_report_submitted'] > 0 ? 100 : 0;
                        ?>
                        <span class="text-sm font-semibold text-gray-900"><?php echo e($reportPercentage); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: <?php echo e($reportPercentage); ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Case Closure Rate</span>
                        <?php
                            $totalCases = count($allIncidents);
                            $closureRate = $totalCases > 0 ? round(($statistics['final_report_clearance'] / $totalCases) * 100) : 0;
                        ?>
                        <span class="text-sm font-semibold text-gray-900"><?php echo e($closureRate); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-purple-600 h-3 rounded-full transition-all" style="width: <?php echo e($closureRate); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance by Facility -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cases by Facility</h3>
            <?php if($complianceByFacility->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $complianceByFacility; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600"><?php echo e($facility); ?></span>
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: <?php echo e(($count / $complianceByFacility->max() * 100)); ?>%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 w-8 text-right"><?php echo e($count); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600">No facility data available</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cases List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cases Requiring Attention</h2>
        </div>

        <?php if($allIncidents->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Facility</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Reported</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Compliance Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $allIncidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-emerald-700"><?php echo e($incident->reference_number); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($incident->created_at->format('M d, Y H:i')); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                        $hoursElapsed = $incident->created_at->diffInHours(now());
                                        if ($hoursElapsed <= 48) {
                                            $status = 'Met';
                                            $badgeClass = 'bg-green-100 text-green-800';
                                        } elseif ($hoursElapsed <= 72) {
                                            $status = 'At Risk';
                                            $badgeClass = 'bg-yellow-100 text-yellow-800';
                                        } else {
                                            $status = 'Overdue';
                                            $badgeClass = 'bg-red-100 text-red-800';
                                        }
                                    ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo e($badgeClass); ?>"><?php echo e($status); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="px-6 py-12 text-center">
                <p class="text-gray-600">No cases to display</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\dmo\compliance.blade.php ENDPATH**/ ?>