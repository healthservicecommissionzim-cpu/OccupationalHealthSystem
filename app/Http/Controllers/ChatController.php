<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatbot;

    public function __construct(ChatbotService $chatbot)
    {
        $this->chatbot = $chatbot;
    }

    /**
     * Send message to chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = session()->getId();
        $response = $this->chatbot->processMessage($request->message, $sessionId);

        return response()->json($response);
    }

    /**
     * Get chat history
     */
    public function getHistory(Request $request)
    {
        $sessionId = session()->getId();
        $history = $this->chatbot->getChatHistory($sessionId);

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }

    /**
     * Log feedback
     */
    public function logFeedback(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:chat_messages,id',
            'helpful' => 'required|boolean',
        ]);

        $this->chatbot->logFeedback($request->message_id, $request->helpful);

        return response()->json(['success' => true]);
    }
}
