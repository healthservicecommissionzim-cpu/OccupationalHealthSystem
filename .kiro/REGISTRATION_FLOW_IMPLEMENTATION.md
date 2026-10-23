# Organization-Based Registration Implementation Summary

## Overview
Successfully implemented a two-step registration flow where users must select their organization (HSC or MOH) before accessing the registration form. The form dynamically hides/shows fields based on the selected organization.

## Completed Implementation

### 1. Database Migration ✅
- **File**: `database/migrations/2026_10_22_204836_add_registration_type_to_users_table.php`
- **Status**: Successfully migrated
- **Changes**: Added `registration_type` enum column to `users` table with values ('hsc', 'moh')

### 2. Model Updates ✅
- **File**: `app/Models/User.php`
- **Changes**: Added `registration_type` to the `$fillable` array for mass assignment

### 3. Controller Updates ✅
- **File**: `app/Http/Controllers/Auth/RegisterController.php`
- **Methods**:
  - `selectOrganization()` - Shows organization selection page
  - `create()` - Accepts organization parameter, validates it, passes `$isHscRegistration` flag to view
  - `store()` - Different validation rules for HSC vs MOH, stores `registration_type` field

**HSC Validation Rules**:
- first_name (required)
- last_name (required)
- email (required, unique)
- phone_number (required)
- designation_id (required)
- password (required, confirmed, strong)

**MOH Validation Rules**:
- All HSC fields plus
- organization_id (nullable)
- department_id (nullable)
- province_id (nullable)
- district_id (nullable)
- facility_type_id (nullable)
- facility_id (nullable)

### 4. Routes ✅
- **File**: `routes/web.php`
- **Routes Added**:
  - `GET /register/select-organization` → `register.select-org`
  - `GET /register?organization=hsc|moh` → `register` (requires org param)
  - `POST /register` → `register.store`
  - `GET /register/facility-data` → `register.facility-data`

### 5. Views Updated ✅

#### a. Organization Selection Page
- **File**: `resources/views/auth/select-organization.blade.php` (NEW)
- **Features**:
  - Beautiful card-based design with dual organization options
  - HSC option (shield icon) - for Conditions Officers
  - MOH option (hospital icon) - for Healthcare Workers
  - Back to login link
  - Fully responsive design
  - Smooth hover effects and transitions

#### b. Registration Form
- **File**: `resources/views/auth/register.blade.php` (MODIFIED)
- **Changes**:
  - Added hidden field `organization_type` containing selected organization
  - Organization Details section conditionally hidden for HSC registration
  - HSC users see only: First Name, Last Name, Email, Phone, Designation, Password
  - MOH users see full form with organization/facility/location fields

#### c. Welcome Page
- **File**: `resources/views/welcome.blade.php` (MODIFIED)
- **Changes**:
  - Desktop Register button: `route('register.select-org')`
  - Mobile Register button: `route('register.select-org')`
  - Both now point to organization selection page instead of direct registration

## Data Flow

### HSC Registration Path
```
1. User clicks "Register" on welcome page
   ↓
2. Organization Selection Page displays
   ↓
3. User clicks "Health Service Commission"
   ↓
4. Registration form loads with organization=hsc
   ↓
5. Organization Details section is hidden
   ↓
6. User fills: First Name, Last Name, Email, Phone, Designation, Password
   ↓
7. Form validates HSC-specific rules (no org/location required)
   ↓
8. User created with registration_type='hsc'
```

### MOH Registration Path
```
1. User clicks "Register" on welcome page
   ↓
2. Organization Selection Page displays
   ↓
3. User clicks "Ministry of Health"
   ↓
4. Registration form loads with organization=moh
   ↓
5. Organization Details section is visible
   ↓
6. User fills all fields (org, facility, province, district, etc.)
   ↓
7. Form validates MOH-specific rules (all fields optional)
   ↓
8. User created with registration_type='moh'
```

## Key Features

✅ **Mandatory Organization Selection** - Users cannot access registration without selecting organization
✅ **Conditional Field Visibility** - HSC hides all organization/location fields
✅ **Separate Validation Rules** - Different requirements for each organization type
✅ **Beautiful UI** - Organization selection page with icons and descriptions
✅ **Responsive Design** - Works on desktop and mobile devices
✅ **Data Persistence** - registration_type stored in database for future reference
✅ **Route Protection** - Organization parameter is validated before proceeding

## Testing Checklist

- [x] Registration routes configured correctly
- [x] Migration applied successfully
- [x] User model updated with fillable field
- [x] Organization selection page displays
- [x] Both welcome page Register buttons updated
- [x] HSC registration hides Organization Details section
- [x] MOH registration shows full form
- [x] Different validation rules applied per organization
- [x] registration_type column exists in database

## Files Modified/Created

### New Files
- `resources/views/auth/select-organization.blade.php`
- `database/migrations/2026_10_22_204836_add_registration_type_to_users_table.php`

### Modified Files
- `app/Http/Controllers/Auth/RegisterController.php`
- `resources/views/auth/register.blade.php`
- `resources/views/welcome.blade.php`
- `app/Models/User.php`
- `routes/web.php`

## Database Schema

```sql
ALTER TABLE users ADD COLUMN registration_type ENUM('hsc', 'moh') DEFAULT 'moh' AFTER password;
```

## Validation Examples

### HSC Registration Submission
```php
[
    'first_name' => 'John',
    'last_name' => 'Smith',
    'email' => 'john.smith@hsc.org.zw',
    'phone_number' => '0712345678',
    'designation_id' => 5,
    'password' => 'SecurePassword123!',
    'password_confirmation' => 'SecurePassword123!',
    'organization_type' => 'hsc'
]
```

### MOH Registration Submission
```php
[
    'first_name' => 'Jane',
    'last_name' => 'Doe',
    'email' => 'jane.doe@moh.org.zw',
    'phone_number' => '0798765432',
    'organization_id' => 1,
    'department_id' => 3,
    'designation_id' => 2,
    'province_id' => 1,
    'district_id' => 2,
    'facility_type_id' => 1,
    'facility_id' => 5,
    'password' => 'SecurePassword456!',
    'password_confirmation' => 'SecurePassword456!',
    'organization_type' => 'moh'
]
```

## Implementation Complete
All requirements have been implemented successfully. The system now properly routes users through organization selection before registration, with appropriate form field visibility based on their organization choice.
