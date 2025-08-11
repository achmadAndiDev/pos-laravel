<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
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
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship to outlets (many-to-many)
     */
    public function outlets(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class, 'user_outlets')
                    ->withTimestamps();
    }

    /**
     * Get the primary outlet for staff roles
     */
    public function primaryOutlet()
    {
        return $this->outlets()->first();
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific role
     */
    public function scopeRole($query, UserRole $role)
    {
        return $query->where('role', $role->value);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $roleValues = array_map(fn($role) => $role instanceof UserRole ? $role->value : $role, $roles);
        return in_array($this->role->value, $roleValues);
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role->hasPermission($permission);
    }

    /**
     * Check if user can access specific outlet
     */
    public function canAccessOutlet(int $outletId): bool
    {
        // Super admin can access all outlets
        if ($this->hasRole(UserRole::SUPER_ADMIN)) {
            return true;
        }

        // Check if user is assigned to this outlet
        return $this->outlets()->where('outlet_id', $outletId)->exists();
    }

    /**
     * Get accessible outlet IDs
     */
    public function getAccessibleOutletIds(): array
    {
        // Super admin can access all outlets
        if ($this->hasRole(UserRole::SUPER_ADMIN)) {
            return Outlet::pluck('id')->toArray();
        }

        return $this->outlets()->pluck('outlet_id')->toArray();
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(UserRole::SUPER_ADMIN);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::ADMIN);
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->hasAnyRole([UserRole::STAF_PEMBELIAN, UserRole::STAF_PENJUALAN]);
    }

    /**
     * Check if user can manage multiple outlets
     */
    public function canManageMultipleOutlets(): bool
    {
        return $this->role->canAccessMultipleOutlets();
    }

    /**
     * Assign outlet to user
     */
    public function assignOutlet(int $outletId): void
    {
        // For staff roles, ensure they only have one outlet
        if ($this->isStaff()) {
            $this->outlets()->sync([$outletId]);
        } else {
            $this->outlets()->syncWithoutDetaching([$outletId]);
        }
    }

    /**
     * Remove outlet from user
     */
    public function removeOutlet(int $outletId): void
    {
        $this->outlets()->detach($outletId);
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role->label();
    }

    /**
     * Get role description
     */
    public function getRoleDescriptionAttribute(): string
    {
        return $this->role->description();
    }
}
