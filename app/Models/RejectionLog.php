<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectionLog extends Model
{
    protected $fillable = ['user_id', 'reason', 'rejected_by'];

    /**
     * Get the user who was rejected.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who rejected this user.
     */
    public function rejectedByUser()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
