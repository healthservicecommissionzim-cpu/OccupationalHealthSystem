# Requirements Document: Mobile Responsive Auth & Public Pages

## Introduction

This feature focuses on making all authentication pages (login, register, forgot password) and public information pages (resources, safety protocols, help desk/support) fully mobile-responsive with consistent typography and design system adherence. The goal is to ensure a seamless, accessible experience across all device sizes (320px to 4K+) while maintaining the HSC brand identity and using "Plus Jakarta Sans" as the canonical font family.

## Glossary

- **System**: The HSC-OHS (Health Service Commission - Occupational Health & Safety) Portal
- **Mobile-Responsive**: Design that adapts smoothly across all screen sizes from 320px to 4K (4096px+)
- **Plus Jakarta Sans**: The canonical font family for the system (weights: 300-800)
- **Brand Color**: Primary green (#1D4533) and supporting palette
- **Breakpoints**: Critical responsive design points (320px, 380px, 500px, 768px, 1024px, 1440px)
- **Font Consistency**: All text uses Plus Jakarta Sans; no fallback to Inter or other font families
- **Accessibility**: WCAG 2.1 Level AA compliance including semantic HTML, color contrast, and keyboard navigation
- **Authentication Pages**: Login, Register, and Forgot Password/Password Reset flows
- **Public Pages**: Resources, Safety Protocols, and Help Desk/Support pages

## Requirements

### Requirement 1: Login Page - Mobile Responsive Layout

**User Story:** As a healthcare worker, I want to access the login page on my mobile device, so that I can securely authenticate from anywhere.

#### Acceptance Criteria

1. WHEN the login page is loaded on a mobile device (320px-500px), THE System SHALL display the form in a single-column layout with proper touch-friendly spacing
2. WHEN a user views the login page on a tablet (501px-768px), THE System SHALL maintain single-column layout with slightly increased padding
3. WHEN a user views the login page on a desktop (769px+), THE System SHALL use appropriate spacing and layout for larger screens
4. WHEN the login form is displayed on any device, THE System SHALL use "Plus Jakarta Sans" as the primary font and NEVER use "Inter" font
5. WHEN touch-screen users interact with form fields, THE System SHALL provide minimum 44px touch targets for input fields and buttons
6. WHERE the device viewport changes, THE System SHALL maintain form usability without horizontal scrolling on any screen size

### Requirement 2: Register Page - Mobile Responsive Layout

**User Story:** As a new healthcare worker, I want to complete the registration form on my mobile device, so that I can create my account securely.

#### Acceptance Criteria

1. WHEN the register page is loaded on a mobile device (320px-500px), THE System SHALL display form fields in a single-column layout with readable line-heights
2. WHEN form sections are present (personal info, facility, consent), THE System SHALL display each section with clear visual separation on all devices
3. WHEN a user selects from dropdown menus on a mobile device, THE System SHALL provide a native or accessible dropdown interface with adequate font size (≥14px)
4. WHEN multi-line text areas are displayed, THE System SHALL ensure they are fully visible without requiring horizontal scrolling
5. WHEN the register page renders on any device, THE System SHALL use "Plus Jakarta Sans" exclusively for all text elements
6. WHILE checkboxes or form elements are visible, THE System SHALL maintain a minimum 44px height for touch targets

### Requirement 3: Forgot Password/Password Reset Page - Mobile Responsive

**User Story:** As a healthcare worker who forgot my password, I want to reset it on my mobile device, so that I can regain account access.

#### Acceptance Criteria

1. WHEN the forgot password page is loaded on mobile (320px-500px), THE System SHALL display the form with clear instructions and a single input field layout
2. WHEN a user submits the password reset request, THE System SHALL display confirmation messaging that is readable and properly formatted on all screen sizes
3. WHEN the reset confirmation or verification form is shown, THE System SHALL use responsive typography that scales appropriately
4. WHEN any password-related page is displayed, THE System SHALL use "Plus Jakarta Sans" font family consistently
5. IF the user is on a mobile device, THEN THE System SHALL ensure the submit button is easily tappable (minimum 44px height)

### Requirement 4: Resources Page - Mobile Responsive and Font Consistency

**User Story:** As a healthcare worker, I want to browse and search resources on my mobile device, so that I can access compliance documents and safety guidelines on the go.

#### Acceptance Criteria

1. WHEN the resources page loads on mobile (320px-500px), THE System SHALL display resource cards in a single-column stack
2. WHEN the resources page loads on tablet (501px-768px), THE System SHALL display 2 columns of resource cards
3. WHEN the resources page loads on desktop (769px+), THE System SHALL display 3 or more columns as appropriate
4. WHEN search functionality is used on any device, THE System SHALL display search results in the responsive grid layout
5. WHEN resource cards are displayed, THE System SHALL use "Plus Jakarta Sans" for all card text (title, description, badges)
6. WHERE badges and metadata tags are present, THE System SHALL ensure they remain readable and don't wrap awkwardly on mobile screens
7. WHILE the page is scrolling, THE System SHALL maintain a sticky header that remains accessible on all screen sizes

### Requirement 5: Safety Protocols Page - Mobile Responsive Navigation

**User Story:** As a healthcare worker, I want to read safety protocols on my mobile device, so that I can access critical safety information quickly.

#### Acceptance Criteria

1. WHEN the safety protocols page is loaded on mobile (320px-500px), THE System SHALL display content in a readable single-column format
2. WHEN a user navigates between protocol sections, THE System SHALL provide clear section headers that are easily distinguishable on all devices
3. WHEN protocol content includes lists, tables, or structured data, THE System SHALL ensure horizontal scroll is NOT required on any screen ≥320px wide
4. WHEN the page is displayed, THE System SHALL use "Plus Jakarta Sans" consistently for headings, body text, and all typography
5. IF the page contains tabular data, THEN THE System SHALL provide responsive table layouts that don't overflow on mobile (using card-based or stacked layouts if needed)

### Requirement 6: Help Desk/Support Page - Mobile Responsive Form

**User Story:** As a healthcare worker needing support, I want to submit a support ticket on my mobile device, so that I can get help quickly.

#### Acceptance Criteria

1. WHEN the support page is loaded on mobile (320px-500px), THE System SHALL display the support form with all fields stacked in a single column
2. WHEN form fields are displayed, THE System SHALL provide clear labels and adequate spacing between fields (minimum 1rem vertical gap)
3. WHEN a user focuses on input fields on a mobile device, THE System SHALL ensure the field is not hidden by the keyboard (scroll viewport if needed)
4. WHEN the support form is displayed on any device, THE System SHALL use "Plus Jakarta Sans" for labels, placeholders, and all text
5. IF validation errors are displayed, THEN THE System SHALL show clear error messages in red text (#ef4444) with readable font size (≥0.75rem)
6. WHILE the user interacts with the form, THE System SHALL maintain submit button visibility and accessibility

### Requirement 7: Font Consistency Across All Pages

**User Story:** As a system administrator, I want all authentication and public pages to use the same font family, so that the brand experience is consistent.

#### Acceptance Criteria

1. THE System SHALL use "Plus Jakarta Sans" as the primary font family on all auth pages (login, register, forgot password)
2. THE System SHALL use "Plus Jakarta Sans" on all public pages (resources, safety protocols, support)
3. WHEN font weights are needed, THE System SHALL use weights 300-800 as defined in the design system
4. IF "Plus Jakarta Sans" fails to load, THE System SHALL fallback to -apple-system, BlinkMacSystemFont, sans-serif (NOT Inter)
5. WHEN users view any page, THE System SHALL NOT render any text in "Inter" font family
6. WHERE different text roles exist (headings, body, labels, etc.), THE System SHALL maintain the same font family while using appropriate weights

### Requirement 8: Responsive Breakpoints and Screen Size Support

**User Story:** As a developer, I want responsive design to follow consistent breakpoints, so that the layout adapts predictably across all devices.

#### Acceptance Criteria

1. THE System SHALL define responsive breakpoints at: 320px, 380px, 500px, 768px, 1024px, 1440px, and 4K (3840px+)
2. WHEN a user resizes their viewport or rotates their device, THE System SHALL adapt the layout smoothly without breaking
3. WHEN content reaches a mobile breakpoint (≤500px), THE System SHALL prioritize readability and single-column layout
4. WHEN content reaches a tablet breakpoint (501px-768px), THE System SHALL use 2-column layouts where appropriate
5. WHEN content reaches a desktop breakpoint (769px+), THE System SHALL use multi-column layouts optimized for larger screens
6. WHERE content spans multiple columns, THE System SHALL ensure the layout is still readable at all screen widths ≥320px

### Requirement 9: Accessibility and Touch Targets

**User Story:** As a user with accessibility needs, I want interactive elements to be properly sized and labeled, so that I can use the system on any device.

#### Acceptance Criteria

1. THE System SHALL ensure all interactive elements (buttons, links, inputs) have a minimum touch target size of 44px × 44px
2. WHEN form inputs are displayed, THE System SHALL use semantic HTML labels associated with input fields via id/for attributes
3. WHEN a user focuses on an element, THE System SHALL provide visible focus indicators (outline or background change)
4. WHERE color is used to convey information, THE System SHALL also use text or icons to ensure information is not lost for colorblind users
5. WHEN buttons are displayed, THE System SHALL use sufficient color contrast (WCAG AA minimum 4.5:1 for text, 3:1 for graphical elements)
6. IF the user is using a keyboard, THE System SHALL allow full navigation through all interactive elements using Tab and Enter keys

### Requirement 10: Header and Navigation Responsiveness

**User Story:** As a user on any device, I want the header and navigation to be accessible and easy to use, so that I can navigate between pages.

#### Acceptance Criteria

1. WHEN the page is loaded on mobile (≤768px), THE System SHALL display a hamburger menu or collapse the navigation
2. WHEN the page is loaded on desktop (769px+), THE System SHALL display the full navigation menu inline
3. WHEN navigation is visible, THE System SHALL ensure all links are keyboard accessible and clearly labeled
4. WHEN the logo is displayed, THE System SHALL ensure it maintains appropriate aspect ratio on all screen sizes
5. WHILE scrolling, THE System SHALL keep the header sticky and accessible on all devices
6. WHERE the header contains multiple elements, THE System SHALL prioritize content visibility over header size on mobile screens

### Requirement 11: Footer Responsiveness

**User Story:** As a user, I want the footer to be properly formatted on all devices, so that I can access footer links and information.

#### Acceptance Criteria

1. WHEN the page is viewed on mobile (320px-768px), THE System SHALL stack footer content in a single column
2. WHEN the page is viewed on desktop (769px+), THE System SHALL display footer content in appropriate columns
3. WHEN footer links are displayed, THE System SHALL ensure they remain clickable with adequate spacing
4. WHEN the footer is rendered, THE System SHALL use "Plus Jakarta Sans" for all text
5. WHERE footer content is lengthy, THE System SHALL use appropriate font sizing that doesn't require horizontal scrolling on mobile

### Requirement 12: Form Validation and Error Messaging - Mobile Responsive

**User Story:** As a user completing a form on mobile, I want to see validation errors clearly, so that I can correct my input.

#### Acceptance Criteria

1. WHEN validation errors occur, THE System SHALL display error messages near the problematic field
2. WHEN error messages appear on mobile (320px-500px), THE System SHALL ensure they don't hide other form content
3. WHEN a user has made an error, THE System SHALL highlight the field with a red border and display red error text (#ef4444)
4. WHEN error messages are displayed, THE System SHALL use readable font size (≥0.75rem) and "Plus Jakarta Sans" font
5. IF there are multiple errors, THEN THE System SHALL display all errors clearly without requiring excessive scrolling

### Requirement 13: Image and Icon Scaling

**User Story:** As a user on any device, I want images and icons to scale appropriately, so that the page looks polished on mobile and desktop.

#### Acceptance Criteria

1. WHEN the HSC logo is displayed, THE System SHALL scale it appropriately for each breakpoint (55px on desktop, 40px on tablet, 35px on mobile)
2. WHEN icons are used in buttons or form elements, THE System SHALL ensure they maintain legibility on all screen sizes
3. WHEN background images or SVG graphics are used, THE System SHALL ensure they render without distortion on any screen size
4. WHERE images are used in resource cards, THE System SHALL ensure they maintain proper aspect ratio and don't overflow containers

### Requirement 14: Color Consistency and Brand Adherence

**User Story:** As a brand stakeholder, I want all pages to maintain HSC brand colors and design consistency, so that the system feels unified.

#### Acceptance Criteria

1. THE System SHALL use brand primary color #1D4533 for all primary buttons and interactive elements
2. THE System SHALL use brand light color #f0fdf4 for backgrounds and non-primary actions
3. WHEN text is displayed on colored backgrounds, THE System SHALL ensure minimum color contrast of 4.5:1 for body text
4. WHEN hover states are displayed, THE System SHALL use brand-dark (#0f2818) for enhanced interactivity feedback
5. WHERE error states are shown, THE System SHALL use red (#ef4444) with adequate contrast on the page background
6. WHILE the page loads, THE System SHALL maintain consistent brand colors without flashing or layout shifts

### Requirement 15: Performance and Loading - Mobile Optimized

**User Story:** As a mobile user on a limited connection, I want pages to load quickly and efficiently, so that I can access content even on slow networks.

#### Acceptance Criteria

1. WHEN CSS is rendered, THE System SHALL use efficient media queries and avoid duplicating styles across breakpoints
2. WHEN images are loaded, THE System SHALL use responsive image techniques (srcset or picture elements if applicable)
3. WHEN the page is displayed on mobile, THE System SHALL minimize unnecessary animations to reduce battery drain
4. WHEN fonts are loaded, THE System SHALL use system fallbacks while "Plus Jakarta Sans" is loading to prevent layout shift (FOUT/FOIT)
5. WHEN JavaScript is executed, THE System SHALL prioritize non-blocking interactions on mobile devices

