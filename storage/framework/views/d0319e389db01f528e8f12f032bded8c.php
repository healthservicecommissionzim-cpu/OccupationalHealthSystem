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

<!-- Main Content (no header) -->
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
<?php /**PATH D:\OccupationalHealth System\resources\views/layouts/minimal.blade.php ENDPATH**/ ?>