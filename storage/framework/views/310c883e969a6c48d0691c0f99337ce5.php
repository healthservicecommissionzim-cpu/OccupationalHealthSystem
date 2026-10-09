

<?php $__env->startSection('page_title', 'HSC Conditions Dashboard'); ?>

<?php $__env->startSection('header_title', 'HSC Conditions Officer'); ?>
<?php $__env->startSection('header_subtitle', 'Occupational Health & Safety Monitoring'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-shield-heart w-5 text-center text-slate-700 text-sm"></i>
            Safety Monitoring
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-slate-700"></i>
            <span>Facility Inspections</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-exclamation-triangle w-4 text-center text-slate-700"></i>
            <span>Risk Assessments</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-line w-4 text-center text-slate-700"></i>
            <span>Compliance Reports</span>
        </a>
    </div>
</div>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-hard-hat w-5 text-center text-slate-700 text-sm"></i>
            Incidents & Hazards
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-triangle-exclamation w-4 text-center text-slate-700"></i>
            <span>Hazard Reports</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-bar w-4 text-center text-slate-700"></i>
            <span>Incident Analysis</span>
        </a>
    </div>
</div>
<a href="<?php echo e(route('support.create_ticket')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<!-- My Support Tickets -->
<a href="<?php echo e(route('support.my_tickets')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-ticket w-5 text-center text-slate-700 text-sm"></i>
    <span>My Support Tickets</span>
</a>
<a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-red-600 hover:bg-red-50 border-l-[3px] border-transparent hover:border-red-300 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
    <span>Logout</span>
</a>
<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2">
    <div>
        <h1 class="text-[15px] font-bold text-navy tracking-wide">HSC CONDITIONS DASHBOARD</h1>
    </div>
</div>
<div class="h-px w-full bg-border mb-4"></div>

<!-- KPI Cards -->
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <h2 class="text-[15px] font-bold text-navy">Performance Metrics</h2>
        <div class="flex items-center gap-2">
            <button class="flex items-center gap-2 px-3.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fa-regular fa-calendar text-slate-400"></i>Last 30 days
                <i class="fa-solid fa-chevron-down text-slate-400 ml-1 text-xs"></i>
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_reports')): ?>
            <button class="flex items-center gap-2 px-3.5 py-1.5 bg-[#0d7a3d] text-white rounded-lg text-xs font-medium hover:bg-[#0a5f30] transition-colors shadow-sm">
                <i class="fa-solid fa-file-export text-xs"></i>Export Report
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Facilities Monitored</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">87</p>
                <p class="text-[10px] text-slate-500 mt-2">Active</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-building-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-blue-600','stroke-width' => '1.5']); ?>
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

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Risk Hazards</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">14</p>
                <p class="text-[10px] text-slate-500 mt-2">Identified</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-amber-600','stroke-width' => '1.5']); ?>
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

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Inspections Done</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">156</p>
                <p class="text-[10px] text-slate-500 mt-2">Compliance</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-emerald-600','stroke-width' => '1.5']); ?>
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

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Compliance Rate</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">82%</p>
                <p class="text-[10px] text-slate-500 mt-2">This month</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-trending-up'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-purple-600','stroke-width' => '1.5']); ?>
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
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Hazards by Category</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="hazardChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Facility Compliance Status</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="complianceChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Inspections -->
<div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6 mt-6 md:mt-8">
    <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Recent Inspections</h3>
    <div class="space-y-3">
        <div class="flex items-start gap-3 pb-3 border-b border-border">
            <div class="w-2 h-2 rounded-full bg-emerald-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-xs md:text-sm font-medium text-navy">Harare Central Hospital</p>
                <p class="text-xs md:text-sm text-slate-500">Inspection completed - Compliant</p>
                <p class="text-[10px] text-slate-400 mt-1"><?php echo e(now()->subDays(1)->diffForHumans()); ?></p>
            </div>
        </div>
        <div class="flex items-start gap-3 pb-3 border-b border-border">
            <div class="w-2 h-2 rounded-full bg-amber-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">Bulawayo Clinic</p>
                <p class="text-[11px] text-slate-500">Minor issues identified - Follow-up scheduled</p>
                <p class="text-[10px] text-slate-400 mt-1"><?php echo e(now()->subDays(3)->diffForHumans()); ?></p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-2 h-2 rounded-full bg-red-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">Mutare Health Center</p>
                <p class="text-[11px] text-slate-500">Critical hazards found - Urgent remediation required</p>
                <p class="text-[10px] text-slate-400 mt-1"><?php echo e(now()->subDays(5)->diffForHumans()); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hazards Chart
        const hazardChart = document.getElementById('hazardChart');
        if (hazardChart) {
            new Chart(hazardChart, {
                type: 'bar',
                data: {
                    labels: ['Chemical', 'Physical', 'Biological', 'Ergonomic', 'Psychological'],
                    datasets: [{
                        label: 'Hazards',
                        data: [5, 4, 2, 2, 1],
                        backgroundColor: '#0d7a3d',
                        borderColor: '#0a5f30',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } }
                }
            });
        }

        // Compliance Chart
        const compChart = document.getElementById('complianceChart');
        if (compChart) {
            new Chart(compChart, {
                type: 'doughnut',
                data: {
                    labels: ['Compliant', 'Minor Issues', 'Major Issues'],
                    datasets: [{
                        data: [71, 14, 2],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\hsc_conditions.blade.php ENDPATH**/ ?>