# Performance Testing Guide: Mobile Responsive Auth & Public Pages

## Overview

This guide provides step-by-step instructions for testing the mobile-responsive authentication and public pages on throttled networks (simulating 3G connections). The goal is to ensure optimal performance with LCP (Largest Contentful Paint) target of <2.5s on 3G networks.

## Prerequisites

- Chrome DevTools or Firefox Developer Tools
- Access to local development server
- Pages to test: login, register, resources, safety protocols, support
- Network conditions: 3G throttle (as defined below)

## Network Throttling Configuration

### 3G Slow Network Conditions
- **Downlink**: 400 kbps
- **Uplink**: 400 kbps
- **Latency**: 400ms
- **Protocol**: HTTP/1.1

### Testing Methodology

1. **Disable Cache**: Disable local caching to simulate first-time visits
2. **Network Throttle**: Apply 3G throttling profile
3. **Measure Metrics**: Capture FCP, LCP, and CLS
4. **Multiple Runs**: Test 3 times and record average

## Chrome DevTools Testing Steps

### 1. Open DevTools
- Press `F12` or `Ctrl+Shift+I` (Windows/Linux) / `Cmd+Option+I` (Mac)
- Navigate to **Performance** tab

### 2. Configure Network Throttling
1. Open **DevTools** → **Network** tab
2. Click the dropdown showing "No throttling" (top-left)
3. Select **Slow 3G** profile (or configure custom):
   - Downlink: 400 kbps
   - Uplink: 400 kbps
   - Latency: 400ms

### 3. Clear Cache
1. In **Network** tab, check **"Disable cache"** checkbox
2. Or press `Ctrl+Shift+Delete` to clear browser cache

### 4. Record Performance
1. Click **Reload** button (or press `F5`)
2. Observe **Performance** tab during page load
3. Wait for page to fully load
4. Review metrics:
   - **FCP** (First Contentful Paint): Should appear in ~1-1.5s
   - **LCP** (Largest Contentful Paint): Target <2.5s
   - **CLS** (Cumulative Layout Shift): Should be <0.1

### 5. Export Results
1. Click **Export** (bottom right of Performance tab)
2. Save as JSON file with page name and date

## Firefox Developer Tools Testing Steps

### 1. Open DevTools
- Press `F12` or `Ctrl+Shift+I` (Windows/Linux) / `Cmd+Option+I` (Mac)
- Navigate to **Performance** tab

### 2. Configure Network Throttling
1. Open **Settings** (gear icon, top-right)
2. Check **"Enable network throttling"**
3. Select **3G Fast** or **3G Slow** preset
4. Or manually configure:
   - Download: 400 kbps
   - Upload: 400 kbps
   - Latency: 400ms

### 3. Record Performance
1. Click **Record** button
2. Reload the page (`F5`)
3. Wait for page to fully load
4. Stop recording
5. Review metrics in **Timings** section

## Testing Checklist

### Page: Login
- [ ] FCP appears in <1.5s
- [ ] LCP completes in <2.5s
- [ ] Form fields are interactive within 2s
- [ ] No layout shift when Plus Jakarta Sans loads
- [ ] Button is clickable

### Page: Register
- [ ] FCP appears in <1.5s
- [ ] LCP completes in <2.5s
- [ ] Form sections load progressively
- [ ] Dropdowns function after 2s
- [ ] No scrolling jank when forms render

### Page: Resources
- [ ] FCP appears in <1.5s
- [ ] LCP completes in <2.5s (resource cards visible)
- [ ] Grid layout renders without shift
- [ ] Images load progressively
- [ ] Search functionality available within 3s

### Page: Safety Protocols
- [ ] FCP appears in <1.5s
- [ ] LCP completes in <2.5s
- [ ] Content sections render without shift
- [ ] No horizontal scrolling artifacts
- [ ] Tables display responsively

### Page: Support Form
- [ ] FCP appears in <1.5s
- [ ] LCP completes in <2.5s
- [ ] Form inputs are focusable within 2s
- [ ] No delay between clicks and field focus

## Performance Metrics Explained

### First Contentful Paint (FCP)
- **Definition**: Time when first content appears on screen
- **Target**: <1.5s on 3G
- **Improvement**: Optimize CSS, defer non-critical JS

### Largest Contentful Paint (LCP)
- **Definition**: Time when largest visible element renders
- **Target**: <2.5s on 3G (requirement: 15.1, 15.4)
- **Improvement**: Lazy-load images, optimize fonts, reduce CSS

