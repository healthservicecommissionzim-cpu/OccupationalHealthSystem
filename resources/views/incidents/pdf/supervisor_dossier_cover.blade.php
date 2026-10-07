<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1D4533;
        }

        .header h1 {
            font-size: 20px;
            color: #1D4533;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section h2 {
            font-size: 14px;
            font-weight: bold;
            color: #1D4533;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #1D4533;
        }

        .section h3 {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th {
            background: #1D4533;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        table tr:nth-child(even) {
            background: #f8fafc;
        }

        .description-box {
            background: #f8fafc;
            padding: 12px;
            border-left: 3px solid #1D4533;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .meta-info {
            font-size: 10px;
            color: #666;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>CASE DOSSIER - SUPERVISOR REPORT</h1>
        <p>Reference: {{ $incident->reference_number }}</p>
        <p>Generated: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- INCIDENT DETAILS -->
    <div class="section">
        <h2>Incident Details</h2>
        <table>
            <tr>
                <td><strong>Reference Number:</strong></td>
                <td>{{ $incident->reference_number }}</td>
                <td><strong>Worker Name:</strong></td>
                <td>{{ $incident->first_name }} {{ $incident->surname }}</td>
            </tr>
            <tr>
                <td><strong>ID Number:</strong></td>
                <td>{{ $incident->id_number ?? 'N/A' }}</td>
                <td><strong>Department:</strong></td>
                <td>{{ $incident->department->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Designation:</strong></td>
                <td>{{ $incident->designation->name ?? 'N/A' }}</td>
                <td><strong>Facility:</strong></td>
                <td>{{ $incident->facility->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>District:</strong></td>
                <td>{{ $incident->district->name ?? 'N/A' }}</td>
                <td><strong>Province:</strong></td>
                <td>{{ $incident->province->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Incident Date:</strong></td>
                <td>{{ $incident->incident_date->format('d M Y') }}</td>
                <td><strong>Incident Time:</strong></td>
                <td>{{ $incident->incident_time ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Incident Type:</strong></td>
                <td>{{ $incident->incidentType->name ?? 'N/A' }}</td>
                <td><strong>Work Related:</strong></td>
                <td>{{ $incident->is_work_related ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <td><strong>Phone Number:</strong></td>
                <td>{{ $incident->phone_number ?? 'N/A' }}</td>
                <td><strong>Email:</strong></td>
                <td>{{ $incident->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Home Address:</strong></td>
                <td colspan="3">{{ $incident->home_address ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- INCIDENT DESCRIPTION -->
    <div class="section">
        <h2>Incident Description</h2>
        <div class="description-box">
            {{ $incident->incident_description ?? 'No description provided' }}
        </div>
    </div>

    <!-- SUPERVISOR VERIFICATION -->
    <div class="section">
        <h2>Supervisor Verification & Assessment</h2>
        <table>
            <tr>
                <td><strong>Supervisor Name:</strong></td>
                <td>{{ $incident->supervisor_name ?? 'N/A' }}</td>
                <td><strong>Verified On:</strong></td>
                <td>{{ $incident->supervisor_verified_at ? $incident->supervisor_verified_at->format('d M Y H:i') : 'Not verified' }}</td>
            </tr>
        </table>
        
        @if ($incident->supervisor_witness_statement)
        <h3>Supervisor Witness Statement</h3>
        <div class="description-box">
            {{ $incident->supervisor_witness_statement }}
        </div>
        @endif
    </div>

    <!-- HOD REPORT -->
    <div class="section">
        <h2>Head of Department / Supervisor Report</h2>
        <div class="description-box">
            {{ $incident->hod_report }}
        </div>
        <div class="meta-info">
            <strong>Submitted by:</strong> {{ $incident->submittedByUser->name ?? 'Unknown' }}<br>
            <strong>Date:</strong> {{ $incident->hod_report_submitted_at->format('d M Y H:i') }}
        </div>
    </div>

    <!-- FIRST AID RECORDS -->
    @if ($firstAidRecords->count() > 0)
    <div class="section">
        <h2>First Aid Records ({{ $firstAidRecords->count() }})</h2>
        @foreach ($firstAidRecords as $index => $record)
        <h3>First Aid Record {{ $index + 1 }}</h3>
        <table>
            <tr>
                <td><strong>Nurse Name:</strong></td>
                <td>{{ $record->nurse_name ?? 'N/A' }}</td>
                <td><strong>License:</strong></td>
                <td>{{ $record->nurse_license_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Location:</strong></td>
                <td>{{ $record->location ?? 'N/A' }}</td>
                <td><strong>Time:</strong></td>
                <td>{{ $record->time_administered ? $record->time_administered->format('d M Y H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Severity:</strong></td>
                <td>{{ $record->severity_level ?? 'N/A' }}</td>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($record->status ?? 'N/A') }}</td>
            </tr>
        </table>
        
        <strong>Injury Description:</strong>
        <div class="description-box">
            {{ $record->injury_description ?? 'N/A' }}
        </div>
        
        <strong>Treatment Provided:</strong>
        <div class="description-box">
            {{ $record->first_aid_description ?? 'N/A' }}
        </div>

        @if ($record->notes)
        <strong>Additional Notes:</strong>
        <div class="description-box">
            {{ $record->notes }}
        </div>
        @endif
        @endforeach
    </div>
    @endif

    <!-- WITNESS STATEMENTS -->
    @if ($witnessStatements->count() > 0)
    <div class="section">
        <h2>Witness Statements ({{ $witnessStatements->count() }})</h2>
        @foreach ($witnessStatements as $index => $witness)
        <h3>Witness {{ $index + 1 }}: {{ $witness->witness_name ?? 'Pending Name' }}</h3>
        <table>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $witness->phone_number ?? 'N/A' }}</td>
                <td><strong>Submitted:</strong></td>
                <td>{{ $witness->statement_submitted_at ? $witness->statement_submitted_at->format('d M Y H:i') : 'Not submitted' }}</td>
            </tr>
        </table>
        
        <strong>Statement:</strong>
        <div class="description-box">
            {{ trim($witness->statement) ?? 'No statement provided' }}
        </div>
        @endforeach
    </div>
    @endif
</body>
</html>
