<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * SECURITY NOTE:
     * - `role` is DELIBERATELY NOT in fillable to prevent privilege escalation
     *   via mass assignment. Set explicitly: $user->role = $validated['role'];
     * - `email_verified_at` is set via Laravel's email verification, not user input
     * - `is_active` is safe to fill because activation status is not privilege-sensitive
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    // ====================================================================
    // RELATIONS
    // ====================================================================

    /**
     * Commodities yang dibuat oleh user ini.
     */
    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'created_by');
    }

    /**
     * Transfers yang diajukan oleh user ini.
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'requested_by');
    }

    /**
     * Disposals yang diajukan oleh user ini.
     */
    public function disposals(): HasMany
    {
        return $this->hasMany(Disposal::class, 'requested_by');
    }

    /**
     * Activity logs oleh user ini.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ====================================================================
    // SCOPES
    // ====================================================================

    /**
     * Scope untuk user aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ====================================================================
    // ROLE HELPERS
    // ====================================================================

    /**
     * Check apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === \App\Enums\Role::ADMIN->value;
    }

    /**
     * Check apakah user adalah staff.
     */
    public function isStaff(): bool
    {
        return $this->role === \App\Enums\Role::STAFF->value;
    }

    /**
     * Check apakah user adalah user biasa.
     */
    public function isUser(): bool
    {
        return $this->role === \App\Enums\Role::USER->value;
    }

    /**
     * Check apakah user bisa menambah admin lain.
     * Hanya admin yang bisa promote user lain menjadi admin.
     */
    public function canAddAdmin(): bool
    {
        return $this->isAdmin();
    }

    // ====================================================================
    // ACCESSORS
    // ====================================================================

    /**
     * Get avatar URL atau default UI Avatars.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff';
    }
}