### Cumulative Layout Shift (CLS)
- **Definition**: Unintended layout shifts during loading
- **Target**: <0.1
- **Improvement**: Reserve space for images/ads, avoid late-injected content

## Font Loading Performance

### Verifying `font-display: swap`
1. Open **Network** tab
2. Look for Google Fonts requests
3. Check **Timing** column - font should load asynchronously
4. Verify: Text visible immediately in fallback font
5. Observe: Font switches to Plus Jakarta Sans when loaded
6. Result: No layout shift should occur

### Checking Font Weights
1. Use DevTools **Computed** tab
2. Select text element
3. Verify `font-family: 'Plus Jakarta Sans'` (not fallback)
4. Verify appropriate `font-weight` (400, 600, 700, etc.)
5. Check no "Inter" font appears

## Optimization Recommendations

### If LCP > 2.5s
1. **Reduce CSS size**: Remove unused styles
2. **Defer JavaScript**: Load non-critical scripts async
3. **Lazy-load images**: Use `loading="lazy"` attribute
4. **Enable compression**: Gzip CSS files
5. **Optimize images**: Use WebP format with fallbacks

### If CLS > 0.1
1. **Set image dimensions**: Prevent size jumps
2. **Reserve space for dynamically injected content**: Use placeholder height
3. **Avoid web fonts causing shifts**: Use `font-display: swap`
4. **Test with font loading disabled**: Verify fallback metrics

### If FCP > 1.5s
1. **Inline critical CSS**: Move above-the-fold styles to `<head>`
2. **Preload resources**: Add `<link rel="preload">` for fonts/images
3. **Reduce JavaScript**: Defer non-essential scripts
4. **Enable caching**: Set proper cache headers

## Real Device Testing

### Mobile Testing on 3G
1. **iOS**: Use Network Link Conditioner tool
   - Download from Apple Additional Tools
   - Configure 3G profile
   - Run page load test

2. **Android**: Use Android Studio Emulator
   - Set network speed to EDGE or UMTS
   - Test page load performance
   - Measure with Chrome DevTools

### Field Data Collection
- Use Google Analytics 4 to monitor real-world LCP
- Check **Performance** reports for regional data
- Target: 75th percentile <2.5s on 3G

## Testing Results Template

```
Page: [LOGIN/REGISTER/RESOURCES/SAFETY/SUPPORT]
Date: [YYYY-MM-DD]
Network: 3G Slow (400kbps, 400ms latency)
Cache: Disabled

Run 1:
- FCP: [time]ms ✓/✗
- LCP: [time]ms ✓/✗
- CLS: [value] ✓/✗
- Issues: [list any]

Run 2:
- FCP: [time]ms ✓/✗
- LCP: [time]ms ✓/✗
- CLS: [value] ✓/✗
- Issues: [list any]

Run 3:
- FCP: [time]ms ✓/✗
- LCP: [time]ms ✓/✗
- CLS: [value] ✓/✗
- Issues: [list any]

Average:
- FCP: [avg]ms (Target: <1500ms) ✓/✗
- LCP: [avg]ms (Target: <2500ms) ✓/✗
- CLS: [avg] (Target: <0.1) ✓/✗

Recommendations:
- [List optimization recommendations if needed]
```

## Continuous Monitoring

### Lighthouse Testing
1. Open page in Chrome
2. Press `F12` → **Lighthouse** tab
3. Select **Mobile** device
4. Click **Generate report**
5. Review **Performance** score
6. Target: 90+ score

### WebPageTest
1. Visit https://www.webpagetest.org
2. Enter page URL
3. Select **3G - Slow** network
4. Choose **Mobile** device
5. Run test and analyze results
6. Compare with baseline metrics

## Tools & References

### Performance Testing Tools
- Chrome DevTools (built-in)
- Firefox Developer Tools (built-in)
- WebPageTest (webpagetest.org)
- Lighthouse (built-in to Chrome)
- Google PageSpeed Insights

### Resources
- [Web Vitals Documentation](https://web.dev/vitals/)
- [Optimizing Fonts Performance](https://web.dev/font-display/)
- [Network Throttling Guide](https://developer.chrome.com/docs/devtools/network/throttling/)
- [CSS Performance Tips](https://web.dev/css/)

## Notes

- All tests performed with cache disabled to simulate worst-case scenario
- Font loading uses `font-display: swap` to prevent FOUT/FOIT
- Media queries optimized to reduce CSS size
- All pages tested at 320px viewport width (mobile)
- Requirements: 15.1, 15.4
