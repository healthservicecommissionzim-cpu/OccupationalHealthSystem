<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends Model
{
    protected $table = 'cost_centers';

    protected $fillable = [
        'facility_id',
        'cost_center_code',
        'cost_center_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the facility this cost center belongs to.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    /**
     * Scope to filter only active cost centers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
