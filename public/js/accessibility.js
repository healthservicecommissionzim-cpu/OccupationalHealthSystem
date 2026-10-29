// Accessibility Toolbar JavaScript
console.log('Accessibility script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing accessibility toolbar');
    
    const panel = document.getElementById('accessibilityPanel');
    const toggleBtn = document.getElementById('toggleAccessibilityPanel');
    const closeBtn = document.getElementById('closeAccessibilityPanel');
    
    console.log('Panel found:', !!panel);
    console.log('Toggle button found:', !!toggleBtn);
    console.log('Close button found:', !!closeBtn);

    if (!panel || !toggleBtn) {
        console.error('Accessibility components not found in DOM');
        return;
    }

    const languageSelect = document.getElementById('languageSelect');
    const increaseBtn = document.getElementById('increaseText');
    const decreaseBtn = document.getElementById('decreaseText');
    const resetBtn = document.getElementById('resetText');
    const resetAllBtn = document.getElementById('resetAllSettings');
    const highContrastToggle = document.getElementById('highContrastToggle');
    const reduceMotionToggle = document.getElementById('reduceMotionToggle');
    const dyslexiaFontToggle = document.getElementById('dyslexiaFontToggle');
    const textSizeDisplay = document.getElementById('textSizeDisplay');

    let currentTextSize = 100;

    // Load saved settings
    loadAccessibilitySettings();

    // Toggle Panel
    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation(); // Prevent potential bubbling issues
        console.log('Toggle button clicked');
        panel.classList.toggle('active');
        const isActive = panel.classList.contains('active');
        console.log('Panel active:', isActive);
        // Accessibility improvement: Update aria-expanded
        toggleBtn.setAttribute('aria-expanded', isActive);
    });

    closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        console.log('Close button clicked');
        panel.classList.remove('active');
        toggleBtn.setAttribute('aria-expanded', 'false');
    });

    // Keyboard shortcut (Alt + A)
    document.addEventListener('keydown', (e) => {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            e.stopPropagation();
            panel.classList.toggle('active');
            const isActive = panel.classList.contains('active');
            toggleBtn.setAttribute('aria-expanded', isActive);
            if (isActive) {
                toggleBtn.focus();
            }
        }
    });

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
        if (panel.classList.contains('active')) {
            if (!panel.contains(e.target) && !toggleBtn.contains(e.target)) {
                panel.classList.remove('active');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Language Selection
    languageSelect.addEventListener('change', (e) => {
        const lang = e.target.value;
        localStorage.setItem('accessibility-language', lang);
        changeLanguage(lang);
    });

    // Text Size Controls
    increaseBtn.addEventListener('click', () => {
        if (currentTextSize < 200) {
            currentTextSize += 20;
            updateTextSize();
        }
    });

    decreaseBtn.addEventListener('click', () => {
        if (currentTextSize > 100) {
            currentTextSize -= 20;
            updateTextSize();
        }
    });

    resetBtn.addEventListener('click', () => {
        currentTextSize = 100;
        updateTextSize();
    });

    // High Contrast
    highContrastToggle.addEventListener('change', (e) => {
        if (e.target.checked) {
            document.body.classList.add('high-contrast');
            localStorage.setItem('accessibility-high-contrast', 'true');
        } else {
            document.body.classList.remove('high-contrast');
            localStorage.setItem('accessibility-high-contrast', 'false');
        }
    });

    // Reduce Motion
    reduceMotionToggle.addEventListener('change', (e) => {
        if (e.target.checked) {
            document.body.classList.add('reduce-motion');
            localStorage.setItem('accessibility-reduce-motion', 'true');
        } else {
            document.body.classList.remove('reduce-motion');
            localStorage.setItem('accessibility-reduce-motion', 'false');
        }
    });

    // Dyslexia Font
    dyslexiaFontToggle.addEventListener('change', (e) => {
        if (e.target.checked) {
            document.body.classList.add('dyslexia-font');
            localStorage.setItem('accessibility-dyslexia-font', 'true');
        } else {
            document.body.classList.remove('dyslexia-font');
            localStorage.setItem('accessibility-dyslexia-font', 'false');
        }
    });

    // Reset All
    resetAllBtn.addEventListener('click', () => {
        if (confirm('Reset all accessibility settings?')) {
            localStorage.removeItem('accessibility-language');
            localStorage.removeItem('accessibility-text-size');
            localStorage.removeItem('accessibility-high-contrast');
            localStorage.removeItem('accessibility-reduce-motion');
            localStorage.removeItem('accessibility-dyslexia-font');
            
            currentTextSize = 100;
            updateTextSize();
            highContrastToggle.checked = false;
            reduceMotionToggle.checked = false;
            dyslexiaFontToggle.checked = false;
            languageSelect.value = 'en';
            
            document.body.classList.remove('high-contrast', 'reduce-motion', 'dyslexia-font');
            document.body.className = document.body.className.replace(/text-size-\d+/g, '');
            
            alert('Settings reset successfully');
        }
    });

    function updateTextSize() {
        // Remove all text size classes
        document.body.className = document.body.className.replace(/text-size-\d+/g, '');
        
        // Add new text size class if not default
        if (currentTextSize !== 100) {
            document.body.classList.add(`text-size-${currentTextSize}`);
        }
        
        textSizeDisplay.textContent = currentTextSize + '%';
        localStorage.setItem('accessibility-text-size', currentTextSize);
    }

    function loadAccessibilitySettings() {
        // Load text size
        const savedTextSize = localStorage.getItem('accessibility-text-size');
        if (savedTextSize) {
            currentTextSize = parseInt(savedTextSize);
            updateTextSize();
        }

        // Load high contrast
        if (localStorage.getItem('accessibility-high-contrast') === 'true') {
            document.body.classList.add('high-contrast');
            highContrastToggle.checked = true;
        }

        // Load reduce motion
        if (localStorage.getItem('accessibility-reduce-motion') === 'true') {
            document.body.classList.add('reduce-motion');
            reduceMotionToggle.checked = true;
        }

        // Load dyslexia font
        if (localStorage.getItem('accessibility-dyslexia-font') === 'true') {
            document.body.classList.add('dyslexia-font');
            dyslexiaFontToggle.checked = true;
        }

        // Load language
        const savedLanguage = localStorage.getItem('accessibility-language');
        if (savedLanguage) {
            languageSelect.value = savedLanguage;
            changeLanguage(savedLanguage);
        }
    }

    function changeLanguage(lang) {
        document.documentElement.lang = lang;
        window.dispatchEvent(new CustomEvent('languageChange', { detail: { language: lang } }));
        console.log('Language changed to:', lang);
    }

    console.log('Accessibility toolbar initialized');
});
