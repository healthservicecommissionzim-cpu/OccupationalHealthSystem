# Accessibility Audit Checklist: Mobile Responsive Auth & Public Pages

## Quick Reference

**Target Scores:**
- ✅ Lighthouse Accessibility: 90+/100
- ✅ axe DevTools: Zero critical issues
- ✅ Color Contrast: WCAG AA minimum (4.5:1 for normal text, 3:1 for large)
- ✅ Keyboard Navigation: All interactive elements reachable via Tab
- ✅ Screen Reader: All content announced correctly

---

## Pages to Audit

- [ ] Login page (`/login`)
- [ ] Register page (`/register`)
- [ ] Forgot password page (`/password-reset/request`)
- [ ] Resources page (`/resources`)
- [ ] Safety protocols page (`/safety-protocols`)
- [ ] Support/Help desk page (`/support`)
- [ ] Welcome page (`/`)

---

## Phase 1: Automated Testing (Lighthouse)

### Chrome DevTools Lighthouse

**Steps:**
1. Open Chrome
2. Navigate to page URL
3. Press F12 to open DevTools
4. Click "Lighthouse" tab
5. Select "Accessibility" category
6. Click "Analyze page load"

### Results Tracking

For each page, record:

| Page | Score | Pass? | Issues Found | Resolution |
|------|-------|-------|--------------|-----------|
| Login | ___ | [ ] | | |
| Register | ___ | [ ] | | |
| Forgot Password | ___ | [ ] | | |
| Resources | ___ | [ ] | | |
| Safety Protocols | ___ | [ ] | | |
| Support | ___ | [ ] | | |
| Welcome | ___ | [ ] | | |

**Target:** All pages score ≥90

### Common Lighthouse Issues & Fixes

| Issue | Fix |
|-------|-----|
| Low contrast text | Increase color contrast ratio to ≥4.5:1 |
| Missing form labels | Add `<label for="input-id">` elements |
| Missing alt text | Add descriptive `alt` attributes to images |
| Missing button names | Add visible text or `aria-label` to icon buttons |
| Inaccessible color | Don't rely on color alone; use icons/text |
| Skipped heading levels | Use H1, H2, H3 sequentially (not H1 > H3) |
| Empty heading | Add visible text to all headings |

---

## Phase 2: axe DevTools Extension

### Installation & Setup

