# Wellness Enhancements - Implementation Guide
## Adding 4 Critical Dimensions to HSC-OHS

**Date:** October 2026  
**Version:** 1.0  

---

## Executive Summary

Implementation plan for adding 4 critical wellness dimensions:

1. **Emotional Support Module** - Psychological safety & counselling integration
2. **Professional Development Tracking** - CPD & skills development
3. **Shift Fatigue Management** - Rest period & fatigue risk assessment
4. **Financial Hardship Support** - Emergency allowances & financial assistance

---

## 1. EMOTIONAL SUPPORT MODULE

### Overview
Structured tracking of psychological support, post-incident debriefing, mental health assessments.

### Who Sees It?
- **Workers**: View scheduled counselling, confirm attendance
- **Supervisors**: Monitor team member counselling status
- **HR Users**: Track completion, identify at-risk workers
- **Counsellors**: Full access to schedule & record sessions
- **DMO**: Monitor high-risk cases, escalate if needed

### How It Works

**Workflow: Incident → Counselling Flag**
1. Work-related injury/trauma incident submitted
2. System automatically flags for counselling assessment IF:
   - Critical incident (fatality, assault, near-miss trauma)
   - Supervisor indicates psychological impact
   - Worker marks as distressing
3. HR receives notification: "Counselling assessment required"
4. HR schedules initial session
5. Counsellor conducts assessment & rates risk level (LOW/MODERATE/HIGH/CRITICAL)
6. If HIGH/CRITICAL: Escalate to DMO & supervisor

**Workflow: Post-Critical Incident Debriefing**
1. Severe incident occurs
2. HR triggers "Debriefing" within 24-72 hours
3. Facilitator (CISM trained) leads structured debriefing
4. Participants: worker, supervisor, peers
5. Checklist followed: facts → reactions → normalization → coping strategies → resources
6. Summary recorded, worker feedback collected
7. Follow-up counselling scheduled if needed

**Database Tables:**
- `counselling_sessions` - Individual counselling records
- `post_incident_debriefings` - Group debriefing records

**Key Fields:**
- session_type (INITIAL_ASSESSMENT, ONGOING_SUPPORT, POST_CRITICAL_INCIDENT)
- risk_level (LOW, MODERATE, HIGH, CRITICAL)
- follow_up_required (boolean)
- effectiveness_rating (1-5 scale)

**Notifications:**
- Worker SMS: "Counselling appointment {date} at {time}. Reply CONFIRM"
- Supervisor SMS: "{worker} flagged HIGH RISK - immediate support needed"
- HR Email: "High-risk case {ref} - DMO escalation recommended"

**Reports:**
- Counselling completion rates by department
- High-risk workers requiring intervention
- Debriefing effectiveness trends
- Follow-up compliance

---

## 2. PROFESSIONAL DEVELOPMENT TRACKING

### Overview
Captures CPD, training, certifications, competency development for each worker.

### Who Sees It?
- **Workers**: View own development records, submit certificates
- **Supervisors**: Monitor team member CPD progress, approve requests
- **HR Users**: Verify certificates, track compliance, flag expiring certifications
- **Finance**: Track training costs & reimbursements
- **Compliance Officer**: Set requirements, generate compliance reports

### How It Works

**Workflow: Training Request → Completion → CPD Credit**
1. Worker identifies training need
2. Logs into system: "Request Professional Development"
3. Fills form:
   - Training title & provider
   - Dates & cost
   - Relevance to role
   - Learning objectives
4. Submits for supervisor approval
5. Supervisor approves & comments
6. HR reviews for budget approval
7. Approved → Worker attends training
8. Upon completion:
   - Worker enters completion date
   - Uploads certificate (PDF)
   - Writes reflection notes
9. HR verifies certificate:
   - Checks authenticity
   - Validates CPD credits
   - Marks in system
10. System updates annual CPD progress (% of requirement met)

**Workflow: CPD Compliance Monitoring**
1. HR sets annual requirement (e.g., 40 credits for nurses)
2. System tracks current totals for all workers
3. Quarterly report:
   - Green: On track (≥75% of requirement)
   - Amber: At risk (50-74%)
   - Red: Non-compliant (<50%)
4. Automated reminders sent to at-risk workers
5. System generates compliance certificate for bodies

**Workflow: Certificate Expiry Management**
1. Mandatory certifications registered (First Aid expires 2 years)
2. System monitors expiry dates
3. 90 days before expiry: Multiple notifications sent
4. Worker renews & uploads new certificate
5. System updates expiry date
6. If expires without renewal: Worker flagged as non-compliant

**Database Tables:**
- `professional_development_records` - Training & certification records
- `cpd_requirements` - Annual requirements by role/department

**Key Fields:**
- training_type (COURSE, CERTIFICATION, WORKSHOP, CONFERENCE)
- status (PLANNED, IN_PROGRESS, COMPLETED, CANCELLED)
- certificate_expiry_date
- cpd_credits_awarded
- supervisor_approved (boolean)
- hr_verified (boolean)

