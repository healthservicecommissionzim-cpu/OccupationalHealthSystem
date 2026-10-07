<!DOCTYPE html>
<html lang="en">
<head>
    <script>window.FontAwesomeConfig = {autoReplaceSvg: 'nest'};</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title') — Health Service Commission</title>
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
        body { display: flex; flex-direction: row; }
        #sidebar { position: fixed; left: 0; top: 0; width: 270px; height: 100vh; flex-shrink: 0; z-index: 20; }
        #mainContent { flex: 1; display: flex; flex-direction: column; height: 100vh; margin-left: 270px; transition: margin-left 0.3s ease; }
        #mainContent.collapsed { margin-left: 0; }
        @media (max-width: 768px) { #mainContent { margin-left: 0; } }
        #header { flex-shrink: 0; }
        @media (min-width: 768px) { #mainContent { margin-left: 270px; } #mainContent.collapsed { margin-left: 0; } }
        @media (max-width: 768px) { #mainContent { margin-left: 0; } }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                if (overlay) overlay.classList.remove('hidden');
                localStorage.setItem('sidebarCollapsed', 'false');
                if (window.innerWidth >= 768) { mainContent.classList.remove('collapsed'); }
            } else {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                localStorage.setItem('sidebarCollapsed', 'true');
                mainContent.classList.add('collapsed');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const isDesktop = window.innerWidth >= 768;
            const savedState = localStorage.getItem('sidebarCollapsed');
            let shouldBeCollapsed;
            if (savedState !== null) {
                shouldBeCollapsed = savedState === 'true';
            } else {
                shouldBeCollapsed = !isDesktop;
            }
            if (shouldBeCollapsed) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                mainContent.classList.add('collapsed');
            } else {
                sidebar.classList.remove('-translate-x-full');
                if (isDesktop) { 
                    mainContent.classList.remove('collapsed');
                    if (overlay) overlay.classList.add('hidden');
                } else { 
                    mainContent.classList.add('collapsed');
                    if (overlay) overlay.classList.remove('hidden');
                }
            }
            const sidebarLinks = document.querySelectorAll('#sidebar a, #sidebar button');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.add('-translate-x-full');
                        if (overlay) overlay.classList.add('hidden');
                        localStorage.setItem('sidebarCollapsed', 'true');
                        mainContent.classList.add('collapsed');
                    }
                });
            });
        });
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const isDesktop = window.innerWidth >= 768;
            const savedState = localStorage.getItem('sidebarCollapsed');
            const shouldBeCollapsed = savedState !== null ? savedState === 'true' : !isDesktop;
            if (shouldBeCollapsed) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                mainContent.classList.add('collapsed');
            } else {
                sidebar.classList.remove('-translate-x-full');
                if (isDesktop) { 
                    mainContent.classList.remove('collapsed');
                    if (overlay) overlay.classList.add('hidden');
                } else { 
                    mainContent.classList.add('collapsed');
                    if (overlay) overlay.classList.remove('hidden');
                }
            }
        });
    </script>
    @yield('extra_styles')
</head>
<body class="bg-surface text-slate-800 font-sans antialiased min-h-screen flex overflow-hidden selection:bg-brand-light selection:text-white">

<!-- Sidebar -->
<aside id="sidebar" class="w-[270px] bg-white border-r border-border flex-shrink-0 flex flex-col h-screen z-20 shadow-sm transition-all duration-300 ease-in-out fixed left-0 top-0 translate-x-0">
    <div class="px-6 py-5 border-b border-border flex items-center gap-3 justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/hsc-logo.png') }}" alt="HSC Logo" class="w-10 h-10 flex-shrink-0">
            <div class="leading-tight">
                <p class="text-[12px] font-bold text-navy leading-tight">Health Service</p>
                <p class="text-[12px] font-bold text-navy leading-tight">Commission of Zimbabwe</p>
            </div>
        </div>
        <button id="sidebarCloseBtn" class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors" onclick="toggleSidebar()">
            <i class="fa-solid fa-times text-xl text-slate-700"></i>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto py-4 space-y-1">
        @yield('sidebar_content')
    </nav>
</aside>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-10 hidden md:hidden" onclick="toggleSidebar()"></div>

