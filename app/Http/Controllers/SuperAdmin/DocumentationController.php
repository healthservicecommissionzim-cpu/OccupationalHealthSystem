<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $docs = $this->getDocumentationStructure();
        $currentDoc = $docs[0] ?? null;
        
        return view('system.documentation', compact('docs', 'currentDoc'));
    }

    public function show($slug)
    {
        $docs = $this->getDocumentationStructure();
        $currentDoc = $this->findDocBySlug($docs, $slug);
        
        if (!$currentDoc) {
            abort(404, 'Documentation page not found');
        }
        
        return view('system.documentation', compact('docs', 'currentDoc'));
    }

    private function getDocumentationStructure()
    {
        return [
            [
                'title' => 'Getting Started',
                'slug' => 'getting-started',
                'icon' => 'fa-rocket',
                'content' => $this->getGettingStartedContent()
            ],
            [
                'title' => 'User Roles & Permissions',
                'slug' => 'user-roles',
                'icon' => 'fa-users',
                'content' => $this->getUserRolesContent()
            ],
            [
                'title' => 'Incident Reporting',
                'slug' => 'incident-reporting',
                'icon' => 'fa-triangle-exclamation',
                'content' => $this->getIncidentReportingContent()
            ],
            [
                'title' => 'Supervisor Workflow',
                'slug' => 'supervisor-workflow',
                'icon' => 'fa-user-tie',
                'content' => $this->getSupervisorWorkflowContent()
            ],
            [
                'title' => 'HR Review Process',
                'slug' => 'hr-review',
                'icon' => 'fa-clipboard-check',
                'content' => $this->getHRReviewContent()
            ],
            [
                'title' => 'Document Management',
                'slug' => 'document-management',
                'icon' => 'fa-file-alt',
                'content' => $this->getDocumentManagementContent()
            ],
            [
                'title' => 'Notifications System',
                'slug' => 'notifications',
                'icon' => 'fa-bell',
                'content' => $this->getNotificationsContent()
            ],
            [
                'title' => 'System Configuration',
                'slug' => 'system-configuration',
                'icon' => 'fa-cog',
                'content' => $this->getSystemConfigContent()
            ],
            [
                'title' => 'Database Backup',
                'slug' => 'database-backup',
                'icon' => 'fa-database',
                'content' => $this->getDatabaseBackupContent()
            ],
            [
                'title' => 'Troubleshooting',
                'slug' => 'troubleshooting',
                'icon' => 'fa-wrench',
                'content' => $this->getTroubleshootingContent()
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'icon' => 'fa-question-circle',
                'content' => $this->getFAQContent()
            ]
        ];
    }

    private function findDocBySlug($docs, $slug)
    {
        foreach ($docs as $doc) {
            if ($doc['slug'] === $slug) {
                return $doc;
            }
        }
        return null;
    }

    private function getGettingStartedContent()
    {
        return <<<'HTML'
<h2>Welcome to the HSC OHS System</h2>
<p>This comprehensive system helps manage occupational health and safety incidents across all Health Service Commission facilities.</p>

<h3>What is this system?</h3>
<p>The HSC OHS (Occupational Health and Safety) System is designed to:</p>
<ul>
    <li><strong>Report workplace incidents</strong> - Workers can quickly report injuries, accidents, or near-misses</li>
    <li><strong>Track incident progress</strong> - Monitor cases from initial report through to resolution</li>
    <li><strong>Manage documentation</strong> - Upload medical reports, witness statements, and other documents</li>
    <li><strong>Ensure compliance</strong> - Follow statutory requirements and HSB regulations</li>
    <li><strong>Generate reports</strong> - Analyze incident trends and patterns</li>
</ul>

<h3>Quick Start Guide</h3>
<ol>
    <li><strong>Log in</strong> - Use your HSC credentials to access the system</li>
    <li><strong>Select your role</strong> - The system will direct you to the appropriate dashboard</li>
    <li><strong>Complete your profile</strong> - Ensure your contact information is up to date</li>
    <li><strong>Explore the dashboard</strong> - Familiarize yourself with the available features</li>
</ol>

<h3>System Requirements</h3>
<ul>
    <li>Modern web browser (Chrome, Firefox, Safari, Edge)</li>
    <li>Internet connection</li>
    <li>Valid HSC email address</li>
    <li>Mobile device or computer</li>
</ul>

<div class="alert alert-info mt-4">
    <i class="fa-solid fa-lightbulb me-2"></i>
    <strong>Tip:</strong> Bookmark this documentation page for quick access to help resources.
</div>
HTML;
    }

    private function getUserRolesContent()
    {
        return <<<'HTML'
<h2>User Roles & Permissions</h2>
<p>The system has four main user roles, each with specific responsibilities and access levels.</p>

<div class="role-card">
    <div class="role-card-header">
        <div class="role-card-icon">
            <i class="fa-solid fa-person"></i>
        </div>
        <div class="role-card-title">
            <h3>Worker</h3>
            <p class="role-purpose">Report workplace incidents and track their progress</p>
        </div>
    </div>
    <h4>Key Permissions</h4>
    <ul>
        <li>Report new incidents</li>
        <li>Upload supporting documents</li>
        <li>View own incident reports</li>
        <li>Track incident status</li>
        <li>Receive notifications about case updates</li>
    </ul>
</div>

<div class="role-card">
    <div class="role-card-header">
        <div class="role-card-icon">
            <i class="fa-solid fa-user-tie"></i>
        </div>
        <div class="role-card-title">
            <h3>Supervisor</h3>
            <p class="role-purpose">Verify and escalate incidents from their facility or department</p>
        </div>
    </div>
    <h4>Key Permissions</h4>
    <ul>
        <li>View incidents from assigned facilities</li>
        <li>Verify incident details</li>
        <li>Add supervisor comments</li>
        <li>Escalate cases to HR</li>
        <li>View first aid records</li>
        <li>Monitor compliance metrics</li>
    </ul>
</div>

<div class="role-card">
    <div class="role-card-header">
        <div class="role-card-icon">
            <i class="fa-solid fa-briefcase"></i>
        </div>
        <div class="role-card-title">
            <h3>HR (Human Resources)</h3>
            <p class="role-purpose">Review, approve, and manage incident cases</p>
        </div>
    </div>
    <h4>Key Permissions</h4>
    <ul>
        <li>Review escalated incidents</li>
        <li>Request additional documentation</li>
        <li>Approve or reject cases</li>
        <li>Manage leave recommendations</li>
        <li>Generate HR reports</li>
        <li>Access all facility incidents</li>
    </ul>
</div>

<div class="role-card">
    <div class="role-card-header">
        <div class="role-card-icon">
            <i class="fa-solid fa-shield"></i>
        </div>
        <div class="role-card-title">
            <h3>Super Admin</h3>
            <p class="role-purpose">Manage the entire system and configuration</p>
        </div>
    </div>
    <h4>Key Permissions</h4>
    <ul>
        <li>All system access</li>
        <li>User management and approvals</li>
        <li>System configuration</li>
        <li>Database backups</li>
        <li>National-level reporting</li>
        <li>Audit log access</li>
    </ul>
</div>

<div class="alert alert-warning mt-4">
    <i class="fa-solid fa-shield-halved me-2"></i>
    <strong>Security Note:</strong> User roles are assigned based on job responsibilities. Contact your system administrator if you need role changes.
</div>
HTML;
    }

    private function getIncidentReportingContent()
    {
        return <<<'HTML'
<h2>How to Report an Incident</h2>
<p>Follow these simple steps to report a workplace incident.</p>

<h3>Step 1: Access the Reporting Form</h3>
<ol>
    <li>Log into the system with your credentials</li>
    <li>Click on "Report Incident" from your dashboard</li>
    <li>The incident form will open</li>
</ol>

<h3>Step 2: Provide Incident Details</h3>
<p>Complete all required fields:</p>
<ul>
    <li><strong>Date & Time:</strong> When did the incident occur?</li>
    <li><strong>Location:</strong> Select your facility and specific area</li>
    <li><strong>Incident Type:</strong> Choose from the dropdown (injury, near-miss, etc.)</li>
    <li><strong>Description:</strong> Describe what happened in detail</li>
    <li><strong>Body Part Affected:</strong> If injury occurred, specify location</li>
</ul>

<h3>Step 3: Add Witnesses (Optional)</h3>
<p>If there were witnesses:</p>
<ul>
    <li>Click "Add Witness"</li>
    <li>Provide witness name and contact details</li>
    <li>Witnesses will receive a link to submit their statement</li>
</ul>

<h3>Step 4: Upload Documents</h3>
<p>Attach relevant documents:</p>
<ul>
    <li>Photos of the incident scene</li>
    <li>Medical reports (if applicable)</li>
    <li>Any other supporting evidence</li>
</ul>

<h3>Step 5: Submit the Report</h3>
<ol>
    <li>Review all information carefully</li>
    <li>Click "Submit Report"</li>
    <li>You'll receive a reference number - save this!</li>
    <li>A confirmation email will be sent to you</li>
</ol>

<div class="alert alert-success mt-4">
    <i class="fa-solid fa-check-circle me-2"></i>
    <strong>Success:</strong> Your incident has been reported! You can track its progress using your reference number.
</div>

<h3>What Happens Next?</h3>
<ol>
    <li><strong>Supervisor Review:</strong> Your supervisor will verify the incident details</li>
    <li><strong>HR Review:</strong> If needed, HR will review and approve the case</li>
    <li><strong>Updates:</strong> You'll receive notifications at each stage</li>
    <li><strong>Resolution:</strong> Case is closed once all actions are complete</li>
</ol>
HTML;
    }

    private function getSupervisorWorkflowContent()
    {
        return <<<'HTML'
<h2>Supervisor Workflow Guide</h2>
<p>As a supervisor, you play a crucial role in verifying and escalating incidents.</p>

<h3>Your Dashboard</h3>
<p>When you log in, you'll see:</p>
<ul>
    <li>Pending incidents requiring your review</li>
    <li>Total incidents from your facility</li>
    <li>Compliance metrics</li>
    <li>Recent activity</li>
</ul>

<h3>Reviewing an Incident</h3>
<ol>
    <li>Click on an incident from your pending list</li>
    <li>Review all details provided by the worker</li>
    <li>Check attached documents and photos</li>
    <li>Verify the accuracy of information</li>
</ol>

<h3>Adding Your Comments</h3>
<p>Provide your supervisor assessment:</p>
<ul>
    <li>Confirm or correct incident details</li>
    <li>Add context about workplace conditions</li>
    <li>Note any immediate actions taken</li>
    <li>Identify safety concerns</li>
</ul>

<h3>Escalating to HR</h3>
<p>When to escalate:</p>
<ul>
    <li>Serious injuries requiring time off work</li>
    <li>Incidents requiring medical treatment</li>
    <li>Cases needing HR intervention</li>
    <li>Statutory reporting requirements</li>
</ul>

<p><strong>How to escalate:</strong></p>
<ol>
    <li>Complete your supervisor report</li>
    <li>Click "Submit to HR"</li>
    <li>The case is automatically routed to the appropriate HR officer</li>
    <li>You'll receive updates on the case progress</li>
</ol>

<div class="alert alert-info mt-4">
    <i class="fa-solid fa-clock me-2"></i>
    <strong>Important:</strong> Please review incidents within 48 hours of submission to ensure timely processing.
</div>
HTML;
    }

    private function getHRReviewContent()
    {
        return <<<'HTML'
<h2>HR Review Process</h2>
<p>HR officers review escalated cases and make final decisions on incident management.</p>

<h3>Case Assignment</h3>
<p>Cases are automatically assigned to HR officers based on:</p>
<ul>
    <li>Facility type (district, provincial, central)</li>
    <li>Current workload (load balancing)</li>
    <li>Geographic responsibility</li>
</ul>

<h3>Your HR Dashboard</h3>
<p>View and manage:</p>
<ul>
    <li><strong>Pending Review:</strong> Cases awaiting your decision</li>
    <li><strong>In Workflow:</strong> Active cases you're managing</li>
    <li><strong>Resolved Cases:</strong> Completed incidents</li>
    <li><strong>Statistics:</strong> Your caseload and metrics</li>
</ul>

<h3>Reviewing a Case</h3>
<ol>
    <li>Click on a case from your pending list</li>
    <li>Review worker's report and supervisor's comments</li>
    <li>Check all uploaded documents</li>
    <li>Verify witness statements (if any)</li>
    <li>Assess compliance with HSB regulations</li>
</ol>

<h3>Requesting Additional Documents</h3>
<p>If more information is needed:</p>
<ul>
    <li>Click "Request Documents"</li>
    <li>Specify what's required</li>
    <li>Worker receives notification automatically</li>
    <li>You're notified when documents are uploaded</li>
</ul>

<h3>Making a Decision</h3>
<p><strong>Approve the case:</strong></p>
<ul>
    <li>Determine if leave is required</li>
    <li>Specify leave duration (based on medical advice)</li>
    <li>Add HR recommendations</li>
    <li>Submit decision</li>
</ul>

<p><strong>Reject the case:</strong></p>
<ul>
    <li>Provide clear reasons for rejection</li>
    <li>Suggest corrective actions if applicable</li>
    <li>Worker is notified immediately</li>
</ul>

<h3>Leave Management</h3>
<p>For approved cases requiring leave:</p>
<ul>
    <li>System tracks leave duration</li>
    <li>Milestones at 90 days and 180 days</li>
    <li>Automatic notifications sent</li>
    <li>Return-to-work monitoring</li>
</ul>

<div class="alert alert-warning mt-4">
    <i class="fa-solid fa-exclamation-triangle me-2"></i>
    <strong>Compliance:</strong> Ensure all decisions comply with HSB 3/2017 and labor regulations.
</div>
HTML;
    }

    private function getDocumentManagementContent()
    {
        return <<<'HTML'
<h2>Document Management</h2>
<p>Learn how to upload, manage, and review documents in the system.</p>

<h3>Supported Document Types</h3>
<p>The system accepts:</p>
<ul>
    <li><strong>Medical Reports:</strong> Doctor's assessments, X-rays, medical certificates</li>
    <li><strong>Witness Statements:</strong> Written testimonies from witnesses</li>
    <li><strong>Photos:</strong> Images of incident scene or injuries</li>
    <li><strong>Forms:</strong> Completed OHS forms and checklists</li>
    <li><strong>Other:</strong> Any relevant supporting documentation</li>
</ul>

<h3>File Requirements</h3>
<ul>
    <li><strong>Formats:</strong> PDF, JPG, PNG, DOC, DOCX</li>
    <li><strong>Size limit:</strong> 10MB per file</li>
    <li><strong>Quality:</strong> Ensure documents are clear and readable</li>
</ul>

<h3>How to Upload Documents</h3>
<p><strong>During incident reporting:</strong></p>
<ol>
    <li>Fill out the incident form</li>
    <li>Scroll to "Supporting Documents" section</li>
    <li>Click "Choose Files" or drag-and-drop</li>
    <li>Select document type from dropdown</li>
    <li>Add description (optional)</li>
    <li>Click "Upload"</li>
</ol>

<p><strong>After incident submission:</strong></p>
<ol>
    <li>Go to your incident tracking page</li>
    <li>Click "Upload Additional Documents"</li>
    <li>Follow the same upload process</li>
    <li>HR is notified automatically</li>
</ol>

<h3>Document Notifications</h3>
<p>When you upload documents:</p>
<ul>
    <li>Assigned HR officer receives email notification</li>
    <li>SMS alert sent if phone number is registered</li>
    <li>Document status shown as "Pending Review"</li>
    <li>You're notified once HR reviews the document</li>
</ul>

<h3>Viewing Documents</h3>
<p>To view uploaded documents:</p>
<ol>
    <li>Open the incident details page</li>
    <li>Scroll to "Attachments" section</li>
    <li>Click on document name to view/download</li>
    <li>Document history shows upload date and uploader</li>
</ol>

<div class="alert alert-info mt-4">
    <i class="fa-solid fa-shield me-2"></i>
    <strong>Privacy:</strong> All documents are securely stored and only accessible to authorized users.
</div>
HTML;
    }

    private function getNotificationsContent()
    {
        return <<<'HTML'
<h2>Notifications System</h2>
<p>Stay informed about incident updates through email and SMS notifications.</p>

<h3>Notification Types</h3>

<h4>1. Worker Notifications</h4>
<ul>
    <li>Incident submission confirmation</li>
    <li>Supervisor review completed</li>
    <li>Case escalated to HR</li>
    <li>HR decision (approved/rejected)</li>
    <li>Document requests</li>
    <li>Leave milestones (90-day, 180-day)</li>
</ul>

<h4>2. Supervisor Notifications</h4>
<ul>
    <li>New incident reported</li>
    <li>Witness statement submitted</li>
    <li>HR decision on escalated case</li>
    <li>Compliance alerts</li>
</ul>

<h4>3. HR Notifications</h4>
<ul>
    <li>Case assigned to you</li>
    <li>Documents uploaded by worker</li>
    <li>Witness statements received</li>
    <li>Case milestone approaching</li>
</ul>

<h3>Notification Channels</h3>

<p><strong>Email Notifications:</strong></p>
<ul>
    <li>Sent to your registered email address</li>
    <li>Contains detailed information</li>
    <li>Includes direct links to relevant pages</li>
    <li>Check your spam folder if not received</li>
</ul>

<p><strong>SMS Notifications:</strong></p>
<ul>
    <li>Sent to registered mobile number</li>
    <li>Brief alert with reference number</li>
    <li>Used for urgent updates</li>
    <li>Requires valid phone number in profile</li>
</ul>

<p><strong>In-App Notifications:</strong></p>
<ul>
    <li>Bell icon in top navigation</li>
    <li>Shows unread notification count</li>
    <li>Click to view notification details</li>
    <li>Mark as read when reviewed</li>
</ul>

<h3>Managing Your Notifications</h3>
<ol>
    <li>Keep your contact details updated</li>
    <li>Ensure email address is correct</li>
    <li>Add mobile number for SMS alerts</li>
    <li>Check notifications regularly</li>
</ol>

<div class="alert alert-success mt-4">
    <i class="fa-solid fa-mobile me-2"></i>
    <strong>Tip:</strong> Add injury.hsc.org.zw to your safe senders list to ensure you receive all notifications.
</div>
HTML;
    }

    private function getSystemConfigContent()
    {
        return <<<'HTML'
<h2>System Configuration</h2>
<p>Super Admin guide to configuring and managing the system.</p>

<h3>User Management</h3>
<p><strong>Approving New Users:</strong></p>
<ol>
    <li>Navigate to "All Users" in dashboard</li>
    <li>Review pending user registrations</li>
    <li>Verify user details and role request</li>
    <li>Approve or reject with comments</li>
    <li>User receives notification of decision</li>
</ol>

<p><strong>Managing User Roles:</strong></p>
<ul>
    <li>Access "Access Control" under System Configuration</li>
    <li>View all roles and permissions</li>
    <li>Assign/revoke permissions as needed</li>
    <li>Create custom roles if required</li>
</ul>

<h3>Facility Management</h3>
<p>Configure health facilities:</p>
<ul>
    <li><strong>Add New Facility:</strong> Click "Add Facility" button</li>
    <li><strong>Edit Facility:</strong> Update name, type, contact details</li>
    <li><strong>Assign Districts:</strong> Link facilities to districts</li>
    <li><strong>Set Facility Type:</strong> District, Provincial, Central Hospital</li>
</ul>

<h3>Department Configuration</h3>
<ul>
    <li>Add or modify department names</li>
    <li>Assign departments to facilities</li>
    <li>Track incidents by department</li>
</ul>

<h3>Dashboard Routing</h3>
<p>Control where users land after login:</p>
<ul>
    <li>Map roles to specific dashboards</li>
    <li>Set default dashboard per role</li>
    <li>Configure redirection rules</li>
</ul>

<h3>Threshold Variables</h3>
<p>Configure system thresholds:</p>
<ul>
    <li><strong>Leave Limit:</strong> Maximum leave days (per HSB 3/2017)</li>
    <li><strong>Milestone Alerts:</strong> 90-day and 180-day notifications</li>
    <li><strong>Auto-escalation:</strong> Timeouts for supervisor review</li>
</ul>

<h3>Provinces & Districts</h3>
<ul>
    <li>Manage geographic divisions</li>
    <li>Add new districts</li>
    <li>Link facilities to locations</li>
</ul>

<div class="alert alert-danger mt-4">
    <i class="fa-solid fa-exclamation-circle me-2"></i>
    <strong>Caution:</strong> Configuration changes affect all users. Test in a non-production environment first.
</div>
HTML;
    }

    private function getDatabaseBackupContent()
    {
        return <<<'HTML'
<h2>Database Backup Guide</h2>
<p>Learn how to create, manage, and restore database backups.</p>

<h3>Why Backup?</h3>
<p>Regular backups protect against:</p>
<ul>
    <li>Data loss from hardware failure</li>
    <li>Accidental data deletion</li>
    <li>System crashes</li>
    <li>Security incidents</li>
    <li>Software bugs</li>
</ul>

<h3>Creating a Manual Backup</h3>
<ol>
    <li>Navigate to System Configuration</li>
    <li>Click "Database Backup"</li>
    <li>Click "Create Backup Now" button</li>
    <li>Wait for backup to complete (1-5 minutes)</li>
    <li>Success message appears with backup filename</li>
</ol>

<h3>Viewing Backup History</h3>
<p>The backup page shows:</p>
<ul>
    <li><strong>Filename:</strong> backup-YYYY-MM-DD-HHMMSS.sql</li>
    <li><strong>Date Created:</strong> When backup was made</li>
    <li><strong>File Size:</strong> Size of backup file</li>
    <li><strong>Actions:</strong> Download or Delete buttons</li>
</ul>

<h3>Downloading a Backup</h3>
<ol>
    <li>Find the backup you want to download</li>
    <li>Click the "Download" button</li>
    <li>Backup file will download to your computer</li>
    <li>Store in a secure location</li>
</ol>

<h3>Deleting Old Backups</h3>
<ol>
    <li>Review backup list</li>
    <li>Identify old or unnecessary backups</li>
    <li>Click "Delete" button</li>
    <li>Confirm deletion</li>
</ol>

<h3>Best Practices</h3>
<ul>
    <li><strong>Regular Schedule:</strong> Create backups weekly (minimum)</li>
    <li><strong>Before Updates:</strong> Always backup before system changes</li>
    <li><strong>Store Offsite:</strong> Download and save to external storage</li>
    <li><strong>Test Restores:</strong> Verify backups can be restored</li>
    <li><strong>Retention Policy:</strong> Keep last 30 days of backups</li>
</ul>

<h3>Restoring from Backup</h3>
<p><strong>Important:</strong> Database restoration requires technical expertise.</p>
<ol>
    <li>Contact your database administrator</li>
    <li>Provide the backup file</li>
    <li>Specify restoration point</li>
    <li>System will be briefly unavailable during restore</li>
</ol>

<div class="alert alert-danger mt-4">
    <i class="fa-solid fa-exclamation-triangle me-2"></i>
    <strong>Critical:</strong> Never delete all backups. Always maintain at least one recent backup.
</div>
HTML;
    }

    private function getTroubleshootingContent()
    {
        return <<<'HTML'
<h2>Troubleshooting Common Issues</h2>
<p>Solutions to frequently encountered problems.</p>

<h3>Login Issues</h3>

<p><strong>Problem: "Invalid credentials" error</strong></p>
<ul>
    <li>Verify email and password are correct</li>
    <li>Check for CAPS LOCK</li>
    <li>Try password reset if forgotten</li>
    <li>Ensure account has been approved</li>
</ul>

<p><strong>Problem: "Account pending approval"</strong></p>
<ul>
    <li>Wait for admin approval (typically 24-48 hours)</li>
    <li>Check email for approval notification</li>
    <li>Contact system administrator if urgent</li>
</ul>

<h3>Incident Reporting Issues</h3>

<p><strong>Problem: Form fields are missing or not visible</strong></p>
<ul>
    <li>Refresh the page (F5 or Ctrl+R)</li>
    <li>Clear browser cache</li>
    <li>Try a different browser</li>
    <li>Check internet connection</li>
</ul>

<p><strong>Problem: Cannot upload documents</strong></p>
<ul>
    <li>Check file size (must be under 10MB)</li>
    <li>Ensure file format is supported (PDF, JPG, PNG, DOC)</li>
    <li>Try compressing large images</li>
    <li>Check internet connection stability</li>
</ul>

<h3>Notification Issues</h3>

<p><strong>Problem: Not receiving email notifications</strong></p>
<ul>
    <li>Check spam/junk folder</li>
    <li>Verify email address in profile is correct</li>
    <li>Add @hsc.org.zw to safe senders</li>
    <li>Check email inbox is not full</li>
</ul>

<p><strong>Problem: Not receiving SMS notifications</strong></p>
<ul>
    <li>Verify mobile number in profile</li>
    <li>Ensure number includes country code</li>
    <li>Check mobile network signal</li>
    <li>SMS may be delayed - wait 5-10 minutes</li>
</ul>

<h3>Dashboard Loading Issues</h3>

<p><strong>Problem: Dashboard is slow or not loading</strong></p>
<ul>
    <li>Check internet connection speed</li>
    <li>Clear browser cache and cookies</li>
    <li>Close unnecessary browser tabs</li>
    <li>Try accessing during off-peak hours</li>
</ul>

<h3>Document Viewing Issues</h3>

<p><strong>Problem: Cannot open uploaded documents</strong></p>
<ul>
    <li>Ensure you have permission to view the document</li>
    <li>Try downloading instead of viewing in browser</li>
    <li>Check PDF reader is installed</li>
    <li>File may be corrupted - request re-upload</li>
</ul>

<h3>Getting Further Help</h3>
<p>If problems persist:</p>
<ol>
    <li>Click "IT Support" in your dashboard</li>
    <li>Submit a support ticket</li>
    <li>Describe the problem in detail</li>
    <li>Include screenshots if possible</li>
    <li>IT team will respond within 24 hours</li>
</ol>

<div class="alert alert-info mt-4">
    <i class="fa-solid fa-life-ring me-2"></i>
    <strong>Need urgent help?</strong> Contact the IT Support desk at support@hsc.org.zw or call ext. 123.
</div>
HTML;
    }

    private function getFAQContent()
    {
        return <<<'HTML'
<h2>Frequently Asked Questions</h2>

<h3>General Questions</h3>

<p><strong>Q: Who can use this system?</strong></p>
<p>A: All HSC employees can use the system. You need to register with your HSC email address and wait for approval.</p>

<p><strong>Q: Is the system available 24/7?</strong></p>
<p>A: Yes, the system is accessible 24 hours a day, 7 days a week. Scheduled maintenance is announced in advance.</p>

<p><strong>Q: Can I access the system from my mobile phone?</strong></p>
<p>A: Yes, the system is mobile-responsive and works on smartphones and tablets.</p>

<h3>Incident Reporting</h3>

<p><strong>Q: How soon after an incident should I report it?</strong></p>
<p>A: Report incidents as soon as possible, ideally within 24 hours. Prompt reporting ensures better documentation and faster response.</p>

<p><strong>Q: Can I report an incident on behalf of someone else?</strong></p>
<p>A: Yes, supervisors can report incidents for workers who are unable to do so themselves. Include details about why you're reporting on their behalf.</p>

<p><strong>Q: What if I don't have all the information at the time of reporting?</strong></p>
<p>A: Submit the report with available information. You can upload additional documents later or provide updates when requested.</p>

<p><strong>Q: Can I edit an incident report after submitting it?</strong></p>
<p>A: No, you cannot edit a submitted report. However, you can add clarifying comments or upload additional documents.</p>

<h3>Document Management</h3>

<p><strong>Q: What documents should I upload?</strong></p>
<p>A: Upload any relevant documentation: medical reports, doctor's notes, photos of the incident scene, witness statements, or completed forms.</p>

<p><strong>Q: How long are documents stored in the system?</strong></p>
<p>A: Documents are stored securely for the duration required by HSB regulations and labor laws (typically 7-10 years).</p>

<p><strong>Q: Who can see my uploaded documents?</strong></p>
<p>A: Only authorized users involved in your case (supervisor, assigned HR officer, super admins) can view your documents.</p>

<h3>Leave Management</h3>

<p><strong>Q: How is injury leave calculated?</strong></p>
<p>A: Leave is based on medical recommendations and HSB 3/2017 guidelines. HR reviews medical reports and approves appropriate leave duration.</p>

<p><strong>Q: What happens after 90 days of leave?</strong></p>
<p>A: You'll receive a notification. Medical re-assessment may be required. The system tracks this milestone automatically.</p>

<p><strong>Q: Can leave be extended beyond initial approval?</strong></p>
<p>A: Yes, with additional medical documentation. Submit new medical reports through the system for HR review.</p>

<h3>Technical Questions</h3>

<p><strong>Q: Why is my account pending approval?</strong></p>
<p>A: All new registrations require super admin approval for security. This typically takes 24-48 hours. You'll receive an email once approved.</p>

<p><strong>Q: How do I reset my password?</strong></p>
<p>A: Click "Forgot Password" on the login page. Enter your email address and follow the reset instructions sent to you.</p>

<p><strong>Q: What browsers are supported?</strong></p>
<p>A: Chrome, Firefox, Safari, and Microsoft Edge (latest versions). Internet Explorer is not supported.</p>

<p><strong>Q: Is my data secure?</strong></p>
<p>A: Yes, the system uses industry-standard encryption, secure authentication, and regular backups. All access is logged for audit purposes.</p>

<h3>Still Have Questions?</h3>
<p>Contact IT Support:</p>
<ul>
    <li>Email: support@hsc.org.zw</li>
    <li>Phone: Extension 123</li>
    <li>Submit a ticket through the IT Support section</li>
</ul>
HTML;
    }
}
