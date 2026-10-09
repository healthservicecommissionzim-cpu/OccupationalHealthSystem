<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['showSocialLinks' => false, 'contactEmail' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['showSocialLinks' => false, 'contactEmail' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<footer class="footer" <?php echo e($attributes); ?>>
    <div class="footer__inner">
        <!-- Footer Content Grid -->
        <div class="footer__content">
            <!-- Section 1: About -->
            <div class="footer__section">
                <h3 class="footer__section-heading">About HSC</h3>
                <ul class="footer__links">
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">About Us</a>
                    </li>
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Mission & Vision</a>
                    </li>
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Leadership</a>
                    </li>
                </ul>
            </div>

            <!-- Section 2: Support -->
            <div class="footer__section">
                <h3 class="footer__section-heading">Support</h3>
                <ul class="footer__links">
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Help Center</a>
                    </li>
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Contact Us</a>
                    </li>
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Report an Issue</a>
                    </li>
                </ul>
            </div>

            <!-- Section 3: Resources -->
            <div class="footer__section">
                <h3 class="footer__section-heading">Resources</h3>
                <ul class="footer__links">
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Safety Protocols</a>
                    </li>
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Documentation</a>
                    </li>
                    <li class="footer__link-item">
                        <a href="#" class="footer__link">Guidelines</a>
                    </li>
                </ul>
            </div>

            <!-- Section 4: Legal -->
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

        <!-- Footer Bottom Section -->
        <div class="footer__bottom">
            <!-- Copyright Text -->
            <p class="footer__copyright">
                &copy; 2026 Health Service Commission of Zimbabwe. All rights reserved.
            </p>

            <!-- Legal Links -->
            <ul class="footer__legal-links">
                <li><a href="#" class="footer__legal-link">Privacy</a></li>
                <li class="footer__legal-separator"></li>
                <li><a href="#" class="footer__legal-link">Terms</a></li>
                <li class="footer__legal-separator"></li>
                <li><a href="#" class="footer__legal-link">Accessibility</a></li>
            </ul>

            <!-- Social Links (Optional) -->
            <?php if($showSocialLinks): ?>
            <ul class="footer__social-links">
                <li><a href="#" class="footer__social-link" aria-label="Facebook" title="Facebook">f</a></li>
                <li><a href="#" class="footer__social-link" aria-label="Twitter" title="Twitter">𝕏</a></li>
                <li><a href="#" class="footer__social-link" aria-label="LinkedIn" title="LinkedIn">in</a></li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</footer>
<?php /**PATH D:\OccupationalHealth System\resources\views\components\footer.blade.php ENDPATH**/ ?>