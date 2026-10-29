

<?php $__env->startSection('page_title', 'Pending PMD Cases'); ?>

<?php $__env->startSection('header_title', 'Pending Cases'); ?>
<?php $__env->startSection('header_subtitle', 'All cases awaiting PMD approval'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>
<a href="<?php echo e(route('dashboards.pmd')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-line w-5 text-center text-slate-700 text-sm"></i>
    <span>PMD Dashboard</span>
</a>
<a href="<?php echo e(route('pmd.pending_cases')); ?>" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-list-check w-5 text-center text-slate-700 text-sm"></i>
    <span>All Pending Cases</span>
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
        <h1 class="text-[15px] font-bold text-navy tracking-wide">PENDING PMD APPROVAL CASES</h1>
        <p class="text-[12px] text-slate-500 mt-1">Complete list of all cases awaiting your review and approval</p>
    </div>
</div>
<div class="h-px w-full bg-border mb-4"></div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Pending</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['total_pending']); ?></p>
                <p class="text-xs text-slate-500 mt-0.5">Awaiting Review</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-inbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-amber-600']); ?>
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
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Escalated to PMD</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['escalated_to_pmd']); ?></p>
                <p class="text-xs text-slate-500 mt-0.5">Ready for Review</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-right-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-blue-600']); ?>
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
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Pending Review</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5"><?php echo e($statistics['pending_pmd_review']); ?></p>
                <p class="text-xs text-slate-500 mt-0.5">In Review</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-emerald-600']); ?>
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

<!-- Cases Table -->
<div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6 mt-6 md:mt-8">
    <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">All Pending Cases</h3>
    <?php if($pendingCases->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm">
                <thead>
                    <tr class="border-b border-border text-slate-500">
                        <th class="text-left py-3 px-2 md:px-4 font-semibold">Reference</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden sm:table-cell">Employee</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden md:table-cell">Facility</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden lg:table-cell">District</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden xl:table-cell">Incident Type</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold">Date Received</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden sm:table-cell">Status</th>
                        <th class="text-center py-3 px-2 md:px-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pendingCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-border hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-2 md:px-4">
                                <span class="font-medium text-brand text-xs md:text-sm"><?php echo e($incident->reference_number); ?></span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden sm:table-cell">
                                <span class="text-slate-700 text-xs md:text-sm"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden md:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm"><?php echo e($incident->facility->name ?? 'N/A'); ?></span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden lg:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm"><?php echo e($incident->facility->district->name ?? 'N/A'); ?></span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden xl:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm"><?php echo e($incident->incidentType->name ?? 'N/A'); ?></span>
                            </td>
                            <td class="py-3 px-2 md:px-4">
                                <span class="text-slate-500 text-xs md:text-sm"><?php echo e($incident->created_at->format('M d, Y')); ?></span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden sm:table-cell">
                                <?php if($incident->status === 'ESCALATED_TO_PMD'): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs md:text-sm font-semibold bg-blue-100 text-blue-700">
                                        <i class="fa-solid fa-arrow-up text-xs md:text-sm mr-1"></i>
                                        Escalated
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs md:text-sm font-semibold bg-amber-100 text-amber-700">
                                        <i class="fa-solid fa-hourglass text-xs md:text-sm mr-1"></i>
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center">
                                <a href="<?php echo e(route('pmd.review_case', $incident)); ?>" class="inline-flex items-center gap-1 px-2 md:px-3 py-1 md:py-1.5 bg-brand text-white rounded text-xs md:text-sm font-medium hover:bg-brand-dark transition-colors">
                                    <i class="fa-solid fa-eye text-xs md:text-sm"></i>
                                    <span class="hidden sm:inline">Review</span><span class="sm:hidden">View</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($pendingCases->hasPages()): ?>
            <div class="mt-6 pt-4 border-t border-border">
                <div class="flex items-center justify-between">
                    <p class="text-xs md:text-sm text-slate-600">
                        Showing <span class="font-medium"><?php echo e($pendingCases->firstItem()); ?></span> to <span class="font-medium"><?php echo e($pendingCases->lastItem()); ?></span> of <span class="font-medium"><?php echo e($pendingCases->total()); ?></span> cases
                    </p>
                    <div class="flex gap-1">
                        <?php if($pendingCases->onFirstPage()): ?>
                            <button disabled class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-400 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                        <?php else: ?>
                            <a href="<?php echo e($pendingCases->previousPageUrl()); ?>" class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php $__currentLoopData = $pendingCases->getUrlRange(1, $pendingCases->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $pendingCases->currentPage()): ?>
                                <button disabled class="px-2 md:px-3 py-1 md:py-1.5 rounded bg-brand text-white text-xs md:text-sm font-medium"><?php echo e($page); ?></button>
                            <?php else: ?>
                                <a href="<?php echo e($url); ?>" class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors"><?php echo e($page); ?></a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($pendingCases->hasMorePages()): ?>
                            <a href="<?php echo e($pendingCases->nextPageUrl()); ?>" class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <button disabled class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-400 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="text-center py-8 md:py-12 text-slate-400">
            <i class="fa-solid fa-inbox text-4xl md:text-5xl mb-3 md:mb-4"></i>
            <p class="text-sm md:text-base font-medium">No pending cases</p>
            <p class="text-xs md:text-sm mt-1">All cases have been reviewed. Great work!</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\pmd_pending_cases.blade.php ENDPATH**/ ?>