**Notifications:**
- Worker: "CPD requirement: {X} credits, you have {Y}. Enroll in courses now"
- Supervisor: "Team member {cert} expiring {date}. Renewal required"
- HR: "{X}% of workforce non-compliant with CPD requirements"

**Reports:**
- CPD compliance rate by department/designation
- Expiring certifications
- Training cost analysis
- CPD credit utilization trends

---

## 3. SHIFT FATIGUE MANAGEMENT

### Overview
Tracks shifts, identifies fatigue risk, ensures adequate rest per ILO standards.

### Who Sees It?
- **Workers**: View shift schedule with fatigue warnings
- **Supervisors**: Monitor team fatigue, adjust shifts, approve rest requests
- **Rostering Officer**: Plan schedules, ensure compliance, swap shifts
- **HR Users**: Monitor compliance, escalate concerns
- **Safety Officer**: Investigate fatigue-related incidents
- **DMO**: Monitor health impact, recommend interventions

### How It Works

**Workflow: Schedule Planning with Fatigue Risk Check**
1. Rostering Officer plans shifts for next month
2. For each assignment, system checks:
   - Rest period since last shift
   - Consecutive shifts worked
   - Total hours this week
3. Fatigue risk calculated:
   - LOW: 11+ hours rest, <4 consecutive shifts
   - MODERATE: 8-10 hours rest, 4-6 consecutive
   - HIGH: <8 hours rest OR >6 consecutive
   - CRITICAL: <5 hours rest OR >7 consecutive
4. If HIGH/CRITICAL: Warning flag shown
5. Rostering Officer can:
   - Adjust times
   - Swap with colleague
   - Add recovery day
   - Override with documented reason
6. Worker views schedule:
   - Green shift: Safe
   - Amber shift: Moderate risk - take care
   - Red shift: High risk - request reassignment if possible

**Workflow: Real-Time Fatigue Monitoring**
1. Daily job runs at 6 AM
2. For each worker scheduled today:
   - Check rest period from yesterday
   - Count consecutive shifts
   - Calculate fatigue risk
3. If HIGH risk:
   - SMS to worker: "Shift fatigue alert: Get 8+ hours sleep before shift"
   - SMS to supervisor: "{worker} HIGH FATIGUE RISK today. Monitor closely"
4. If CRITICAL risk:
   - Immediate notifications to worker, supervisor, HR, DMO
   - Recommendation: Consider reassignment

**Workflow: Fatigue-Related Incident Reporting**
1. Safety incident occurs (error, near-miss, injury)
2. Supervisor reports & checks "Fatigue factor"
3. System auto-populates:
   - Shift schedule that day
   - Consecutive shifts worked
   - Hours this week
   - Rest period
4. If fatigue high OR excessive hours:
   - Safety team notified
   - DMO consulted
   - Supervisor gets shift adjustment recommendations
   - Quarterly trend report compiled

**Workflow: Worker Requests Rest**
1. Worker sees excessive consecutive shifts
2. System suggests: "Rest day recommended after 6 consecutive shifts"
3. Worker can:
   - Request specific day off
   - Swap shift with colleague
   - Request early finish
4. Supervisor reviews with context
5. If approved: Schedule updated, fatigue risk recalculated

**Database Tables:**
- `shift_assignments` - Daily shift records
- `fatigue_risk_policies` - ILO compliance rules by facility/role
- `fatigue_incidents` - Fatigue-related incidents

**Key Fields:**
- shift_type (DAY, EVENING, NIGHT, ON_CALL, CUSTOM)
- shift_duration_hours
- rest_period_hours (calculated)
- is_consecutive_shift (calculated)
- fatigue_risk_level (LOW, MODERATE, HIGH, CRITICAL)
- meets_rest_requirement (boolean)
- rest_violation (boolean)

**ILO Standards Enforced:**
- Minimum 11 hours rest between shifts
- Maximum 6 consecutive shifts
- Minimum 2 days off per week
- Maximum 48 hours per week
- Maximum 88 hours per fortnight

**Notifications:**
- Worker: "Shift fatigue alert: 6 consecutive shifts completed. Rest recommended"
- Supervisor: "CRITICAL fatigue risk: {worker} scheduled after insufficient rest"
- Safety Officer: "Fatigue-related incident detected: High-risk shift pattern identified"

**Reports:**
- Shift compliance rate
- Fatigue-related incident trends
- ILO violation tracking
- Worker fatigue exposure summary

---

## 4. FINANCIAL HARDSHIP SUPPORT

### Overview
Tracks emergency assistance, hardship allowances, expedited compensation.

### Who Sees It?
- **Workers**: Submit requests, track approval/payment status
- **Supervisors**: Review requests, provide recommendations
- **HR Users**: Assess eligibility, approve amounts, track compliance
- **Finance**: Process payments, verify bank details
- **Compliance Officer**: Monitor timely payment, flag delays
- **Super Admin**: System configuration, policy override

### How It Works

