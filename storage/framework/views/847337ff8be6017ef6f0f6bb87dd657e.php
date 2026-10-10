

<?php
    $userRole = auth()->user()->getPrimaryRole()->name ?? null;
    $isDMO = $userRole === 'dmo';
    $isMedicalSuper = $userRole === 'medical_superintendent';
    $isCMO = $userRole === 'cmo';
    
    $pageTitle = match($userRole) {
        'dmo' => 'District Medical Officer Review Panel',
        'medical_superintendent' => 'Medical Superintendent Review Panel',
        'cmo' => 'Chief Medical Officer Review Panel',
        default => 'Clinical Executive Review Panel'
    };
    
    $pageSubtitle = match($userRole) {
        'dmo' => 'District-Level Medical Case Assessment & Escalation',
        'medical_superintendent' => 'Institutional Medical Review & Authorization',
        'cmo' => 'Chief Medical Officer Case Review & Ministry Escalation',
        default => 'Medical Case Assessment & Authorization'
    };
?>

<?php $__env->startSection('page_title', $pageTitle); ?>

<?php $__env->startSection('header_title', $pageTitle); ?>
<?php $__env->startSection('header_subtitle', $pageSubtitle); ?>

<?php $__env->startSection('sidebar_content'); ?>
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Back to Main Dashboard</span>
</a>

<?php if(auth()->user()->hasRole('dmo')): ?>
<!-- DMO-Specific Navigation -->
<div class="px-5 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dashboards</div>

<!-- All Cases -->
<a href="<?php echo e(route('dashboards.dmo.medical_review', ['filter' => 'all'])); ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-stethoscope w-5 text-center text-slate-700 text-sm"></i>
    <span>Medical Review - All Cases</span>
</a>

<!-- Reports & Analytics Dropdown -->
<div class="mx-2" x-data="{ open: false }">
    <button @click="open = !open" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
        <i class="fa-solid fa-chart-bar w-5 text-center text-slate-700 text-sm"></i>
        <span>Reports & Analytics</span>
        <i class="fa-solid fa-chevron-down w-4 text-slate-400 ml-auto text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="pl-4 space-y-1 mt-1">
        <a href="<?php echo e(route('dashboards.dmo.reports_analytics')); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-chart-line w-4 text-center text-slate-600 text-xs"></i>
            <span>Operational Reports</span>
        </a>
        <a href="<?php echo e(route('dashboards.dmo.reports_analytics', ['view' => 'injury_analysis'])); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-microscope w-4 text-center text-slate-600 text-xs"></i>
            <span>Injury Analysis</span>
        </a>
        <a href="<?php echo e(route('dashboards.dmo.reports_analytics', ['view' => 'complex_cases'])); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-hourglass-end w-4 text-center text-slate-600 text-xs"></i>
            <span>Complex Cases</span>
        </a>
    </div>
</div>

<!-- Compliance Dropdown -->
<div class="mx-2" x-data="{ open: false }">
    <button @click="open = !open" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
        <i class="fa-solid fa-shield-check w-5 text-center text-slate-700 text-sm"></i>
        <span>Compliance</span>
        <i class="fa-solid fa-chevron-down w-4 text-slate-400 ml-auto text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="pl-4 space-y-1 mt-1">
        <a href="<?php echo e(route('dashboards.dmo.compliance')); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-flag-checkered w-4 text-center text-slate-600 text-xs"></i>
            <span>Statutory Deadlines</span>
        </a>
        <a href="<?php echo e(route('dashboards.dmo.compliance', ['view' => 'medical_reports'])); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-file-medical w-4 text-center text-slate-600 text-xs"></i>
            <span>Medical Reports</span>
        </a>
        <a href="<?php echo e(route('dashboards.dmo.compliance', ['view' => 'clearance'])); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-file-circle-check w-4 text-center text-slate-600 text-xs"></i>
            <span>Clearance Status</span>
        </a>
    </div>
</div>

