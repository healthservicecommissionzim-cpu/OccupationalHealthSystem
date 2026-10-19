<?php

namespace App\Services;

use App\Models\ChatMessage;
use Carbon\Carbon;

class ChatbotService
{
    protected $responseRules = [];

    public function __construct()
    {
        $this->initializeResponseRules();
    }

    /**
     * Initialize all rule-based responses
     */
    private function initializeResponseRules()
    {
        $this->responseRules = [
            // Greetings
            [
                'patterns' => ['hello', 'hi', 'hey', 'greetings', 'good morning', 'good afternoon', 'good evening'],
                'responses' => [
                    "Hello! 👋 Welcome to HSC-OHS. I'm here to help you with any questions about our system. What can I assist you with today?",
                    "Hi there! 😊 I'm your HSC-OHS assistant. How can I help you?",
                    "Greetings! Welcome to the Occupational Health & Safety Portal. What do you need help with?",
                ]
            ],
            // Password Issues
            [
                'patterns' => ['forgot password', 'reset password', 'password reset', 'cannot login', 'password expired', 'reset my password'],
                'responses' => [
                    "No problem! Here's how to reset your password:\n\n1. Click the 'Forgot Password' link on the login page\n2. Enter your email address\n3. You'll receive a password reset link via email within 2 minutes\n4. Click the link and create a new password\n\nIf you don't see the email, check your spam folder!",
                ]
            ],
            // Account Locked
            [
                'patterns' => ['account locked', 'locked out', 'too many attempts', 'login failed', 'access denied'],
                'responses' => [
                    "Your account may be temporarily locked due to multiple failed login attempts.\n\n✓ Wait 30 minutes and try again\n✓ Clear your browser cache\n✓ Try a different browser\n\nIf the issue persists, contact our support team: support@hsc.org.zw or call +263 77 123 4567",
                ]
            ],
            // Report Incident
            [
                'patterns' => ['how to report', 'report incident', 'submit incident', 'file report', 'file incident', 'report injury'],
                'responses' => [
                    "Here's how to report an incident:\n\n📋 Steps:\n1. Log in to your account\n2. Click 'Report Incident' button on the home page\n3. Fill in the incident details (who, what, when, where)\n4. Select the incident type (injury, exposure, near-miss, etc.)\n5. Submit the form\n6. You'll receive a reference number for tracking\n\nYour report is confidential and secure! 🔒",
                ]
            ],
            // Track Incident
            [
                'patterns' => ['track incident', 'check status', 'where is my report', 'incident status', 'track report'],
                'responses' => [
                    "To track your incident:\n\n1. Log in to your account\n2. Click 'Track & Upload' on the home page\n3. Find your incident using the reference number\n4. View the current status and any updates\n5. Upload supporting documents if requested\n\nYou can also upload medical reports, witness statements, or photos here.",
                ]
            ],
            // Upload Documents
            [
                'patterns' => ['upload document', 'attach file', 'upload photo', 'add attachment', 'upload evidence', 'upload report'],
                'responses' => [
                    "To upload documents:\n\n📎 Process:\n1. Go to 'Track & Upload' section\n2. Find your incident\n3. Click 'Upload Documents'\n4. Select files (medical reports, photos, witness statements, etc.)\n5. Click 'Upload'\n\n✓ Accepted formats: PDF, Word, Images, Excel\n✓ Max file size: 10MB per file\n✓ You can upload multiple files",
                ]
            ],
            // Account Creation
            [
                'patterns' => ['create account', 'sign up', 'register', 'new account', 'how to register'],
                'responses' => [
                    "Ready to register? Here's how:\n\n📝 Registration Steps:\n1. Click the 'Register' button on the home page\n2. Fill in your personal details (name, email, phone)\n3. Select your department and designation\n4. Choose your organization, province, and facility\n5. Create a secure password (minimum 8 characters)\n6. Agree to the terms and conditions\n7. Click 'Create Account'\n\nYou'll receive a confirmation email. Your account will be approved within 24 hours.",
                ]
            ],
            // Login Issues
            [
                'patterns' => ['cannot login', 'login problem', 'login failed', 'cannot access', 'access denied', 'not working'],
                'responses' => [
                    "Let's troubleshoot your login issue:\n\n🔧 Quick fixes:\n1. Clear your browser cache and cookies\n2. Try using a different web browser (Chrome, Firefox, Safari, Edge)\n3. Check your internet connection\n4. Make sure you're using the correct email address\n5. Verify your account is approved (check your email)\n\nStill having issues? Contact: support@hsc.org.zw",
                ]
            ],
            // System Features
            [
                'patterns' => ['what can i do', 'features', 'how does this work', 'first aid log', 'medical board'],
                'responses' => [
                    "HSC-OHS provides several key features:\n\n✓ Report Incident - Log workplace injuries and exposures\n✓ Track & Upload - Monitor case progress and upload documents\n✓ First Aid Log - Record minor first aid incidents quickly\n✓ Resources - Access safety protocols and guidelines\n✓ Support - Chat with us or submit support tickets\n\nEach feature is designed to keep you safe and informed!",
                ]
            ],
            // Help/Support
            [
                'patterns' => ['help', 'support', 'contact support', 'need help', 'assistance', 'talk to human', 'speak to agent'],
                'responses' => [
                    "I'm here to help! 🤝\n\nIf I haven't solved your issue, here are other support options:\n\n📧 Email: support@hsc.org.zw\n📞 Phone: +263 77 123 4567\n🕐 Hours: Monday-Friday, 8 AM - 5 PM (Zimbabwe Time)\n💬 Chat with our team: Click 'Help Desk' in the menu\n\nOr fill out a support request and our team will get back to you within 2 hours!",
                ]
            ],
            // Privacy/Security
            [
                'patterns' => ['security', 'privacy', 'confidential', 'data protected', 'is my data safe', 'encryption'],
                'responses' => [
                    "Your data is secure with us! 🔒\n\n🛡️ Security Measures:\n✓ All data is encrypted (SSL/TLS)\n✓ Regular security audits\n✓ Compliant with healthcare privacy laws\n✓ Your personal information is never shared\n✓ Two-factor authentication available\n\nWe take your privacy seriously!",
                ]
            ],
            // Browser/Technical Issues
            [
                'patterns' => ['page not loading', 'error', 'broken', 'not working', 'technical issue', 'bug', 'slow'],
                'responses' => [
                    "Sorry you're experiencing a technical issue! 😞\n\n🔧 Try these steps:\n1. Refresh the page (F5 or Ctrl+R)\n2. Clear browser cache and cookies\n3. Try a different browser\n4. Check your internet connection\n5. Try again in a few minutes\n\nIf the issue persists, please contact support with the error message you see.",
                ]
            ],
            // Feedback/Suggestions
            [
                'patterns' => ['feedback', 'suggestion', 'idea', 'improve', 'feature request', 'comment'],
                'responses' => [
                    "We'd love to hear from you! 💡\n\nYour feedback helps us improve HSC-OHS. To submit your suggestion:\n\n1. Click 'Help Desk' or contact support\n2. Select 'Feature Request' or 'Feedback'\n3. Tell us your idea\n4. Click Submit\n\nWe review all suggestions and implement the best ones! Thank you for helping us improve! 🙏",
                ]
            ],
            // General Questions
            [
                'patterns' => ['what', 'how', 'why', 'when', 'where', 'which', 'who'],
                'responses' => [
                    "That's a great question! I'm here to help.\n\nCould you be more specific? For example:\n• 'How do I report an incident?'\n• 'What is first aid log?'\n• 'How to upload documents?'\n• 'Why is my account locked?'\n\nFeel free to ask anything about the system!",
                ]
            ],
        ];
    }

