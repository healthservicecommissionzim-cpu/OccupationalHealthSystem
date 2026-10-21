# HSC-OHS Chatbot Knowledge Base
## Complete System Guide for Intelligent Chatbot Assistance

**Document Version**: 1.0  
**Last Updated**: October 21, 2026  
**Purpose**: Comprehensive knowledge base for HSC-OHS chatbot system  
**Audience**: Chatbot users seeking information about incident reporting, case tracking, resources, and support

---

## TABLE OF CONTENTS

1. [System Overview & Purpose](#1-system-overview--purpose)
2. [Getting Started Guide](#2-getting-started-guide)
3. [User Roles & Capabilities](#3-user-roles--capabilities)
4. [How to Report an Incident](#4-how-to-report-an-incident)
5. [Tracking Your Case](#5-tracking-your-case)
6. [Uploading Documents](#6-uploading-documents)
7. [Understanding Notifications](#7-understanding-notifications)
8. [Leave Milestones & Check-Ins](#8-leave-milestones--check-ins)
9. [Support & Help](#9-support--help)
10. [Resources & Compliance Hub](#10-resources--compliance-hub)
11. [Safety Protocols & Emergency Procedures](#11-safety-protocols--emergency-procedures)
12. [Frequently Asked Questions](#12-frequently-asked-questions)
13. [Troubleshooting Guide](#13-troubleshooting-guide)
14. [System Navigation & Sitemap](#14-system-navigation--sitemap)

---

## 1. SYSTEM OVERVIEW & PURPOSE

### What is HSC-OHS?

**HSC-OHS** (Health Service Commission - Occupational Health & Safety Portal) is Zimbabwe's primary digital platform for healthcare workers to report and manage occupational health and safety incidents.

The system provides:
- **Secure incident reporting** for injuries, exposures, and near-misses
- **Real-time case tracking** with progress updates
- **Confidential communication** with occupational health professionals
- **Resource library** with compliance documents and safety guidelines
- **24/7 accessibility** from any device with internet access

### Core Mission

To protect Zimbabwe's healthcare workforce by:
- Enabling rapid response to occupational health emergencies
- Reducing workplace injuries and illnesses in healthcare facilities
- Ensuring fair compensation through NSSA integration
- Providing evidence-based safety guidelines and protocols
- Maintaining comprehensive audit trails for compliance

### Who Operates HSC-OHS?

- **Health Service Commission (HSC)** - Government oversight body
- **Ministry of Health & Child Care (MoHCC)** - Policy coordination
- **National Social Security Authority (NSSA)** - Workers' compensation integration
- **Occupational Health & Safety Team** - Clinical specialists and support staff

### Key Benefits

✅ **Available 24/7** - Report anytime, even outside business hours  
✅ **Confidential** - Military-grade encryption, protected by privacy laws  
✅ **Free** - No cost to report; all services included  
✅ **Supported** - Occupational Health Doctor reviews each case personally  
✅ **Tracked** - See every step of your case progress in real-time  
✅ **Accessible** - Works on phone, tablet, or computer  
✅ **Integrated** - Automatic connection to NSSA compensation system  
✅ **Helpful** - Dedicated support team and 24/7 chatbot assistance

---

## 2. GETTING STARTED GUIDE

### For First-Time Users

#### Option A: Report Without Login (Fastest - 3 Minutes)

**You Don't Need an Account To:**
- Report an incident
- Provide reference number information
- Track case via reference number + phone number
- Submit witness statements

**To Report Without Login:**
1. Visit the homepage at `https://injury.hsc.org.zw`
2. Click "Report an Incident" button
3. Fill out quick form with:
   - Your full name
   - Phone number (for SMS updates)
   - Healthcare facility
   - Date and time of incident
   - Brief description (2-3 sentences)
   - Incident type: Injury / Exposure / Near-Miss / First Aid
4. Click "Submit"
5. **You'll receive a reference number immediately** (e.g., OHS-2026-08-001)

#### Option B: Create an Account (For Full Access)

**Why Create an Account?**
- View all your cases in one dashboard
- Upload documents without entering reference number each time
- Automatic SMS/email notifications
- Save your profile information
- Faster incident reporting with auto-filled details

**Registration Steps:**
1. Go to `https://injury.hsc.org.zw`
2. Click "Register" button (or go directly to `/register`)
3. Enter information:
   - Full name (as on ID)
   - Email address (personal or work)
   - Phone number (for SMS notifications)
   - Healthcare facility (select from dropdown)
   - Job title/Designation
   - Department
   - Create a password (minimum 8 characters with mix of upper/lower case, numbers)
4. Click "Create Account"
5. **Check your email** for verification link
6. Click the link to activate your account
7. Log in with email and password

**Account Status:**
- **New accounts require approval** before first access
- Approval typically takes 24-48 hours
- You'll receive email when account is activated
- Super admin team manually verifies all new registrations

---

## 3. USER ROLES & CAPABILITIES

### Workers (General Public)

**What You Can Do:**
- Report incidents and exposures 24/7
- Track case progress via reference number
- Upload supporting documents (medical reports, photos)
- View incident timeline and status updates
- Receive SMS/email notifications
- Access public resources and safety guidelines
- Submit support requests for help

**What You Cannot Do:**
- View other workers' cases
- Approve or reject documents
- Access HR dashboard or administrative functions
- See financial or compensation data

**Access**: Public report form at `/incidents/create` or logged-in dashboard

---

### Registered Workers (With Account)

**Same as above, PLUS:**
- Faster incident reporting (details auto-filled)
- Centralized dashboard showing all your cases
- Document upload history
- Leave milestone tracking
- Personalized notifications
- Support ticket submissions

**Access**: Login at `/login` with email/password

---

### HR & Occupational Health Staff

**Access Level**: High, role-based permissions

**Can Do:**
- View assigned incidents
- Review and verify worker-uploaded documents
- Update case status and add notes
- Manage leave milestones (30, 90, 180-day check-ins)
- Create executive recommendations
- Generate reports and export data
- Send notifications to workers

**Common Staff Roles:**
- **District HR** - Manages cases for one district
- **Hospital HR** - Manages cases for one hospital  
- **Central HR** - Provincial coordination
- **Occupational Health Doctor** - Clinical assessments
- **Occupational Health Nurse** - Initial assessments
- **Medical Board Members** - Specialized case reviews

**Access**: Secure login at `/login` with assigned credentials

---

### Super Administrators

**Access Level**: Full system access

**Can Do:**
- Manage all users and role assignments
- Configure system settings and thresholds
- View comprehensive analytics and dashboards
- Generate compliance reports
- Manage facilities, departments, organization structure
- Access audit logs for all system activities
- Manage support ticket system
- Monitor communication logs
- Access system documentation

**Access**: Super admin dashboard at `/super-admin` with full permissions

---

## 4. HOW TO REPORT AN INCIDENT

### Method 1: Quick Report (3 Minutes - Recommended)

The fastest way to report an incident if something just happened.

**What You'll Need:**
- Your full name
- Phone number (for SMS notifications)
- Name of your healthcare facility
- Date and time the incident occurred
- Brief description (2-3 sentences)

**Steps:**
1. Navigate to `https://injury.hsc.org.zw/incidents/create`
2. Click "Quick Report"
3. Fill in the form:
   - **Full Name**: Your legal name
   - **Phone Number**: Mobile number for SMS updates
   - **Healthcare Facility**: Select from dropdown
   - **Date & Time**: When the incident happened
   - **Incident Type**: Choose one:
     - Injury (cut, burn, needlestick, fracture, etc.)
     - Exposure (bloodborne pathogen, chemical, biological)
     - Near-Miss (almost happened but no one hurt)
     - First Aid (minor incident already treated)
   - **Brief Description**: 2-3 sentences about what happened
4. Click "Submit"
5. **Instant Confirmation**: System generates your reference number

**After Quick Report:**
- You'll receive the reference number on screen (write it down!)
- SMS confirmation sent to phone number provided
- Within 24 hours, you'll be prompted to complete detailed report
- Email with instructions on next steps

---

### Method 2: Detailed Report (Comprehensive - 10 Minutes)

For more complex incidents requiring detailed documentation.

**What You'll Need:**
- Quick report reference number (or create account first)
- Witness names and contact information
- Incident location details
- Details of any body parts affected (if injury)
- Any medical attention already provided
- Photos or documents (optional but helpful)

**Steps:**
1. Go to `https://injury.hsc.org.zw/incidents/create`
2. Click "Detailed Report"
3. Complete these sections:

   **A. Personal Information** (auto-filled if logged in)
   - Full name
   - Email address
   - Phone number
   - Healthcare facility
   - Department
   - Job title

   **B. Incident Details**
   - Date incident occurred (DD/MM/YYYY)
   - Time incident occurred (HH:MM)
   - Incident location (ward, department, specific area)
   - Incident type (Injury / Exposure / Near-Miss)
   - Incident description (detailed explanation of what happened)
   - Body parts affected (if injury - select from checklist)

   **C. Witness Information**
   - Witness #1 full name
   - Witness #1 phone number
   - Witness #1 email
   - Witness #2 full name (optional)
   - Witness #2 phone number (optional)
   - Witness #2 email (optional)

   **D. Medical Information**
   - Was medical attention provided? (Yes/No)
   - If yes, what facility? (Hospital, clinic, first aid station, etc.)
   - Type of treatment received (select from list or describe)
   - Any medications administered?
   - Current health status (recovering, still symptomatic, etc.)

   **E. Documentation** (optional but recommended)
   - Upload incident photographs
   - Upload medical reports
   - Upload test results
   - Upload other supporting documents
   - File size limit: 25 MB per file

4. Review all information for accuracy
5. Click "Submit Detailed Report"
6. **Confirmation**: Reference number confirmed

**Incident Reference Number Format**:
- Example: **OHS-2026-08-001**
- Used to track case progress
- Save this number - you'll need it to check status
- Received via SMS and email

---

## 5. TRACKING YOUR CASE

### Without an Account (Using Reference Number)

**What You'll Need:**
- Your incident reference number (e.g., OHS-2026-08-001)
- Phone number used in initial report

**Steps:**
1. Go to `https://injury.hsc.org.zw/incidents/track`
2. Enter reference number
3. Enter phone number (must match original report)
4. Click "Find My Case"
5. **View Your Case Information:**
   - Current status
   - Timeline of all updates
   - Documents uploaded and verified
   - Next steps in the process
   - Assigned contact person

**What You Can Do From This Page:**
- Upload additional documents
- View all notifications sent to you
- See expected timeline to final decision
- Track milestone dates (30, 90, 180-day check-ins if leave involved)

---

## 6. UPLOADING DOCUMENTS

### Why Upload Documents?

Documents provide evidence that helps HR and occupational health teams understand your incident. Examples:
- Medical reports from doctors
- Blood test or lab results
- Injury photographs
- Incident scene photographs
- Medical certificates

### How to Upload Documents

**Option 1: From Case Tracking Page**
1. Go to `https://injury.hsc.org.zw/incidents/track`
2. Enter reference number and phone number
3. Click "Upload Documents"
4. Select document category
5. Click "Choose File" and select file
6. Click "Upload"

**Option 2: From Logged-In Dashboard**
1. Log in to account
2. Go to "My Cases"
3. Click the relevant case
4. Click "Upload Documents"
5. Follow same upload process

### Important Document Requirements

**File Format**: PDF, JPG, PNG, Word document (.docx)  
**File Size**: Maximum 25 MB per file  
**Image Quality**: Clear, legible photographs  
**Document Status**: Shows as "PENDING" until HR verifies  
**HR Verification**: Documents reviewed within 24 hours  

---

## 7. UNDERSTANDING NOTIFICATIONS

### Types of Notifications You'll Receive

The system sends updates via **Email** and **SMS** (text message).

### Email Notifications

**Example 1: Case Status Update**
- **Subject**: "Your OHS Case Status Update - OHS-2026-08-001"
- **Contains**: Current status, who's reviewing, what happens next

**Example 2: Documents Received**
- **Subject**: "We Received Your Documents - OHS-2026-08-001"
- **Contains**: List of documents received, next review steps

**Example 3: Milestone Check-In Due**
- **Subject**: "Your 30-Day Case Milestone Check-In Required"
- **Contains**: Date check-in is due, what it means, how to proceed

### SMS Notifications (Text Messages)

**Format**: Brief text messages to phone number on file

**Example 1**: "HSC-OHS: Your case OHS-2026-08-001 status updated. Check email for details."

**Example 2**: "HSC-OHS: Documents uploaded for OHS-2026-08-001. HR reviewing. You'll hear within 24 hours."

---

## 8. LEAVE MILESTONES & CHECK-INS

### What Are Leave Milestones?

If your incident results in medical leave, the system schedules automatic check-ins:

- **30-Day Check-In** (1 month after incident)
- **90-Day Check-In** (3 months after incident)  
- **180-Day Check-In** (6 months after incident)

### How Milestones Work

**Step 1**: Doctor determines if leave is needed  
**Step 2**: System sends automatic reminders 5 days before each milestone  
**Step 3**: Doctor contacts you for check-in discussion  
**Step 4**: Doctor updates your case status  
**Step 5**: You're notified of decision (continue leave / return to work / extend)  

---

## 9. SUPPORT & HELP

### Create a Support Ticket

**When to Submit:**
- System login issues
- Document upload problems
- Questions about your case
- Technical problems with website
- Account approval delays
- Any other concerns

**Steps:**
1. Go to `https://injury.hsc.org.zw`
2. Click chat icon or "Get Help"
3. Select "Create Support Ticket"
4. Describe your issue
5. Select category (Account, Incident, Case Status, Document Upload, Technical, Other)
6. Click "Submit"

**Response Timeline:**
- Support team reviews within 24 business hours
- You'll receive email with assigned agent
- Agent responds to your issue

### Support Contact

**Email**: support@hsc.org.zw  
**Hours**: 8 AM - 5 PM, Monday - Friday  
**Response Time**: Within 24 business hours  

---

## 10. RESOURCES & COMPLIANCE HUB

### What Resources Are Available?

The HSC-OHS system provides free access to:
- National regulatory & compliance documents
- Clinical protocols & hazard safety procedures
- Mental health & wellness resources
- Training & institutional materials

### How to Access Resources

**From Homepage:**
1. Click "Resources & Compliance"
2. Browse resources by category
3. Search for specific topics
4. Download PDF, Excel, or Word files

### Resource Categories

**A. National Regulatory & Compliance**
- Occupational Safety and Health Bill, 2025
- NSSA Forms and guidelines
- Workers' Compensation Claims Guide

**B. Clinical Protocols & Hazard Safety**
- Needle-Stick & PEP Flowchart
- Hospital Chemical SDS Directory
- Patient Ergonomics & Safe Lifting
- Heat Stress Management

**C. Mental Health & Wellness**
- Burnout Self-Assessment
- Wellness Action Plan
- 24/7 Crisis Directory
- Stress Management Resources

**D. Training & Institutional Materials**
- 5-Minute Toolbox Talks
- Ward Hazard Risk Assessment Matrix
- OHS Committee SOP Template
- Incident Investigation Forms

### All Resources Are Free

- ✅ No login required to access
- ✅ No cost or subscription needed
- ✅ Available 24/7
- ✅ Updated regularly with latest guidelines

---

## 11. SAFETY PROTOCOLS & EMERGENCY PROCEDURES

### Emergency Procedures Available on System

Access at `https://injury.hsc.org.zw/safety-protocols`

### For Needle-Stick or Sharp Injury

**Immediate Actions (First 2 Minutes):**
1. **Stop work immediately**
2. **Allow wound to bleed** - Let blood flow naturally for 10-15 seconds
3. **Wash wound** - Use soap and running water; wash for at least 1 minute
4. **Dry wound** - Use clean towel
5. **Apply antiseptic** - Povidone iodine or chlorhexidine
6. **Cover wound** - Sterile bandage

**Within 30 Minutes:**
1. **Report to occupational health clinic**
2. **Receive Post-Exposure Prophylaxis (PEP)** if indicated
3. **Blood test** - Baseline testing for HIV, Hepatitis B, Hepatitis C
4. **Document incident**

### For Blood or Body Fluid Exposure

**Immediate Actions:**
1. **Wash exposed skin** - Soap and water, 10-15 seconds
2. **Eyes exposed** - Flush with saline or water for 5 minutes
3. **Mucous membranes** - Rinse with water for 2-3 minutes
4. **Stop bleeding** - Allow small cuts to bleed briefly

**Next Steps:**
1. **Report incident immediately**
2. **Receive medical evaluation**
3. **Baseline blood tests** - If needed
4. **Document incident at `/incidents/create`**

### For Chemical Exposure

**Immediate Actions:**
1. **Remove from source** - Move to fresh air
2. **Remove contaminated clothing** - Wash skin with water
3. **Check SDS** - Locate Safety Data Sheet for chemical
4. **Follow specific treatment** - Chemical-specific instructions
5. **Seek medical help** - Report to occupational health clinic

### Mental Health Crisis Response

**If You're In Crisis:**
- **24/7 Crisis Hotline**: [Number in resources]
- **Crisis Counselor**: Available 24/7
- **Emergency Services**: Call 999

**Resources Available:**
1. Go to `/resources` → Mental Health & Wellness
2. Download "24/7 Healthcare Worker Crisis Directory"
3. Contact local counseling services
4. Access peer support networks

---

## 12. FREQUENTLY ASKED QUESTIONS

### General System Questions

**Q: Is reporting mandatory?**  
A: Strongly encouraged but technically voluntary. Reporting ensures support and potential compensation eligibility.

**Q: Is my report confidential?**  
A: Yes. Protected under privacy laws. No information is sold or shared with outside companies.

**Q: Who will see my report?**  
A: Only necessary personnel (occupational health doctor, HR staff for your case, super admins for admin purposes). NOT shared publicly or with your employer except for essential work-related decisions.

**Q: Do I need an account?**  
A: No. You can report and track cases without login using your reference number.

**Q: What if I lose my reference number?**  
A: You can recover it by going to `/incidents/track` and entering your phone number.

**Q: How long does the process take?**  
A: Typically 2-12 weeks depending on complexity.

---

### Questions About Uploading Documents

**Q: What documents should I upload?**  
A: Doctor's letter, blood test results, X-ray reports, medical certificates, incident photos, witness statements.

**Q: What file size limit?**  
A: Maximum 25 MB per file.

**Q: What file types are accepted?**  
A: PDF, JPG, PNG, Word (.docx), Excel (.xlsx).

**Q: Can I upload later?**  
A: Yes, anytime during your case.

**Q: Will HR contact me after upload?**  
A: Yes. Email confirmation within 24 hours, plus SMS if major change.

---

### Questions About Case Tracking

**Q: How do I check my status?**  
A: Go to `/incidents/track` with reference number + phone, OR log in to dashboard.

**Q: How often is status updated?**  
A: Updated when supervisor verifies (3-5 days), HR reviews (5-15 days), medical assesses (5-15 days), documents verified (24 hours).

**Q: What if status hasn't changed in weeks?**  
A: Submit support ticket with your reference number.

---

### Questions About Compensation

**Q: Will I get paid for time off?**  
A: If approved as work-related with recommended leave, you may be eligible for NSSA compensation.

**Q: What if my case is rejected - can I appeal?**  
A: Yes. You have 14 days to file appeal with additional information.

---

## 13. TROUBLESHOOTING GUIDE

### Account & Login Issues

**Problem: I forgot my password**
1. Go to login page
2. Click "Forgot Password?"
3. Enter email address
4. Check email for reset link (including spam folder)
5. Click link and enter new password

**Problem: My account is locked**
- Wait 30 minutes and try again
- If still locked, submit support ticket: "Account locked"
- Support team will unlock within 24 hours

---

### Incident Reporting Issues

**Problem: Quick report didn't generate reference number**
1. Check page is fully loaded
2. Look for reference number on screen
3. Check email - might have been sent there
4. Try submitting form again
5. Contact support if still no reference number

**Problem: File upload fails - "File too large"**
- File size limit is 25 MB
- Check your file size (Windows: right-click → Properties; Mac: right-click → Get Info)
- If larger, compress file or reduce image quality
- Try uploading again

---

### Case Tracking Issues

**Problem: Can't find my case using reference number**
1. Double-check reference number (exact number with hyphens)
2. Verify phone number matches original report
3. If you have account, log in to see all cases
4. Submit support ticket with name, phone, facility if still missing

**Problem: Case status shows old information**
1. Refresh page (F5 or Command+R on Mac)
2. Clear browser cache
3. Try different browser
4. Submit support ticket if still old after refresh

---

### Notification Issues

**Problem: Not receiving SMS notifications**
1. Check account settings - SMS might be disabled
2. Verify phone number is current in settings
3. Make sure SMS is enabled in Notification Preferences
4. Submit support ticket if still not receiving after enabling

**Problem: Receiving too many notifications**
1. Log in to account
2. Go to Settings → Notification Preferences
3. Disable SMS or email (you choose which to keep)

---

## 14. SYSTEM NAVIGATION & SITEMAP

### Website Map for HSC-OHS

**All public pages at: https://injury.hsc.org.zw**

### Public Pages (No Login Needed)

| Page | URL | Purpose |
|------|-----|---------|
| **Homepage** | `/` | Welcome, navigation, feature overview |
| **Report Incident** | `/incidents/create` | Quick or detailed incident reporting |
| **Track Case** | `/incidents/track` | View case status using reference number |
| **First Aid Report** | `/first-aid/token` | Report minor first aid incidents |
| **Resources Hub** | `/resources` | Download compliance docs, protocols, guides |
| **Safety Protocols** | `/safety-protocols` | Emergency procedures (PEP, exposure, etc.) |
| **Witness Statement** | `/incidents/witness/{token}` | Submit witness statement via email link |

### Authenticated Pages (Login Required)

| Page | URL | Purpose |
|------|-----|---------|
| **Dashboard** | `/dashboard` | Overview of all your cases |
| **My Cases** | `/dashboard/my-cases` | List of your reported incidents |
| **Profile** | `/profile` | Update personal information |
| **Settings** | `/profile/settings` | Notification preferences, security |
| **Support Tickets** | `/support/tickets` | View your help requests |

### Authentication Pages

| Page | URL | Purpose |
|------|-----|---------|
| **Login** | `/login` | Sign in with email/password |
| **Register** | `/register` | Create new account |
| **Reset Password** | `/password-reset` | Recover forgotten password |

---

### Quick Navigation Tips

**I want to...** | **Do this:**
---|---
Report an incident | Click "Report Incident" on homepage or go to `/incidents/create`
Track my case | Enter reference number at `/incidents/track`
Download resources | Go to `/resources` and click download
Learn safety protocols | Visit `/safety-protocols`
Get help/contact support | Click "Get Help" and submit ticket
Log in to account | Click "Login" and enter email/password

---

## APPENDIX: Important Numbers & Links

### Support Contacts

**Email**: support@hsc.org.zw  
**Phone**: [to be filled with HSC number]  
**Hours**: 8:00 AM - 5:00 PM, Monday - Friday  

### Useful Websites

- **HSC Website**: www.hsc.org.zw
- **Ministry of Health**: www.moh.gov.zw
- **NSSA Website**: www.nssa.org.zw

### Emergency Resources

**Mental Health Crisis**: [24/7 hotline number]  
**Medical Emergency**: 999  
**Occupational Health After Hours**: [emergency number]  

---

**Document Version**: 1.0  
**Last Updated**: October 21, 2026  
**Next Review**: January 2027  

**END OF KNOWLEDGE BASE**
