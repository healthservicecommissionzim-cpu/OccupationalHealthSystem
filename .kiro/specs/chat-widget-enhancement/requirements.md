# Requirements Document: Chat Widget Enhancement

## Introduction

This document specifies requirements for enhancing the welcome page chat widget for the HSC-OHS (Health Service Commission - Occupational Health and Safety) portal. The current chat widget is a floating button that opens a basic support request modal for unauthenticated users. This enhancement will modernize the design, improve user experience, add valuable functionality, and integrate with existing system capabilities while maintaining healthcare-appropriate standards.

## Glossary

- **Chat_Widget**: The floating button interface at bottom-right of welcome page that triggers support interactions
- **Support_Modal**: The modal dialog that displays the support request form
- **Support_Ticket**: A record in the system created when a user submits a support request
- **Authenticated_User**: A logged-in healthcare worker, HR staff, supervisor, or administrator
- **Public_User**: An unauthenticated visitor to the welcome page
- **HSC-OHS_Portal**: The Health Service Commission Occupational Health and Safety system
- **Quick_Action**: A predefined button that navigates users to specific portal features
- **File_Attachment**: A document or image file uploaded by a user within the support request
- **Notification_System**: The existing SMS and email infrastructure for sending notifications
- **Incident**: A workplace injury or safety event tracked in the system
- **Response_Time**: The estimated duration before support staff will respond to a request

## Requirements

### Requirement 1: Modern Visual Design

**User Story:** As a portal visitor, I want the chat widget to have a modern, professional appearance that matches healthcare standards, so that I feel confident using it for support requests.

#### Acceptance Criteria

1. THE Chat_Widget SHALL use HSC brand colors (primary brand color, slate accents, white backgrounds)
2. WHEN the Chat_Widget is displayed, THE Chat_Widget SHALL include smooth animation transitions for opening and closing
3. THE Chat_Widget SHALL display a professional headset or chat icon
4. THE Support_Modal SHALL use rounded corners (minimum 12px border radius) and shadow effects for depth
5. THE Support_Modal SHALL maintain readability with minimum 14px font size for body text
6. WHEN displayed on mobile devices, THE Support_Modal SHALL occupy 95% of viewport width with appropriate padding
7. THE Chat_Widget SHALL remain accessible with sufficient color contrast ratios (WCAG 2.1 AA minimum 4.5:1)

### Requirement 2: Enhanced User Experience

**User Story:** As a portal visitor, I want intuitive interaction with the chat widget, so that I can quickly access support without confusion.

#### Acceptance Criteria

1. WHEN a user hovers over the Chat_Widget, THE Chat_Widget SHALL display a tooltip with text "Get Help" or "Contact Support"
2. WHEN the Support_Modal is open, THE System SHALL prevent scrolling of the background page
3. WHEN a user clicks outside the Support_Modal, THE Support_Modal SHALL close and restore background scrolling
4. WHEN the Support_Modal opens, THE System SHALL automatically focus the first input field
5. THE System SHALL display form validation errors inline beneath each field
6. WHEN a user types in any field, THE System SHALL clear any previous validation error for that field
7. WHEN the Support_Modal closes, THE System SHALL reset all form fields and validation states

### Requirement 3: File Attachment Support

**User Story:** As a portal visitor, I want to attach screenshots or documents to my support request, so that I can clearly communicate technical issues or provide required documentation.

#### Acceptance Criteria

1. THE Support_Modal SHALL include a file upload input that accepts images and PDF documents
2. WHEN a user selects files, THE System SHALL validate file types are PNG, JPG, JPEG, GIF, or PDF only
3. WHEN a user selects files, THE System SHALL validate total upload size does not exceed 10MB
4. WHEN file validation fails, THE System SHALL display an error message explaining the file type or size restriction
5. WHEN files are selected, THE System SHALL display a preview list showing filename and file size
6. WHEN multiple files are selected, THE System SHALL allow users to remove individual files before submission
7. WHEN the form is submitted, THE System SHALL upload File_Attachments and associate them with the Support_Ticket

### Requirement 4: Quick Action Buttons

