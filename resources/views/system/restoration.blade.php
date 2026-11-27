<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>System Restoration / Fall Back · HSC-OHS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; color: #263149; }
        
        .container { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .icon-box { width: 48px; height: 48px; background: #e9f8f3; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .icon-box i { color: #079b6d; font-size: 24px; }
        .title { font-size: 28px; font-weight: 800; color: #101a31; margin: 0; }
        .subtitle { font-size: 14px; color: #718096; margin-top: 4px; }
        
        .btn { padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-secondary { background: #e1e7ee; color: #263149; }
        .btn-secondary:hover { background: #d1d9e1; }
        
        /* Card */
        .card { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .card-title { font-size: 18px; font-weight: 700; color: #101a31; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
        .card-title i { color: #079b6d; }
        .card-desc { font-size: 14px; color: #718096; margin-bottom: 24px; }
        
        /* Coming Soon */
        .coming-soon { text-align: center; padding: 60px 20px; }
        .coming-soon-icon { font-size: 72px; color: #e1e7ee; margin-bottom: 20px; }
        .coming-soon-title { font-size: 24px; font-weight: 700; color: #263149; margin-bottom: 12px; }
        .coming-soon-text { font-size: 15px; color: #718096; max-width: 600px; margin: 0 auto; line-height: 1.6; }
        
        /* Feature List */
        .feature-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 32px; }
        .feature-item { background: #f8fafb; padding: 20px; border-radius: 12px; border-left: 4px solid #079b6d; }
        .feature-item-title { font-size: 15px; font-weight: 700; color: #263149; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .feature-item-title i { color: #079b6d; font-size: 18px; }
        .feature-item-desc { font-size: 13px; color: #718096; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="icon-box">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <div>
                    <h1 class="title">System Restoration / Fall Back</h1>
                    <p class="subtitle">Restore system to previous state or rollback changes</p>
                </div>
            </div>
            <a href="{{ route('dashboards.super_admin') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Coming Soon Card -->
        <div class="card">
            <div class="coming-soon">
                <div class="coming-soon-icon">
                    <i class="fa-solid fa-tools"></i>
                </div>
                <h2 class="coming-soon-title">Coming Soon</h2>
                <p class="coming-soon-text">
                    The System Restoration and Fallback feature is currently under development. 
                    This powerful tool will allow you to restore your system to a previous state, 
                    rollback recent changes, and recover from system issues.
                </p>
            </div>

            <!-- Planned Features -->
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-item-title">
                        <i class="fa-solid fa-database"></i>
                        Database Restoration
                    </div>
                    <div class="feature-item-desc">
                        Restore database to any previous backup point with one click. 
                        Preview changes before applying restoration.
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-item-title">
                        <i class="fa-solid fa-code-branch"></i>
                        Version Rollback
                    </div>
                    <div class="feature-item-desc">
                        Rollback application code to previous stable versions. 
                        Automatic backup before rollback for safety.
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-item-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Point-in-Time Recovery
                    </div>
                    <div class="feature-item-desc">
                        Restore system to exact point in time. Perfect for recovering 
                        from accidental changes or data corruption.
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-item-title">
                        <i class="fa-solid fa-shield-halved"></i>
                        Safe Rollback Mode
                    </div>
                    <div class="feature-item-desc">
                        Automated validation and health checks before and after restoration. 
                        Automatic rollback if issues detected.
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-item-title">
                        <i class="fa-solid fa-list-check"></i>
                        Pre-flight Checklist
                    </div>
                    <div class="feature-item-desc">
                        Comprehensive checklist before restoration. Ensures all 
                        requirements met for safe system recovery.
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-item-title">
                        <i class="fa-solid fa-file-contract"></i>
                        Audit Trail
                    </div>
                    <div class="feature-item-desc">
                        Complete audit log of all restoration activities. Track who, 
                        what, when, and why for compliance.
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card" style="background: #e9f8f3; border: 1px solid #c3f0df;">
            <div style="display: flex; gap: 16px; align-items: start;">
                <i class="fa-solid fa-info-circle" style="color: #079b6d; font-size: 24px; margin-top: 2px;"></i>
                <div>
                    <div style="font-weight: 700; color: #263149; margin-bottom: 8px; font-size: 15px;">
                        In the meantime...
                    </div>
                    <div style="font-size: 14px; color: #5a6c7d; line-height: 1.6;">
                        You can still create and manage database backups from the 
                        <a href="{{ route('system.database_backup') }}" style="color: #079b6d; font-weight: 600; text-decoration: underline;">Database Backup</a> 
                        page. Regular backups are automatically created and can be downloaded or restored manually if needed.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
