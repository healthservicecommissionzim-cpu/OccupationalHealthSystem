# Implementation Plan: Mobile Responsive Auth & Public Pages

## Overview

This implementation plan converts the feature design into incremental, deliverable tasks. Each task builds on previous steps, focusing on responsive CSS architecture, font consistency, and component refinement. Tasks are structured to enable early testing and validation.

## Tasks

- [x] 1. Set up global styles and CSS variables
  - Create or update the main stylesheet with CSS variable definitions for colors, spacing, typography, and breakpoints
  - Define the complete font stack: "Plus Jakarta Sans" primary with system font fallbacks (NO "Inter")
  - Add font imports from Google Fonts for all weights (300-800)
  - Set up base element resets and accessibility foundations
  - Define transition and shadow variables for consistency
  - _Requirements: 7.1, 7.2, 7.6, 14.1, 14.2_

- [x] 2. Implement responsive base layout and header
  - [x] 2.1 Build responsive header component with sticky positioning
    - Create header layout using Flexbox with adaptive spacing
    - Logo: responsive sizing (35px mobile, 50px tablet, 55px desktop)
    - Navigation: visible on desktop (769px+), hidden/hamburger on mobile (≤768px)
    - Apply "Plus Jakarta Sans" font exclusively to header elements
    - _Requirements: 1.1, 4.7, 10.1, 10.2, 10.5_
  
  - [x] 2.2 Implement mobile hamburger menu with accessibility
    - Create hamburger button (44×44px minimum touch target)
    - Build full-screen overlay menu for mobile (≤768px)
    - Menu items: keyboard navigable (Tab, Escape)
    - Add focus trap within menu when open
    - _Requirements: 10.1, 9.3, 9.6, 10.3_
  
  - [ ]* 2.3 Write unit tests for header and navigation components
    - Test responsive layout at breakpoints (320px, 500px, 768px, 1024px)
    - Test hamburger menu open/close and keyboard navigation
    - Test logo scaling and aspect ratio
    - _Requirements: 10.1, 10.2_

- [x] 3. Implement responsive footer
  - [x] 3.1 Build responsive footer layout
    - Mobile (≤768px): single-column stack
    - Desktop (769px+): multi-column grid (3-4 columns)
    - Apply "Plus Jakarta Sans" font to all footer text
    - Use appropriate font sizes for mobile (0.75rem) and desktop (0.9rem)
    - _Requirements: 11.1, 11.2, 11.4_
  
  - [ ]* 3.2 Write unit tests for footer responsiveness
    - Test column layout at breakpoints
    - Test text readability and sizing
    - Test no horizontal scrolling on mobile

