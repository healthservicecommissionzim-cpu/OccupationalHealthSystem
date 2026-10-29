<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 30px 20px; }
        .greeting { font-size: 16px; color: #101a31; margin-bottom: 15px; font-weight: 600; }
        .message { font-size: 14px; color: #4a5568; line-height: 1.8; margin-bottom: 20px; }
        .info-box { background: #f8fafb; border-left: 4px solid #079b6d; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .info-box strong { display: block; color: #101a31; margin-bottom: 5px; font-size: 12px; text-transform: uppercase; }
        .info-box p { margin: 5px 0; font-size: 14px; color: #4a5568; }
        .btn { display: inline-block; padding: 14px 28px; background: #079b6d; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; margin: 20px 0; }
        .btn:hover { background: #067c58; }
        .footer { background: #f8fafb; padding: 20px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; }
        .link-box { background: #fff8e7; border: 1px solid #f6d48b; padding: 12px; border-radius: 6px; margin: 15px 0; word-break: break-all; }
        .link-box small { font-size: 11px; color: #8a95a5; display: block; margin-bottom: 5px; }
        .link-box a { color: #079b6d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Witness Statement Required</h1>
            <p>Health Services Commission - Occupational Health & Safety</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $witnessToken->witness_name }},</p>
            
            <p class="message">
                You are listed as a witness for an occupational health incident that occurred on 
                <strong>{{ $incident->incident_date->format('d F Y') }}</strong> at 
                <strong>{{ $incident->facility->name ?? 'N/A' }}</strong>.
            </p>
            
            <p class="message">
                Your witness statement is required to complete the incident investigation. 
                Please submit your statement as soon as possible by clicking the button below.
            </p>
            
            <div class="info-box">
                <strong>Incident Details:</strong>
                <p><strong>Reference:</strong> {{ $incident->reference_number }}</p>
                <p><strong>Date:</strong> {{ $incident->incident_date->format('d M Y') }}</p>
                <p><strong>Facility:</strong> {{ $incident->facility->name ?? 'N/A' }}</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $statementLink }}" class="btn">Submit Witness Statement</a>
            </div>
            
            <div class="link-box">
                <small>Or copy and paste this link into your browser:</small>
                <a href="{{ $statementLink }}">{{ $statementLink }}</a>
            </div>
            
            <p class="message" style="margin-top: 25px; font-size: 13px; color: #718096;">
                <strong>Important:</strong> This link is unique to you and should not be shared with others. 
                If you have any questions, please contact your supervisor or the HSC OHS team.
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Health Services Commission of Zimbabwe</strong></p>
            <p>Occupational Health & Safety Division</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
