

<?php $__env->startSection('page_title', 'Supervisor Dashboard'); ?>

<?php $__env->startSection('header_title', 'Supervisor Dashboard'); ?>
<?php $__env->startSection('header_subtitle', 'Incident Oversight & Management'); ?>

<?php $__env->startSection('before_styles'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('sidebar_content'); ?>
<!-- OVERVIEW SECTION -->
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Overview</div>
<a href="<?php echo e(route('dashboards.supervisor')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_incidents')): ?>
<a href="<?php echo e(route('supervisor.all_incidents')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-triangle-exclamation w-5 text-center text-slate-700 text-sm"></i>
    <span>All Incidents</span>
</a>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create_incidents')): ?>
<a href="<?php echo e(route('incidents.create')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-clipboard w-5 text-center text-slate-700 text-sm"></i>
    <span>Report Incident</span>
</a>
<?php endif; ?>

<a href="<?php echo e(route('supervisor.return_to_work_tracking')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-person-hiking w-5 text-center text-slate-700 text-sm"></i>
    <span>Return-to-Work Tracking</span>
</a>

<a href="<?php echo e(route('supervisor.monthly_summary')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-calendar w-5 text-center text-slate-700 text-sm"></i>
    <span>Monthly Summary</span>
</a>

<!-- IT Support -->
<a href="<?php echo e(route('support.create_ticket')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<!-- My Support Tickets -->
<a href="<?php echo e(route('support.my_tickets')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-ticket w-5 text-center text-slate-700 text-sm"></i>
    <span>My Support Tickets</span>
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<!-- KPI Cards -->
<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-brand"></i> INCIDENT STATISTICS
        </h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-white border border-slate-200 rounded-lg text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    <span>
                        <?php if($dateRange === '7days'): ?>
                            Last 7 days
                        <?php elseif($dateRange === '90days'): ?>
                            Last 90 days
                        <?php elseif($dateRange === 'all'): ?>
                            All time
                        <?php else: ?>
                            Last 30 days
                        <?php endif; ?>
                    </span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-xs md:text-sm"></i>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 md:w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-10">
                    <a href="<?php echo e(route('dashboards.supervisor', ['date_range' => '7days'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 border-b border-slate-100 <?php echo e($dateRange === '7days' ? 'bg-slate-50' : ''); ?>">
                        <i class="fa-solid fa-check text-brand <?php echo e($dateRange === '7days' ? '' : 'opacity-0'); ?>"></i>
                        <span>Last 7 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.supervisor', ['date_range' => '30days'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 border-b border-slate-100 <?php echo e($dateRange === '30days' ? 'bg-slate-50' : ''); ?>">
                        <i class="fa-solid fa-check text-brand <?php echo e($dateRange === '30days' ? '' : 'opacity-0'); ?>"></i>
                        <span>Last 30 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.supervisor', ['date_range' => '90days'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 border-b border-slate-100 <?php echo e($dateRange === '90days' ? 'bg-slate-50' : ''); ?>">
                        <i class="fa-solid fa-check text-brand <?php echo e($dateRange === '90days' ? '' : 'opacity-0'); ?>"></i>
                        <span>Last 90 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.supervisor', ['date_range' => 'all'])); ?>" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-slate-600 hover:bg-slate-50 <?php echo e($dateRange === 'all' ? 'bg-slate-50' : ''); ?>">
                        <i class="fa-solid fa-check text-brand <?php echo e($dateRange === 'all' ? '' : 'opacity-0'); ?>"></i>
                        <span>All time</span>
                    </a>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_reports')): ?>
            <button class="flex items-center justify-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-brand text-white rounded-lg text-xs md:text-sm font-medium hover:bg-brand-dark transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-xs md:text-sm"></i>
                <span class="hidden sm:inline">Export Report</span>
                <span class="sm:hidden">Export</span>
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <!-- Total Incidents Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
            <div class="h-0.5 md:h-1 bg-brand"></div>
            <div class="p-4 md:p-5 flex items-start justify-between gap-4">
                <div class="flex-1">
                    <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Total Incidents</span>
                    <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2"><?php echo e(number_format($statistics['total_incidents'])); ?></p>
                    <p class="text-xs md:text-sm text-slate-500 mt-2">All assigned cases</p>
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

        <!-- Pending Verification Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="pending">
            <div class="h-0.5 md:h-1 bg-brand"></div>
            <div class="p-4 md:p-5 flex items-start justify-between gap-4">
                <div class="flex-1">
                    <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Pending Review</span>
                    <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2"><?php echo e(number_format($statistics['pending_verification'])); ?></p>
                    <p class="text-xs md:text-sm text-slate-500 mt-2">Awaiting action</p>
                </div>
                <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-circle'); ?>
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

        <!-- Verified & Escalated Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="verified">
            <div class="h-0.5 md:h-1 bg-brand"></div>
            <div class="p-4 md:p-5 flex items-start justify-between gap-4">
                <div class="flex-1">
                    <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Verified & Escalated</span>
                    <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2"><?php echo e(number_format($statistics['verified_escalated'])); ?></p>
                    <p class="text-xs md:text-sm text-slate-500 mt-2">Processed cases</p>
                </div>
                <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 md:w-7 md:h-7 text-emerald-600','stroke-width' => '1.5']); ?>
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

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Incidents by Type</h3>
        <div id="incidentTypeChart" style="height: 220px;"></div>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">First Aid Recording Rate</h3>
        <div id="firstAidChart" style="height: 220px;"></div>
    </div>
</div>

<!-- Assigned Cases - Pending Review Card -->
<?php if($recentIncidents->count() > 0): ?>
<?php
    $totalCount = $recentIncidents->count();
?>
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 md:p-6 mb-6" x-data="{ expanded: false }">
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
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700 hidden lg:table-cell">Incident Type</th>
                    <th class="text-left py-3 px-2 md:px-4 font-semibold text-slate-700">Date</th>
                    <th class="text-center py-3 px-2 md:px-4 font-semibold text-slate-700">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentIncidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $displayCount = $loop->iteration;
                    ?>
                    <tr x-show="expanded || <?php echo e($displayCount); ?> <= 3" class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm font-medium text-slate-900"><?php echo e($incident->reference_number); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-700 hidden sm:table-cell"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-700 hidden md:table-cell"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-700 hidden lg:table-cell"><?php echo e($incident->incidentType?->name ?? 'Unknown'); ?></td>
                        <td class="py-3 px-2 md:px-4 text-xs md:text-sm text-slate-600"><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                        <td class="py-3 px-2 md:px-4 text-center">
                            <a href="<?php echo e(route('incidents.supervisor_view', ['incident' => $incident])); ?>" class="inline-flex items-center gap-1 px-2 md:px-3 py-1 md:py-1.5 bg-brand text-white rounded text-xs md:text-sm font-medium hover:bg-brand-dark transition-colors">
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
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Chart rendering code
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare data for incident types chart
        const incidentTypeArray = <?php echo json_encode($incidentsByType, 15, 512) ?>;
        console.log('Raw Incident Type Array:', incidentTypeArray);
        
        const typeLabels = incidentTypeArray.map(item => item.name || 'Unknown');
        const typeData = incidentTypeArray.map(item => parseInt(item.count, 10));
        
        console.log('Type Labels:', typeLabels);
        console.log('Type Data:', typeData);

        // Incident Types Bar Chart (Horizontal)
        if (document.getElementById('incidentTypeChart') && typeLabels.length > 0) {
            // Calculate dynamic height based on number of bars
            const chartHeight = Math.max(200, typeLabels.length * 20 + 30);
            
            // For horizontal bar charts, we need to use x,y format
            const formattedData = typeLabels.map((label, index) => ({
                x: label,
                y: typeData[index]
            }));
            
            const incidentTypeOptions = {
                series: [{
                    name: 'Incidents',
                    data: formattedData
                }],
                chart: {
                    type: 'bar',
                    height: chartHeight,
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
                        horizontal: true,
                        borderRadius: 6,
                        barHeight: '70%',
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
                    type: 'numeric',
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 500
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    }
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
                        type: 'horizontal',
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
                            return val + ' incidents';
                        }
                    }
                }
            };

            const incidentTypeChart = new ApexCharts(document.querySelector("#incidentTypeChart"), incidentTypeOptions);
            incidentTypeChart.render();
        }

        // Prepare data for first aid recording rate
        const firstAidRecordedPercentage = <?php echo e($firstAidStats['recorded_percentage']); ?>;
        const firstAidNotRecordedPercentage = 100 - firstAidRecordedPercentage;

        // First Aid Recording Rate Gauge Chart
        if (document.getElementById('firstAidChart')) {
            const firstAidOptions = {
                series: [firstAidRecordedPercentage],
                chart: {
                    type: 'radialBar',
                    height: 220,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '60%'
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: '13px',
                                fontWeight: 600,
                                color: '#64748b',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                fontWeight: 700,
                                color: '#0f172a',
                                offsetY: 5,
                                formatter: function(val) {
                                    return Math.round(val) + '%';
                                }
                            },
                            total: {
                                show: true,
                                label: 'Recording Rate',
                                fontSize: '12px',
                                fontWeight: 600,
                                color: '#64748b'
                            }
                        },
                        track: {
                            background: '#e2e8f0',
                            strokeWidth: '8px'
                        },
                        startAngle: -90,
                        endAngle: 90
                    }
                },
                colors: ['#0d7a3d'],
                labels: ['First Aid Recorded'],
                stroke: {
                    lineCap: 'round'
                }
            };

            const firstAidChart = new ApexCharts(document.querySelector("#firstAidChart"), firstAidOptions);
            firstAidChart.render();
        }
    });