<!-- Main Content -->
<div id="mainContent" class="flex-1 flex flex-col h-screen overflow-hidden relative">
    <!-- Top Nav -->
    <header id="header" class="h-20 bg-white border-b border-border flex items-center justify-between px-6 flex-shrink-0 z-10 shadow-sm">
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-slate-100 transition-colors" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars text-xl text-slate-700"></i>
            </button>
            <div class="hidden sm:block">
                <p class="text-[13px] font-semibold text-navy">@yield('header_title')</p>
                <p class="text-[11px] text-slate-400">@yield('header_subtitle')</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative flex items-center justify-center w-10 h-10 rounded-lg hover:bg-slate-100 transition-colors" title="Notifications">
                    <i class="fa-solid fa-bell text-slate-700 text-lg transition-transform" :class="{ 'rotate-180': open }"></i>
                    @if(isset($unreadNotificationCount) && $unreadNotificationCount > 0)
                    <span class="absolute top-1 right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                        {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
                    </span>
                    @endif
                </button>
                @if(isset($allNotifications))
                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg border border-border shadow-lg z-50 max-h-96 overflow-y-auto" x-transition>
                    <div class="p-4 border-b border-border sticky top-0 bg-white">
                        <p class="text-[13px] font-semibold text-navy">Pending Review Cases</p>
                    </div>
                    @forelse($allNotifications as $item)
                    <div class="p-3 border-b border-slate-100 hover:bg-slate-50 transition-colors text-[12px] group">
                        @if($item->notification_type === 'PENDING_REVIEW')
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-navy">{{ $item->incident->reference_number }}</p>
                                    <p class="text-slate-600 mt-1">
                                        <span class="font-medium">{{ $item->incident->first_name ?? '' }} {{ $item->incident->surname ?? '' }}</span>
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                    <a href="{{ route('incidents.hr_dossier', ['incident' => $item->incident]) }}" class="px-2.5 py-1 bg-brand text-white text-[10px] font-medium rounded hover:bg-brand-dark transition-colors whitespace-nowrap">
                                        Review
                                    </a>
                                    <p class="text-slate-400 text-[11px]">{{ $item->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @else
                            <p class="font-semibold text-navy">{{ $item->notification_type }}</p>
                            <p class="text-slate-600 mt-1">Case {{ $item->incident?->reference_number ?? 'N/A' }}</p>
                            <p class="text-slate-400 mt-1 text-[11px]">{{ $item->created_at->diffForHumans() }}</p>
                        @endif
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500 text-[12px]">
                        <i class="fa-solid fa-inbox text-slate-300 text-2xl mb-2 block"></i>
                        <p>No pending review cases</p>
                    </div>
                    @endforelse
                </div>
                @endif
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200">
                <i class="fa-solid fa-calendar text-slate-400 text-xs"></i>
                <span class="text-[12px] font-medium text-slate-600">{{ now()->format('M d, Y') }}</span>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 pl-2 border-l border-slate-200 hover:bg-slate-50 rounded px-2 py-1 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-brand-light flex items-center justify-center text-white font-semibold text-xs ring-2 ring-slate-100">
                        @php
                            $fullName = Auth::user()->name;
                            $initials = implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', trim($fullName))));
                            echo strtoupper(substr($initials, 0, 2));
                        @endphp
                    </div>
                    <div class="hidden md:block leading-tight text-left">
                        <p class="text-[13px] font-semibold text-navy">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400">{{ Auth::user()->roles->first()?->display_name ?? 'User' }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] ml-1 transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" @click="open = false" class="fixed inset-0 z-40"></div>
                <div x-show="open" class="absolute right-0 mt-2 w-56 bg-white rounded-lg border border-border shadow-lg z-50" x-transition>
                    <div class="p-4 border-b border-border">
                        <p class="text-[13px] font-semibold text-navy">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="p-2 border-b border-border space-y-1">
                        <a href="{{ route('profile.show') }}" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition-colors">
                            <i class="fa-solid fa-user text-slate-600 w-4"></i>
                            <span>View Profile</span>
                        </a>
                        <a href="{{ route('support.create_ticket') }}" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition-colors">
                            <i class="fa-solid fa-circle-question text-slate-600 w-4"></i>
                            <span>Get IT Help</span>
                        </a>
                    </div>
                    <div class="p-2 border-t border-border">
                        <form method="POST" action="{{ route('logout') }}">@csrf
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

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 xl:p-10">
        <div class="max-w-[1800px] mx-auto space-y-6">
            @yield('dashboard_content')
            <footer id="footer" class="pt-6 pb-4 text-center border-t border-border mt-2">
                <p class="text-[12px] text-slate-400 font-medium">© 2026 Health Service Commission of Zimbabwe. All rights reserved.</p>
            </footer>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.mark-notification-btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.dataset.notificationId;
                fetch(`/api/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Change icon color to indicate marked as read
                        this.querySelector('i').classList.add('text-emerald-600');
                        
                        // Fade out and remove the notification
                        const notification = this.closest('.border-b');
                        notification.style.opacity = '0';
                        notification.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            notification.remove();
                            // Update counter
                            const badge = document.querySelector('.fa-bell').parentElement.querySelector('span');
                            if (badge) {
                                const count = parseInt(badge.textContent) - 1;
                                if (count <= 0) {
                                    badge.remove();
                                } else {
                                    badge.textContent = count > 9 ? '9+' : count;
                                }
                            }
                        }, 300);
                    }
                });
            });
        });
    });
</script>

@yield('scripts')

<!-- Welcome Modal -->
<div id="welcomeModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-all duration-300">
        <!-- Welcome Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-brand to-brand-light rounded-full flex items-center justify-center animate-bounce">
                <i class="fas fa-hand-wave text-white text-3xl"></i>
            </div>
        </div>

        <!-- Welcome Text -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-slate-900 mb-2">Welcome!</h2>
            <p class="text-lg text-brand font-semibold mb-4">
                {{ auth()->user()->name ?? 'User' }}
            </p>
            <p class="text-slate-600 text-sm">
                You're now logged into the Health Service Commission system. Let's make today productive!
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="mt-6 h-1 bg-slate-200 rounded-full overflow-hidden">
            <div class="h-full bg-brand rounded-full animate-pulse" style="animation: shrink 3s linear forwards;"></div>
        </div>
    </div>
</div>

<style>
    @keyframes shrink {
        0% { width: 100%; }
        100% { width: 0%; }
    }
    
    #welcomeModal.show {
        opacity: 1;
        visibility: visible;
    }
    
    #welcomeModal.show .bg-white {
        scale: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeModal = document.getElementById('welcomeModal');
        if (welcomeModal) {
            // Check if welcome modal has already been shown in this session
            const hasSeenWelcome = sessionStorage.getItem('hasSeenWelcomeModal');
            
            if (!hasSeenWelcome) {
                // Show modal with animation
                setTimeout(() => {
                    welcomeModal.classList.add('show');
                    // Mark that the welcome modal has been shown
                    sessionStorage.setItem('hasSeenWelcomeModal', 'true');
                }, 100);
                
                // Auto close after 3 seconds
                setTimeout(() => {
                    welcomeModal.classList.remove('show');
                }, 3100);
            }
        }
    });
</script>

</body>
</html>
