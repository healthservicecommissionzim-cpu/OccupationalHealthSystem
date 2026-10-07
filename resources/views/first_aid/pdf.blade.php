<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Aid Record</title>
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
        .record-id {
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
        .status-badge.approved { background: #dcfce7; color: #15803d; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Occupational Health & Safety System</h1>
            <p>First Aid Record</p>
        </div>

        <div class="record-id">
            Record ID: {{ $record->id }} | Incident Reference: {{ $record->incident->reference_number ?? 'N/A' }}
        </div>

        <!-- Nurse Information Section -->
        <div class="section">
            <div class="section-title">Nurse Information</div>
            <div class="row">
                <div class="label">Name:</div>
                <div class="value">{{ $record->nurse_name }}</div>
            </div>
            <div class="row">
                <div class="label">License / EC Number:</div>
                <div class="value">{{ $record->nurse_license_number }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Injury Information Section -->
        <div class="section">
            <div class="section-title">Injury Information</div>
            <div class="row">
                <div class="label">Description:</div>
                <div class="value">{{ $record->injury_description }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- First Aid Details Section -->
        <div class="section">
            <div class="section-title">First Aid Details</div>
            <div class="row">
                <div class="label">Treatment Provided:</div>
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
                <div class="label">Injury Severity:</div>
                <div class="value">{{ ucfirst($record->severity_level) }}</div>
            </div>
        </div>

        @if ($record->notes)
            <div class="divider"></div>

            <!-- Additional Notes Section -->
            <div class="section">
                <div class="section-title">Additional Notes</div>
                <div class="row">
                    <div class="label">Notes:</div>
                    <div class="value">{{ $record->notes }}</div>
                </div>
            </div>
        @endif

        <div class="divider"></div>

        <!-- Status Section -->
        <div class="section">
            <div class="section-title">Record Status</div>
            <div class="row">
                <div class="label">Status:</div>
                <div class="value">
                    <span class="status-badge {{ $record->status }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </div>
            </div>
            @if ($record->reviewed_by)
                <div class="row">
                    <div class="label">Reviewed By:</div>
                    <div class="value">{{ $record->reviewer->name ?? 'N/A' }}</div>
                </div>
                <div class="row">
                    <div class="label">Reviewed On:</div>
                    <div class="value">{{ $record->reviewed_at->format('Y-m-d H:i') ?? 'N/A' }}</div>
                </div>
            @endif
            @if ($record->review_comments)
                <div class="row">
                    <div class="label">Review Comments:</div>
                    <div class="value">{{ $record->review_comments }}</div>
                </div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- Timeline Section -->
        <div class="section">
            <div class="section-title">Timeline</div>
            <div class="row">
                <div class="label">Submitted On:</div>
                <div class="value">{{ $record->created_at->format('Y-m-d H:i') }}</div>
            </div>
            <div class="row">
                <div class="label">Last Updated:</div>
                <div class="value">{{ $record->updated_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('Y-m-d H:i') }}</p>
            <p>Occupational Health & Safety System</p>
        </div>
    </div>
</body>
</html>