</script>
<?php $__env->stopSection(); ?>



<!-- Real-Time Dashboard Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = '<?php echo e($dateRange); ?>';
    const apiUrl = "<?php echo e(route('api.supervisor_dashboard_data')); ?>?date_range=" + dateRange;
    const pollInterval = 30000; // 30 seconds
    let pollIntervalId = null;

    // Add last updated timestamp display
    const statsSection = document.querySelector('[data-dashboard-stats]') || 
                        document.querySelector('.grid.grid-cols-1');
    if (statsSection) {
        const lastUpdatedDiv = document.createElement('div');
        lastUpdatedDiv.id = 'lastUpdated';
        lastUpdatedDiv.className = 'text-right text-[11px] text-slate-500 mt-2';
        lastUpdatedDiv.innerHTML = '<i class="fa-solid fa-sync text-[10px]"></i> Auto-refreshing every 30s...';
        statsSection.parentNode.insertBefore(lastUpdatedDiv, statsSection.nextSibling);
    }

    // Function to update dashboard with new data
    function updateDashboard(data) {
        if (!data.success) return;

        // Update statistics cards
        const stats = data.statistics;
        const cardSelectors = [
            { selector: '[data-stat="total"]', value: stats.total_incidents },
            { selector: '[data-stat="pending"]', value: stats.pending_verification },
            { selector: '[data-stat="verified"]', value: stats.verified_escalated },
            { selector: '[data-stat="rejected"]', value: stats.rejected },
            { selector: '[data-stat="in_progress"]', value: stats.in_progress },
            { selector: '[data-stat="resolved"]', value: stats.resolved },
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

        // Update first aid stats
        const firstAidPercent = data.first_aid_stats.recorded_percentage;
        const firstAidElement = document.querySelector('[data-stat="first-aid"]');
        if (firstAidElement) {
            const percentElement = firstAidElement.querySelector('p:first-of-type');
            if (percentElement) {
                percentElement.textContent = firstAidPercent + '%';
            }
        }

        // Update compliance stats
        const complianceElement = document.querySelector('[data-stat="compliant"]');
        if (complianceElement) {
            const percentElement = complianceElement.querySelector('p:first-of-type');
            if (percentElement) {
                const compliancePercent = data.compliance_stats.compliant > 0 
                    ? Math.round((data.compliance_stats.compliant / (data.compliance_stats.compliant + data.compliance_stats.partial_compliant + data.compliance_stats.noncompliant)) * 100)
                    : 0;
                percentElement.textContent = compliancePercent + '%';
            }
        }

        // Update notifications badge
        const notificationsBadge = document.querySelector('[data-notifications-count]');
        if (notificationsBadge) {
            notificationsBadge.textContent = data.unread_notifications;
        }

        // Update timestamp
        const lastUpdated = document.querySelector('#lastUpdated');
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
            console.error('Error fetching dashboard data:', error);
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
            fetchDashboardData(); // Fetch fresh data immediately
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', stopPolling);
});
</script>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\supervisor.blade.php ENDPATH**/ ?>