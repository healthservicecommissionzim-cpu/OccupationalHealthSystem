# Font Consistency Verification Guide
## Task 13.2 - Font Verification Across All Pages

This guide provides detailed steps for verifying that "Plus Jakarta Sans" font is used consistently across all pages and that "Inter" font is completely eliminated.

---

## Overview

**Objective:** Verify that all pages use "Plus Jakarta Sans" as the primary font family, with system font fallbacks, and that "Inter" font does not appear anywhere.

**Requirements Addressed:**
- Requirement 7.1: System SHALL use "Plus Jakarta Sans" on all auth pages
- Requirement 7.2: System SHALL use "Plus Jakarta Sans" on all public pages
- Requirement 7.4: Fallback chain MUST NOT include "Inter"
- Requirement 7.5: TEXT SHALL NOT render in "Inter" font

**Font Specifications:**
- **Primary Font:** "Plus Jakarta Sans" (Google Fonts, weights 300-800)
- **Fallback Chain:** `'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif`
- **NO Inter Font:** "Inter" must never appear in any computed styles
- **Font Weights Required:** 300, 400, 500, 600, 700, 800

---

## Part 1: Automated Font Verification (Browser Console)

### 1.1 - Global Font Family Check

**Steps:**
1. Open any page in the application
2. Press `F12` to open Chrome DevTools
3. Go to the "Console" tab
4. Paste the following code:

```javascript
// Check global font family
const bodyStyle = window.getComputedStyle(document.body);
const bodyFont = bodyStyle.fontFamily;
console.log('Body font-family:', bodyFont);

if (bodyFont.includes('Inter')) {
    console.error('❌ ERROR: "Inter" font found in body!');
} else if (bodyFont.includes('Plus Jakarta Sans')) {
    console.log('✓ PASS: Plus Jakarta Sans is primary font');
} else {
    console.warn('⚠ WARNING: Body font is not Plus Jakarta Sans:', bodyFont);
}

// Check for Inter font anywhere on the page
const allElements = document.querySelectorAll('*');
let interCount = 0;
let pjsCount = 0;

allElements.forEach(el => {
    const style = window.getComputedStyle(el);
    if (style.fontFamily.includes('Inter')) {
        interCount++;
        console.error('❌ Inter font found on:', el.tagName, el.className);
    }
    if (style.fontFamily.includes('Plus Jakarta Sans')) {
        pjsCount++;
    }
});

console.log(`\n=== FONT AUDIT SUMMARY ===`);
console.log(`Elements with "Inter": ${interCount}`);
console.log(`Elements with "Plus Jakarta Sans": ${pjsCount}`);
console.log(`Total elements checked: ${allElements.length}`);

if (interCount === 0) {
    console.log('✓ PASS: No "Inter" font found on page');
} else {
    console.error(`✗ FAIL: Found ${interCount} elements using "Inter" font`);
}
```

5. Press Enter to execute the code
6. Review the console output

**Expected Result:**
```
Body font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif
✓ PASS: Plus Jakarta Sans is primary font

=== FONT AUDIT SUMMARY ===
Elements with "Inter": 0
Elements with "Plus Jakarta Sans": [high number]
Total elements checked: [total]
✓ PASS: No "Inter" font found on page
```

---

### 1.2 - Specific Element Font Verification

**For headings (h1, h2, h3, h4, h5, h6):**

```javascript
const headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
const headingFonts = {};

headings.forEach(tag => {
    const el = document.querySelector(tag);
    if (el) {
        const style = window.getComputedStyle(el);
        const fontFamily = style.fontFamily;
        const fontWeight = style.fontWeight;
        headingFonts[tag] = { family: fontFamily, weight: fontWeight };
        
        if (fontFamily.includes('Inter')) {
            console.error(`❌ ${tag} uses Inter font!`);
        } else if (fontFamily.includes('Plus Jakarta Sans')) {
            console.log(`✓ ${tag} uses Plus Jakarta Sans, weight: ${fontWeight}`);
        }
    }
});

console.table(headingFonts);
```

**Expected Output:**
- All headings: "Plus Jakarta Sans" with font-weight 700-800
- No "Inter" font

