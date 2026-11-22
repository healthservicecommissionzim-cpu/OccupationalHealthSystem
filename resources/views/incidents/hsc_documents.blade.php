<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Supporting Documents · {{ $incident->reference_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem; }
        .header-left { display: flex; align-items: center; gap: 1.5rem; }
        .header-icon { width: 52px; height: 52px; border-radius: 12px; background: linear-gradient(135deg, #0d7a3d 0%, #1bb74d 100%); color: white; display: grid; place-items: center; font-size: 24px; flex-shrink: 0; }
        .header h1 { font-size: 28px; font-weight: 750; color: #101a31; }
        .header p { font-size: 13px; color: #718096; margin-top: 6px; }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.5rem;
            background: white;
            color: #0d7a3d;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 650;
            border: 1px solid #ccd6df;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .back-btn:hover {
            background: #f8fafb;
            border-color: #0d7a3d;
        }
        .back-btn i { font-size: 14px; }
        .stats-bar {
            background: white;
            border: 1px solid #e1e7ee;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            gap: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 18px;
        }
        .stat-icon.verified {
            background: #dcfce7;
            color: #15803d;
        }
        .stat-icon.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .stat-info h4 {
            font-size: 24px;
            font-weight: 700;
            color: #101a31;
        }
        .stat-info p {
            font-size: 11px;
            color: #8a95a5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .doc-card {
            background: white;
            border: 1px solid #e1e7ee;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .doc-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        }
        .doc-header {
            padding: 0.9rem 1rem;
            background: #f8fafb;
            border-bottom: 1px solid #e1e7ee;
        }
        .doc-type {
            font-size: 10px;
            font-weight: 700;
            color: #8a95a5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .doc-name {
            font-size: 12px;
            font-weight: 600;
            color: #101a31;
            margin-bottom: 6px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .doc-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .doc-status.verified {
            background: #dcfce7;
            color: #15803d;
        }
        .doc-status.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .doc-body {
            padding: 1rem;
        }
        .doc-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 0.85rem;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #4e596c;
        }
        .meta-item i {
            color: #8a95a5;
            width: 14px;
            font-size: 10px;
        }
        .doc-actions {
            display: flex;
            gap: 6px;
        }
        .btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
        }
        .btn-primary {
            background: #0d7a3d;
            color: white;
        }
        .btn-primary:hover {
            background: #0a6331;
        }
        .btn-secondary {
            background: white;
            color: #0d7a3d;
            border: 1px solid #d4dce6;
        }
        .btn-secondary:hover {
            background: #f8fafb;
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border: 1px solid #e1e7ee;
            border-radius: 12px;
        }
        .empty-state i {
            font-size: 64px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: #101a31;
            margin-bottom: 8px;
        }
        .empty-state p {
            font-size: 14px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-icon"><i class="fa-solid fa-paperclip"></i></div>
                <div>
                    <h1>Supporting Documents</h1>
                    <p>{{ $incident->reference_number }} · {{ $incident->first_name }} {{ $incident->surname }}</p>
                </div>
            </div>
            <a href="{{ route('incidents.hsc_view', ['incident' => $incident->id]) }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Case
            </a>
        </div>

        @php
            $allDocuments = $incident->attachments ?? collect();
            $verifiedCount = $allDocuments->where('upload_status', 'VERIFIED')->count();
            $pendingCount = $allDocuments->where('upload_status', 'PENDING')->count();
        @endphp

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-icon verified">
                    <i class="fa-solid fa-files"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ $allDocuments->count() }}</h4>
                    <p>Total Documents</p>
                </div>
            </div>
            @if($verifiedCount > 0)
            <div class="stat-item">
                <div class="stat-icon verified">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ $verifiedCount }}</h4>
                    <p>Verified</p>
                </div>
            </div>
            @endif
            @if($pendingCount > 0)
            <div class="stat-item">
                <div class="stat-icon pending">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ $pendingCount }}</h4>
                    <p>Pending Review</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Documents Grid -->
        @if($allDocuments->count() > 0)
        <div class="doc-grid">
            @foreach($allDocuments as $attachment)
            <div class="doc-card">
                <div class="doc-header">
                    <div class="doc-type">{{ \App\Models\IncidentAttachment::DOCUMENT_TYPES[$attachment->document_type] ?? 'Document' }}</div>
                    <div class="doc-name">{{ $attachment->file_name }}</div>
                    <span class="doc-status {{ strtolower($attachment->upload_status) }}">
                        <i class="fa-solid fa-{{ $attachment->upload_status === 'VERIFIED' ? 'check' : 'clock' }}"></i> 
                        {{ $attachment->upload_status }}
                    </span>
                </div>
                <div class="doc-body">
                    <div class="doc-meta">
                        <div class="meta-item">
                            <i class="fa-solid fa-calendar"></i>
                            <span>Uploaded {{ $attachment->created_at->diffForHumans() }}</span>
                        </div>
                        @if($attachment->file_size)
                        <div class="meta-item">
                            <i class="fa-solid fa-hard-drive"></i>
                            <span>{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                        </div>
                        @endif
                        @if($attachment->uploaded_by_user_id)
                        <div class="meta-item">
                            <i class="fa-solid fa-user"></i>
                            <span>{{ $attachment->uploadedBy->name ?? 'Unknown' }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="doc-actions">
                        <a href="{{ route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment]) }}" target="_blank" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        <a href="{{ route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment]) }}" download class="btn btn-secondary">
                            <i class="fa-solid fa-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fa-solid fa-inbox"></i>
            <h3>No Documents Uploaded</h3>
            <p>There are no supporting documents available for this case yet.</p>
        </div>
        @endif
    </div>
</body>
</html>
