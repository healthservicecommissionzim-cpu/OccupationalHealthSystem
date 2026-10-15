<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title') — Health Service Commission</title>
    
    <!-- DNS Prefetch & Preconnect for faster CDN loading -->
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Font Awesome - Single source with fallback -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Tailwind CSS - CDN with config -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Optimized with font-display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js - Deferred for non-blocking load (SINGLE INSTANCE) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- ApexCharts - Only loaded when needed, deferred -->
    <script defer src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
        
        /* Font Awesome Icon Fix - Ensure visibility with fallback */
        .fa, .fas, .far, .fal, .fat, .fad, .fab, .fa-solid, .fa-regular, .fa-light, .fa-thin, .fa-duotone, .fa-brands,
        i[class*="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands", sans-serif !important;
            font-weight: 900;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: inline-block !important;
            width: 1.25em;
            text-align: center;
        }
        
        /* Ensure regular icons have correct weight */
        .fa-regular, .far {
            font-weight: 400 !important;
        }
        
        /* Ensure brands icons use correct font */
        .fa-brands, .fab {
            font-family: "Font Awesome 6 Brands", sans-serif !important;
        }
        
        /* Fallback: If Font Awesome doesn't load, show colored circles */
        #sidebar i[class*="fa-"]::before {
            color: #0d7a3d !important;
            opacity: 1 !important;
        }
        
        /* Force sidebar icons to be visible */
        #sidebar .fa-solid,
        #sidebar .fa-regular,
        #sidebar i[class*="fa-"] {
            color: #334155 !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Active link icon color */
        #sidebar a.bg-white i[class*="fa-"],
        #sidebar a.bg-white .fa-solid {
            color: #0d7a3d !important;
        }
    </style>
    <script>
        // Sidebar toggle function - called by button clicks
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const isDesktop = window.innerWidth >= 768;
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            console.log('Toggle sidebar clicked. Currently hidden:', isHidden);
            
            if (isHidden) {
                // Show sidebar
                sidebar.classList.remove('-translate-x-full');
                localStorage.setItem('sidebarCollapsed', 'false');
                
                if (isDesktop) {
                    mainContent.classList.remove('collapsed');
                    if (overlay) overlay.classList.add('hidden');
                } else {
                    if (overlay) overlay.classList.remove('hidden');
                }
                console.log('Sidebar expanded');
            } else {
                // Hide sidebar
                sidebar.classList.add('-translate-x-full');
                localStorage.setItem('sidebarCollapsed', 'true');
                mainContent.classList.add('collapsed');
                if (overlay) overlay.classList.add('hidden');
                console.log('Sidebar collapsed');
            }
        }

        // Apply sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const isDesktop = window.innerWidth >= 768;
            
            // RESET: Clear any saved collapsed state - always start expanded
            localStorage.removeItem('sidebarCollapsed');
            
            console.log('Page loaded. Is Desktop:', isDesktop);
            
            // Always show sidebar expanded on desktop, hide on mobile by default
            if (isDesktop) {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.remove('collapsed');
                if (overlay) overlay.classList.add('hidden');
            } else {
                // On mobile, start collapsed
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.add('collapsed');
                if (overlay) overlay.classList.add('hidden');
            }
            
            // Auto-close sidebar on mobile when clicking links
            const sidebarLinks = document.querySelectorAll('#sidebar a, #sidebar button:not(#sidebarCloseBtn)');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only auto-close on mobile
                    if (window.innerWidth < 768) {
                        sidebar.classList.add('-translate-x-full');
                        if (overlay) overlay.classList.add('hidden');
                        localStorage.setItem('sidebarCollapsed', 'true');
                        mainContent.classList.add('collapsed');
                    }
                });
            });
        });

        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const overlay = document.getElementById('sidebarOverlay');
                const isDesktop = window.innerWidth >= 768;
                
                // On resize, always show on desktop, hide on mobile
                if (isDesktop) {
                    sidebar.classList.remove('-translate-x-full');
                    mainContent.classList.remove('collapsed');
                    if (overlay) overlay.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    mainContent.classList.add('collapsed');
                    if (overlay) overlay.classList.add('hidden');
                }
            }, 100);
        });
    </script>
    
    <script>
        // Font Awesome Icon Fallback - Replace with SVG icons if FA doesn't load
        document.addEventListener('DOMContentLoaded', function() {
            // SVG icon templates
            const svgIcons = {
                'fa-chart-line': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>',
                'fa-chart-pie': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>',
                'fa-map-location-dot': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                'fa-briefcase': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                'fa-users': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'fa-headset': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>',
                'fa-chart-bar': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                'fa-file-chart-line': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'fa-map-marked-alt': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>',
                'fa-calendar-days': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                'fa-file-export': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'fa-cog': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                'fa-hospital': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
                'fa-building': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
                'fa-id-badge': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>',
                'fa-sitemap': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>',
                'fa-map': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>',
                'fa-lock': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>',
                'fa-sliders': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>',
                'fa-arrow-right-to-bracket': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>',
                'fa-comment-dots': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>',
                'fa-bullhorn': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>',
                'fa-sms': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>',
                'fa-envelope': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                'fa-shield': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                'fa-chevron-down': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>',
                'fa-chevron-up': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>',
                'fa-chevron-right': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                'fa-triangle-exclamation': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                'fa-clock': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'fa-percent': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                'fa-hourglass-end': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'fa-arrow-left': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>',
                'fa-magnifying-glass': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>',
                'fa-plus': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>',
                'fa-times': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
                'fa-check': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                'fa-edit': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                'fa-inbox': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>',
                'fa-table-list': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
                'fa-circle': '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle></svg>',
                'fa-clipboard': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>',
                'fa-person-hiking': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
                'fa-calendar': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                'fa-ticket': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>',
                'fa-list-check': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>',
                'fa-stethoscope': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm0 0c0 1.105 1.343 2 3 2s3-.895 3-2-1.343-2-3-2-3 .895-3 2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14m-6 0h6m2 0a2 2 0 100-4 2 2 0 000 4z"></path></svg>',
                'fa-microscope': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm0 0c0 1.105 1.343 2 3 2s3-.895 3-2-1.343-2-3-2-3 .895-3 2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14"></path></svg>',
                'fa-shield-check': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                'fa-flag-checkered': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>',
                'fa-gavel': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>',
                'fa-fire': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>',
                'fa-chart-area': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>',
                'fa-file-medical': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'fa-file-circle-check': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'fa-clipboard-check': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>',
                'fa-arrow-up-right': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7m0 0H7m10 0v10"></path></svg>',
                'fa-arrow-right-from-bracket': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>',
                'fa-thumbs-up': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>',
                'fa-user': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
                'fa-circle-question': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'fa-eye': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>',
                'fa-eye-slash': '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>'
            };
            
            // Find all Font Awesome icons
            const icons = document.querySelectorAll('i[class*="fa-"]');
            
            icons.forEach(icon => {
                // Check if icon has no content (FA didn't load)
                const hasContent = icon.textContent.trim().length > 0 || icon.querySelector('svg');
                
                if (!hasContent) {
                    // Get all classes
                    const classes = Array.from(icon.classList);
                    
                    // Find matching SVG icon
                    for (const cls of classes) {
                        if (svgIcons[cls]) {
                            icon.innerHTML = svgIcons[cls];
                            icon.style.display = 'inline-flex';
                            icon.style.alignItems = 'center';
                            icon.style.justifyContent = 'center';
                            break;
                        }
                    }
                }
            });
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
            <svg class="w-6 h-6" style="color: #334155;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
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
                <svg class="w-6 h-6" style="color: #334155;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="hidden sm:block">
                <p class="text-[13px] font-semibold text-navy">@yield('header_title')</p>
                <p class="text-[11px] text-slate-400">@yield('header_subtitle')</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-colors" title="Notifications">
                    <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open }" style="color: #0f172a;" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                    </svg>
                    @if(isset($unreadNotificationCount) && $unreadNotificationCount > 0)
                    <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
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
                        <button onclick="openProfileModal()" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition-colors">
                            <i class="fa-solid fa-user text-slate-600 w-4"></i>
                            <span>View Profile</span>
                        </button>
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

<!-- Profile Modal Component -->
@include('components.profile-modal')

</body>
</html>
