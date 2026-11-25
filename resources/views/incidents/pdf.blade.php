<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            position: relative;
        }
        
        /* Watermark */
        body::before {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            background-image: url('{{ asset('images/hsc-logo.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.05;
            z-index: -1;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        .header {
            background: linear-gradient(135deg, #1D4533 0%, #0f2818 100%);
            padding: 20px 25px;
            margin: -20px -20px 25px -20px;
            border-bottom: 4px solid #079b6d;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-logo {
            width: 70px;
            height: 70px;
            background: white;
            padding: 8px;
            border-radius: 50%;
            border: 3px solid #079b6d;
            flex-shrink: 0;
        }
        .header-text {
            text-align: left;
        }
        .header h1 {
            color: white;
            font-size: 24px;
            margin-bottom: 4px;
            font-weight: bold;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .subtitle {
            color: #a7f3d0;
            font-size: 12px;
            margin-bottom: 0;
            line-height: 1.3;
            font-weight: 600;
        }
        .header .tagline {
            color: #d1fae5;
            font-size: 10px;
            font-style: italic;
            margin-top: 2px;
        }
        .header-right {
            text-align: right;
        }
        .doc-id {
            background: rgba(255,255,255,0.15);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .qr-placeholder {
            width: 70px;
            height: 70px;
            background: white;
            border: 2px solid #079b6d;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666;
            text-align: center;
            padding: 5px;
        }
        .security-strip {
            background: rgba(255,255,255,0.1);
            padding: 10px 15px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #d1fae5;
            border: 1px dashed rgba(255,255,255,0.3);
        }
        .security-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .security-icon {
            color: #079b6d;
            background: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .reference-number {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid #079b6d;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #1D4533;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ref-label {
            font-size: 11px;
            color: #15803d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .ref-value {
            font-size: 18px;
            color: #1D4533;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #1D4533;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.verified { background: #dbeafe; color: #0c4a6e; }
        .status-badge.investigating { background: #fce7f3; color: #831843; }
        .status-badge.resolved { background: #dcfce7; color: #15803d; }
        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 11px;
            color: #999;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background: #f3f4f6;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #1D4533;
            border: 1px solid #d1d5db;
        }
        table td {
            padding: 8px;
            font-size: 11px;
            border: 1px solid #e5e7eb;
        }
        table tr:nth-child(even) {
            background: #f9fafb;
        }
        .doc-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .doc-status.verified { background: #dcfce7; color: #15803d; }
        .doc-status.pending { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <div class="header-left">
                    <img src="{{ public_path('images/hsc-logo.png') }}" alt="HSC Logo" class="header-logo">
                    <div class="header-text">
                        <h1>Health Service Commission</h1>
                        <p class="subtitle">Republic of Zimbabwe</p>
                        <p class="tagline">Occupational Health & Safety System · Official Incident Report</p>
                    </div>
                </div>
                <div class="header-right">
                    <div class="doc-id">
                        DOC ID: HSC-{{ date('Ymd') }}-{{ substr(md5($incident->reference_number), 0, 6) }}
                    </div>
                    <div class="qr-placeholder">
                        Verification Code:<br>
                        {{ strtoupper(substr(md5($incident->reference_number . $incident->created_at), 0, 8)) }}
                    </div>
                </div>
            </div>
            <div class="security-strip">
                <div class="security-item">
                    <span class="security-icon">🔒</span>
                    <span>Official Document</span>
                </div>
                <div class="security-item">
                    <span class="security-icon">✓</span>
                    <span>Digitally Generated: {{ now()->format('Y-m-d H:i:s') }}</span>
                </div>
                <div class="security-item">
                    <span class="security-icon">📄</span>
                    <span>HSC Authorized</span>
                </div>
                <div class="security-item">
                    <span class="security-icon">⚠</span>
                    <span>Confidential</span>
                </div>
            </div>
        </div>

        <div class="reference-number">
            <div>
                <div class="ref-label">Official Reference Number</div>
                <div class="ref-value">{{ $incident->reference_number }}</div>
            </div>
            <div style="text-align: right; font-size: 10px; color: #15803d;">
                <div>Reported: {{ $incident->created_at->format('d M Y') }}</div>
                <div style="font-size: 9px; color: #666;">Last Updated: {{ $incident->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- Injured Person Section -->
        <div class="section">
            <div class="section-title">Injured Person Information</div>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Name:</td>
                        <td>{{ $incident->first_name }} {{ $incident->surname }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Designation:</td>
                        <td>{{ $incident->designation->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Department:</td>
                        <td>{{ $incident->department->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Phone:</td>
                        <td>{{ $incident->phone_number }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Email:</td>
                        <td>{{ $incident->email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="divider"></div>

        <!-- Incident Details Section -->
        <div class="section">
            <div class="section-title">Incident Details</div>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Date:</td>
                        <td>{{ $incident->incident_date->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Time:</td>
                        <td>{{ $incident->incident_time }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Facility:</td>
                        <td>{{ $incident->facility->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">District:</td>
                        <td>{{ $incident->district->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Province:</td>
                        <td>{{ $incident->province->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Description:</td>
                        <td>{{ $incident->incident_description }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Supervisor Name:</td>
                        <td>{{ $incident->supervisor_name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Supervisor Designation:</td>
                        <td>{{ $incident->supervisorDesignation->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; color: #1D4533;">Status:</td>
                        <td>
                            @php
                                // Map technical statuses to user-friendly labels
                                $statusMap = [
                                    'PENDING_SUPERVISOR_VERIFICATION' => 'Awaiting Supervisor Verification',
                                    'VERIFIED_SUPERVISOR' => 'Supervisor Verified',
                                    'REJECTED_SUPERVISOR' => 'Rejected by Supervisor',
                                    'ESCALATED_TO_HR' => 'Awaiting HR Review',
                                    'PENDING_HR_REVIEW' => 'Awaiting HR Review',
                                    'APPROVED_BY_HR' => 'HR Approved',
                                    'REJECTED_BY_HR' => 'Rejected by HR',
                                    'ESCALATED_TO_DMO' => 'Awaiting District Medical Officer',
                                    'ESCALATED_TO_MEDICAL_SUPERINTENDENT' => 'Awaiting Medical Superintendent',
                                    'ESCALATED_TO_PMD' => 'Under PMD Review',
                                    'PENDING_PMD_REVIEW' => 'Awaiting PMD Review',
                                    'APPROVED_BY_PMD' => 'PMD Approved',
                                    'REJECTED_BY_PMD' => 'Rejected by PMD',
                                    'UNDER_MEDICAL_REVIEW' => 'Medical Board Reviewing',
                                    'PENDING_MEDICAL_BOARD' => 'Awaiting Medical Board',
                                    'RESOLVED' => 'Case Resolved',
                                    'CLOSED' => 'Case Closed',
                                ];
                                
                                $statusLabel = $statusMap[$incident->status] ?? str_replace('_', ' ', ucwords(strtolower($incident->status)));
                                
                                $statusClass = 'pending';
                                if (in_array($incident->status, ['VERIFIED_SUPERVISOR', 'APPROVED_BY_HR', 'APPROVED_BY_PMD'])) {
                                    $statusClass = 'verified';
                                } elseif (in_array($incident->status, ['UNDER_MEDICAL_REVIEW', 'PENDING_MEDICAL_BOARD'])) {
                                    $statusClass = 'investigating';
                                } elseif (in_array($incident->status, ['RESOLVED', 'CLOSED'])) {
                                    $statusClass = 'resolved';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($incident->firstAidRecords->count() > 0)
            <div class="divider"></div>

            <!-- First Aid Section -->
            <div class="section">
                <div class="section-title">First Aid Record</div>
                @foreach ($incident->firstAidRecords as $record)
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">Nurse Name:</td>
                                <td>{{ $record->nurse_name }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">License/EC Number:</td>
                                <td>{{ $record->nurse_license_number }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">Injury Description:</td>
                                <td>{{ $record->injury_description }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">First Aid Provided:</td>
                                <td>{{ $record->first_aid_description }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">Time Administered:</td>
                                <td>{{ $record->time_administered->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">Location:</td>
                                <td>{{ $record->location }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">Severity:</td>
                                <td>{{ ucfirst($record->severity_level) }}</td>
                            </tr>
                            @if ($record->notes)
                                <tr>
                                    <td style="width: 30%; font-weight: bold; color: #1D4533;">Notes:</td>
                                    <td>{{ $record->notes }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="width: 30%; font-weight: bold; color: #1D4533;">Status:</td>
                                <td>
                                    <span class="status-badge {{ $record->status }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
        @endif

        @if ($incident->attachments->count() > 0)
            <div class="divider"></div>

            <!-- Submitted Documents Section -->
            <div class="section">
                <div class="section-title">Submitted Documents</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 60%;">Document Type</th>
                            <th style="width: 30%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incident->attachments as $index => $attachment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $docTypeLabels = [
                                            'doctors_report' => 'Doctor\'s Report',
                                            'medical_records' => 'Medical Records',
                                            'witness_statement' => 'Witness Statement',
                                            'photos' => 'Photos/Images',
                                            'lab_results' => 'Laboratory Results',
                                            'xray_scan' => 'X-ray/Scan Results',
                                            'police_report' => 'Police Report',
                                            'insurance_claim' => 'Insurance Claim Form',
                                            'other' => 'Other Supporting Document',
                                            'injury_report' => 'INJURY REPORT FORM ZII49',
                                            'treatment_records' => 'Certified Copies of Initial Treatment Records',
                                            'birth_certificate' => 'Certified Long Children\'s Birth Certificates',
                                        ];
                                        $docLabel = $docTypeLabels[$attachment->document_type] ?? ucwords(str_replace('_', ' ', $attachment->document_type));
                                    @endphp
                                    {{ $docLabel }}
                                </td>
                                <td>
                                    @php
                                        $docStatus = $attachment->verification_status ?? 'PENDING';
                                        $docStatusLabel = $docStatus === 'VERIFIED' ? 'Verified' : 'Submitted';
                                        $docStatusClass = $docStatus === 'VERIFIED' ? 'verified' : 'pending';
                                    @endphp
                                    <span class="doc-status {{ $docStatusClass }}">{{ $docStatusLabel }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer">
            <div style="border-top: 2px solid #1D4533; padding-top: 15px; margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <div style="text-align: left;">
                        <p style="font-size: 11px; color: #333; margin: 0;"><strong>Health Service Commission</strong></p>
                        <p style="font-size: 9px; color: #666; margin: 2px 0;">Occupational Health & Safety System</p>
                        <p style="font-size: 9px; color: #666; margin: 2px 0;">Republic of Zimbabwe</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 9px; color: #666; margin: 0;">Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
                        <p style="font-size: 9px; color: #666; margin: 2px 0;">Report ID: {{ $incident->reference_number }}</p>
                        <p style="font-size: 8px; color: #999; margin: 2px 0;">Digital Signature: {{ strtoupper(substr(hash('sha256', $incident->reference_number . $incident->created_at . now()), 0, 16)) }}</p>
                    </div>
                </div>
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px; border-radius: 4px; margin-top: 10px;">
                    <p style="font-size: 8px; color: #15803d; margin: 0; text-align: center;">
                        ⚠ <strong>AUTHENTICITY NOTICE:</strong> This is an official document generated by the HSC-OHS System. 
                        Any alteration or unauthorized reproduction is prohibited and may constitute fraud. 
                        Verify authenticity at: injury.hsc.org.zw/verify or contact HSC directly.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