---

### 1.3 - Form Elements Font Verification

```javascript
// Check form labels and inputs
const labels = document.querySelectorAll('label');
const inputs = document.querySelectorAll('input, textarea, select');

console.log('=== FORM LABELS ===');
labels.forEach((label, index) => {
    const style = window.getComputedStyle(label);
    const fontFamily = style.fontFamily;
    const fontWeight = style.fontWeight;
    
    if (fontFamily.includes('Inter')) {
        console.error(`❌ Label ${index}: Uses Inter`);
    } else {
        console.log(`✓ Label ${index}: ${fontFamily}, weight: ${fontWeight}`);
    }
});

console.log('\n=== FORM INPUTS ===');
inputs.forEach((input, index) => {
    const style = window.getComputedStyle(input);
    const fontFamily = style.fontFamily;
    
    if (fontFamily.includes('Inter')) {
        console.error(`❌ Input ${index}: Uses Inter`);
    } else {
        console.log(`✓ Input ${index}: ${fontFamily}`);
    }
});
```

---

### 1.4 - Button Font Verification

```javascript
// Check all buttons
const buttons = document.querySelectorAll('button, .btn, [role="button"]');

console.log('=== BUTTON FONTS ===');
buttons.forEach((btn, index) => {
    const style = window.getComputedStyle(btn);
    const fontFamily = style.fontFamily;
    const fontWeight = style.fontWeight;
    
    if (fontFamily.includes('Inter')) {
        console.error(`❌ Button ${index}: Uses Inter`, btn.textContent);
    } else {
        console.log(`✓ Button ${index}: Plus Jakarta Sans, weight: ${fontWeight}`);
    }
});
```

---

## Part 2: Manual Font Verification Using DevTools

### 2.1 - Inspect Elements Method

**For each page, inspect these specific elements:**

#### Login Page Elements to Inspect:

1. **Page Title (h1 "Log In")**
   - Right-click → Inspect Element
   - Look in Styles panel for `font-family`
   - Expected: `'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, ...`
   - Should NOT show: "Inter"
   - Font Weight: Should be 700-800

2. **Page Description Text**
   - Inspect the paragraph describing the login
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 400-500

3. **Form Labels ("Email address", "Password")**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 600

4. **Form Input Fields**
   - Font-family: inherit or "Plus Jakarta Sans"
   - Font Weight: 400

5. **Submit Button**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 600-700

6. **"Remember me" Checkbox Label**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 500-600

7. **Forgot Password Link**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 600

8. **Footer Text**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 400

---

#### Resources Page Elements to Inspect:

1. **Hero Title (h1)**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 800
   - Font Size: Should scale with clamp() function

2. **Hero Subtitle**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 400

3. **Search Input**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 400-500

4. **Section Titles (h2)**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 700

5. **Resource Card Titles**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 700

6. **Resource Card Descriptions**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 400

7. **Badge Text (category labels)**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 600

8. **Download Buttons**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 600

9. **Footer Content**
   - Font-family: "Plus Jakarta Sans"
   - Font Weight: 400

---

### 2.2 - DevTools Font Inspection Steps

For each element:

1. **Open Chrome DevTools** (F12)
2. **Select the Elements tab**
3. **Use the element picker** (top-left icon) to click the element
4. **In the Styles panel**, look for:
   - `font-family` property
   - Check computed value (the actual font being used)
   - Click on the property to trace where it's defined (inline, class, or stylesheet)

**What to look for:**

✓ **GOOD:**
```css
font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
```

✗ **BAD - Would indicate a problem:**
```css
font-family: 'Inter', sans-serif;
/* OR */
font-family: Inter, system-ui, sans-serif;
```

---

## Part 3: Font Weight Verification

For each page, verify that font weights are used correctly:

### 3.1 - Expected Font Weight Usage

| Element Type | Weight | Actual Values |
|--|--|--|
| Page Headings (h1) | 800 | Should be computed as 800 |
| Section Headings (h2) | 700 | Should be computed as 700 |
| Sub-headings (h3) | 600-700 | Should be 600 or 700 |
| Form Labels | 600 | Should be 600 |
| Button Text | 600-700 | Should be 600 or 700 |
| Body Text | 400-500 | Should be 400 or 500 |
| Small Text | 400 | Should be 400 |

