<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public const ROLE_SUPERUSER = 'superuser';
    public const ROLE_PENGURUS_BESAR = 'pengurus_besar';
    public const ROLE_KOMISARIAT_WILAYAH = 'komisariat_wilayah';
    public const ROLE_GURU = 'guru';

    /**
     * Available roles
     *
     * @var array<string>
     */
    public const ROLES = [
        self::ROLE_SUPERUSER,
        self::ROLE_PENGURUS_BESAR,
        self::ROLE_KOMISARIAT_WILAYAH,
        self::ROLE_GURU,
    ];

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
        'sekolah_id',
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
     * Get the sekolah that the user belongs to.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    /**
     * Get the kabupaten that the user (wilayah) manages.
     */
    public function kabupaten(): BelongsToMany
    {
        return $this->belongsToMany(Kabupaten::class, 'user_kabupaten');
    }

    /**
     * Check if user is superuser
     */
    public function isSuperuser(): bool
    {
        return $this->hasRole(self::ROLE_SUPERUSER);
    }

    /**
     * Check if user is pengurus besar
     */
    public function isPengurusBesar(): bool
    {
        return $this->hasRole(self::ROLE_PENGURUS_BESAR);
    }

    /**
     * Check if user is komisariat wilayah
     */
    public function isKomisariatWilayah(): bool
    {
        return $this->hasRole(self::ROLE_KOMISARIAT_WILAYAH);
    }

    /**
     * Check if user is guru
     */
    public function isGuru(): bool
    {
        return $this->hasRole(self::ROLE_GURU);
    }

    /**
     * Check if user can access a specific sekolah
     */
    public function canAccessSekolah(int $sekolahId): bool
    {
        // Superuser can access all data
        if ($this->isSuperuser()) {
            return true;
        }

        // Pengurus besar can access all data
        if ($this->isPengurusBesar()) {
            return true;
        }

        // Komisariat wilayah can access data in their managed kabupaten
        if ($this->isKomisariatWilayah()) {
            $sekolah = Sekolah::find($sekolahId);
            if (!$sekolah) {
                return false;
            }
            
            // Check if user manages the kabupaten where this sekolah is located
            return $this->kabupaten()->where('kabupaten.id', $sekolah->kabupaten_id)->exists();
        }

        // Guru can only access their own sekolah
        return $this->sekolah_id === $sekolahId;
    }

    /**
     * Scope to filter users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->role($role);
    }

    /**
     * Scope to filter users by sekolah
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->where('sekolah_id', $sekolahId);
    }
}
