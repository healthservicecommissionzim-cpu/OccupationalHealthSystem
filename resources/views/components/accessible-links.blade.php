<!-- Accessible Links Component -->
<!-- This component provides utility classes and helpers for creating accessible links -->

<style>
/* Accessible Link Styles */
.accessible-link {
    color: #1D4533;
    text-decoration: none;
    font-weight: 600;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
}

.accessible-link:hover {
    border-bottom-color: #1D4533;
}

.accessible-link:focus {
    outline: 2px solid #1D4533;
    outline-offset: 2px;
}

/* Link with icon */
.accessible-link-with-icon {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.accessible-link-with-icon i {
    font-size: 0.9em;
}

/* Button-styled links */
.link-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #1D4533;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.link-button:hover {
    background: #0f2818;
    transform: translateY(-2px);
}

.link-button:focus {
    outline: 2px solid #f59e0b;
    outline-offset: 2px;
}

.link-button:active {
    transform: translateY(0);
}

/* Secondary button style */
.link-button.secondary {
    background: white;
    color: #1D4533;
    border: 2px solid #1D4533;
}

.link-button.secondary:hover {
    background: #f0fdf4;
}

/* Small links */
.link-small {
    font-size: 13px;
}

/* Underlined links */
.link-underlined {
    text-decoration: underline;
}

.link-underlined:hover {
    text-decoration-thickness: 2px;
}

/* Link with visual indicator */
.link-with-arrow::after {
    content: ' →';
    margin-left: 4px;
}

.link-with-arrow:hover::after {
    margin-left: 8px;
}

/* Skip to main content link */
.skip-to-main {
    position: absolute;
    top: -40px;
    left: 0;
    background: #1D4533;
    color: white;
    padding: 8px;
    text-decoration: none;
    z-index: 100;
    font-size: 14px;
    font-weight: 600;
}

.skip-to-main:focus {
    top: 0;
}

/* External link indicator */
.external-link::after {
    content: ' ⧉';
    font-size: 0.8em;
}

/* Accessible link group */
.link-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.link-group.inline {
    flex-direction: row;
    flex-wrap: wrap;
}

/* Breadcrumb navigation */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6b7280;
}

.breadcrumb a {
    color: #1D4533;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: #0f2818;
}

.breadcrumb a:focus {
    outline: 2px solid #1D4533;
    outline-offset: 2px;
}

.breadcrumb-separator {
    color: #d1d5db;
}

@media (max-width: 640px) {
    .link-group.inline {
        flex-direction: column;
    }

    .link-button {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Utility component for common link patterns -->
<script>
// Helper function to create accessible links
function createAccessibleLink(text, href, options = {}) {
    const link = document.createElement('a');
    link.href = href;
    link.textContent = text;
    link.className = 'accessible-link';
    
    if (options.withIcon) {
        link.classList.add('accessible-link-with-icon');
        const icon = document.createElement('i');
        icon.className = options.icon || 'fa-solid fa-arrow-right';
        link.insertBefore(icon, link.firstChild);
    }
    
    if (options.button) {
        link.classList.add('link-button');
        if (options.secondary) {
            link.classList.add('secondary');
        }
    }
    
    if (options.external) {
        link.classList.add('external-link');
        link.setAttribute('target', '_blank');
        link.setAttribute('rel', 'noopener noreferrer');
    }
    
    if (options.ariaLabel) {
        link.setAttribute('aria-label', options.ariaLabel);
    }
    
    return link;
}

// Export for use in templates
window.createAccessibleLink = createAccessibleLink;
</script>

<!-- Examples of proper link usage (documentation) -->
<!--
PROPER LINK USAGE EXAMPLES:

1. DESCRIPTIVE TEXT (❌ Bad / ✅ Good):
   ❌ <a href="/resources">Click here</a>
   ✅ <a href="/resources">Learn more about safety protocols</a>

2. WITH ICON:
   <a href="/resources" class="accessible-link-with-icon">
       <i class="fa-solid fa-file"></i>
       View detailed safety guidelines
   </a>

3. BUTTON-STYLE LINK:
   <a href="/download" class="link-button">
       <i class="fa-solid fa-download"></i>
       Download comprehensive guide
   </a>

4. EXTERNAL LINK:
   <a href="https://external.com" class="accessible-link external-link">
       Learn more about regulations
   </a>

5. WITH ARIA LABEL:
   <a href="/resources" aria-label="Learn about occupational health resources">
       Resources
   </a>

6. SKIP TO MAIN CONTENT:
   <a href="#main-content" class="skip-to-main">Skip to main content</a>

7. BREADCRUMB NAVIGATION:
   <nav class="breadcrumb">
       <a href="/">Home</a>
       <span class="breadcrumb-separator">/</span>
       <a href="/resources">Resources</a>
       <span class="breadcrumb-separator">/</span>
       <span>Safety Protocols</span>
   </nav>
-->
