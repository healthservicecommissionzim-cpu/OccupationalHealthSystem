# Design Document: Mobile Responsive Auth & Public Pages

## Overview

This design addresses making all authentication and public-facing pages mobile-responsive while maintaining HSC brand consistency and using "Plus Jakarta Sans" as the canonical font. The solution focuses on responsive CSS architecture using mobile-first design principles with defined breakpoints (320px, 380px, 500px, 768px, 1024px, 1440px, 4K).

The approach involves:
1. Unifying font family across all pages (replacing "Inter" with "Plus Jakarta Sans")
2. Implementing responsive grid and layout systems
3. Creating touch-friendly form elements and buttons
4. Ensuring accessibility and WCAG 2.1 Level AA compliance
5. Optimizing for performance on mobile networks

## Architecture

### Component Organization

The system is organized into three main layers:

1. **Global Styles Layer**
   - CSS variables for colors, spacing, typography, shadows, and breakpoints
   - Base element resets and accessibility foundations
   - Animation definitions and transitions
   - Font loading and fallback strategies

2. **Responsive Layout Layer**
   - Mobile-first media queries at defined breakpoints
   - Flexible grid systems (CSS Grid, Flexbox)
   - Container-based responsive patterns
   - Viewport-based scaling for headers, footers, forms

3. **Component Layer**
   - Individual component styles (forms, buttons, cards, navigation)
   - Component variants for different screen sizes
   - Interaction states (hover, focus, active, disabled)
   - Error and validation states

### Responsive Breakpoint Strategy

**Mobile-first approach with these breakpoints:**
- `320px`: Ultra-small phones (SE, older models)
- `380px`: Small phones
- `500px`: Medium phones (landscape mode)
- `768px`: Tablets and small laptops
- `1024px`: Standard desktops
- `1440px`: Large monitors
- `3840px`: 4K displays

**Implementation pattern:**
```css
/* Base styles - mobile first */
.element { /* mobile defaults */ }

/* Tablet and up */
@media (min-width: 768px) { .element { /* tablet styles */ } }

/* Desktop and up */
@media (min-width: 1024px) { .element { /* desktop styles */ } }
```

### Font System

**Primary Font: Plus Jakarta Sans**
- Imported from Google Fonts with all weights (300-800)
- Used on ALL text elements: headings, body, labels, buttons, badges
- Fallback chain: `'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif`
- NO use of "Inter" or any other font family

**Typography Scaling:**
- Use `clamp()` for fluid typography on headings
- Fixed sizing with media query adjustments for body text
- Maintain 1.5-1.6 line-height for readability
- Base font size: 16px on mobile, scales to 18px on desktop

## Components and Interfaces

### 1. Form System

