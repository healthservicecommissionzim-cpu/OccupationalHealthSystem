<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITSupportTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'resolution_notes',
        'assigned_to',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
