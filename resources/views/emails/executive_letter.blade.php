<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Tahoma, sans-serif; font-size: 12px; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px; }
        .letter-ref { font-weight: bold; color: #1D4533; }
        .message-box { background: #f9f9f9; border-left: 4px solid #1D4533; padding: 12px; margin: 20px 0; }
        .footer { border-top: 1px solid #ddd; padding-top: 15px; margin-top: 20px; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Dear Recipient,</p>
            <p>Please find attached the Executive Recommendation Letter for case <span class="letter-ref">{{ $incident->reference_number }}</span>.</p>
        </div>

        @if($customMessage)
            <div class="message-box">
                <p><strong>Message from Sender:</strong></p>
                <p>{{ $customMessage }}</p>
            </div>
        @endif

        <div class="content">
            <p>The letter contains the clinical recommendation and audit verification for the above-referenced incident.</p>
            <p>If you have any questions or require additional information, please contact the sender directly.</p>
        </div>

        <div class="footer">
            <p><strong>Health Service Commission</strong><br>
            Occupational Health & Safety Division<br>
            {{ config('app.url') }}</p>
            <p style="margin-top: 10px; font-size: 10px; color: #999;">
                This is an automated message. Please do not reply directly to this email.
            </p>
        </div>
    </div>
</body>
</html>
