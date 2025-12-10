<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Role constants
     */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_WILAYAH = 'wilayah';
    public const ROLE_SEKOLAH = 'sekolah';

    /**
     * Available roles
     *
     * @var array<string>
     */
    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_WILAYAH,
        self::ROLE_SEKOLAH,
    ];

    /**
     * Permission constants
     */
    public const PERMISSION_VIEW_ALL_DATA = 'view_all_data';
    public const PERMISSION_MANAGE_SANTRI = 'manage_santri';
    public const PERMISSION_MANAGE_ALUMNI = 'manage_alumni';
    public const PERMISSION_MANAGE_LEMBAGA = 'manage_lembaga';
    public const PERMISSION_VIEW_REPORTS = 'view_reports';
    public const PERMISSION_EXPORT_DATA = 'export_data';

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
        'lembaga_id',
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
        ];
    }

    /**
     * Get the lembaga that the user belongs to.
     */
    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    /**
     * Check if user is super admin (PB Alkhairaat)
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    /**
     * Check if user is wilayah
     */
    public function isWilayah(): bool
    {
        return $this->hasRole(self::ROLE_WILAYAH);
    }

    /**
     * Check if user is sekolah
     */
    public function isSekolah(): bool
    {
        return $this->hasRole(self::ROLE_SEKOLAH);
    }

    /**
     * Check if user can access a specific lembaga
     */
    public function canAccessLembaga(int $lembagaId): bool
    {
        // Super admin can access all data (read-only)
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Wilayah can access data in their region (implemented later based on provinsi)
        if ($this->isWilayah()) {
            return true; // For now, allow all - can be refined later
        }

        // Sekolah can only access their own lembaga
        return $this->lembaga_id === $lembagaId;
    }

    /**
     * Check if user can modify data (not super_admin for certain operations)
     */
    public function canModifyLembagaData(): bool
    {
        // Super admin cannot add/modify/delete lembaga data, only view
        return !$this->isSuperAdmin() && $this->can(self::PERMISSION_MANAGE_LEMBAGA);
    }

    /**
     * Scope to filter users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->role($role);
    }

    /**
     * Scope to filter users by lembaga
     */
    public function scopeByLembaga($query, int $lembagaId)
    {
        return $query->where('lembaga_id', $lembagaId);
    }
}
