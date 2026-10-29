<!-- Accessibility Toolbar Component -->
<div class="accessibility-toolbar" id="accessibilityToolbar">
    <button class="accessibility-btn" id="toggleAccessibilityPanel" aria-label="Open accessibility options" title="Accessibility Options (Alt + A)">
        <i class="fa-solid fa-universal-access"></i>
    </button>
</div>

<!-- Accessibility Panel -->
<div class="accessibility-panel" id="accessibilityPanel" aria-label="Accessibility Options">
    <div class="accessibility-panel-header">
        <h2>Accessibility Options</h2>
        <button class="close-btn" id="closeAccessibilityPanel" aria-label="Close accessibility panel">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <div class="accessibility-panel-content">
        <!-- Language Selection -->
        <div class="accessibility-section">
            <label for="languageSelect">Select Language / Sarudza Mutauro</label>
            <select id="languageSelect" class="language-select">
                <option value="en">English</option>
                <option value="sn">Shona (Chishona)</option>
                <option value="nd">Ndebele (Sindebele)</option>
            </select>
        </div>

        <!-- Text Size Control -->
        <div class="accessibility-section">
            <label>Text Size</label>
            <div class="text-size-controls">
                <button class="text-size-btn" id="decreaseText" aria-label="Decrease text size" title="Decrease text size">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <span class="text-size-display" id="textSizeDisplay">100%</span>
                <button class="text-size-btn" id="increaseText" aria-label="Increase text size" title="Increase text size">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button class="text-size-btn reset-btn" id="resetText" aria-label="Reset text size to default" title="Reset text size">
                    Reset
                </button>
            </div>
            <small>Adjust text size from 100% to 200%</small>
        </div>

        <!-- High Contrast -->
        <div class="accessibility-section">
            <label class="checkbox-label">
                <input type="checkbox" id="highContrastToggle" aria-label="Toggle high contrast mode">
                <span>High Contrast Mode</span>
            </label>
        </div>

        <!-- Reduce Motion -->
        <div class="accessibility-section">
            <label class="checkbox-label">
                <input type="checkbox" id="reduceMotionToggle" aria-label="Toggle reduced motion">
                <span>Reduce Motion</span>
            </label>
        </div>

        <!-- Dyslexia-friendly Font -->
        <div class="accessibility-section">
            <label class="checkbox-label">
                <input type="checkbox" id="dyslexiaFontToggle" aria-label="Toggle dyslexia-friendly font">
                <span>Dyslexia-Friendly Font</span>
            </label>
        </div>

        <!-- Reset All -->
        <div class="accessibility-section">
            <button id="resetAllSettings" class="reset-all-btn">Reset All Settings</button>
        </div>

        <!-- Help -->
        <div class="accessibility-section">
            <p class="accessibility-help">
                <strong>Keyboard Shortcuts:</strong><br>
                Alt + A: Open accessibility panel<br>
                Tab: Navigate between elements<br>
                Enter: Activate buttons/links
            </p>
        </div>
    </div>
</div>

<style>
.accessibility-toolbar {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9998;
}

.accessibility-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #1D4533;
    color: white;
    border: none;
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.accessibility-btn:hover {
    background: #0f2818;
    transform: scale(1.1);
}

.accessibility-btn:focus {
    outline: 3px solid #f59e0b;
    outline-offset: 2px;
}

.accessibility-panel {
    display: none;
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 320px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    z-index: 9999;
    max-height: 500px;
    overflow-y: auto;
}

.accessibility-panel.active {
    display: flex;
    flex-direction: column;
}

.accessibility-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.accessibility-panel-header h2 {
    font-size: 14px;
    font-weight: 700;
    color: #1D4533;
    margin: 0;
}

.close-btn {
    background: none;
    border: none;
    font-size: 20px;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
}

.close-btn:hover {
    color: #1D4533;
}

.accessibility-panel-content {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.accessibility-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.accessibility-section label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.language-select,
.accessibility-section input[type="text"] {
    padding: 8px 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    font-family: inherit;
}

.language-select:focus,
.accessibility-section input[type="text"]:focus {
    outline: 2px solid #1D4533;
    outline-offset: 0;
}

.text-size-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.text-size-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #d1d5db;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.text-size-btn:hover {
    background: #f3f4f6;
    border-color: #1D4533;
}

.text-size-btn:focus {
    outline: 2px solid #1D4533;
}

.text-size-display {
    flex: 1;
    text-align: center;
    font-weight: 600;
    font-size: 13px;
    color: #1D4533;
}

.reset-btn {
    background: #f3f4f6;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    color: #374151;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #1D4533;
}

.reset-all-btn {
    padding: 8px 12px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.reset-all-btn:hover {
    background: #dc2626;
}

.accessibility-help {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
    line-height: 1.6;
}

.accessibility-panel small {
    font-size: 12px;
    color: #9ca3af;
}

/* High Contrast Mode */
body.high-contrast {
    background: white;
    color: black;
}

body.high-contrast * {
    border-color: black !important;
}

body.high-contrast button {
    border: 2px solid black !important;
}

/* Reduce Motion */
body.reduce-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
}

/* Dyslexia-friendly Font */
body.dyslexia-font {
    font-family: 'OpenDyslexic', 'Courier New', monospace;
}

/* Text Size */
body.text-size-120 {
    font-size: 120%;
}

body.text-size-140 {
    font-size: 140%;
}

body.text-size-160 {
    font-size: 160%;
}

body.text-size-180 {
    font-size: 180%;
}

body.text-size-200 {
    font-size: 200%;
}

@media (max-width: 640px) {
    .accessibility-toolbar {
        bottom: 20px;
        right: 20px;
    }

    .accessibility-btn {
        width: 44px;
        height: 44px;
        font-size: 20px;
    }

    .accessibility-panel {
        bottom: 80px;
        right: 20px;
        width: 280px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toolbar = document.getElementById('accessibilityToolbar');
    const panel = document.getElementById('accessibilityPanel');
    const toggleBtn = document.getElementById('toggleAccessibilityPanel');
    const closeBtn = document.getElementById('closeAccessibilityPanel');
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
    toggleBtn.addEventListener('click', () => {
        panel.classList.toggle('active');
    });

    closeBtn.addEventListener('click', () => {
        panel.classList.remove('active');
    });

    // Keyboard shortcut (Alt + A)
    document.addEventListener('keydown', (e) => {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            panel.classList.toggle('active');
            if (panel.classList.contains('active')) {
                toggleBtn.focus();
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
        location.reload();
    });

    function updateTextSize() {
        const sizeClass = `text-size-${currentTextSize}`;
        document.body.className = document.body.className.replace(/text-size-\d+/g, '');
        if (currentTextSize !== 100) {
            document.body.classList.add(sizeClass);
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
        }
    }

    function changeLanguage(lang) {
        // This will trigger translation via a separate system
        // For now, we store the preference
        document.documentElement.lang = lang;
        window.dispatchEvent(new CustomEvent('languageChange', { detail: { language: lang } }));
    }
});
</script>
