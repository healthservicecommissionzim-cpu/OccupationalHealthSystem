<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get incidents of this type.
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Scope to get only active incident types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