<!-- Safety Analytics Dropdown -->
<div class="mx-2" x-data="{ open: false }">
    <button @click="open = !open" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
        <i class="fa-solid fa-fire w-5 text-center text-slate-700 text-sm"></i>
        <span>Safety Analytics</span>
        <i class="fa-solid fa-chevron-down w-4 text-slate-400 ml-auto text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="pl-4 space-y-1 mt-1">
        <a href="<?php echo e(route('dashboards.dmo.safety_analytics')); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-chart-area w-4 text-center text-slate-600 text-xs"></i>
            <span>LTIFR & Trends</span>
        </a>
        <a href="<?php echo e(route('dashboards.dmo.safety_analytics', ['view' => 'injury_distribution'])); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-map w-4 text-center text-slate-600 text-xs"></i>
            <span>Injury Distribution</span>
        </a>
        <a href="<?php echo e(route('dashboards.dmo.safety_analytics', ['view' => 'shift_analysis'])); ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-clock w-4 text-center text-slate-600 text-xs"></i>
            <span>Shift Analysis</span>
        </a>
    </div>
</div>

<?php elseif($isCMO): ?>
<!-- CMO-Specific Navigation -->
<div class="px-5 pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Case Management</div>

<!-- Pending Cases -->
<a href="<?php echo e(route('dashboards.cmo.pending_cases')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-inbox w-5 text-center text-slate-700 text-sm"></i>
    <span>Pending Cases</span>
</a>

<!-- All Cases -->
<a href="<?php echo e(route('dashboards.cmo.all_cases')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-list-check w-5 text-center text-slate-700 text-sm"></i>
    <span>All Cases</span>
</a>

<!-- Case Analytics Dropdown -->
<div class="mx-2" x-data="{ open: false }">
    <button @click="open = !open" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
        <i class="fa-solid fa-chart-bar w-5 text-center text-slate-700 text-sm"></i>
        <span>Case Analytics</span>
        <i class="fa-solid fa-chevron-down w-4 text-slate-400 ml-auto text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="pl-4 space-y-1 mt-1">
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-chart-pie w-4 text-center text-slate-600 text-xs"></i>
            <span>By Facility</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-chart-line w-4 text-center text-slate-600 text-xs"></i>
            <span>By Injury Type</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-400 transition-all font-medium text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-slate-600 text-xs"></i>
            <span>By Department</span>
        </a>
    </div>
</div>

<!-- Decision Summary -->
<a href="#" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-clipboard-check w-5 text-center text-slate-700 text-sm"></i>
    <span>Decision Summary</span>
</a>

<!-- Ministry Escalations -->
<a href="#" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-arrow-up-right w-5 text-center text-slate-700 text-sm"></i>
    <span>Ministry Escalations</span>
</a>

<!-- IT Support -->
<a href="<?php echo e(route('support.create_ticket')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<!-- My Support Tickets -->
<a href="<?php echo e(route('support.my_tickets')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-emerald-600 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-ticket w-5 text-center text-slate-700 text-sm"></i>
    <span>My Support Tickets</span>
</a>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
   


