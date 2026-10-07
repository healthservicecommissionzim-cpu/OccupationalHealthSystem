# Form Components Guide

## Task 4.1 Implementation: Responsive Form Component System

This guide documents the form components created as part of Task 4.1 - "Create form component with responsive inputs and labels."

### Files Created

1. **resources/css/forms.css** - Responsive form stylesheet with CSS-based styling
2. **resources/views/components/form-input.blade.php** - Text input component
3. **resources/views/components/form-textarea.blade.php** - Textarea component
4. **resources/views/components/form-select.blade.php** - Select dropdown component
5. **resources/views/components/form-checkbox.blade.php** - Checkbox component

---

## CSS Architecture (forms.css)

### Key Features

- **Mobile-First Design**: Base styles optimized for mobile, with media queries for tablet/desktop
- **Responsive Padding**: 0.5rem (mobile) → 0.625rem (tablet) → 0.7rem (desktop)
- **Touch-Friendly**: 44px minimum height for all input fields
- **Brand Colors**: Uses CSS variables from app.css
- **Plus Jakarta Sans**: All form text uses canonical font family
- **Accessibility**: Semantic HTML, focus states, error messaging
- **Error States**: Red borders (#ef4444), error text below fields
- **Focus Indicators**: Brand color border + glow shadow (var(--shadow-focus))

### Breakpoints

```css
Mobile:     ≤499px   - 0.5rem padding, compact spacing
Tablet:     500-767px - 0.625rem padding, medium spacing
Desktop:    768px+   - 0.7rem padding, relaxed spacing
```

### CSS Variables Used

From `resources/css/app.css`:

```css
--font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
--brand: #1D4533;
--brand-dark: #0f2818;
--slate-300: #cbd5e1;
--slate-400: #94a3b8;
--slate-700: #334155;
--red-500: #ef4444;
--shadow-focus: 0 0 0 3px var(--brand-glow);
--transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
```

---

## Component Usage

### 1. Text Input (`form-input.blade.php`)

Basic usage:
```blade
<x-form-input
    label="Email Address"
    name="email"
    type="email"
    placeholder="you@example.com"
    icon="envelope"
    required
/>
```

Props:
- `label` (string): Label text displayed above input
- `name` (string): Input name and id (if id not specified)
- `type` (string): HTML input type (default: "text")
- `placeholder` (string): Placeholder text
- `value` (mixed): Current value (uses old() for form repopulation)
- `error` (string): Custom error message
- `icon` (string): Font Awesome icon name (without "fa-")
- `required` (bool): Shows required indicator
- `disabled` (bool): Disables input
- `helper` (string): Helper text under label
- `id` (string): Custom HTML id (defaults to name)

Features:
- Semantic HTML: `<label for="">` paired with input
- Icon support via left-positioned absolute icon
- Error text in red (#ef4444) below field
- Automatic error detection from Laravel validation
- 44px minimum height for touch targets
- Plus Jakarta Sans font with 0.875rem base size

#### Example with Icon:
```blade
<x-form-input
    label="Password"
    name="password"
    type="password"
    icon="lock"
    required
    helper="Minimum 8 characters"
/>
```

#### Example with Custom Error:
```blade
<x-form-input
    label="Username"
    name="username"
    error="Username is already taken"
    icon="user"
    required
/>
```

---

### 2. Textarea (`form-textarea.blade.php`)

Basic usage:
```blade
<x-form-textarea
    label="Message"
    name="message"
    placeholder="Enter your message here..."
    required
/>
```

Props:
- Same as form-input, plus:
- `rows` (int): Number of rows (default: 5)

Features:
- Vertical resize only
- Minimum height: 120px (mobile), 140px (desktop)
- Same responsive padding as inputs
- Error support and validation

#### Example:
```blade
<x-form-textarea
    label="Support Ticket Description"
    name="description"
    placeholder="Describe your issue..."
    icon="message"
    required
    rows="6"
/>
```

---

### 3. Select Dropdown (`form-select.blade.php`)

Basic usage:
```blade
<x-form-select
    label="Country"
    name="country"
    :options="[
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
    ]"
    required
/>
```

Props:
- Same as form-input, plus:
- `options` (array): Key-value pairs for options
- `placeholder` (string): Placeholder option text (default: "Select an option")

Features:
- Custom styled dropdown arrow
- Brand color on focus
- Responsive icon support
- Error handling

#### Example with Icon:
```blade
<x-form-select
    label="Department"
    name="department"
    icon="building"
    :options="$departments"
    required
    helper="Select your department"
/>
```

---

### 4. Checkbox (`form-checkbox.blade.php`)

Basic usage:
```blade
<x-form-checkbox
    label="I agree to the terms and conditions"
    name="agree_terms"
    required
/>
```

Props:
- `label` (string): Label text displayed next to checkbox
- `name` (string): Input name and id
- `value` (string): Checkbox value (default: "1")
- `checked` (bool): Checked state
- `required` (bool): Required field
- `disabled` (bool): Disabled state
- `helper` (string): Helper text
- `id` (string): Custom HTML id

Features:
- 44px minimum touch target (including label)
- Brand color when checked
- Semantic HTML with label association
- Custom checkbox styling with brand colors

#### Example:
```blade
<x-form-checkbox
    label="Remember me"
    name="remember"
    value="true"
/>

<x-form-checkbox
    label="Subscribe to updates"
    name="subscribe"
    checked="{{ old('subscribe') }}"
/>
```

---

## Form Layout Utilities

### Multi-Column Layouts with `.form-row`

```blade
<div class="form-row">
    <x-form-input name="first_name" label="First Name" required />
    <x-form-input name="last_name" label="Last Name" required />
</div>
```

Responsive behavior:
- Mobile (≤499px): 1 column (full width each)
- Tablet (500-767px): 2 columns
- Desktop (768px+): 2 columns with larger gap

#### Three-Column Layout:
```blade
<div class="form-row three">
    <x-form-input name="city" label="City" />
    <x-form-input name="state" label="State" />
    <x-form-input name="zip" label="ZIP Code" />
</div>
```

Three columns only appear on desktop (768px+).

#### Full-Width Field in Multi-Column:
```blade
<div class="form-row">
    <x-form-textarea name="bio" label="Biography" class="full" />
</div>
```

---

## Validation & Error Display

### Automatic Error Detection

Components automatically detect Laravel validation errors:

```blade
<x-form-input
    label="Email"
    name="email"
    required
/>
```

When validation fails:
- Field gets `.has-error` class
- Red border (#ef4444) appears
- Error text displays in red below field
- Icon turns red (if present)
- aria-invalid="true" set for accessibility

### Custom Error Messages

```blade
<x-form-input
    label="Email"
    name="email"
    error="This email is already registered"
/>
```

### Controller Example

```php
$validated = $request->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8',
    'agree_terms' => 'required|accepted',
]);
```

The form will automatically display these validation errors.

---

## Accessibility Features

### Semantic HTML

All components use proper semantic HTML:

```html
<!-- Proper label association -->
<label for="email">Email</label>
<input id="email" type="email" name="email" />

<!-- Error association -->
<input aria-describedby="email-error" />
<div id="email-error" role="alert">Error message</div>

<!-- Required indicator -->
<span class="required" aria-label="required">*</span>
```

### Focus States

- Visible outline: 2px solid brand color
- Glow shadow: var(--shadow-focus)
- High contrast on focus
- Works with keyboard navigation

### Touch Targets

- Minimum 44px × 44px for all interactive elements
- Proper spacing between clickable elements
- Checkbox group: 44px minimum height including label

### Screen Reader Support

- Proper `<label for="">` associations
- aria-describedby for error messages
- aria-invalid for invalid fields
- role="alert" for error text

---

## Styling Customization

### CSS Classes for Custom Styling

```blade
<!-- Add custom classes -->
<x-form-input
    name="email"
    class="my-custom-class"
/>

<!-- Merge with attributes -->
<x-form-input
    name="email"
    :attributes="['data-test' => 'email-field']"
/>
```

### Overriding Default Styles

Modify `resources/css/forms.css`:

```css
/* Change input padding */
input[type='text'],
input[type='email'] {
    padding: 0.75rem 1rem;
}

/* Change focus color */
input:focus {
    border-color: #your-custom-color;
}
```

---

## Mobile Responsive Behavior

### Input Field Sizing

**Mobile (≤499px):**
- Padding: 0.5rem 0.75rem
- Font size: 0.875rem
- 44px minimum height
- Full width within container

**Tablet (500-767px):**
- Padding: 0.625rem 0.75rem
- Font size: 0.875rem
- 44px minimum height
- 2-column layout available

**Desktop (768px+):**
- Padding: 0.7rem 0.75rem
- Font size: 0.875rem
- 44px minimum height
- 2-3+ column layout available

### No Horizontal Scrolling

- All inputs size to 100% width of container
- Icons don't cause overflow
- Error text wraps properly on mobile
- Labels stack above inputs

---

## Requirements Compliance

Task 4.1 validates:

✅ **Requirement 1.1** - Login page responsive layout on mobile  
✅ **Requirement 2.1** - Register page responsive layout on mobile  
✅ **Requirement 4.1** - Form component with responsive inputs  
✅ **Requirement 6.1** - Support page responsive form  
✅ **Requirement 9.1** - 44px minimum touch targets  
✅ **Requirement 9.2** - Semantic HTML labels with proper association  
✅ **Requirement 12.4** - Font: Plus Jakarta Sans on all form text

### Key Features Implemented

1. **Semantic HTML**: `<label for="">` paired with inputs
2. **Input Sizing**: 44px minimum height on all screen sizes
3. **Responsive Padding**: 0.5rem (mobile), 0.7rem (desktop)
4. **Border and Focus Styles**: Brand color (#1D4533) on focus
5. **Plus Jakarta Sans**: All form text uses canonical font
6. **Touch-Friendly**: All interactive elements properly sized
7. **Error Messaging**: Below fields in red (#ef4444)
8. **Accessibility**: Focus indicators, aria attributes, semantic HTML

---

## Integration with Login/Register Pages

To use these components in login or register pages:

```blade
<!-- Login Form -->
<form method="POST" action="{{ route('login') }}" novalidate>
    @csrf

    <x-form-input
        label="Email address"
        name="email"
        type="email"
        placeholder="you@example.com"
        icon="envelope"
        required
    />

    <x-form-input
        label="Password"
        name="password"
        type="password"
        icon="lock"
        required
    />

    <x-form-checkbox
        label="Remember me"
        name="remember"
    />

    <button type="submit" class="btn btn-primary">Log in</button>
</form>
```

---

## Next Steps

Task 4.2 will implement form validation and error messaging.  
Task 4.3 will build the responsive button system.

This foundation ensures all forms across the application maintain consistent, responsive, accessible styling.
