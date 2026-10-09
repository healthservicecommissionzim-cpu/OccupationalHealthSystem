

<?php $__env->startSection('page_title', 'HR Dashboard'); ?>

<?php $__env->startSection('header_title', 'HR Management'); ?>
<?php $__env->startSection('header_subtitle', 'Incident Review, Employee Oversight & HR Operations'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="<?php echo e(route('dashboards.hr')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>

<!-- All Incidents - Standalone Link -->
<a href="<?php echo e(route('incidents.hr_all')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-triangle-exclamation w-5 text-center text-slate-700 text-sm"></i>
    <span>All Incidents</span>
</a>

<!-- Pending Review - Standalone Link -->
<a href="<?php echo e(route('incidents.hr_pending_review')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-clock w-5 text-center text-slate-700 text-sm"></i>
    <span>Pending Review</span>
</a>

<!-- Reports Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-file-chart-line w-5 text-center text-slate-700 text-sm"></i>
            Reports
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="<?php echo e(route('hr.review_rate')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-percent w-4 text-center text-slate-700"></i>
            <span>Review Rate</span>
        </a>
        <a href="<?php echo e(route('hr.processing_time')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hourglass-end w-4 text-center text-slate-700"></i>
            <span>Processing Time</span>
        </a>
        <a href="<?php echo e(route('hr.compliance_metrics')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-pie w-4 text-center text-slate-700"></i>
            <span>Compliance Metrics</span>
        </a>
        <a href="<?php echo e(route('hr.monthly_summary')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-calendar-days w-4 text-center text-slate-700"></i>
            <span>Monthly Summary</span>
        </a>
        <a href="<?php echo e(route('hr.incident_facility')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-slate-700"></i>
            <span>Incident by Facility</span>
        </a>
        <a href="<?php echo e(route('hr.incident_department')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-slate-700"></i>
            <span>Incident by Department</span>
        </a>
        <a href="<?php echo e(route('hr.compliance_report')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-clipboard-check w-4 text-center text-slate-700"></i>
            <span>Compliance Report</span>
        </a>
        <a href="<?php echo e(route('hr.escalation_report')); ?>" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-arrow-up-right w-4 text-center text-slate-700"></i>
            <span>Escalation Report</span>
        </a>
    </div>
</div>

<!-- Leave Management -->
<a href="#" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-calendar-days w-5 text-center text-slate-700 text-sm"></i>
    <span>Leave Management</span>
</a>

<!-- IT Support -->
<a href="<?php echo e(route('support.create_ticket')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<!-- KPI Cards -->
<div>
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-navy">Performance Metrics</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-white border border-slate-200 rounded-lg text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    <span id="dateRangeLabel">All time</span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-xs md:text-sm"></i>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 md:w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-10">
                    <a href="<?php echo e(route('dashboards.hr', ['date_range' => '7days'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 border-b border-slate-100 transition-colors" id="range7days">
                        <i class="fa-solid fa-check text-brand opacity-0"></i>
                        <span>Last 7 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.hr', ['date_range' => '30days'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 border-b border-slate-100 transition-colors" id="range30days">
                        <i class="fa-solid fa-check text-brand opacity-0"></i>
                        <span>Last 30 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.hr', ['date_range' => '90days'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 border-b border-slate-100 transition-colors" id="range90days">
                        <i class="fa-solid fa-check text-brand opacity-0"></i>
                        <span>Last 90 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.hr', ['date_range' => 'all'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 transition-colors" id="rangeAll">
                        <i class="fa-solid fa-check text-brand"></i>
                        <span>All time</span>
                    </a>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_reports')): ?>
            <button class="flex items-center justify-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-[#0d7a3d] text-white rounded-lg text-xs md:text-sm font-medium hover:bg-[#0a5f30] transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-xs md:text-sm"></i>
                <span class="hidden sm:inline">Export Report</span>
                <span class="sm:hidden">Export</span>
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
    <!-- Total Cases -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Total Cases</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2"><?php echo e($statistics['total_cases'] ?? 0); ?></p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">HR-relevant cases</p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-briefcase'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 md:w-7 md:h-7 text-purple-600','stroke-width' => '1.5']); ?>
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

    <!-- Pending HR Review -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="pending">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Pending Review</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2"><?php echo e($statistics['pending_review'] ?? 0); ?></p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">Awaiting your action</p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-inbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 md:w-7 md:h-7 text-amber-600','stroke-width' => '1.5']); ?>
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

    <!-- In Workflow -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="active">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">In Workflow</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2"><?php echo e($statistics['active_incidents'] ?? 0); ?></p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">In progress</p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-right-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 md:w-7 md:h-7 text-blue-600','stroke-width' => '1.5']); ?>
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
</div>