<!-- KPI Cards -->
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
        <h2 class="text-[13px] font-bold text-navy">Performance Metrics</h2>
        <div class="flex items-center gap-2">
            <div class="relative" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    <span id="dateRangeLabel">All time</span>
                    <i class="fa-solid fa-chevron-down text-slate-400 ml-1 text-xs"></i>
                </button>
                <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-10">
                    <a href="<?php echo e(route('dashboards.clinical_executive', ['date_range' => '7days'])); ?>" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-600 hover:bg-slate-50 border-b border-slate-100 transition-colors">
                        <i class="fa-solid fa-check text-brand opacity-0" id="check7days"></i>
                        <span>Last 7 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.clinical_executive', ['date_range' => '30days'])); ?>" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-600 hover:bg-slate-50 border-b border-slate-100 transition-colors">
                        <i class="fa-solid fa-check text-brand opacity-0" id="check30days"></i>
                        <span>Last 30 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.clinical_executive', ['date_range' => '90days'])); ?>" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-600 hover:bg-slate-50 border-b border-slate-100 transition-colors">
                        <i class="fa-solid fa-check text-brand opacity-0" id="check90days"></i>
                        <span>Last 90 days</span>
                    </a>
                    <a href="<?php echo e(route('dashboards.clinical_executive', ['date_range' => 'all'])); ?>" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                        <i class="fa-solid fa-check text-brand opacity-0" id="checkAll"></i>
                        <span>All time</span>
                    </a>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_reports')): ?>
            <button class="flex items-center gap-2 px-3 py-1 bg-[#0d7a3d] text-white rounded-lg text-[11px] font-medium hover:bg-[#0a5f30] transition-colors shadow-sm">
                <i class="fa-solid fa-file-export text-xs"></i>Export Report
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4">
    <!-- Pending Review Card -->
    <?php if($isCMO): ?>
    <a href="<?php echo e(route('dashboards.cmo.pending_cases')); ?>" class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden cursor-pointer">
    <?php else: ?>
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
    <?php endif; ?>
        <div class="h-0.5 bg-brand"></div>
        <div class="p-3 flex items-start justify-between gap-3">
            <div class="flex-1">
                <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Pending Review</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['pending_review']); ?></p>
                <p class="text-[9px] text-slate-500 mt-1">Awaiting action</p>
            </div>
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-inbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-amber-600','stroke-width' => '1.5']); ?>
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
    <?php if($isCMO): ?>
    </a>
    <?php else: ?>
    </div>
    <?php endif; ?>

    <!-- Total Cases Card -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-3 flex items-start justify-between gap-3">
            <div class="flex-1">
                <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Total Cases</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['total_incidents']); ?></p>
                <p class="text-[9px] text-slate-500 mt-1"><?php echo e($filterScope); ?></p>
            </div>
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-stethoscope'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-blue-600','stroke-width' => '1.5']); ?>
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

    <!-- Compliance Risk Card (DMO Only) -->
    <?php if($isDMO): ?>
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-3 flex items-start justify-between gap-3">
            <div class="flex-1">
                <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Critical Risk</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['critical_risk'] ?? 0); ?></p>
                <p class="text-[9px] text-slate-500 mt-1">Statutory deadline risk</p>
            </div>
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-red-600','stroke-width' => '1.5']); ?>
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

    <!-- Escalated to PMD Card (DMO Only) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-3 flex items-start justify-between gap-3">
            <div class="flex-1">
                <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Escalated</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['completed_approvals'] ?? 0); ?></p>
                <p class="text-[9px] text-slate-500 mt-1">Cases escalated</p>
            </div>
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-emerald-600','stroke-width' => '1.5']); ?>
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
    <?php elseif($isCMO): ?>
    <!-- Escalated to Ministry Card (CMO Only) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-3 flex items-start justify-between gap-3">
            <div class="flex-1">
                <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Ministry</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['escalated_to_ministry'] ?? 0); ?></p>
                <p class="text-[9px] text-slate-500 mt-1">Ministry referrals</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-right-circle'); ?>
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

    <!-- Statutory Risk Card (CMO Only) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-3">
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-7 h-7 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-exclamation-triangle text-orange-600 text-xs"></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-700">Statutory Risk Cases</span>
            </div>
            <p class="text-xl font-bold text-orange-600 tracking-tight"><?php echo e($statistics['statutory_risk_cases'] ?? 0); ?></p>
            <p class="text-[9px] font-semibold text-orange-600 mt-1 flex items-center gap-0.5">
                <i class="fa-solid fa-clock text-xs"></i>Deadline risk cases
            </p>
        </div>
    </div>
    <?php else: ?>
    <!-- Active Units Card (Other Roles) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all cursor-default overflow-hidden">
        <div class="h-1 bg-emerald-700"></div>
        <div class="p-3">
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-hospital text-emerald-600 text-xs"></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-700">Active Units</span>
            </div>
            <p class="text-xl font-bold text-emerald-600 tracking-tight"><?php echo e(count($incidentsByUnit)); ?></p>
            <p class="text-[9px] font-semibold text-emerald-600 mt-1 flex items-center gap-0.5">
                <i class="fa-solid fa-location-dot text-xs"></i>Your scope
            </p>
        </div>
    </div>
    <?php endif; ?>
    </div>