    /**
     * Process user message and get bot response
     */
    public function processMessage($userMessage, $sessionId = null)
    {
        $startTime = microtime(true);

        // Sanitize input
        $userMessage = trim($userMessage);
        if (empty($userMessage)) {
            return [
                'success' => false,
                'response' => "I didn't catch that. Could you please rephrase your question?",
                'type' => 'rule',
            ];
        }

        // Try to match rule-based response
        $botResponse = $this->findMatchingResponse($userMessage);

        $responseType = 'rule';
        if (!$botResponse) {
            $botResponse = "I'm not sure how to help with that. Could you provide more details? Or contact our support team: support@hsc.org.zw";
            $responseType = 'fallback';
        }

        $responseTime = round((microtime(true) - $startTime) * 1000);

        // Log the conversation
        ChatMessage::create([
            'session_id' => $sessionId ?? session()->getId(),
            'user_message' => $userMessage,
            'bot_response' => $botResponse,
            'message_type' => $responseType,
            'response_time_ms' => $responseTime,
        ]);

        return [
            'success' => true,
            'response' => $botResponse,
            'type' => $responseType,
        ];
    }

    /**
     * Find matching response based on user message
     */
    private function findMatchingResponse($userMessage)
    {
        $lowerMessage = strtolower($userMessage);
        
        foreach ($this->responseRules as $rule) {
            foreach ($rule['patterns'] as $pattern) {
                // Check if pattern matches
                if (stripos($lowerMessage, $pattern) !== false) {
                    // Return a random response from the rule
                    return $rule['responses'][array_rand($rule['responses'])];
                }
            }
        }

        return null;
    }

    /**
     * Get chat history
     */
    public function getChatHistory($sessionId = null, $limit = 50)
    {
        $query = ChatMessage::query();

        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Log feedback on response
     */
    public function logFeedback($messageId, $wasHelpful)
    {
        return ChatMessage::find($messageId)?->update(['was_helpful' => $wasHelpful]);
    }
}
