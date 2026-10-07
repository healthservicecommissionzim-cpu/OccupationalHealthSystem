@props(['showSocialLinks' => false, 'contactEmail' => null])

<footer class="footer" {{ $attributes }}>
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
            @if ($showSocialLinks)
            <ul class="footer__social-links">
                <li><a href="#" class="footer__social-link" aria-label="Facebook" title="Facebook">f</a></li>
                <li><a href="#" class="footer__social-link" aria-label="Twitter" title="Twitter">𝕏</a></li>
                <li><a href="#" class="footer__social-link" aria-label="LinkedIn" title="LinkedIn">in</a></li>
            </ul>
            @endif
        </div>
    </div>
</footer>
