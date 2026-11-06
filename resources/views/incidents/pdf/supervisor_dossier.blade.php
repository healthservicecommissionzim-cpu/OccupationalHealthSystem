<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supervisor Case Dossier - {{ $incident->reference_number }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1a1a1a;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #1D4533;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18pt;
            color: #1D4533;
            font-weight: 700;
            margin: 8px 0 4px;
            letter-spacing: -0.3px;
        }
        
        .header p {
            font-size: 9pt;
            color: #666;
        }
        
        .meta-info {
            background: #f8fafb;
            border: 1px solid #e1e7ee;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        
        .meta-row {
            display: table-row;
        }
        
        .meta-label {
            display: table-cell;
            font-weight: 700;
            color: #1D4533;
            font-size: 9pt;
            padding: 4px 15px 4px 0;
            width: 30%;
        }
        
        .meta-value {
            display: table-cell;
            color: #333;
            font-size: 9pt;
            padding: 4px 0;
        }
        
        .section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: 700;
            color: #1D4533;
            border-bottom: 2px solid #1D4533;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        
        .subsection-title {
            font-size: 10pt;
            font-weight: 700;
            color: #333;
            margin: 12px 0 6px;
        }
        
        .content-box {
            background: #ffffff;
            border: 1px solid #e1e7ee;
            border-radius: 4px;
            padding: 12px;
            margin: 8px 0;
        }
        
        .field {
            margin-bottom: 10px;
        }
        
        .field-label {
            font-weight: 700;
            color: #555;
            font-size: 9pt;
            margin-bottom: 3px;
        }
        
        .field-value {
            color: #333;
            font-size: 9.5pt;
            line-height: 1.6;
        }
        
        .witness-item {
            background: #f8fafb;
            border-left: 3px solid #1D4533;
            padding: 10px 12px;
            margin: 8px 0;
        }
        
        .witness-name {
            font-weight: 700;
            color: #1D4533;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        
        .witness-statement {
            color: #444;
            font-size: 9.5pt;
            line-height: 1.6;
            margin-top: 6px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .badge-success {
            background: #e9f8f3;
            color: #067c58;
        }
        
        .badge-pending {
            background: #fff8e7;
            color: #c67a00;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #999;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        table th {
            background: #f8fafb;
            font-weight: 700;
            font-size: 9pt;
            text-align: left;
            padding: 8px;
            border-bottom: 2px solid #e1e7ee;
        }
        
        table td {
            padding: 8px;
            border-bottom: 1px solid #e1e7ee;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/hsc-logo.png') }}" alt="HSC Logo">
        <h1>SUPERVISOR CASE DOSSIER</h1>
        <p>Health Service Commission of Zimbabwe<br>Occupational Health & Safety Division</p>
    </div>
    
    <!-- Case Metadata -->
    <div class="meta-info">
        <div class="meta-row">
            <div class="meta-label">Reference Number:</div>
            <div class="meta-value">{{ $incident->reference_number }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Generated Date:</div>
            <div class="meta-value">{{ now()->format('d F Y H:i') }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Generated By:</div>
            <div class="meta-value">{{ auth()->user()->name }} (Supervisor)</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Case Status:</div>
            <div class="meta-value">{{ strtoupper(str_replace('_', ' ', $incident->status)) }}</div>
        </div>
    </div>
    
    <!-- Section 1: Incident Information -->
    <div class="section">
        <div class="section-title">1. INCIDENT INFORMATION</div>
        
        <div class="content-box">
            <div class="field">
                <div class="field-label">Employee Name</div>
                <div class="field-value">{{ $incident->first_name }} {{ $incident->surname }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Employee Number</div>
                <div class="field-value">{{ $incident->employee_number }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Facility</div>
                <div class="field-value">{{ $incident->facility->name ?? 'N/A' }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Department</div>
                <div class="field-value">{{ $incident->department->name ?? 'N/A' }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Incident Date</div>
                <div class="field-value">{{ $incident->incident_date->format('d F Y') }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Incident Type</div>
                <div class="field-value">{{ $incident->incidentType->name ?? 'N/A' }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Submitted Date</div>
                <div class="field-value">{{ $incident->created_at->format('d F Y H:i') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Section 2: Incident Description -->
    <div class="section">
        <div class="section-title">2. INCIDENT DESCRIPTION</div>
        
        <div class="content-box">
            <div class="field-value">
                {{ $incident->incident_description ?? 'No description provided' }}
            </div>
        </div>
    </div>
    
    <!-- Section 3: Supervisor Verification -->
    <div class="section">
        <div class="section-title">3. SUPERVISOR VERIFICATION</div>
        
        <div class="content-box">
            @if($incident->supervisor_verification_date)
                <div class="field">
                    <div class="field-label">Verification Status</div>
                    <div class="field-value">
                        <span class="badge badge-success">✓ VERIFIED</span>
                    </div>
                </div>
                
                <div class="field">
                    <div class="field-label">Verified By</div>
                    <div class="field-value">{{ $incident->supervisor->name ?? 'N/A' }}</div>
                </div>
                
                <div class="field">
                    <div class="field-label">Verification Date</div>
                    <div class="field-value">{{ $incident->supervisor_verification_date->format('d F Y H:i') }}</div>
                </div>
                
                @if($incident->supervisor_comments)
                <div class="field">
                    <div class="field-label">Supervisor Comments</div>
                    <div class="field-value">{{ $incident->supervisor_comments }}</div>
                </div>
                @endif
            @else
                <div class="field-value">Pending supervisor verification</div>
            @endif
        </div>
    </div>
    
    <!-- Section 4: Witness Statements -->
    <div class="section">
        <div class="section-title">4. WITNESS STATEMENTS</div>
        
        @if($witnessTokens->count() > 0)
            @foreach($witnessTokens as $witness)
                <div class="witness-item">
                    <div class="witness-name">
                        {{ $witness->witness_name }}
                        @if($witness->statement_submitted_at)
                            <span class="badge badge-success">Submitted</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </div>
                    
                    <div style="font-size: 8.5pt; color: #666; margin-top: 2px;">
                        Phone: {{ $witness->phone_number }} 
                        @if($witness->email)
                            | Email: {{ $witness->email }}
                        @endif
                    </div>
                    
                    @if($witness->statement_submitted_at)
                        <div class="witness-statement">
                            <strong>Statement:</strong><br>
                            {{ $witness->statement }}
                        </div>
                        <div style="font-size: 8pt; color: #888; margin-top: 6px;">
                            Submitted: {{ $witness->statement_submitted_at->format('d M Y H:i') }}
                        </div>
                    @else
                        <div style="font-size: 9pt; color: #999; margin-top: 4px; font-style: italic;">
                            Statement not yet submitted
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="content-box">
                <div class="field-value" style="color: #999; font-style: italic;">No witnesses recorded for this incident</div>
            </div>
        @endif
    </div>
    
    <!-- Section 5: Supervisor/HOD Report -->
    <div class="section">
        <div class="section-title">5. SUPERVISOR/HOD REPORT</div>
        
        <div class="content-box">
            @if($incident->hod_report)
                <div class="field-value">
                    {{ $incident->hod_report }}
                </div>
                
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #e1e7ee;">
                    <div style="font-size: 8.5pt; color: #666;">
                        <strong>Submitted By:</strong> {{ $incident->submittedByUser->name ?? 'Unknown' }}<br>
                        <strong>Submission Date:</strong> {{ $incident->hod_report_submitted_at->format('d F Y H:i') }}
                    </div>
                </div>
            @else
                <div class="field-value" style="color: #999; font-style: italic;">Report not yet submitted</div>
            @endif
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p><strong>CONFIDENTIAL DOCUMENT</strong> | Generated: {{ now()->format('d F Y H:i') }} | Page 1 of 1</p>
        <p style="margin-top: 3px; font-size: 7pt;">This document contains sensitive information and should be handled in accordance with HSC data protection policies.</p>
    </div>
</body>
</html>