</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-1 gap-3 md:gap-4 mt-6 md:mt-8">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 md:p-6 overflow-hidden">
        <div class="h-0.5 md:h-1 bg-emerald-700"></div>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-chart-bar text-blue-600 text-sm"></i>
                </div>
                <h3 class="text-[12px] font-bold text-navy">Cases Breakdown</h3>
            </div>
            <div class="flex items-center gap-2">
                <button id="toggleInjury" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    By Injury Type
                </button>
                <button id="toggleDept" class="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold hover:bg-slate-300 transition-colors shadow-sm">
                    By Department
                </button>
            </div>
        </div>

        <!-- Cases by Injury Type Chart (Vertical Bar) -->
        <div id="injuryChartContainer">
            <div id="injuryChart"></div>
        </div>

        <!-- Cases by Department Chart (Horizontal Bar) -->
        <div id="deptChartContainer" style="display: none;">
            <div id="deptChart"></div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set active date range indicator
        const params = new URLSearchParams(window.location.search);
        const dateRange = params.get('date_range') || 'all';
        
        const dateRangeLabels = {
            '7days': 'Last 7 days',
            '30days': 'Last 30 days',
            '90days': 'Last 90 days',
            'all': 'All time'
        };
        
        document.getElementById('dateRangeLabel').textContent = dateRangeLabels[dateRange];
        
        // Show checkmark for active range
        const checkboxIds = {
            '7days': 'check7days',
            '30days': 'check30days',
            '90days': 'check90days',
            'all': 'checkAll'
        };
        
        if (checkboxIds[dateRange]) {
            document.getElementById(checkboxIds[dateRange]).classList.remove('opacity-0');
        }
        
        // Units Chart using ApexCharts (Horizontal Bar)
        const injuryData = <?php echo json_encode($statistics['incidents_by_injury_type']->toArray() ?? [], 15, 512) ?>;
        const deptData = <?php echo json_encode($statistics['incidents_by_department']->toArray() ?? [], 15, 512) ?>;
        
        const injuryLabels = Object.keys(injuryData);
        const injuryValues = Object.values(injuryData).map(v => parseInt(v, 10));
        
        const deptLabels = Object.keys(deptData);
        const deptValues = Object.values(deptData).map(v => parseInt(v, 10));
        
        // Injury Type Chart Options (Vertical Bar) - First chart
        const injuryChartOptions = {
            series: [{
                name: 'Cases',
                data: injuryValues
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: injuryLabels,
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 600,
                        colors: '#64748b'
                    },
                    rotate: -45
                },
                axisBorder: {
                    show: true,
                    color: '#e2e8f0'
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Cases',
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        color: '#64748b'
                    }
                },
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#64748b'
                    },
                    formatter: function(val) {
                        if (val % 1 === 0) {
                            return val;
                        }
                        return '';
                    }
                },
                decimalsInFloat: 0,
                forceNiceScale: false
            },
            colors: ['#0d7a3d'],
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
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
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return Math.floor(val) + ' cases';
                    }
                }
            }
        };

        // Department Chart Options (Horizontal Bar)
        const deptChartOptions = {
            series: [{
                name: 'Cases',
                data: deptValues
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: deptLabels,
                type: 'category',
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#64748b'
                    },
                    formatter: function(val) {
                        if (val % 1 === 0) {
                            return val;
                        }
                        return '';
                    }
                },
                decimalsInFloat: 0,
                axisBorder: {
                    show: true,
                    color: '#e2e8f0'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#64748b'
                    }
                }
            },
            colors: ['#0d7a3d'],
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: false
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
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return Math.floor(val) + ' cases';
                    }
                }
            }
        };
        
        const injuryChart = new ApexCharts(document.querySelector("#injuryChart"), injuryChartOptions);
        injuryChart.render();

        const deptChart = new ApexCharts(document.querySelector("#deptChart"), deptChartOptions);
        deptChart.render();

        // Toggle functionality
        const toggleInjuryBtn = document.getElementById('toggleInjury');
        const toggleDeptBtn = document.getElementById('toggleDept');
        const injuryChartContainer = document.getElementById('injuryChartContainer');
        const deptChartContainer = document.getElementById('deptChartContainer');

        toggleInjuryBtn.addEventListener('click', function() {
            injuryChartContainer.style.display = 'block';
            deptChartContainer.style.display = 'none';
            toggleInjuryBtn.classList.remove('bg-slate-200', 'text-slate-700');
            toggleInjuryBtn.classList.add('bg-blue-600', 'text-white');
            toggleDeptBtn.classList.remove('bg-blue-600', 'text-white');
            toggleDeptBtn.classList.add('bg-slate-200', 'text-slate-700');
            injuryChart.forceRerender();
        });

        toggleDeptBtn.addEventListener('click', function() {
            injuryChartContainer.style.display = 'none';
            deptChartContainer.style.display = 'block';
            toggleDeptBtn.classList.remove('bg-slate-200', 'text-slate-700');
            toggleDeptBtn.classList.add('bg-blue-600', 'text-white');
            toggleInjuryBtn.classList.remove('bg-blue-600', 'text-white');
            toggleInjuryBtn.classList.add('bg-slate-200', 'text-slate-700');
            deptChart.forceRerender();
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views/dashboards/clinical_executive.blade.php ENDPATH**/ ?>