**Chrome:**
1. Install from [Chrome Web Store](https://chrome.google.com/webstore)
2. Click extension icon to open

**Firefox:**
1. Install from [Firefox Add-ons](https://addons.mozilla.org/firefox/)
2. Click extension icon to open

### Testing Each Page

**Steps for each page:**
1. Navigate to page
2. Click axe DevTools icon
3. Click "Scan ALL of my page"
4. Review results in popup

### Results Template

**Page:** [PAGE NAME]
**Date:** [DATE]
**Results:**

| Severity | Count | Status |
|----------|-------|--------|
| Critical | ___ | [ ] Fixed |
| Serious | ___ | [ ] Reviewed |
| Moderate | ___ | [ ] Reviewed |
| Minor | ___ | [ ] Reviewed |

### Priority Matrix

| Level | Action Required |
|-------|-----------------|
| 🔴 Critical | MUST fix before launch |
| 🟠 Serious | Should fix before launch |
| 🟡 Moderate | Consider fixing |
| 🔵 Minor | Nice to fix |

---

## Phase 3: Keyboard Navigation Testing

### Test Procedure

**For each page:**

1. Load page in browser
2. Press **Tab** repeatedly - verify:
   - Focus moves to next interactive element
   - Focus is always visible
   - No focus gets trapped
   - Focus order is logical (top-to-bottom, left-to-right)

3. Test **Shift+Tab** - focus should move backward

4. When focused on a button/link, press **Enter** - should activate

5. When focused on checkbox, press **Space** - should toggle

6. For dropdowns/selects, verify **Arrow Keys** navigate options

7. For modals/menus, press **Escape** - should close

### Focus Order Checklist

**Login Page:**
- [ ] Focus on email input
- [ ] Focus on password input
- [ ] Focus on "Remember me" checkbox
- [ ] Focus on submit button
- [ ] Focus on "Forgot password?" link
- [ ] Focus on sign up link
- No elements skipped, logical order

**Register Page:**
- [ ] Focus on first form field
- [ ] Tab through all sections sequentially
- [ ] Focus on each select/dropdown
- [ ] Focus on consent checkboxes
- [ ] Focus on submit button
- Logical order maintained

**Resources Page:**
- [ ] Focus on search input
- [ ] Focus on search button
- [ ] Focus on each resource card (if clickable)
- [ ] Focus on pagination controls (if present)
- Logical, predictable order

**Support Page:**
- [ ] Focus on all form fields in order
- [ ] Focus on submit button
- No fields skipped
- Clear order

### Focus Visibility Test

When an element is focused (during Tab navigation):
- [ ] Focus indicator is clearly visible
- [ ] Focus indicator is not obscured
- [ ] Focus indicator has sufficient contrast
- [ ] Focus indicator is approximately 2px outline or similar

**Expected Focus Styles:**
```css
button:focus,
input:focus,
a:focus {
  outline: 2px solid #1D4533; /* brand color */
  box-shadow: 0 0 0 4px rgba(29, 69, 51, 0.25); /* glow effect */
}
```

---

## Phase 4: Screen Reader Testing

### Windows: NVDA (Free)

**Installation:**
1. Download from https://www.nvaccess.org/download/
2. Install and launch
3. Start NVDA (Ctrl+Alt+N when installed)

**Testing:**
1. Open browser with NVDA running
2. Navigate to page
3. Press **Down Arrow** or **Right Arrow** to move through content
4. NVDA should announce:
   - Page title
   - Headings and heading levels
   - Form labels before inputs
   - Button text or aria-label
   - List items and list structure
   - Error messages
   - Success messages

**Login Page Test:**
- [ ] NVDA announces "Login" or similar heading
- [ ] NVDA announces "Email input" with label
- [ ] NVDA announces "Password input" with label
- [ ] NVDA announces "Remember me" checkbox with label
- [ ] NVDA announces "Login button"
- [ ] Error messages announced when form invalid

**Forms Test:**
- [ ] All labels paired with inputs
- [ ] Required fields announced as required
- [ ] Error messages announced immediately
- [ ] Success message announced after submission

### macOS/iOS: VoiceOver

**macOS - Activation:**
- System Preferences > Accessibility > VoiceOver > Enable
- Or: Cmd+F5

**macOS - Navigation:**
- **VO+Right Arrow**: Move forward
- **VO+Left Arrow**: Move backward
- **VO+Space**: Activate
- **VO+Up/Down Arrows**: Read context

**iOS - Activation:**
- Settings > Accessibility > VoiceOver > Enable

**iOS - Navigation:**
- **Swipe Right**: Move forward
- **Swipe Left**: Move backward
- **Double-tap**: Activate
- **Two-finger swipe**: Speak entire screen

### Screen Reader Test Checklist

**All Pages:**
- [ ] Page title announced
- [ ] Page structure clear (headings, landmarks)
- [ ] All interactive elements announced
- [ ] Links have descriptive text (not just "click here")
- [ ] Buttons have text or aria-label
- [ ] Images have descriptive alt text
- [ ] Error messages announced
- [ ] Success messages announced
- [ ] Form section landmarks announced

**Forms Specifically:**
- [ ] Input label announced before input
- [ ] Input type announced (email, password, text, etc.)
- [ ] Required field status announced
- [ ] Valid/invalid state announced
- [ ] Error message linked to field
- [ ] Submit button announced

---

## Phase 5: Color Contrast Verification

### Using WebAIM Contrast Checker

**Online Tool:** https://webaim.org/resources/contrastchecker/

**Steps:**
1. Open contrast checker
2. For each text/background pair:
   - Enter foreground color (hex code)
   - Enter background color (hex code)
   - Check ratio shown
   - Verify ✓ AA or ✓ AAA

### Using Chrome DevTools

**Steps:**
1. Right-click element
2. Select "Inspect"
3. In Elements panel, select color swatch
4. DevTools shows contrast ratio
5. Verify AA ✓ or AAA ✓

### Text Contrast Requirements

**WCAG AA (Minimum):**
- Normal text: 4.5:1 ratio
- Large text (18px+ or 14px+ bold): 3:1 ratio

**WCAG AAA (Enhanced):**
- Normal text: 7:1 ratio
- Large text: 4.5:1 ratio

### Elements to Check

**On each page:**

| Element | Foreground | Background | Ratio Needed | ✓ |
|---------|-----------|-----------|--------------|---|
| Body text | #334155 | #ffffff | 4.5:1 | [ ] |
| Headings | #1e293b | #ffffff | 4.5:1 | [ ] |
| Primary button | #ffffff | #1D4533 | 4.5:1 | [ ] |
| Secondary button | #1D4533 | #f0fdf4 | 4.5:1 | [ ] |
| Error text | #ef4444 | #ffffff | 4.5:1 | [ ] |
| Links | #0369a1 | #ffffff | 4.5:1 | [ ] |
| Placeholder text | #94a3b8 | #ffffff | 3:1 | [ ] |
| Disabled button | #94a3b8 | #e2e8f0 | 3:1 | [ ] |
| Badge text | #ffffff | #1D4533 | 4.5:1 | [ ] |
| Footer text | #64748b | #0f172a | 4.5:1 | [ ] |

### Color Contrast Issues & Fixes

| Issue | Cause | Fix |
|-------|-------|-----|
| Low contrast | Colors too similar | Increase lightness/darkness difference |
| Button text unreadable | Light text on light background | Use dark text or darker background |
| Link color unclear | Blue on dark background | Use lighter blue or different color |
| Placeholder text too faint | Very light gray | Use darker gray (≥⅓ of black) |

---

## Phase 6: Visual Accessibility

### High Contrast Mode Testing

**Windows:**
1. Settings > Ease of Access > High Contrast
2. Select a high contrast theme
3. Reload page in browser
4. Verify:
   - [ ] Text remains readable
   - [ ] Buttons distinguishable
   - [ ] Form fields visible
   - [ ] No information lost

**macOS:**
1. System Preferences > Accessibility > Display
2. Check "Increase Contrast"
3. Reload page
4. Verify readability

### Zoom Testing (200%)

**Steps:**
1. In browser: Ctrl/Cmd + + (several times to reach 200%)
2. Verify:
   - [ ] No horizontal scrolling required
   - [ ] Text remains readable
   - [ ] Layout doesn't break
   - [ ] Interactive elements still accessible
   - [ ] No content cut off

### Color Blindness Simulation

**Online Tool:** https://www.color-blindness.com/coblis-color-blindness-simulator/

**Steps:**
1. Take screenshot of page
2. Upload to simulator
3. View in each color blindness mode:
   - [ ] Deuteranopia (red-green, most common)
   - [ ] Protanopia (red-green)
   - [ ] Tritanopia (blue-yellow)
   - [ ] Achromatopsia (complete)
4. Verify information is understandable without color

**Checklist:**
- [ ] Status information not conveyed by color alone
- [ ] Error state has icon + text (not just red)
- [ ] Success state has icon + text (not just green)
- [ ] Links distinguishable without color (underlined or bold)
- [ ] Buttons distinguishable without color (has border/shape)

### Animation & Motion Testing

**prefers-reduced-motion:**

1. **Windows:**
   - Settings > Ease of Access > Display
   - Check "Show animations"

2. **macOS:**
   - System Preferences > Accessibility > Display
   - Check "Reduce motion"

3. **iOS:**
   - Settings > Accessibility > Motion
   - Enable "Reduce Motion"

4. Reload page
5. Verify:
   - [ ] Essential content visible (not in animation)
   - [ ] No distracting animations
   - [ ] No rapid flashing (>3 flashes per second)

---

## Phase 7: Form Accessibility

### Form Structure Check

For each form on the page:
- [ ] Each input has associated `<label>` element
- [ ] Label `for` attribute matches input `id`
- [ ] Required fields marked with `required` attribute or aria-required
- [ ] Error messages linked to fields with `aria-describedby`
- [ ] Error text appears below field, not in placeholder

### Input Validation Testing

**For each form field:**

1. Leave field empty, submit
   - [ ] Error message appears
   - [ ] Error message is red (#ef4444)
   - [ ] Error text font ≥0.75rem
   - [ ] Focus returns to field with error

2. Enter invalid data (e.g., invalid email)
   - [ ] Error message specific (not "Invalid")
   - [ ] Field highlighted with red border
   - [ ] Error visible on mobile without scrolling

3. Correct the error
   - [ ] Error message disappears
   - [ ] Border color returns to normal
   - [ ] Field can be submitted successfully

### Touch Target Size Verification

For each form input:
- [ ] Minimum height: 44px
- [ ] Minimum width: 44px
- [ ] Sufficient space between inputs (≥0.5rem gap)
- [ ] Labels positioned above or to the left (not floating)
- [ ] Labels easily clickable (at least 44px tall)

---

## Phase 8: Focus State Visibility

### Visual Focus Indicator Check

For each interactive element (button, link, input, checkbox):

**When focused (during Tab navigation):**
- [ ] Focus outline visible (≥2px)
- [ ] Focus outline contrasts with background
- [ ] Focus outline not cut off
- [ ] Focus outline surrounds entire element
- [ ] Focus indicator persistent (not removed on interaction)

**Expected Results:**
```
Button focus:    2px solid outline with subtle shadow
Input focus:     2px solid border change + shadow
Link focus:      2px solid outline
Checkbox focus:  2px solid outline around box
```

### Focus Order Verification

1. Open page
2. Press Tab repeatedly
3. Verify focus order matches visual layout:
   - Starts at top of page
   - Moves left-to-right, top-to-bottom
   - No backwards jumps
   - All interactive elements reached
   - Never trapped in loop

---

## Master Accessibility Checklist

### Automated Testing
- [ ] Lighthouse score ≥90/100 on all pages
- [ ] axe DevTools: zero critical issues
- [ ] axe DevTools: minimal serious issues (document reasons)

### Manual Keyboard Testing
- [ ] All interactive elements reachable via Tab
- [ ] Focus order is logical
- [ ] Focus always visible
- [ ] Escape closes modals/menus
- [ ] Enter activates buttons
- [ ] Space toggles checkboxes

### Screen Reader Testing
- [ ] NVDA reads page correctly (Windows)
- [ ] VoiceOver reads page correctly (macOS)
- [ ] Form labels announced with inputs
- [ ] Headings announced hierarchically
- [ ] Error messages announced
- [ ] Success messages announced

### Color & Contrast
- [ ] All text ≥4.5:1 contrast (normal text)
- [ ] All large text ≥3:1 contrast
- [ ] No information conveyed by color alone
- [ ] Color blind simulation readable

### Visual Accessibility
- [ ] Readable at 200% zoom
- [ ] High contrast mode readable
- [ ] No flashing/seizure-inducing animations
- [ ] Reduced motion respected
- [ ] Touch targets ≥44px

### Form Accessibility
- [ ] Semantic labels paired with inputs
- [ ] Error messages clear and actionable
- [ ] Validation feedback visible on mobile
- [ ] Form submission confirmed
- [ ] Keyboard navigation through form

---

## Sign-Off Template

**Accessibility Audit Completed:**

| Page | Lighthouse | axe Issues | Keyboard | Screen Reader | Contrast | Status |
|------|-----------|-----------|----------|---------------|----------|--------|
| Login | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |
| Register | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |
| Forgot Password | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |
| Resources | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |
| Safety Protocols | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |
| Support | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |
| Welcome | ___/100 | _____ | ✓/✗ | ✓/✗ | ✓/✗ | PASS/FAIL |

**Overall Status:** PASS ✓ / FAIL ✗

**Auditor:** _________________________ **Date:** _________

**Notes:**
_________________________________________________________________
_________________________________________________________________

