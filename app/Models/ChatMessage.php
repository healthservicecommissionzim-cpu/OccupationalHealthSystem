<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'session_id',
        'user_message',
        'bot_response',
        'message_type',
        'response_time_ms',
        'was_helpful',
    ];

    protected $casts = [
        'was_helpful' => 'boolean',
        'response_time_ms' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