**Workflow: Emergency Hardship Request**
1. Worker experiences financial strain (lost income, medical costs)
2. Logs into system: "Request Financial Assistance"
3. Form fields:
   - Request type (Emergency Hardship, Advance Compensation, Medical, Transport, Other)
   - Amount needed
   - Justification text
   - Supporting documents (upload receipts, bills, pay slips)
   - Bank details for transfer
4. Submits with documents
5. Supervisor notified: "{worker} requesting {amount} for {reason}"
6. Supervisor reviews & recommends (within 24 hours)
7. HR assesses:
   - Verifies justification
   - Checks reasonableness
   - Determines eligible amount
8. HR approves & documents amount
9. Finance processes:
   - Verifies bank details
   - Transfers funds
   - Sends confirmation
10. Worker receives SMS: "Financial assistance {amount} transferred to account"
11. System marked as PAID
12. Worker can view receipt

**Workflow: Automatic Hardship Detection**
1. Daily background job monitors long-term cases
2. For incidents on sick leave > 30 days:
   - Flag for potential hardship
   - Note medical certificate = wage reduction
3. For incidents > 90 days:
   - HIGH PRIORITY flag
   - Imminent further wage reduction
4. HR proactively contacts worker: "If experiencing hardship, assistance available"

**Workflow: Compensation Payment Tracking**
1. Incident determines compensation eligibility
2. System tracks:
   - NSSA claim submission date
   - Expected compensation amount
   - Payment date expectation
3. On expected date:
   - System checks if payment received
   - If YES: Log payment
   - If NO: Create alert
4. Alert escalation:
   - Day 7 overdue: Red flag + HR notification
   - Day 30 overdue: Escalate to Compliance Officer
5. Monthly report: Payment status, delays, outstanding amounts

**Workflow: Timely Allowance Management**
1. Worker approved for ongoing allowances (transport, accommodation)
2. HR sets frequency (weekly/monthly)
3. System auto-creates payment approvals
4. Finance processes each payment
5. Worker receives allowance
6. Audit logged with payment details
7. Monthly audit: Verify all approved allowances processed

**Database Tables:**
- `financial_assistance_requests` - Emergency assistance & advance compensation
- `compensation_payment_tracking` - NSSA claim & payment monitoring
- `allowance_audit_log` - Allowance payment history

**Key Fields - Assistance Requests:**
- request_type (EMERGENCY_HARDSHIP, ADVANCE_COMPENSATION, MEDICAL_EXPENSE)
- request_status (SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED, PAID)
- requested_amount
- supporting_documents (JSON array of file paths)
- supervisor_recommendation & approval
- hr_approval_notes & amount
- payment_status & reference
- worker_acknowledgement

**Key Fields - Compensation Tracking:**
- nssa_claim_reference
- total_compensation_owed
- amount_received_to_date
- expected_payment_date
- financial_hardship_identified (boolean)
- expedited_support_granted (boolean)

**Notifications:**
- Worker: "Hardship assistance request received. Supervisor review within 24 hours"
- HR: "URGENT: Hardship request {amount} from {worker} - assess within 48 hours"
- Finance: "Process emergency assistance {amount} for {worker} - transfer to account {XXXX}"
- Worker: "Compensation payment {ref} overdue. Escalated to Compliance Officer"

**Reports:**
- Average processing time for assistance requests
- Payment compliance rate (% paid on time)
- Outstanding compensation & allowances
- Financial hardship statistics
- Expedited support audit trail

---

## SYSTEM INTEGRATION POINTS

### Notification Channels
- SMS via Econet for urgent alerts
- Email for documentation & approval requests
- In-app notifications on dashboard
- System alerts for escalations

### User Roles & Permissions
- New permissions for each module
- Field-level restrictions (workers see own data only)
- Approval chain enforcement (supervisor → HR → Finance)
- Audit trail for all actions

### Incident Lifecycle Integration
```
Incident Submitted
  ↓
Counselling Flagged (Emotional Support)
Shift Fatigue Risk Assessed (Shift Fatigue)
CPD Requirements Checked (Professional Development)
  ↓
HR Review
Financial Hardship Check (Financial Support)
  ↓
Ongoing Monitoring
Leave Management ↔ Financial Tracking
Return-to-Work ↔ Shift Fatigue Monitoring
  ↓
Resolution
```

---

## SUCCESS METRICS

### Emotional Support
- 100% of critical incidents get debriefing within 72 hours
- Counselling compliance ≥ 80%
- 0 high-risk workers without follow-up

### Professional Development
- CPD compliance ≥ 90% annually
- 0 expired mandatory certifications
- 100% of staff have development plan

### Shift Fatigue
- 0 ILO violations (within 30 days)
- Fatigue incidents reduced 50%
- Violations identified & corrected immediately

### Financial Hardship
- Emergency assistance within 48 hours
- 100% compensation paid on time
- 0 workers in hardship without offer

---

## NEXT STEPS

1. **Review & Approval**: Stakeholder review
2. **Team Assignment**: Assign developers to each module
3. **Database Kickoff**: Migration & model development
4. **Weekly Syncs**: Track progress & resolve blockers