**Mobile Optimizations:**
- Single-column layout on screens ≤500px
- Input fields: minimum 44px height for touch targets
- Label text: 0.75-0.8rem for clarity without overwhelming
- Field spacing: minimum 1rem vertical gap
- Error messages: positioned below field, red text (#ef4444), 0.75rem font

**Focus States:**
- Border color changes to brand color (#1D4533)
- 3px outline with brand-glow shadow
- No focus loss in mobile browsers

**Responsive Adjustments:**
```
Mobile (≤500px):
- Input padding: 0.5rem 0.75rem
- Font size: 0.85rem
- Border-radius: 6px

Tablet (501-768px):
- Input padding: 0.6rem 0.9rem
- Font size: 0.9rem
- Border-radius: 8px

Desktop (769px+):
- Input padding: 0.7rem 1rem
- Font size: 0.95rem
- Border-radius: 10px
```

### 2. Button System

**Touch-Friendly Design:**
- Minimum dimensions: 44px × 44px for clickable area
- Primary button: brand green (#1D4533) background
- Text: white, "Plus Jakarta Sans" font weight 600-700

**States:**
- Normal: 0 2px 8px shadow with brand-glow
- Hover (desktop): scale 1.05, enhanced shadow, darker background
- Focus: visible outline + shadow
- Active/Pressed: scale 0.98
- Disabled: 0.6 opacity, no shadow, no hover effects

### 3. Navigation System

**Mobile (<768px):**
- Header: sticky, 1rem padding
- Navigation: hidden, accessible via hamburger menu
- Menu overlay: full-screen, 280px sidebar on desktop
- Logo: 40px height
- Buttons: stacked vertically

**Tablet (768px-1024px):**
- Header: sticky, 1rem padding
- Navigation: visible, gap 1.5rem between items
- Logo: 50px height
- Buttons: inline, gap 0.5rem

**Desktop (1024px+):**
- Header: sticky, 1rem 4% padding
- Navigation: visible, gap 2rem
- Logo: 55px height
- Buttons: inline with spacing

### 4. Grid System for Resource Cards

**Mobile (≤500px):**
- 1 column
- Card width: 100%
- Gap: 1rem

**Tablet (501-768px):**
- 2 columns
- Gap: 1.25rem
- Cards: equal width with 2-column layout

**Desktop (769-1440px):**
- 3 columns
- Gap: 1.5rem

**Large Desktop (1440px+):**
- 4 columns or maintain 3 with wider cards
- Gap: 2rem

### 5. Hero Section Responsive Typography

**Mobile (≤500px):**
- Heading: 1.5rem (clamp adjusts)
- Subtitle: 0.95rem
- Padding: 1.5rem 2%

**Tablet (501-768px):**
- Heading: 2rem
- Subtitle: 1rem
- Padding: 2.5rem 4%

**Desktop (769px+):**
- Heading: 3-3.5rem (using clamp)
- Subtitle: 1.1rem
- Padding: 4rem 4%

### 6. Footer

**Mobile (≤768px):**
- Single column stack
- Padding: 1.5rem 2%
- Font size: 0.75-0.8rem
- Text align: center

**Desktop (769px+):**
- Multi-column grid (3-4 columns)
- Padding: 2rem 4%
- Font size: 0.8-0.9rem
- Text align: left

## Data Models

### CSS Variables (Global Scope)

```css
:root {
  /* Colors */
  --brand: #1D4533;
  --brand-dark: #0f2818;
  --brand-light: #f0fdf4;
  --brand-glow: rgba(29, 69, 51, 0.25);
  --slate-50: #FBFBFA;
  --slate-100: #f1f5f9;
  --slate-200: #e2e8f0;
  --slate-300: #cbd5e1;
  --slate-400: #94a3b8;
  --slate-500: #64748b;
  --slate-600: #475569;
  --slate-700: #334155;
  --slate-800: #1e293b;
  --slate-900: #0f172a;
  --white: #ffffff;
  --red-500: #ef4444;
  --green-500: #22c55e;
  
  /* Spacing */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;
  --spacing-2xl: 3rem;
  
  /* Typography */
  --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-extrabold: 800;
  
  /* Sizing */
  --radius: 12px;
  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 16px;
  
  /* Shadows */
  --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
  --shadow-md: 0 8px 32px rgba(29, 69, 51, 0.12);
  --shadow-lg: 0 16px 48px rgba(29, 69, 51, 0.16);
  --shadow-focus: 0 0 0 3px var(--brand-glow);
  
  /* Transitions */
  --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
  --transition-base: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  --transition-slow: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  
  /* Breakpoints (for reference) */
  --bp-xs: 320px;
  --bp-sm: 380px;
  --bp-md: 500px;
  --bp-lg: 768px;
  --bp-xl: 1024px;
  --bp-2xl: 1440px;
  --bp-4k: 3840px;
}
```

### Responsive Type Scale

**Mobile (base):**
- H1: 1.5rem / 1.8rem (clamp: clamp(1.5rem, 5vw, 3rem))
- H2: 1.25rem / 1.5rem
- H3: 1.1rem / 1.3rem
- Body: 0.9-0.95rem / 1.5-1.6 line-height
- Small: 0.75-0.8rem / 1.4 line-height

**Desktop (with media queries):**
- H1: 3-3.5rem / 1.2 line-height
- H2: 1.5rem / 1.3 line-height
- H3: 1.2rem / 1.4 line-height
- Body: 0.95-1rem / 1.6 line-height

## Correctness Properties

A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.

### Property 1: Font Family Consistency
**For any** page in the system (login, register, resources, safety protocols, support), all text elements (headings, body, labels, buttons, badges) should render using "Plus Jakarta Sans" font family, never "Inter".
**Validates: Requirements 7.1, 7.2, 7.5, 7.6**

### Property 2: Touch Target Minimum Size
**For any** interactive element (button, link, input field, checkbox) on any screen size, the clickable/tappable area must be at least 44px × 44px to meet accessibility standards.
**Validates: Requirements 1.5, 2.6, 3.5, 9.1**

### Property 3: Responsive Layout Adapts at Breakpoints
**For any** page when viewport width changes, the layout should smoothly transition and reflow content without horizontal scrolling on screens ≥320px wide. Grid columns should be: 1 column (≤500px), 2-3 columns (501-768px), 3+ columns (769px+).
**Validates: Requirements 1.6, 2.5, 4.1, 4.2, 4.3, 5.3, 8.1, 8.2, 8.3**

### Property 4: Color Contrast for Readability
**For any** text displayed on a background (including buttons, links, error messages), the color contrast ratio must be at least 4.5:1 for normal text and 3:1 for large text or graphical elements (WCAG AA compliance).
**Validates: Requirements 9.5, 14.3, 14.4**

### Property 5: Form Validation Error Clarity
**For any** form field with validation errors, the error state must include: red border (#ef4444), red error text below the field (≥0.75rem font size), and the error message must be visible without requiring scrolling on mobile screens.
**Validates: Requirements 12.1, 12.2, 12.3, 12.4**

### Property 6: Focus State Keyboard Navigation
**For any** interactive element when focused (via keyboard Tab navigation or screen reader), a visible focus indicator must appear (outline, background change, or shadow) that has sufficient contrast (≥3:1).
**Validates: Requirements 9.3, 9.6, 10.3**

### Property 7: Header Sticky Accessibility
**For any** page, the header element must remain sticky (position: fixed or position: sticky) and accessible at the top of the viewport on all screen sizes, without covering page content or form inputs.
**Validates: Requirements 4.7, 10.5**

### Property 8: Image Scaling Without Distortion
**For any** image or icon (logo, card images, SVG graphics) on any screen size, the element must scale proportionally without distortion, stretching, or overflow beyond its container.
**Validates: Requirements 13.1, 13.2, 13.3, 13.4**

### Property 9: No Horizontal Scrolling Required
**For any** page content on screens ≥320px wide, no horizontal scrolling should be required to view all content. Tables, cards, forms, and text should fit within the viewport or use responsive stacking.
**Validates: Requirements 1.6, 2.4, 5.3, 6.2, 11.3, 15.1**

### Property 10: Brand Color Consistency
**For any** interactive element or brand-related component, the primary color must be #1D4533 (brand green) for primary actions, #f0fdf4 (brand light) for secondary actions, and #0f2818 (brand dark) for hover states.
**Validates: Requirements 14.1, 14.2, 14.5, 14.6**

### Property 11: Form Label-Input Association
**For any** form input field, the semantic HTML structure must include a `<label>` element with a `for` attribute matching the input's `id`, ensuring screen readers properly announce the field.
**Validates: Requirements 9.2**

### Property 12: Mobile Menu Accessibility
**For any** mobile navigation menu (hamburger menu on ≤768px), the menu must be keyboard accessible, with focus trap within the menu when open, and a clear close button with visible focus indicator.
**Validates: Requirements 10.1, 10.3**

### Property 13: Responsive Typography Readability
**For any** text content at any breakpoint, the line-height must be ≥1.5 and font size must be ≥14px (0.875rem) for body text to maintain WCAG readability standards.
**Validates: Requirements 1.4, 2.5, 4.5, 5.4, 6.4, 7.6**

### Property 14: Button Hover/Active States
**For any** button element, the hover state (on desktop) must provide visual feedback (scale change, shadow enhancement, or color change), and the active/pressed state must provide additional feedback (scale down, darker color).
**Validates: Requirements 14.6**

### Property 15: Loading Performance Optimization
**For any** page load on mobile networks, CSS should use efficient media queries without duplicate rules, fonts should load without causing layout shift (using font-display strategies), and animations should be disabled on prefers-reduced-motion.
**Validates: Requirements 15.1, 15.2, 15.4, 15.5**

## Error Handling

### Form Validation Errors
- **Approach**: Display error messages below problematic fields with red styling
- **User Feedback**: Clear, actionable error text (e.g., "Email is required" not "Error")
- **Focus Management**: Automatically focus first invalid field on form submit
- **Persistence**: Keep error messages visible until user corrects input and re-validates

### Responsive Layout Failures
- **Approach**: Test at all defined breakpoints; use min/max-width constraints to prevent layout breaking
- **Fallback**: Use mobile-first defaults if viewport falls outside defined breakpoints
- **Testing**: Verify no horizontal scrolling at 320px minimum width

### Font Loading Failures
- **Approach**: Use system font fallback chain while "Plus Jakarta Sans" loads
- **Strategy**: Implement font-display: swap to show fallback fonts immediately
- **Validation**: Never render "Inter" font; if Plus Jakarta Sans unavailable, use system fonts only

### Browser Compatibility Issues
- **Approach**: Use CSS Grid and Flexbox with vendor prefixes where needed
- **Testing**: Verify on Chrome, Firefox, Safari (desktop and iOS), Edge
- **Graceful Degradation**: Ensure core functionality works even if modern CSS features don't support

## Testing Strategy

### Unit Testing Approach

**Form Component Tests:**
- Test form field rendering with various states (empty, filled, error, disabled, focused)
- Test input validation (email format, required fields, min/max length)
- Test form submission and error display
- Test keyboard navigation (Tab, Enter, Escape)

**Button Component Tests:**
- Test button rendering with different sizes and states (normal, hover, focus, active, disabled)
- Test button click handlers and loading states
- Test accessibility attributes (aria-label, aria-pressed, disabled state)

**Layout Responsive Tests:**
- Test page layout at each defined breakpoint (320px, 380px, 500px, 768px, 1024px, 1440px)
- Test no horizontal scrolling on any width ≥320px
- Test content visibility at all breakpoints
- Test image scaling and aspect ratios

**Navigation Tests:**
- Test mobile hamburger menu (open/close, keyboard accessibility)
- Test header sticky behavior at all breakpoints
- Test navigation link rendering and focus states

**Accessibility Tests:**
- Test focus indicators on all interactive elements
- Test color contrast on buttons, links, text (use tools like axe DevTools)
- Test keyboard navigation (Tab through all elements)
- Test label-input associations with screen reader

**Font Tests:**
- Test "Plus Jakarta Sans" renders on all pages (no "Inter" font visible)
- Test font weights render correctly (300, 400, 500, 600, 700, 800)
- Test font fallback chain when Plus Jakarta Sans doesn't load

### Property-Based Testing Approach

**Minimum 100 iterations per property test**

**Property Test Configuration:**
Each property test will reference its design document property and requirements. Format:
```
// Feature: mobile-responsive-auth-public-pages, Property 1: Font Family Consistency
// Validates: Requirements 7.1, 7.2, 7.5, 7.6
```

**Property Tests to Implement:**

1. **Font Family Property Test** (Property 1)
   - Generate random page names and element selectors
   - Verify computed font-family always contains "Plus Jakarta Sans"
   - Verify "Inter" never appears in computed styles

2. **Touch Target Size Property Test** (Property 2)
   - Generate random interactive elements (buttons, links, inputs)
   - Verify bounding box dimensions ≥44px × 44px
   - Test across all breakpoints

3. **Responsive Layout Property Test** (Property 3)
   - Generate random viewport widths (320, 380, 500, 768, 1024, 1440, 3840)
   - Verify layout adapts appropriately (1 col ≤500, 2-3 col 501-768, 3+ col 769+)
   - Verify no horizontal scrolling required

4. **Color Contrast Property Test** (Property 4)
   - Generate random text/background color combinations from CSS variables
   - Verify contrast ratio calculation ≥4.5:1 for normal text, ≥3:1 for large text
   - Test all brand colors and states

5. **Form Error State Property Test** (Property 5)
   - Generate random form fields with validation errors
   - Verify red border color (#ef4444) applied
   - Verify error message displayed with correct font size (≥0.75rem)
   - Verify error visibility on mobile screens

6. **Focus State Property Test** (Property 6)
   - Generate random interactive elements
   - Verify focus state styling present (outline, background, shadow)
   - Verify focus outline visible with sufficient contrast

7. **Header Sticky Property Test** (Property 7)
   - Generate random scroll positions
   - Verify header remains at top of viewport (position: sticky or fixed)
   - Verify header doesn't cover content below

8. **Image Scaling Property Test** (Property 8)
   - Generate random image sizes and viewport widths
   - Verify images scale proportionally without distortion
   - Verify aspect ratio maintained

9. **No Horizontal Scroll Property Test** (Property 9)
   - Generate random screen widths from 320px upward
   - Verify document.documentElement.scrollWidth ≤ viewport.width + 1px (accounting for scrollbar)
   - Test on all pages and breakpoints

10. **Brand Color Property Test** (Property 10)
    - Generate random brand color usage contexts
    - Verify primary actions use #1D4533
    - Verify secondary actions use #f0fdf4
    - Verify hover states use #0f2818

### Integration Testing Approach

**End-to-End User Flows:**
- Complete login flow on mobile, tablet, and desktop
- Complete registration flow with all form fields
- Browse resources page and filter/search
- Submit support ticket form
- Navigate between pages using header navigation

**Cross-Device Testing:**
- Test on actual iOS and Android devices or emulators
- Test at different orientations (portrait, landscape)
- Test with mobile keyboard visible
- Test with screen reader enabled

**Performance Testing:**
- Measure page load time on 3G network (simulate with DevTools)
- Measure Time to First Paint (FCP) and Largest Contentful Paint (LCP)
- Measure Cumulative Layout Shift (CLS) to ensure no jank
- Verify no layout shift when "Plus Jakarta Sans" loads

### Accessibility Audit

- Use axe DevTools or Lighthouse to scan for accessibility issues
- Verify WCAG 2.1 Level AA compliance
- Test with keyboard-only navigation
- Test with screen reader (NVDA on Windows, VoiceOver on macOS/iOS)
- Test with high contrast mode enabled

