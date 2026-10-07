<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentWitnessToken extends Model
{
    protected $fillable = [
        'incident_id',
        'witness_name',
        'phone_number',
        'token',
        'statement',
        'token_sent_at',
        'statement_submitted_at',
        'witness_last_reminder_sent_at',
        'token_expired_at',
        'is_expired',
    ];

    protected $casts = [
        'token_sent_at' => 'datetime',
        'statement_submitted_at' => 'datetime',
        'witness_last_reminder_sent_at' => 'datetime',
        'token_expired_at' => 'datetime',
        'is_expired' => 'boolean',
    ];

    /**
     * Get the incident this witness token belongs to.
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the attachments/statements uploaded by this witness.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(IncidentAttachment::class, 'witness_token_id');
    }

    /**
     * Check if the witness has submitted their statement.
     * 
     * @return bool
     */
    public function hasSubmitted(): bool
    {
        return $this->statement_submitted_at !== null;
    }

    /**
     * Check if the witness statement is still pending.
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return !$this->hasSubmitted();
    }

    /**
     * Check if this token is still valid for submission.
     */
    public function isValid(): bool
    {
        return !$this->is_expired && !$this->statement_submitted_at;
    }

    /**
     * Check if token has been used (statement submitted).
     */
    public function hasBeenUsed(): bool
    {
        return $this->statement_submitted_at !== null;
    }

    /**
     * Mark token as expired after statement submission.
     */
    public function expireToken(): void
    {
        $this->update([
            'is_expired' => true,
            'token_expired_at' => now(),
        ]);
    }

    /**
     * Submit a witness statement and expire the token.
     */
    public function submitStatement(string $statement): void
    {
        $this->update([
            'statement' => $statement,
            'statement_submitted_at' => now(),
            'is_expired' => true,
            'token_expired_at' => now(),
        ]);
    }

    /**
     * Generate a unique cryptographically secure token.
     */
    public static function generateToken(): string
    {
        return \Illuminate\Support\Str::uuid()->toString();
    }
}