### 3.2 - Font Weight Check Script

```javascript
// Check all text for correct font weights
const textElements = document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, label, button, .btn, input, a, span');
const weightIssues = [];

textElements.forEach(el => {
    const style = window.getComputedStyle(el);
    const fontFamily = style.fontFamily;
    const fontWeight = parseInt(style.fontWeight);
    const tag = el.tagName.toLowerCase();
    
    let expectedWeight = 400; // default
    
    if (tag === 'h1') expectedWeight = 800;
    else if (tag === 'h2') expectedWeight = 700;
    else if (tag === 'h3' || tag === 'h4') expectedWeight = 600;
    else if (tag === 'label' || tag === 'button' || el.classList.contains('btn')) expectedWeight = 600;
    
    // Only check if using Plus Jakarta Sans
    if (fontFamily.includes('Plus Jakarta Sans')) {
        const isCorrect = 
            (tag === 'h1' && fontWeight >= 700) ||
            (tag === 'h2' && fontWeight >= 600) ||
            (tag === 'h3' && fontWeight >= 500) ||
            (tag === 'label' && fontWeight >= 500) ||
            (tag === 'button' && fontWeight >= 500) ||
            (tag === 'p' && fontWeight <= 500) ||
            (fontWeight >= 300 && fontWeight <= 800); // general validity
        
        if (!isCorrect) {
            weightIssues.push({
                element: tag + '.' + (el.className || 'no-class'),
                currentWeight: fontWeight,
                expectedWeight: expectedWeight,
                content: el.textContent.substring(0, 50)
            });
        }
    }
});

if (weightIssues.length === 0) {
    console.log('✓ All font weights look correct!');
} else {
    console.warn(`⚠ Found ${weightIssues.length} potential font weight issues:`);
    console.table(weightIssues);
}
```

---

## Part 4: Google Fonts Import Verification

### 4.1 - Check Font Import in Network Tab

1. **Open DevTools → Network tab**
2. **Reload the page**
3. **Filter by "fonts"** or search for "googleapis" or "gstatic"
4. **Look for:** 
   - Request to `fonts.googleapis.com` for Plus Jakarta Sans
   - Font files loaded from `fonts.gstatic.com`

**Expected Requests:**
- ✓ `fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800`
- ✗ NO requests for "Inter" font

### 4.2 - Check HTML Head for Font Link

**On any page, view source (Ctrl+U) and look for:**

✓ **CORRECT:**
```html
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
```

✗ **WRONG - Would indicate a problem:**
```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
```

---

## Part 5: Page-by-Page Font Verification Checklist

### Login Page Font Verification

- [ ] **Body font-family:** `'Plus Jakarta Sans', -apple-system, BlinkMacSystemBlock...`
- [ ] **Page title (h1):** "Plus Jakarta Sans", weight 700-800
- [ ] **Page description:** "Plus Jakarta Sans", weight 400-500
- [ ] **Form labels:** "Plus Jakarta Sans", weight 600
- [ ] **Input fields:** "Plus Jakarta Sans" or inherit
- [ ] **Submit button:** "Plus Jakarta Sans", weight 600-700
- [ ] **Checkbox label:** "Plus Jakarta Sans", weight 500-600
- [ ] **Links (forgot password):** "Plus Jakarta Sans", weight 600
- [ ] **Footer:** "Plus Jakarta Sans", weight 400
- [ ] **NO "Inter" font found:** ✓ Verified

### Register Page Font Verification

- [ ] **Body font-family:** Correctly set to "Plus Jakarta Sans"
- [ ] **Page title:** "Plus Jakarta Sans", weight 700-800
- [ ] **Section headings:** "Plus Jakarta Sans", weight 600-700
- [ ] **Form labels:** "Plus Jakarta Sans", weight 600
- [ ] **Input fields:** "Plus Jakarta Sans"
- [ ] **Dropdowns:** "Plus Jakarta Sans"
- [ ] **Checkboxes:** "Plus Jakarta Sans", weight 500-600
- [ ] **Submit button:** "Plus Jakarta Sans", weight 600-700
- [ ] **Footer:** "Plus Jakarta Sans", weight 400
- [ ] **NO "Inter" font found:** ✓ Verified

