<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #c0392b; color: white; padding: 20px; text-align: center; }
        .alert { background-color: #fadbd8; border: 2px solid #c0392b; border-radius: 4px; padding: 15px; margin: 20px 0; }
        .details { background-color: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 4px; font-family: monospace; font-size: 12px; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Job Failure Alert</h1>
            <p>Leave Milestone Check Job Failed</p>
        </div>

        <div class="alert">
            <p><strong>Status:</strong> The automated leave milestone check job has failed after 3 retry attempts.</p>
            <p><strong>Time:</strong> {{ $timestamp }}</p>
            <p><strong>Impact:</strong> Leave milestone checks are paused until this issue is resolved.</p>
        </div>

        <h3>Error Details</h3>
        <div class="details">
            <p><strong>Error Message:</strong></p>
            <p>{{ $error }}</p>
            
            <p><strong>File:</strong> {{ $file }}</p>
            <p><strong>Line:</strong> {{ $line }}</p>
            
            <p><strong>Stack Trace:</strong></p>
            <pre style="overflow-x: auto; background-color: white; padding: 10px; border-radius: 4px;">{{ $trace }}</pre>
        </div>

        <div style="background-color: #fef5e7; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #f39c12;">
            <h4>🔧 Recommended Actions:</h4>
            <ol>
                <li>Check the application logs for detailed error information</li>
                <li>Verify database connectivity and incident table integrity</li>
                <li>Review recent code changes in LeaveManagementService</li>
                <li>Manually check for incidents reaching 90/180-day milestones</li>
                <li>Contact the development team if the issue persists</li>
            </ol>
        </div>

        <div class="footer">
            <p>Occupational Health and Safety System - HSC</p>
            <p>This is an automated alert. Please address this issue promptly.</p>
        </div>
    </div>
</body>
</html>