**User Story:** As a portal visitor, I want quick access to common portal functions from the chat widget, so that I can navigate efficiently without searching through menus.

#### Acceptance Criteria

1. THE Support_Modal SHALL display a Quick_Action section above the support form
2. THE Quick_Action section SHALL include a "Track My Incident" button
3. THE Quick_Action section SHALL include a "Report New Incident" button
4. THE Quick_Action section SHALL include a "View FAQs" button
5. WHEN a user clicks a Quick_Action button, THE System SHALL navigate to the appropriate page
6. THE Quick_Action buttons SHALL display relevant icons (magnifying glass for track, alert for report, question mark for FAQs)
7. THE Quick_Action section SHALL be visually separated from the support form with a divider or spacing

### Requirement 5: Priority Level Selection

**User Story:** As a portal visitor, I want to indicate the urgency of my support request, so that critical issues receive faster attention.

#### Acceptance Criteria

1. THE Support_Modal form SHALL include a priority level field
2. THE priority field SHALL offer three options: "Low", "Normal", "High"
3. THE System SHALL default priority selection to "Normal"
4. WHEN priority is "High", THE System SHALL display a confirmation message explaining high priority is for urgent issues only
5. WHEN a Support_Ticket is created, THE System SHALL store the selected priority level
6. THE System SHALL visually distinguish priority options with color coding (green for Low, amber for Normal, red for High)

### Requirement 6: Estimated Response Time Display

**User Story:** As a portal visitor, I want to know when I can expect a response to my support request, so that I can manage my expectations.

#### Acceptance Criteria

1. THE Support_Modal SHALL display estimated Response_Time information in the header or introduction section
2. THE estimated Response_Time SHALL state "Typical response within 24-48 hours during business days"
3. WHERE priority is "High", THE System SHALL display "Urgent requests typically reviewed within 4-8 hours"
4. THE Response_Time display SHALL include support hours information ("Monday-Friday, 8AM-5PM CAT")

### Requirement 7: Notification Integration

**User Story:** As a portal visitor, I want to receive confirmation of my support request via SMS and email, so that I have a record and reference number.

#### Acceptance Criteria

1. WHEN a Support_Ticket is successfully created, THE System SHALL send an email notification to the user's provided email address
2. WHEN a Support_Ticket is successfully created, THE System SHALL send an SMS notification to the user's provided phone number
3. THE email notification SHALL include the ticket reference number, submitted details, and expected response time
4. THE SMS notification SHALL include the ticket reference number and a link to the HSC-OHS portal
5. IF SMS sending fails, THE System SHALL log the failure and continue processing without blocking ticket creation
6. THE notifications SHALL use the existing Notification_System infrastructure (Econet SMS service)

### Requirement 8: Real-Time Status Indicators

**User Story:** As a portal visitor, I want to see visual feedback during form submission, so that I know the system is processing my request.

#### Acceptance Criteria

1. WHEN the form is being submitted, THE System SHALL disable the submit button
2. WHEN the form is being submitted, THE System SHALL display a loading spinner or progress indicator
3. WHEN the form is being submitted, THE submit button text SHALL change to "Submitting..." or similar
4. WHEN submission completes successfully, THE System SHALL display a success message with the ticket reference number
5. WHEN submission fails, THE System SHALL re-enable the submit button and display a specific error message
6. THE System SHALL display submission status for minimum 2 seconds before allowing modal closure

### Requirement 9: Keyboard Navigation and Accessibility

**User Story:** As a portal visitor using assistive technology, I want full keyboard navigation support, so that I can use the chat widget without a mouse.

#### Acceptance Criteria

1. THE Chat_Widget SHALL be focusable and activatable via keyboard (Tab + Enter)
2. WHEN the Support_Modal is open, THE System SHALL trap focus within the modal (Tab cycles through modal elements only)
3. WHEN the Support_Modal is open, THE System SHALL close the modal when Escape key is pressed
4. THE System SHALL maintain logical tab order through form fields (name, email, phone, category, priority, message, attachments, submit)
5. THE Chat_Widget and all interactive elements SHALL have descriptive ARIA labels
6. THE Support_Modal SHALL announce its presence to screen readers when opened
7. THE System SHALL associate form labels with input fields using proper HTML label elements

