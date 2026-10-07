<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleDashboardMapping extends Model
{
    protected $table = 'role_dashboard_mappings';

    protected $fillable = [
        'role_id',
        'dashboard_name',
        'dashboard_route',
        'controller_action',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the role that this mapping belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Scope to get only active mappings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get mappings for a specific role.
     */
    public function scopeForRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope to get mappings for a specific dashboard.
     */
    public function scopeForDashboard($query, $dashboardName)
    {
        return $query->where('dashboard_name', $dashboardName);
    }

    /**
     * Scope ordered by sort priority.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
    }
}
