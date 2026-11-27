<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CommunicationLog;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BroadcastMessageController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show the broadcast message form
     */
    public function create()
    {
        // Get all users for recipient selection
        $users = User::where('status', 'approved')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone_number']);

        return view('super-admin.broadcast.create', compact('users'));
    }

    /**
     * Send broadcast message
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|in:email,sms',
            'recipient_type' => 'required|in:single,multiple,all',
            'recipient_id' => 'required_if:recipient_type,single|exists:users,id',
            'recipient_ids' => 'required_if:recipient_type,multiple|array',
            'recipient_ids.*' => 'exists:users,id',
            'subject' => 'required_if:channel,email|max:255',
            'message' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Determine recipients
        $recipients = $this->getRecipients($request);

        if ($recipients->isEmpty()) {
            return back()->with('error', 'No valid recipients found.')->withInput();
        }

        // Send messages
        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            try {
                if ($request->channel === 'email') {
                    $this->sendEmail($recipient, $request->subject, $request->message);
                } else {
                    $this->sendSMS($recipient, $request->message);
                }
                $sent++;
            } catch (\Exception $e) {
                Log::error('Broadcast message failed', [
                    'recipient_id' => $recipient->id,
                    'channel' => $request->channel,
                    'error' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        $message = "Broadcast sent successfully! Sent: {$sent}, Failed: {$failed}";
        
        return redirect()->route('system.communication_logs.index')
            ->with('success', $message);
    }

    /**
     * Get recipients based on selection
     */
    private function getRecipients(Request $request)
    {
        $query = User::where('status', 'approved');

        switch ($request->recipient_type) {
            case 'single':
                return $query->where('id', $request->recipient_id)->get();
            
            case 'multiple':
                return $query->whereIn('id', $request->recipient_ids)->get();
            
            case 'all':
                return $query->get();
            
            default:
                return collect();
        }
    }

    /**
     * Send email to recipient
     */
    private function sendEmail($recipient, $subject, $message)
    {
        if (!$recipient->email) {
            throw new \Exception('Recipient has no email address');
        }

        Mail::send([], [], function ($mail) use ($recipient, $subject, $message) {
            $mail->to($recipient->email)
                ->subject($subject)
                ->html($this->formatEmailHtml($subject, $message));
        });

        // Log the communication
        CommunicationLog::create([
            'communication_type' => 'BROADCAST_EMAIL',
            'channel' => 'email',
            'recipient_user_id' => $recipient->id,
            'recipient_email' => $recipient->email,
            'subject' => $subject,
            'message_preview' => substr($message, 0, 200),
            'full_content' => $message,
            'status' => 'SENT',
            'delivery_status' => 'PENDING',
            'sent_at' => now(),
            'first_attempt_at' => now(),
        ]);
    }

    /**
     * Send SMS to recipient
     */
    private function sendSMS($recipient, $message)
    {
        if (!$recipient->phone_number) {
            throw new \Exception('Recipient has no phone number');
        }

        // Send SMS using SMS service
        $this->smsService->send(
            $recipient->phone_number,
            $message,
            'BROADCAST_SMS',
            $recipient->id
        );
    }

    /**
     * Format email HTML
     */
    private function formatEmailHtml($subject, $message)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #079b6d; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
                .content { background: white; padding: 30px; border: 1px solid #e1e7ee; border-radius: 0 0 8px 8px; }
                .footer { text-align: center; margin-top: 20px; color: #718096; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>{$subject}</h2>
                </div>
                <div class='content'>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
                <div class='footer'>
                    <p>This is a broadcast message from HSC-OHS System</p>
                    <p>&copy; " . date('Y') . " Health Service Commission</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