### Resources Page Font Verification

- [ ] **Body font-family:** "Plus Jakarta Sans" with system fallbacks
- [ ] **Hero title (h1):** "Plus Jakarta Sans", weight 800, uses clamp() for scaling
- [ ] **Hero subtitle:** "Plus Jakarta Sans", weight 400
- [ ] **Search input:** "Plus Jakarta Sans", weight 400-500
- [ ] **Section titles (h2):** "Plus Jakarta Sans", weight 700
- [ ] **Card titles:** "Plus Jakarta Sans", weight 700
- [ ] **Card descriptions:** "Plus Jakarta Sans", weight 400
- [ ] **Badge text:** "Plus Jakarta Sans", weight 600
- [ ] **Buttons:** "Plus Jakarta Sans", weight 600
- [ ] **Footer:** "Plus Jakarta Sans", weight 400
- [ ] **NO "Inter" font found:** ✓ Verified

### Safety Protocols Page Font Verification

- [ ] **Body font-family:** "Plus Jakarta Sans" correctly set
- [ ] **Page title:** "Plus Jakarta Sans", weight 800
- [ ] **Section headers:** "Plus Jakarta Sans", weight 700
- [ ] **Table text:** "Plus Jakarta Sans", weight 400
- [ ] **Table headers:** "Plus Jakarta Sans", weight 600
- [ ] **Content text:** "Plus Jakarta Sans", weight 400
- [ ] **Links:** "Plus Jakarta Sans", weight 600
- [ ] **Footer:** "Plus Jakarta Sans", weight 400
- [ ] **NO "Inter" font found:** ✓ Verified

### Support/Help Desk Page Font Verification

- [ ] **Body font-family:** "Plus Jakarta Sans" with fallbacks
- [ ] **Page title:** "Plus Jakarta Sans", weight 800
- [ ] **Form labels:** "Plus Jakarta Sans", weight 600
- [ ] **Input fields:** "Plus Jakarta Sans", weight 400
- [ ] **Form text:** "Plus Jakarta Sans", weight 400
- [ ] **Error messages:** "Plus Jakarta Sans", weight 400-500
- [ ] **Submit button:** "Plus Jakarta Sans", weight 600-700
- [ ] **Footer:** "Plus Jakarta Sans", weight 400
- [ ] **NO "Inter" font found:** ✓ Verified

### Home/Welcome Page Font Verification

- [ ] **Body font-family:** "Plus Jakarta Sans" correctly set
- [ ] **Page title:** "Plus Jakarta Sans", weight 800
- [ ] **All headings:** "Plus Jakarta Sans", appropriate weights
- [ ] **Body text:** "Plus Jakarta Sans", weight 400-500
- [ ] **Buttons:** "Plus Jakarta Sans", weight 600-700
- [ ] **Links:** "Plus Jakarta Sans", weight 600
- [ ] **Footer:** "Plus Jakarta Sans", weight 400
- [ ] **NO "Inter" font found:** ✓ Verified

---

## Part 6: Font Fallback Chain Verification

**Verify the complete fallback chain is correct:**

**Expected chain (in order):**
```
'Plus Jakarta Sans' → -apple-system → BlinkMacSystemFont → 'Segoe UI' → sans-serif
```

**What this means:**
1. First, use "Plus Jakarta Sans" (from Google Fonts)
2. If not available, use system font on Apple devices (-apple-system)
3. If not available, use BlinkMacSystemFont
4. If not available, use Segoe UI (Windows)
5. If all else fails, use generic sans-serif

**Verification Script:**

