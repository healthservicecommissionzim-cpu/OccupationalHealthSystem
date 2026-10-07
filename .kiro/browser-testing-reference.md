# Browser Testing Reference Guide

## Quick Browser Testing Checklist

This guide provides quick reference for testing across all required browsers.

---

## Desktop Browsers

### Chrome (Latest)
**Setup:** https://www.google.com/chrome/

**Quick Test Procedure:**
1. Open https://injury.hsc.org.zw
2. Press F12 to open DevTools
3. Check Console tab - should have 0 errors
4. Check Network tab - all requests should load
5. Responsive Design Mode: Ctrl+Shift+M
6. Test at breakpoints: 320px, 500px, 768px, 1024px, 1440px

**Testing Checklist:**
- [ ] Page loads without errors
- [ ] No console errors (red X)
- [ ] All images load
- [ ] CSS loads correctly
- [ ] Fonts render (Plus Jakarta Sans, not Inter)
- [ ] Form inputs work
- [ ] Buttons clickable
- [ ] No horizontal scrolling at breakpoints
- [ ] Mobile responsive layout works

**Tools to Use:**
- DevTools Performance tab: Check paint times
- DevTools Accessibility tab: Inspect accessibility properties
- DevTools Lighthouse: Run accessibility audit

---

### Firefox (Latest)
**Setup:** https://www.mozilla.org/f