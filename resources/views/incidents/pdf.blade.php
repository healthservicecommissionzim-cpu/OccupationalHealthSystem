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
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1D4533;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1D4533;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .reference-number {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #1D4533;
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
        .row {
            display: flex;
            margin-bottom: 12px;
        }
        .label {
            flex: 0 0 30%;
            font-weight: bold;
            color: #1D4533;
            font-size: 12px;
        }
        .value {
            flex: 1;
            color: #333;
            font-size: 12px;
            word-break: break-word;
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
            <h1>Occupational Health & Safety System</h1>
            <p>Incident Report</p>
        </div>

        <div class="reference-number">
            Reference Number: {{ $incident->reference_number }}
        </div>

        <!-- Injured Person Section -->
        <div class="section">
            <div class="section-title">Injured Person Information</div>
            <div class="row">
                <div class="label">Name:</div>
                <div class="value">{{ $incident->first_name }} {{ $incident->surname }}</div>
            </div>
            <div class="row">
                <div class="label">Designation:</div>
                <div class="value">{{ $incident->designation->name ?? 'N/A' }}</div>
            </div>
            <div class="row">
                <div class="label">Department:</div>
                <div class="value">{{ $incident->department->name ?? 'N/A' }}</div>
            </div>
            <div class="row">
                <div class="label">Phone:</div>
                <div class="value">{{ $incident->phone_number }}</div>
            </div>
            <div class="row">
                <div class="label">Email:</div>
                <div class="value">{{ $incident->email }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Incident Details Section -->
        <div class="section">
            <div class="section-title">Incident Details</div>
            <div class="row">
                <div class="label">Date:</div>
                <div class="value">{{ $incident->incident_date->format('Y-m-d') }}</div>
            </div>
            <div class="row">
                <div class="label">Time:</div>
                <div class="value">{{ $incident->incident_time }}</div>
            </div>
            <div class="row">
                <div class="label">Description:</div>
                <div class="value">{{ $incident->incident_description }}</div>
            </div>
            <div class="row">
                <div class="label">Status:</div>
                <div class="value">
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
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Location Section -->
        <div class="section">
            <div class="section-title">Location Information</div>
            <div class="row">
                <div class="label">Facility:</div>
                <div class="value">{{ $incident->facility->name ?? 'N/A' }}</div>
            </div>
            <div class="row">
                <div class="label">District:</div>
                <div class="value">{{ $incident->district->name ?? 'N/A' }}</div>
            </div>
            <div class="row">
                <div class="label">Province:</div>
                <div class="value">{{ $incident->province->name ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Supervisor Section -->
        <div class="section">
            <div class="section-title">Supervisor Information</div>
            <div class="row">
                <div class="label">Name:</div>
                <div class="value">{{ $incident->supervisor_name }}</div>
            </div>
            <div class="row">
                <div class="label">Designation:</div>
                <div class="value">{{ $incident->supervisorDesignation->name ?? 'N/A' }}</div>
            </div>
        </div>

        @if ($incident->firstAidRecords->count() > 0)
            <div class="divider"></div>

            <!-- First Aid Section -->
            <div class="section">
                <div class="section-title">First Aid Record</div>
                @foreach ($incident->firstAidRecords as $record)
                    <div class="row">
                        <div class="label">Nurse Name:</div>
                        <div class="value">{{ $record->nurse_name }}</div>
                    </div>
                    <div class="row">
                        <div class="label">License/EC Number:</div>
                        <div class="value">{{ $record->nurse_license_number }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Injury Description:</div>
                        <div class="value">{{ $record->injury_description }}</div>
                    </div>
                    <div class="row">
                        <div class="label">First Aid Provided:</div>
                        <div class="value">{{ $record->first_aid_description }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Time Administered:</div>
                        <div class="value">{{ $record->time_administered->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Location:</div>
                        <div class="value">{{ $record->location }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Severity:</div>
                        <div class="value">{{ ucfirst($record->severity_level) }}</div>
                    </div>
                    @if ($record->notes)
                        <div class="row">
                            <div class="label">Notes:</div>
                            <div class="value">{{ $record->notes }}</div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="label">Status:</div>
                        <div class="value">
                            <span class="status-badge {{ $record->status }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </div>
                    </div>
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

        <div class="divider"></div>

        <!-- Timeline Section -->
        <div class="section">
            <div class="section-title">Timeline</div>
            <div class="row">
                <div class="label">Reported On:</div>
                <div class="value">{{ $incident->created_at->format('Y-m-d H:i') }}</div>
            </div>
            <div class="row">
                <div class="label">Last Updated:</div>
                <div class="value">{{ $incident->updated_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('Y-m-d H:i') }}</p>
            <p>Occupational Health & Safety System</p>
        </div>
    </div>
</body>
</html>