<!-- Last Updated Timestamp -->
<div id="lastUpdatedHR" class="text-right text-xs md:text-sm text-slate-500 mt-6 md:mt-8">
    <i class="fa-solid fa-sync text-xs md:text-sm"></i> Auto-refreshing every 30s...
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 gap-4 md:gap-6 mt-6 md:mt-8">
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy flex items-center gap-1.5 md:gap-2">
                <i class="fa-solid fa-chart-column text-brand"></i>
                <span>Cases by Injury Type</span>
                <span class="text-slate-400 text-xs md:text-sm font-medium">[<span id="chartTotal"><?php echo e(array_sum($incidentsByInjuryType)); ?></span>]</span>
            </h3>
        </div>
        <div id="facilityChart" style="height: 280px;"></div>
    </div>
</div>

<!-- Assigned Cases - Pending Review Card -->
<?php if($pendingReviewIncidents->count() > 0): ?>
<?php
    $totalCount = $pendingReviewIncidents->count();
?>
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 md:p-6 mt-6 md:mt-8" x-data="{ expanded: false }">
    <div class="flex items-center justify-between mb-4 md:mb-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy">Assigned Cases - Pending Review <span class="text-slate-500">[<?php echo e($totalCount); ?>]</span></h3>
        <button @click="expanded = !expanded" class="px-2 md:px-3 py-1.5 md:py-2 text-xs md:text-sm font-medium text-brand hover:bg-brand/5 rounded-lg transition-colors">
            <span x-show="!expanded">View All</span>
            <span x-show="expanded">Show Less</span>
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-xs md:text-sm">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700">Reference</th>
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700 hidden sm:table-cell">Employee</th>
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700 hidden md:table-cell">Facility</th>
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700 hidden lg:table-cell">Department</th>
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700">Date</th>
                    <th class="text-center py-3 px-2 md:px-4 font-semibold text-slate-700">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pendingReviewIncidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $displayCount = $loop->iteration;
                    ?>
                    <tr x-show="expanded || <?php echo e($displayCount); ?> <= 3" class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm font-medium text-slate-900"><?php echo e($incident->reference_number); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-700 hidden sm:table-cell"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-700 hidden md:table-cell"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-700 hidden lg:table-cell"><?php echo e($incident->department?->name ?? 'N/A'); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-600"><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                        <td class="py-3 px-2 md:px-4 text-center">
                            <a href="<?php echo e(route('incidents.hr_dossier', ['incident' => $incident])); ?>" class="inline-flex items-center gap-1 px-2 md:px-3 py-1 md:py-1.5 bg-brand text-white rounded text-xs md:text-sm font-medium hover:bg-brand-dark transition-colors">
                                <i class="fa-solid fa-eye text-xs md:text-sm"></i>
                                <span class="hidden sm:inline">Review</span><span class="sm:hidden">View</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <?php if($totalCount > 3): ?>
    <div class="mt-3 text-center text-xs md:text-sm text-slate-500">
        Showing <span x-text="expanded ? '<?php echo e($totalCount); ?>' : '3'"></span> of <?php echo e($totalCount); ?> cases
    </div>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 md:p-6 mt-6 md:mt-8">
    <div class="text-center py-8 md:py-12">
        <i class="fa-solid fa-inbox text-slate-300 text-4xl md:text-5xl mb-4"></i>
        <p class="text-sm md:text-base text-slate-500">No incidents pending review</p>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    let facilityChartInstance = null;
    let injuryChartInstance = null;
    let departmentChartInstance = null;
    let currentChartType = 'facility';

    function createInjuryChart() {
        const injuryData = <?php echo json_encode($incidentsByInjuryType ?? [], 15, 512) ?>;
        const injuryLabels = Object.keys(injuryData).map(label => 
            label.length > 18 ? label.substring(0, 18) + '...' : label
        );
        const injuryValues = Object.values(injuryData);
        
        // Optimized height for compact card
        const calculatedHeight = 280;
        
        // Calculate max value and round up to nearest integer
        const maxValue = Math.max(...injuryValues, 0);
        const maxRounded = Math.ceil(maxValue) || 5;
        
        const injuryChartOptions = {
            series: [{
                name: 'Cases',
                data: injuryValues
            }],
            chart: {
                type: 'bar',
                height: calculatedHeight,
                sparkline: {
                    enabled: false
                },
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    columnWidth: '65%',
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#0d7a3d'],
            xaxis: {
                categories: injuryLabels,
                labels: {
                    style: {
                        fontSize: '9px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    maxHeight: 60,
                    offsetY: 2
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '9px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    },
                    offsetX: -8
                },
                min: 0,
                max: maxRounded,
                tickAmount: Math.min(5, maxRounded)
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 2,
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    left: 4,
                    right: 4,
                    top: 8,
                    bottom: 2
                }
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '12px'
                },
                y: {
                    formatter: function(val) {
                        return Math.floor(val) + ' case' + (Math.floor(val) !== 1 ? 's' : '');
                    }
                },
                marker: {
                    show: false
                }
            }
        };
        
        return new ApexCharts(document.querySelector("#facilityChart"), injuryChartOptions);
    }

    function createDepartmentChart() {
        const departmentData = <?php echo json_encode($incidentsByDepartment ?? [], 15, 512) ?>;
        const departmentLabels = Object.keys(departmentData).map(label => 
            label.length > 25 ? label.substring(0, 25) + '...' : label
        );
        const departmentValues = Object.values(departmentData);
        
        // Calculate height based on number of items - responsive but compact
        const itemCount = departmentValues.length;
        const calculatedHeight = Math.max(250, itemCount * 20 + 50);
        
        // Calculate max value and round up to nearest even number
        const maxValue = Math.max(...departmentValues, 0);
        const maxEven = Math.ceil(maxValue / 2) * 2 || 2;
        
        const departmentChartOptions = {
            series: [{
                name: 'Cases',
                data: departmentValues
            }],
            chart: {
                type: 'bar',
                height: calculatedHeight,
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 6,
                    columnWidth: '45%',
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false,
                offsetX: 30,
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: ['#1D4533']
                }
            },
            colors: ['#0d7a3d'],
            xaxis: {
                categories: departmentLabels,
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                min: 0,
                max: maxEven,
                tickAmount: maxEven
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    gradientToColors: ['#1bb74d'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + ' cases';
                    }
                }
            }
        };
        
        return new ApexCharts(document.querySelector("#facilityChart"), departmentChartOptions);
    }

    function updateChart(type) {
        if (facilityChartInstance) {
            facilityChartInstance.destroy();
            facilityChartInstance = null;
        }
        if (injuryChartInstance) {
            injuryChartInstance.destroy();
            injuryChartInstance = null;
        }
        if (departmentChartInstance) {
            departmentChartInstance.destroy();
            departmentChartInstance = null;
        }

        updateTotalAndLabel(type);

        if (type === 'injury') {
            injuryChartInstance = createInjuryChart();
            injuryChartInstance.render();
        } else if (type === 'department') {
            departmentChartInstance = createDepartmentChart();
            departmentChartInstance.render();
        }
    }

        document.addEventListener('DOMContentLoaded', function() {
        // Initialize with injury chart only
        const injuryData = <?php echo json_encode($incidentsByInjuryType ?? [], 15, 512) ?>;
        const total = Object.values(injuryData).reduce((sum, val) => sum + val, 0);
        document.getElementById('chartTotal').textContent = total;
        
        // Incident Status Donut Chart
        const statusData = <?php echo json_encode($incidentsByStatus, 15, 512) ?>;
        const statusLabels = Object.keys(statusData).map(status => 
            status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
        );
        const statusValues = Object.values(statusData);
        
        const statusChartOptions = {
            series: statusValues,
            chart: {
                type: 'donut',
                height: 200,
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000
                }
            },
            labels: statusLabels,
            colors: ['#0d7a3d', '#1bb74d', '#0f8650', '#16a34a', '#22c55e', '#84cc16', '#a3e635', '#bfdbfe'],
            dataLabels: {
                enabled: false,
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex];
                },
                style: {
                    fontSize: '14px',
                    fontWeight: 700,
                    colors: ['#fff']
                },
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 1,
                    opacity: 0.5
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#334155'
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#0f172a',
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Cases',
                                fontSize: '13px',
                                fontWeight: 600,
                                color: '#64748b',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontWeight: 500,
                labels: {
                    colors: '#334155'
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 2
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['#fff']
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + ' cases';
                    }
                }
            }
        };
        
        const statusChart = new ApexCharts(document.querySelector("#incidentStatusChart"), statusChartOptions);
        statusChart.render();

        // Initialize injury chart as default
        injuryChartInstance = createInjuryChart();
        injuryChartInstance.render();

        // Highlight the current date range
        const urlParams = new URLSearchParams(window.location.search);
        const dateRange = urlParams.get('date_range') || 'all';
        
        // Map date range to button element and label
        const dateRangeMap = {
            '7days': { btn: 'range7days', label: 'Last 7 days' },
            '30days': { btn: 'range30days', label: 'Last 30 days' },
            '90days': { btn: 'range90days', label: 'Last 90 days' },
            'all': { btn: 'rangeAll', label: 'All time' }
        };
        
        const currentRange = dateRangeMap[dateRange] || dateRangeMap['all'];
        document.getElementById('dateRangeLabel').textContent = currentRange.label;
        
        // Highlight selected button
        Object.values(dateRangeMap).forEach(item => {
            document.getElementById(item.btn).classList.remove('bg-slate-50');
            document.getElementById(item.btn).querySelector('i').classList.add('opacity-0');
        });
        
        document.getElementById(currentRange.btn).classList.add('bg-slate-50');
        document.getElementById(currentRange.btn).querySelector('i').classList.remove('opacity-0');
    });