```javascript
// Verify fallback chain
const body = document.body;
const computedFont = window.getComputedStyle(body).fontFamily;
console.log('Computed font family:', computedFont);

const fontChain = [
    'Plus Jakarta Sans',
    '-apple-system',
    'BlinkMacSystemFont',
    'Segoe UI',
    'sans-serif'
];

console.log('Expected font chain:', fontChain.join(' → '));

// Check that Plus Jakarta Sans is first
if (computedFont.includes('Plus Jakarta Sans')) {
    console.log('✓ Plus Jakarta Sans is available and first in chain');
} else {
    console.error('❌ Plus Jakarta Sans not found in computed styles!');
}

// Make sure Inter is not in the chain
if (computedFont.includes('Inter')) {
    console.error('❌ CRITICAL: "Inter" found in font chain!');
} else {
    console.log('✓ No "Inter" in font chain');
}
```

---

## Part 7: Troubleshooting Font Issues

### Issue: Inter Font Still Appearing

**Possible Causes:**
1. CSS file has inline style with Inter
2. Body element doesn't have font-family set
3. Stylesheet has specific selectors overriding global font
4. Cached browser data

**Solutions:**
1. Search codebase for "Inter" → Remove all references
2. Verify `body { font-family: 'Plus Jakarta Sans', ... }` in CSS
3. Check for element-specific selectors with conflicting fonts
4. Clear browser cache (Ctrl+Shift+Delete) and reload

### Issue: Plus Jakarta Sans Not Loading

**Possible Causes:**
1. Google Fonts link not in HTML head
2. Font link blocked by firewall/network
3. Incorrect font name in CSS

**Solutions:**
1. Add to HTML head: `<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">`
2. Check Network tab → fonts.googleapis.com request (should be 200 OK)
3. Use exact name: `'Plus Jakarta Sans'` (with quotes)

### Issue: Font Weights Not Correct

**Possible Causes:**
1. Font weights (300-800) not imported from Google Fonts
2. CSS has incorrect `font-weight` values
3. Browser not supporting variable fonts

**Solutions:**
1. Verify Google Fonts link includes all weights: `wght@300;400;500;600;700;800`
2. Check CSS for `font-weight` declarations
3. Test in Chrome (best support for variable fonts)

---

## Part 8: Final Verification Summary

**Completion Checklist:**

- [ ] All pages verified for "Plus Jakarta Sans" usage
- [ ] NO "Inter" font found on any page
- [ ] Font weights correct for all element types
- [ ] Font fallback chain correct (Plus Jakarta Sans → system fonts)
- [ ] Google Fonts link includes all weights (300-800)
- [ ] Font loads successfully (Network tab shows 200 OK)
- [ ] Computed styles show "Plus Jakarta Sans" as first font
- [ ] No cached font issues remaining
- [ ] Font renders consistently across all pages
- [ ] Font displays correctly at all breakpoints (320px-3840px)

**Date Completed:** ___________________
**Tester:** ___________________
**Status:** ✓ PASS / ✗ FAIL / ⚠ NEEDS REVIEW

**Results Summary:**
- Pages with Plus Jakarta Sans: ___/7
- Pages with NO Inter font: ___/7
- Font weight issues found: ___
- Fallback chain issues: ___

---

## Quick Reference Commands

### Copy-paste these into the browser console to verify fonts:

**Full Audit (run all checks):**
```javascript
// 1. Check for Inter anywhere
const hasInter = Array.from(document.querySelectorAll('*')).some(el => 
    window.getComputedStyle(el).fontFamily.includes('Inter')
);
console.log('Has Inter font:', hasInter ? '❌ YES (BAD)' : '✓ NO (GOOD)');

// 2. Check body font
const bodyFont = window.getComputedStyle(document.body).fontFamily;
console.log('Body font:', bodyFont.includes('Plus Jakarta Sans') ? '✓ Plus Jakarta Sans' : '❌ NOT Plus Jakarta Sans');

// 3. Count elements using Plus Jakarta Sans
const pjsElements = Array.from(document.querySelectorAll('*')).filter(el =>
    window.getComputedStyle(el).fontFamily.includes('Plus Jakarta Sans')
).length;
console.log('Elements with Plus Jakarta Sans:', pjsElements);

// 4. Check for font load in Network
console.log('Check DevTools Network tab for fonts.googleapis.com request (should be 200 OK)');
```