- [-] 4. Build responsive form system with validation
  - [x] 4.1 Create form component with responsive inputs and labels
    - Semantic HTML: `<label for="">` paired with inputs
    - Input sizing: 44px minimum height on all screen sizes
    - Responsive padding: 0.5rem (mobile), 0.7rem (desktop)
    - Border and focus styles with brand colors (#1D4533)
    - Apply "Plus Jakarta Sans" to all form text
    - _Requirements: 1.1, 2.1, 4.1, 6.1, 9.1, 9.2, 12.4_
  
  - [x] 4.2 Implement form validation and error messaging
    - Display error messages below fields in red (#ef4444)
    - Highlight invalid fields with red border
    - Error text font size: ≥0.75rem on all screens
    - Ensure errors don't hide other form content on mobile
    - Clear errors when user corrects input
    - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_
  
  - [x] 4.3 Build responsive button system
    - Primary button: brand green (#1D4533), 44px minimum height
    - Font: "Plus Jakarta Sans" weight 600-700
    - States: normal, hover (scale/shadow), focus (outline), active (scale down), disabled (opacity)
    - Responsive sizing: adjusts based on screen size
    - _Requirements: 1.5, 3.5, 9.1, 14.1, 14.5_
  
  - [ ]* 4.4 Write unit tests for form components
    - Test input rendering and state changes (empty, filled, error, disabled, focused)
    - Test validation logic (email format, required fields)
    - Test button states and click handlers
    - Test keyboard navigation (Tab, Enter)
    - Test accessibility attributes
    - _Requirements: 1.1, 2.1, 9.2, 9.3, 12.3_

- [x] 5. Implement login page responsive layout and styles
  - [x] 5.1 Refactor login page to use new global styles and font
    - Remove "Inter" font references; use only "Plus Jakarta Sans"
    - Replace inline styles with CSS classes where appropriate
    - Implement mobile-first responsive layout (single-column on mobile)
    - Update colors to use CSS variables (--brand, --slate-*, etc.)
    - _Requirements: 1.1, 1.2, 1.3, 1.4_3
  
  - [x] 5.2 Add responsive breakpoints for login form
    - 320px-500px: tight spacing, single column, smaller fonts (0.85rem)
    - 501px-768px: increased padding, same single column
    - 769px+: centered card with appropriate max-width
    - _Requirements: 1.1, 1.2, 1.3, 1.6_
  
  - [x] 5.3 Implement focus states and accessibility for login
    - Visible focus indicators on input fields (outline + shadow)
    - Password toggle button: 44px minimum touch target, accessible
    - "Remember me" checkbox: 1rem touch area
    - Submit button: 44px minimum height
    - _Requirements: 1.5, 9.1, 9.3, 9.6_
  
  - [ ]* 5.4 Write unit tests for login page responsiveness
    - Test layout at breakpoints (320px, 500px, 768px, 1024px)
    - Test no horizontal scrolling on any screen
    - Test form fields and buttons meet 44px minimum
    - Test "Plus Jakarta Sans" font renders
    - _Requirements: 1.1, 1.2, 1.6_

- [x] 6. Implement register page responsive layout and styles
  - [x] 6.1 Refactor register page to use new global styles and font
    - Remove "Inter" font; use "Plus Jakarta Sans" exclusively
    - Implement mobile-first single-column form layout
    - Update CSS variables and color usage
    - Ensure form sections have clear visual separation
    - _Requirements: 2.1, 2.3, 2.5, 7.4_
  
  - [x] 6.2 Add responsive multi-section form layout
    - Mobile (≤500px): single-column, stacked sections
    - Tablet (501px-768px): single-column with better spacing
    - Desktop (769px+): potentially 2-column for some sections if applicable
    - _Requirements: 2.1, 2.2, 2.3_
  
  - [x] 6.3 Implement responsive dropdown and select elements
    - Custom select styling with brand colors
    - Font size ≥14px on all screens
    - Touch-friendly dropdown on mobile
    - Clear visual indicators (arrow icon)
    - _Requirements: 2.3, 9.1_
  
  - [x] 6.4 Build responsive checkbox system for consent
    - Custom checkbox styling with brand colors
    - 44px minimum touch area (including label)
    - Labels: semantic HTML with proper association
    - Font: "Plus Jakarta Sans" at appropriate sizes
    - _Requirements: 2.6, 9.1, 9.2_
  
  - [ ]* 6.5 Write unit tests for register page responsiveness
    - Test layout and spacing at all breakpoints
    - Test form sections and visual separation
    - Test dropdowns and selects responsive behavior
    - Test checkbox touch targets (44px minimum)
    - _Requirements: 2.1, 2.2, 2.6_

- [x] 7. Implement forgot password page responsive layout
  - [x] 7.1 Create forgot password/reset page with responsive layout
    - Mobile: single-column, minimal form (1-2 fields)
    - Clear instructions and messaging
    - Font: "Plus Jakarta Sans" exclusively
    - _Requirements: 3.1, 3.3, 3.4, 7.1_
  
  - [x] 7.2 Implement confirmation and verification messaging
    - Success message: readable on all screens
    - Error states: clear, accessible
    - Font sizing adjusts per screen size
    - _Requirements: 3.2, 3.3_
  
  - [ ]* 7.3 Write unit tests for password reset flow
    - Test responsive layout at breakpoints
    - Test form submission and messaging
    - Test button accessibility (44px minimum)

- [x] 8. Implement resources page responsive grid system
  - [x] 8.1 Build responsive resource card grid
    - Mobile (≤500px): 1 column, full-width cards
    - Tablet (501px-768px): 2 columns, equal width
    - Desktop (769px-1440px): 3 columns
    - Large desktop (1440px+): 3-4 columns
    - Card gap: 1rem (mobile), 1.5rem (desktop)
    - _Requirements: 4.1, 4.2, 4.3, 8.1, 8.2, 8.3_
  
  - [x] 8.2 Update resource card typography and styling
    - Card title: "Plus Jakarta Sans" 1rem-1.1rem, weight 700
    - Description: "Plus Jakarta Sans" 0.9rem, weight 400
    - Badges: "Plus Jakarta Sans" 0.75rem, weight 600
    - Apply brand colors (--brand for badges, --slate-* for text)
    - _Requirements: 4.5, 7.1, 7.2_
  
  - [x] 8.3 Implement responsive search functionality
    - Search input: responsive sizing and padding
    - Results: update grid layout dynamically
    - No horizontal scrolling at any screen width
    - _Requirements: 4.2, 8.1, 9.9_
  
  - [ ]* 8.4 Write property test for resource grid responsiveness
    - **Property 3: Responsive Layout Adapts at Breakpoints**
    - **Validates: Requirements 4.1, 4.2, 4.3, 8.1_
    - Generate random viewport widths (320, 500, 768, 1024, 1440)
    - Verify grid adapts: 1 col (≤500), 2 col (501-768), 3+ col (769+)
    - Verify no horizontal overflow

- [x] 9. Implement safety protocols page responsive layout
  - [x] 9.1 Build responsive protocol content layout
    - Mobile (≤500px): single-column, stacked sections
    - Tablet/Desktop: multi-column as appropriate
    - Section headers: "Plus Jakarta Sans" with responsive font size
    - Font: "Plus Jakarta Sans" exclusively
    - _Requirements: 5.1, 5.2, 5.4, 7.1_
  
  - [x] 9.2 Handle responsive tables and structured data
    - Tables on mobile: convert to card-based or stacked layout (no overflow)
    - Tables on desktop: standard table layout
    - Ensure no horizontal scrolling on any screen width ≥320px
    - _Requirements: 5.3, 5.5, 9.9_
  
  - [ ]* 9.3 Write unit tests for protocol page responsiveness
    - Test layout at breakpoints
    - Test table responsiveness (no scrolling)
    - Test content visibility on all screens

- [x] 10. Implement support/help desk page responsive form
  - [x] 10.1 Build responsive support form layout
    - Mobile (≤500px): single-column form stack
    - Form fields: responsive sizing and spacing
    - Field gap: ≥1rem vertical spacing
    - _Requirements: 6.1, 6.2, 6.3, 12.1_
  
  - [x] 10.2 Implement mobile keyboard handling
    - Ensure focused inputs are not hidden by mobile keyboard
    - Scroll viewport to show input when focused
    - Test on actual mobile devices
    - _Requirements: 6.3, 6.6_
  
  - [x] 10.3 Build responsive error and success messaging
    - Error messages: red (#ef4444), ≥0.75rem font, below field
    - Success message: readable on all screens
    - No content hidden by messages
    - _Requirements: 6.5, 12.2, 12.3_
  
  - [ ]* 10.4 Write unit tests for support form
    - Test layout and spacing at breakpoints
    - Test form validation and error display
    - Test keyboard focus management

- [x] 11. Implement responsive hero and feature sections
  - [x] 11.1 Build responsive hero section
    - Mobile: 1.5rem heading, centered, tight padding
    - Tablet: 2rem heading, 2.5rem padding
    - Desktop: 3-3.5rem heading (using clamp), 4rem padding
    - Font: "Plus Jakarta Sans" exclusively
    - _Requirements: 1.4, 4.5, 7.1, 8.1_
  
  - [x] 11.2 Implement responsive feature card grid
    - Mobile: 1 column, full-width cards
    - Tablet: 2 columns
    - Desktop: 3-4 columns
    - Card icons and text: "Plus Jakarta Sans" font
    - _Requirements: 8.1, 8.2, 8.3_
  
  - [ ]* 11.3 Write unit tests for hero and feature sections
    - Test responsive layout and typography
    - Test card grid at breakpoints

- [x] 12. Audit and fix font consistency across all pages
  - [x] 12.1 Audit each page for "Inter" font usage
    - Login page: remove all "Inter" references
    - Register page: remove all "Inter" references
    - Resources page: verify uses "Plus Jakarta Sans"
    - Safety protocols page: verify uses "Plus Jakarta Sans"
    - Support page: verify uses "Plus Jakarta Sans"
    - Welcome page: verify uses "Plus Jakarta Sans"
    - _Requirements: 7.1, 7.2, 7.5_
  
  - [x] 12.2 Update all pages to use canonical font stack
    - Add font-family rule to body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif
    - Remove any references to "Inter" font
    - Ensure all element selectors use inherited font
    - _Requirements: 7.1, 7.4, 7.5_
  
  - [x] 12.3 Verify font weights render correctly on all pages
    - Test font weights 300, 400, 500, 600, 700, 800
    - Headings: weight 700-800
    - Body text: weight 400-500
    - Labels: weight 600
    - _Requirements: 7.2, 7.3_

- [x] 13. Checkpoint - Responsive layout validation
  - [x] 13.1 Test all pages at defined breakpoints
    - Manual testing at: 320px, 380px, 500px, 768px, 1024px, 1440px, 3840px
    - Verify no horizontal scrolling at any breakpoint
    - Verify layout transitions are smooth
    - Check for layout shifts or content jumping
    - _Requirements: 1.1, 1.6, 2.1, 8.1, 9.9_
  
  - [x] 13.2 Verify font consistency across all pages
    - Use DevTools to inspect computed font-family on all elements
    - Verify "Plus Jakarta Sans" renders, never "Inter"
    - Check font weights display correctly
    - _Requirements: 7.1, 7.2, 7.5_
  
  - [x] 13.3 Test touch target sizes on mobile
    - Measure interactive element sizes (buttons, inputs, links)
    - Verify all meet ≥44px minimum height and width
    - Test with DevTools device emulation
    - _Requirements: 1.5, 2.6, 9.1_

- [x] 14. Implement accessibility features and focus states
  - [x] 14.1 Add visible focus indicators on all interactive elements
    - Outline style: 2px solid --brand-glow or similar
    - Shadow: var(--shadow-focus)
    - All elements: buttons, links, inputs, checkboxes, selects
    - _Requirements: 9.3, 9.6, 14.6_
  
  - [x] 14.2 Implement color contrast validation
    - All text on background: ≥4.5:1 contrast (WCAG AA)
    - Large text (18px+): ≥3:1 contrast
    - Test with axe DevTools or Lighthouse
    - _Requirements: 9.5, 14.3, 14.4_
  
  - [x] 14.3 Verify keyboard navigation completeness
    - All interactive elements reachable via Tab key
    - Focus order makes logical sense
    - Escape key closes modals/menus
    - _Requirements: 9.6, 10.3_
  
  - [ ]* 14.4 Write accessibility unit tests
    - Test focus indicators visible on all elements
    - Test color contrast ratios
    - Test keyboard navigation and focus order

- [x] 15. Implement image and icon responsive scaling
  - [x] 15.1 Update logo sizing for responsive breakpoints
    - Mobile: 35px height
    - Tablet: 50px height
    - Desktop: 55px height
    - Maintain aspect ratio at all sizes
    - _Requirements: 13.1_
  
  - [x] 15.2 Implement responsive icon styling
    - Icons in buttons: scale with button size
    - Icons in cards: responsive sizing (1rem mobile, 1.5rem desktop)
    - Font size for icon fonts: match context
    - _Requirements: 13.2_
  
  - [x] 15.3 Verify image scaling without distortion
    - Background images: cover/contain as appropriate
    - SVG graphics: scale proportionally
    - Card images: maintain aspect ratio
    - _Requirements: 13.3, 13.4_
  
  - [ ]* 15.4 Write property test for image scaling
    - **Property 8: Image Scaling Without Distortion**
    - **Validates: Requirements 13.1, 13.2, 13.3, 13.4**
    - Generate random image sizes and viewport widths
    - Verify aspect ratio preserved
    - Verify no overflow or distortion

- [x] 16. Implement color consistency and brand adherence
  - [x] 16.1 Audit color usage across all pages
    - Primary actions: #1D4533 (--brand)
    - Secondary actions: #f0fdf4 (--brand-light)
    - Hover states: #0f2818 (--brand-dark)
    - Error states: #ef4444 (--red-500)
    - Verify consistent usage across all pages
    - _Requirements: 14.1, 14.2, 14.5, 14.6_
  
  - [x] 16.2 Update color references to use CSS variables
    - Replace hardcoded color values with CSS variable references
    - Ensure all color usage follows design system
    - _Requirements: 14.1, 14.2_
  
  - [ ]* 16.3 Write property test for brand color consistency
    - **Property 10: Brand Color Consistency**
    - **Validates: Requirements 14.1, 14.2, 14.5, 14.6**
    - Generate random color contexts (primary action, hover, error, etc.)
    - Verify colors match expected brand palette

- [x] 17. Optimize performance for mobile networks
  - [x] 17.1 Optimize CSS for performance
    - Remove duplicate media query rules
    - Use efficient selectors (avoid deep nesting)
    - Minimize CSS file size
    - _Requirements: 15.1_
  
  - [x] 17.2 Implement font loading optimization
    - Use font-display: swap to prevent FOUT/FOIT
    - Preload critical font weights
    - Verify no layout shift when Plus Jakarta Sans loads
    - _Requirements: 15.4_
  
  - [x] 17.3 Test performance on throttled network
    - Simulate 3G network in DevTools
    - Measure FCP (First Contentful Paint) and LCP (Largest Contentful Paint)
    - Target: LCP <2.5s on 3G
    - _Requirements: 15.1, 15.4_
  
  - [ ]* 17.4 Write performance unit tests
    - Test font loading doesn't cause layout shift
    - Test CSS media queries don't cause redundant renders
    - Measure paint times at different breakpoints

- [-] 18. Comprehensive property-based testing
  - [ ] 18.1 Write property test for font family consistency
    - **Property 1: Font Family Consistency**
    - **Validates: Requirements 7.1, 7.2, 7.5, 7.6**
    - Minimum 100 iterations
    - Generate random page elements
    - Verify Plus Jakarta Sans, never Inter
  
  - [ ] 18.2 Write property test for touch target sizes
    - **Property 2: Touch Target Minimum Size**
    - **Validates: Requirements 1.5, 2.6, 3.5, 9.1**
    - Minimum 100 iterations
    - Generate random interactive elements
    - Verify ≥44px × 44px dimensions
  
  - [ ] 18.3 Write property test for responsive layout
    - **Property 3: Responsive Layout Adapts at Breakpoints**
    - **Validates: Requirements 1.6, 2.5, 4.1, 4.2, 4.3, 5.3, 8.1, 8.2, 8.3**
    - Minimum 100 iterations
    - Generate random viewport widths and content
    - Verify grid columns: 1 (≤500px), 2-3 (501-768px), 3+ (769px+)
    - Verify no horizontal scrolling
  
  - [ ] 18.4 Write property test for color contrast
    - **Property 4: Color Contrast for Readability**
    - **Validates: Requirements 9.5, 14.3, 14.4**
    - Minimum 100 iterations
    - Generate random text/background color pairs
    - Verify contrast ratio ≥4.5:1 (normal), ≥3:1 (large text)
  
  - [ ] 18.5 Write property test for form error states
    - **Property 5: Form Validation Error Clarity**
    - **Validates: Requirements 12.1, 12.2, 12.3, 12.4**
    - Minimum 100 iterations
    - Generate random form fields with errors
    - Verify red border, error message, visibility on mobile
  
  - [ ] 18.6 Write property test for focus states
    - **Property 6: Focus State Keyboard Navigation**
    - **Validates: Requirements 9.3, 9.6, 10.3**
    - Minimum 100 iterations
    - Generate random interactive elements
    - Verify focus indicator visible and accessible
  
  - [x] 18.7 Write property test for no horizontal scroll
    - **Property 9: No Horizontal Scrolling Required**
    - **Validates: Requirements 1.6, 2.4, 5.3, 6.2, 11.3, 15.1**
    - Minimum 100 iterations
    - Generate random screen widths ≥320px
    - Verify scrollWidth ≤ viewport width

- [x] 19. Checkpoint - All tests passing
  - Ensure all property-based tests pass (minimum 100 iterations each)
  - Ensure all unit tests pass for forms, layout, navigation, accessibility
  - Run full accessibility audit with axe DevTools or Lighthouse
  - Manual testing: login, register, browse resources on iOS and Android devices
  - Ask the user if questions arise, no show-stoppers encountered.

- [-] 20. Final validation and cross-device testing
  - [-] 20.1 Test on real devices
    - iPhone SE, iPhone 12, iPhone 14 Pro Max (iOS)
    - Samsung Galaxy A12, Galaxy S21, Galaxy S21 Ultra (Android)
    - iPad (tablet), iPad Pro (large tablet)
    - Test both portrait and landscape orientations
    - _Requirements: All_
  
  - [-] 20.2 Test in multiple browsers
    - Chrome (desktop and mobile)
    - Firefox (desktop and mobile)
    - Safari (macOS, iOS)
    - Edge (desktop)
    - _Requirements: All_
  
  - [-] 20.3 Verify no horizontal scrolling at any screen size
    - DevTools device emulation at 320px, 380px, 500px, 768px, 1024px, 1440px
    - Manual testing on real devices
    - Test in landscape and portrait
    - _Requirements: 1.6, 2.4, 5.3, 6.2, 9.9_
  
  - [-] 20.4 Final accessibility compliance check
    - axe DevTools scan on all pages
    - Lighthouse audit (target: 90+ accessibility score)
    - Keyboard navigation test (Tab through all elements)
    - Screen reader test (VoiceOver, NVDA)
    - _Requirements: 9.1, 9.2, 9.3, 9.5, 9.6, 14.1, 14.2_

- [ ] 21. Final checkpoint - Ready for production
  - All pages are fully responsive (320px to 4K+)
  - All pages use "Plus Jakarta Sans" exclusively (no "Inter")
  - All interactive elements are accessible (44px minimum, keyboard nav, focus states)
  - All colors follow brand design system
  - All pages maintain brand consistency and visual polish
  - No horizontal scrolling on any screen size ≥320px
  - All property-based tests passing (minimum 100 iterations)
  - Ask the user if questions arise or if any adjustments are needed.

## Notes

- Tasks marked with `*` are optional unit/property tests but are recommended for quality assurance
- Each task references specific requirements for full traceability
- Focus on incremental validation—checkpoint tasks ensure progress verification
- Property tests use minimum 100 iterations to ensure broad input coverage
- Cross-device testing should include both emulation and real devices
- Font consistency is critical—verify "Plus Jakarta Sans" on every page, never "Inter"

 