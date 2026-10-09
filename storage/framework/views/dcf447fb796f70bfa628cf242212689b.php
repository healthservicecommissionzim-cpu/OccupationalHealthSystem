<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSC-OHS | Occupational Health System</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50">
    <!-- Responsive Header with Mobile Menu -->
    <header class="header" role="banner">
        <div class="header__inner">
            <!-- Logo -->
            <a href="/" class="header__logo" aria-label="HSC-OHS Home">
                <span class="header__logo-text">HSC-OHS</span>
                <span class="header__subtitle">Occupational Health System</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="header__nav" aria-label="Main Navigation">
                <ul class="header__nav-list">
                    <li class="header__nav-item">
                        <a href="/" class="header__nav-link <?php echo e(request()->is('/') ? 'active' : ''); ?>">Home</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="/resources" class="header__nav-link <?php echo e(request()->is('resources') ? 'active' : ''); ?>">Resources</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="/safety-protocols" class="header__nav-link <?php echo e(request()->is('safety-protocols') ? 'active' : ''); ?>">Safety Protocols</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="/support" class="header__nav-link <?php echo e(request()->is('support') ? 'active' : ''); ?>">Support</a>
                    </li>
                </ul>
            </nav>

            <!-- Header Actions -->
            <div class="header__actions">
                <!-- Mobile Hamburger Menu Button -->
                <button class="header__hamburger" id="mobile-menu-toggle" aria-label="Toggle Mobile Menu" aria-expanded="false">
                    <div class="header__hamburger-icon">
                        <span class="header__hamburger-line"></span>
                        <span class="header__hamburger-line"></span>
                        <span class="header__hamburger-line"></span>
                    </div>
                </button>

                <!-- User Profile Button -->
                <?php if(auth()->guard()->check()): ?>
                    <button class="header__user-button" title="User Profile" aria-label="User Profile Menu">
                        <?php echo e(auth()->user()->name[0] ?? 'U'); ?>

                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu" id="mobile-menu" role="navigation" aria-label="Mobile Navigation">
        <div class="mobile-menu__panel">
            <!-- Mobile Menu Header -->
            <div class="mobile-menu__header">
                <h2 class="mobile-menu__title">Menu</h2>
                <button class="mobile-menu__close" id="mobile-menu-close" aria-label="Close Menu">
                    <span class="mobile-menu__close-icon">×</span>
                </button>
            </div>

            <!-- Mobile Menu Content -->
            <div class="mobile-menu__content">
                <ul class="mobile-menu__nav-list">
                    <li class="mobile-menu__nav-item">
                        <a href="/" class="mobile-menu__nav-link <?php echo e(request()->is('/') ? 'active' : ''); ?>">Home</a>
                    </li>
                    <li class="mobile-menu__nav-item">
                        <a href="/resources" class="mobile-menu__nav-link <?php echo e(request()->is('resources') ? 'active' : ''); ?>">Resources</a>
                    </li>
                    <li class="mobile-menu__nav-item">
                        <a href="/safety-protocols" class="mobile-menu__nav-link <?php echo e(request()->is('safety-protocols') ? 'active' : ''); ?>">Safety Protocols</a>
                    </li>
                    <li class="mobile-menu__nav-item">
                        <a href="/support" class="mobile-menu__nav-link <?php echo e(request()->is('support') ? 'active' : ''); ?>">Support</a>
                    </li>
                </ul>
            </div>

            <!-- Mobile Menu Footer -->
            <div class="mobile-menu__footer">
                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="header__nav-link" style="width: 100%; text-align: left; padding: var(--spacing-md) var(--spacing-lg); border-radius: var(--radius-md);">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="/login" class="header__nav-link" style="width: 100%; text-align: left; padding: var(--spacing-md) var(--spacing-lg); border-radius: var(--radius-md);">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="footer__inner">
            <!-- Footer Content Grid -->
            <div class="footer__content">
                <!-- About Section -->
                <div class="footer__section">
                    <h3 class="footer__section-heading">About HSC-OHS</h3>
                    <ul class="footer__links">
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Organization</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Mission & Vision</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Our Team</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources Section -->
                <div class="footer__section">
                    <h3 class="footer__section-heading">Resources</h3>
                    <ul class="footer__links">
                        <li class="footer__link-item">
                            <a href="/resources" class="footer__link">Documentation</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="/safety-protocols" class="footer__link">Safety Protocols</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Guidelines</a>
                        </li>
                    </ul>
                </div>

                <!-- Support Section -->
                <div class="footer__section">
                    <h3 class="footer__section-heading">Support</h3>
                    <ul class="footer__links">
                        <li class="footer__link-item">
                            <a href="/support" class="footer__link">Help Center</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Contact Us</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Report an Issue</a>
                        </li>
                    </ul>
                </div>

                <!-- Legal Section -->
                <div class="footer__section">
                    <h3 class="footer__section-heading">Legal</h3>
                    <ul class="footer__links">
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Privacy Policy</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Terms of Service</a>
                        </li>
                        <li class="footer__link-item">
                            <a href="#" class="footer__link">Accessibility</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Divider -->
            <div class="footer__divider"></div>

            <!-- Footer Bottom -->
            <div class="footer__bottom">
                <!-- Copyright -->
                <p class="footer__copyright">
                    &copy; <?php echo e(date('Y')); ?> Health Service Commission of Zimbabwe. All rights reserved.<br>
                    <span>HSB Circular 3 of 2017 | Occupational Health & Safety System</span>
                </p>

                <!-- Legal Links -->
                <ul class="footer__legal-links">
                    <li><a href="#" class="footer__legal-link">Sitemap</a></li>
                    <li class="footer__legal-separator"></li>
                    <li><a href="#" class="footer__legal-link">Cookies</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Toggle Script -->
    <script>
        (function() {
            const hamburger = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeButton = document.getElementById('mobile-menu-close');
            let lastFocusedElement = null;
            let focusTrapActive = false;

            function openMenu() {
                mobileMenu.classList.add('open');
                hamburger.classList.add('open');
                hamburger.setAttribute('aria-expanded', 'true');
                lastFocusedElement = document.activeElement;
                focusTrapActive = true;
                moveFocusToMenu();
            }

            function closeMenu() {
                mobileMenu.classList.remove('open');
                hamburger.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
                focusTrapActive = false;
                if (lastFocusedElement && lastFocusedElement.focus) {
                    lastFocusedElement.focus();
                }
            }

            function moveFocusToMenu() {
                const focusableElements = mobileMenu.querySelectorAll(
                    'a, button, [tabindex]:not([tabindex="-1"])'
                );
                if (focusableElements.length > 0) {
                    focusableElements[0].focus();
                }
            }

            function handleEscape(e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                    closeMenu();
                }
            }

            function handleTab(e) {
                if (!focusTrapActive) return;

                const focusableElements = mobileMenu.querySelectorAll(
                    'a, button, [tabindex]:not([tabindex="-1"])'
                );
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (e.key === 'Tab') {
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }

            // Event listeners
            hamburger.addEventListener('click', () => {
                if (mobileMenu.classList.contains('open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            closeButton.addEventListener('click', closeMenu);

            // Close menu when clicking outside
            mobileMenu.addEventListener('click', (e) => {
                if (e.target === mobileMenu) {
                    closeMenu();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', handleEscape);
            document.addEventListener('keydown', handleTab);

            // Close menu on link clicks
            const menuLinks = mobileMenu.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        })();
    </script>

    <!-- Global utility functions -->
    <script>
        document.addEventListener('alpine:init', () => {
            window.notify = function(message, type = 'success') {
                console.log(`[${type.toUpperCase()}] ${message}`);
            };
        });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\layouts\app.blade.php ENDPATH**/ ?>