<!DOCTYPE html>
<html lang="en">
<head>
    <script>window.FontAwesomeConfig = {autoReplaceSvg: 'nest'};</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('page_title'); ?> — Health Service Commission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#0d7a3d',
                            light: '#1bb74d',
                            dark: '#0a5f30',
                        },
                        navy: '#1e3a5f',
                        surface: '#f8fafc',
                        border: '#e8ecf1',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: Tahoma, sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <?php echo $__env->yieldContent('extra_styles'); ?>
</head>
<body class="bg-surface text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-brand-light selection:text-white">

<!-- Top Navigation Bar -->
<header class="h-20 bg-white border-b border-border flex items-center justify-between px-6 flex-shrink-0 z-10 shadow-sm sticky top-0">
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
            <img src="<?php echo e(asset('images/hsc-logo.png')); ?>" alt="HSC Logo" class="w-10 h-10 flex-shrink-0">
            <div class="leading-tight">
                <p class="text-[12px] font-bold text-navy leading-tight">Health Service</p>
                <p class="text-[12px] font-bold text-navy leading-tight">Commission</p>
            </div>
        </a>
        <div class="hidden sm:block border-l border-slate-200 pl-6">
            <p class="text-[14px] font-semibold text-navy"><?php echo $__env->yieldContent('page_title'); ?></p>
            <p class="text-[11px] text-slate-400 mt-0.5"><?php echo $__env->yieldContent('page_subtitle'); ?></p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200">
            <i class="fa-solid fa-calendar text-slate-400 text-xs"></i>
            <span class="text-[12px] font-medium text-slate-600"><?php echo e(now()->format('M d, Y')); ?></span>
        </div>
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 pl-2 border-l border-slate-200 hover:bg-slate-50 rounded px-2 py-1 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-brand-light flex items-center justify-center text-white font-semibold text-xs ring-2 ring-slate-100">
                    <?php
                        $fullName = Auth::user()->name;
                        $initials = implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', trim($fullName))));
                        echo strtoupper(substr($initials, 0, 2));
                    ?>
                </div>
                <div class="hidden md:block leading-tight text-left">
                    <p class="text-[13px] font-semibold text-navy"><?php echo e(Auth::user()->name); ?></p>
                    <p class="text-[10px] text-slate-400"><?php echo e(Auth::user()->roles->first()?->display_name ?? 'User'); ?></p>
                </div>
                <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] ml-1 transition-transform" :class="{ 'rotate-180': open }"></i>
            </button>
            <div x-show="open" @click="open = false" class="fixed inset-0 z-40"></div>
            <div x-show="open" class="absolute right-0 mt-2 w-56 bg-white rounded-lg border border-border shadow-lg z-50" x-transition>
                <div class="p-4 border-b border-border">
                    <p class="text-[13px] font-semibold text-navy"><?php echo e(Auth::user()->name); ?></p>
                    <p class="text-[11px] text-slate-500 mt-1"><?php echo e(Auth::user()->email); ?></p>
                </div>
                <div class="p-2 border-b border-border space-y-1">
                    <a href="<?php echo e(route('profile.show')); ?>" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition-colors">
                        <i class="fa-solid fa-user text-slate-600 w-4"></i>
                        <span>View Profile</span>
                    </a>
                    <a href="<?php echo e(route('support.create_ticket')); ?>" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition-colors">
                        <i class="fa-solid fa-circle-question text-slate-600 w-4"></i>
                        <span>Get IT Help</span>
                    </a>
                </div>
                <div class="p-2 border-t border-border">
                    <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?>
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket text-red-500 w-4"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="flex-1 overflow-y-auto p-6 lg:p-8">
    <div class="max-w-[1600px] mx-auto">
        <?php echo $__env->yieldContent('page_content'); ?>
        
        <!-- Footer -->
        <footer class="mt-12 pt-6 border-t border-border text-center">
            <p class="text-[12px] text-slate-400 font-medium">© 2026 Health Service Commission of Zimbabwe. All rights reserved.</p>
        </footer>
    </div>
</main>

<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\layouts\fullpage.blade.php ENDPATH**/ ?>