<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAssignment extends Model
{
    protected $fillable = ['user_id', 'role_id', 'assigned_by', 'removed_at'];

    protected $casts = [
        'assigned_at' => 'datetime',
        'removed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user assigned this role.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role assigned to the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who made the assignment.
     */
    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
