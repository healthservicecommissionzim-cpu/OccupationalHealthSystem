<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        @page {
            margin: 40px 20px 30px 20px;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            position: relative;
        }
        
        /* Text Watermark */
        body::before {
            content: 'Health Service Commission';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            font-weight: bold;
            color: #1D4533;
            opacity: 0.06;
            z-index: -1;
            white-space: nowrap;
            letter-spacing: 2px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        
        /* Clean Professional Header */
        .header {
            border-bottom: 3px solid #1D4533;
            padding-bottom: 20px;
            margin-bottom: 25px;
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
        }
        .header-left-inner {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            object-fit: contain;
        }
        .header-text h1 {
            color: #1D4533;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
            line-height: 1.2;
        }
        .header-text .subtitle {
            color: #666;
            font-size: 12px;
            margin: 0 0 5px 0;
        }
        .header-text .tagline {
            color: #079b6d;
            font-size: 11px;
            font-weight: 600;
            margin: 0;
        }
        .header-reference {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 30%;
        }
        .header-reference .ref-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 3px 0;
        }
        .header-reference .ref-number {
            font-size: 20px;
            color: #1D4533;
            font-weight: 800;
            letter-spacing: 1px;
            margin: 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #1D4533;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 12px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-left-inner">
                    <img src="{{ public_path('images/hsc-logo.png') }}" alt="HSC Logo" class="header-logo">
                    <div class="header-text">
                        <h1>Health Service Commission</h1>
                        <p class="tagline">Occupational Health & Safety System · Official Incident Report</p>
                    </div>
                </div>
            </div>
            <div class="header-reference">
                <p class="ref-label">Official Reference Number</p>
                <p class="ref-number">{{ $incident->reference_number }}</p>
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

        <div class="footer">
            <div style="border-top: 2px solid #1D4533; padding-top: 15px; margin-top: 20px;">
                <p style="font-size: 8px; color: #dc2626; margin: 0; text-align: center; font-weight: bold;">
                    AUTHENTICITY NOTICE: OFFICIAL HSC-OHS SYSTEM DOCUMENT. ALTERATION OR COPYING IS PROHIBITED AND MAY CONSTITUTE FRAUD.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
