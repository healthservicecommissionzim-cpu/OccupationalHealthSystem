<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'organization_id',
        'department_id',
        'designation_id',
        'province_id',
        'district_id',
        'facility_type_id',
        'facility_id',
        'password',
        'status',
        'account_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_at',
        'can_reapply_at',
        'last_login_ip',
        'last_login_at',
        'failed_login_attempts',
        'last_failed_login_at',
        'mfa_enabled',
        'mfa_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'can_reapply_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_failed_login_at' => 'datetime',
            'mfa_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the roles that belong to this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has any of the specified roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if user has all of the specified roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        return count($roles) === $this->roles()->whereIn('name', $roles)->count();
    }

    /**
     * Get the primary role for dashboard routing.
     */
    public function getPrimaryRole(): ?Role
    {
        return $this->roles()->first();
    }

    /**
     * Get the dashboard route for user's primary role.
     */
    public function getDashboardRoute(): string
    {
        $role = $this->getPrimaryRole();
        
        if (!$role) {
            return 'dashboards.supervisor';
        }

        return match($role->name) {
            'super_admin' => 'dashboards.super_admin',
            'supervisor' => 'dashboards.supervisor',
            'district_hr' => 'dashboards.district_hr',
            'hospital_hr' => 'dashboards.hospital_hr',
            'central_hr' => 'dashboards.central_hr',
            'dmo' => 'dashboards.clinical_executive',
            'medical_superintendent' => 'dashboards.clinical_executive',
            'cmo' => 'dashboards.clinical_executive',
            'pmd' => 'dashboards.pmd',
            'mohhc_hr' => 'dashboards.mohhc_hr',
            'labour_social' => 'dashboards.labour_social',
            'medical_board' => 'dashboards.medical_board',
            'hsc_conditions' => 'dashboards.hsc_conditions',
            default => 'dashboards.supervisor',
        };
    }

    /**
     * Check if user is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if user is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get all permissions for this user through their roles.
     */
    public function getAllPermissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Check if user has a specific permission by slug.
     */
    public function hasPermission(string $slug): bool
    {
        return $this->getAllPermissions()
            ->where('slug', $slug)
            ->isNotEmpty();
    }

    /**
     * Check if user has any of the specified permissions.
     */
    public function hasAnyPermission(array $slugs): bool
    {
        $userPermissions = $this->getAllPermissions()->pluck('slug')->toArray();
        return !empty(array_intersect($slugs, $userPermissions));
    }

    /**
     * Check if user has all of the specified permissions.
     */
    public function hasAllPermissions(array $slugs): bool
    {
        $userPermissions = $this->getAllPermissions()->pluck('slug')->toArray();
        return count($slugs) === count(array_intersect($slugs, $userPermissions));
    }

    /**
     * Get the organization this user belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the department this user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the designation this user has.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get the facility this user belongs to.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the facility type this user belongs to.
     */
    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }

    /**
     * Get the province this user belongs to.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the district this user belongs to.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the user who approved this user.
     */
    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if user can retry after rejection (7 days passed).
     */
    public function canRetryApplication(): bool
    {
        if ($this->status !== 'rejected') {
            return false;
        }

        if (!$this->can_reapply_at) {
            return false;
        }

        return now()->isAfter($this->can_reapply_at);
    }

    /**
     * Get days remaining until user can reapply.
     */
    public function getDaysUntilRetry(): ?int
    {
        if ($this->status !== 'rejected' || !$this->can_reapply_at) {
            return null;
        }

        if ($this->canRetryApplication()) {
            return 0;
        }

        return (int)ceil(now()->floatDiffInDays($this->can_reapply_at));
    }

    /**
     * Reset user status to pending for retry (called when user reapplies).
     */
    public function retryApplication(): bool
    {
        if (!$this->canRetryApplication()) {
            return false;
        }

        $this->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'rejected_at' => null,
            'can_reapply_at' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return true;
    }
}