</script>

<!-- Real-Time Dashboard Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = new URLSearchParams(window.location.search).get('date_range') || 'all';
    const apiUrl = "<?php echo e(route('api.hr_dashboard_data')); ?>?date_range=" + dateRange;
    const pollInterval = 30000; // 30 seconds
    let pollIntervalId = null;

    // Function to update dashboard with new data
    function updateDashboard(data) {
        if (!data.success) return;

        const stats = data.statistics;
        
        // Update KPI cards
        const cardSelectors = [
            { selector: '[data-stat="pending"]', value: stats.pending_review },
            { selector: '[data-stat="active"]', value: stats.active_incidents },
            { selector: '[data-stat="resolved"]', value: stats.resolved_incidents },
            { selector: '[data-stat="rejected"]', value: stats.rejected_incidents },
            { selector: '[data-stat="total"]', value: stats.total_cases },
        ];

        cardSelectors.forEach(({ selector, value }) => {
            const element = document.querySelector(selector);
            if (element) {
                const numberElement = element.querySelector('p:first-of-type') || element.querySelector('p');
                if (numberElement) {
                    numberElement.textContent = value.toLocaleString();
                }
            }
        });

        // Update timestamp
        const lastUpdated = document.querySelector('#lastUpdatedHR');
        if (lastUpdated) {
            lastUpdated.innerHTML = '<i class="fa-solid fa-sync-alt text-[10px] animate-spin"></i> Last updated: ' + data.timestamp;
        }
    }

    // Function to fetch fresh data
    function fetchDashboardData() {
        fetch(apiUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            updateDashboard(data);
        })
        .catch(error => {
            console.error('Error fetching HR dashboard data:', error);
        });
    }

    // Start polling
    function startPolling() {
        pollIntervalId = setInterval(fetchDashboardData, pollInterval);
    }

    // Stop polling
    function stopPolling() {
        if (pollIntervalId) {
            clearInterval(pollIntervalId);
            pollIntervalId = null;
        }
    }

    // Initial fetch
    fetchDashboardData();

    // Start polling when page is visible
    startPolling();

    // Pause polling when page is hidden, resume when visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopPolling();
        } else {
            startPolling();
            fetchDashboardData();
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', stopPolling);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\hr.blade.php ENDPATH**/ ?>