### Requirement 10: Mobile-Responsive Enhancements

**User Story:** As a mobile device user, I want the chat widget to work seamlessly on small screens, so that I can request support from any device.

#### Acceptance Criteria

1. WHEN viewport width is below 768px, THE Support_Modal SHALL adjust to full-screen presentation
2. WHEN viewport width is below 768px, THE Quick_Action buttons SHALL stack vertically with full width
3. THE Chat_Widget button SHALL remain accessible on mobile without overlapping other UI elements
4. WHEN the mobile keyboard appears, THE Support_Modal SHALL scroll to keep the focused field visible
5. THE file upload interface SHALL use native mobile file picker capabilities
6. THE Chat_Widget button SHALL have minimum 48px touch target size for mobile usability

### Requirement 11: Form Validation and Error Handling

**User Story:** As a portal visitor, I want clear validation feedback, so that I can correct errors and successfully submit my request.

#### Acceptance Criteria

1. WHEN a required field is empty on submission, THE System SHALL display an error message "This field is required"
2. WHEN an email address is invalid, THE System SHALL display an error message "Please enter a valid email address"
3. WHEN a phone number does not match the expected format, THE System SHALL display an error message "Please enter a valid Zimbabwe phone number (+263...)"
4. WHEN the message field contains fewer than 10 characters, THE System SHALL display an error message "Please provide at least 10 characters"
5. WHEN the message field exceeds 5000 characters, THE System SHALL display a warning showing remaining character count
6. THE System SHALL validate all fields before allowing form submission
7. WHEN validation fails, THE System SHALL focus the first field with an error

### Requirement 12: Session Persistence

**User Story:** As a portal visitor, I want my partially completed form to be saved if I accidentally close the modal, so that I don't lose my progress.

#### Acceptance Criteria

1. WHEN a user types in any form field, THE System SHALL save the field value to browser localStorage
2. WHEN the Support_Modal reopens, THE System SHALL restore previously entered values from localStorage
3. WHEN the form is successfully submitted, THE System SHALL clear all saved form data from localStorage
4. THE System SHALL preserve form data for maximum 24 hours in localStorage
5. THE System SHALL NOT persist file attachments in localStorage (only field values)

### Requirement 13: Analytics and Tracking

**User Story:** As a system administrator, I want to track chat widget usage patterns, so that I can identify common support needs and improve the portal.

#### Acceptance Criteria

1. WHEN the Chat_Widget is clicked, THE System SHALL log an analytics event "chat_widget_opened"
2. WHEN a Quick_Action button is clicked, THE System SHALL log an analytics event with the action type
3. WHEN a Support_Ticket is created, THE System SHALL log the ticket category and priority level
4. WHEN form submission fails, THE System SHALL log the error type and validation failures
5. THE System SHALL NOT log any personally identifiable information in analytics events

### Requirement 14: Multi-Language Support Preparation

**User Story:** As a system architect, I want the chat widget structure to support future multi-language content, so that English and Shona translations can be added without redesign.

#### Acceptance Criteria

1. THE System SHALL use string constants or translation keys for all user-facing text
2. THE System SHALL structure HTML to accommodate text expansion (30% minimum) for translations
3. THE Support_Modal labels and placeholders SHALL reference language constants rather than hardcoded strings
4. THE System SHALL include HTML lang attribute on dynamic content sections

### Requirement 15: Performance Optimization

**User Story:** As a portal visitor with limited bandwidth, I want the chat widget to load quickly, so that I can access support without delays.

#### Acceptance Criteria

1. THE Chat_Widget JavaScript SHALL be loaded asynchronously to avoid blocking page render
2. THE System SHALL lazy-load the Support_Modal HTML only when the Chat_Widget is first clicked
3. THE System SHALL compress all chat widget assets (CSS, JavaScript) for production deployment
4. THE Chat_Widget SHALL initialize within 500ms of page load completion
5. THE Support_Modal SHALL open within 200ms of Chat_Widget click
6. THE System SHALL NOT include external dependencies or third-party libraries for chat widget functionality
