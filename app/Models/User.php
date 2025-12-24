<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EditorList> $editorLists
 * @property-read int|null $editor_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kabupaten> $kabupaten
 * @property-read int|null $kabupaten_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole(string $role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
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
    public const ROLE_KOMISARIAT_DAERAH = 'komisariat_daerah';
    public const ROLE_SEKOLAH = 'sekolah';

    /**
     * Available roles
     *
     * @var array<string>
     */
    public const ROLES = [
        self::ROLE_SUPERUSER,
        self::ROLE_PENGURUS_BESAR,
        self::ROLE_KOMISARIAT_WILAYAH,
        self::ROLE_KOMISARIAT_DAERAH,
        self::ROLE_SEKOLAH,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password'
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
     * Get the kabupaten that the user manages.
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
     * Check if user is komisariat daerah
     */
    public function isKomisariatDaerah(): bool
    {
        return $this->hasRole(self::ROLE_KOMISARIAT_DAERAH);
    }

    /**
     * Check if user is sekolah
     */
    public function isSekolah(): bool
    {
        return $this->hasRole(self::ROLE_SEKOLAH);
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
            return $this->kabupaten()->where('kabupaten.id', $sekolah->id_kabupaten)->exists();
        }

        // Komisariat daerah can access data in their managed kabupaten
        if ($this->isKomisariatDaerah()) {
            $sekolah = Sekolah::find($sekolahId);
            if (!$sekolah) {
                return false;
            }
            
            // Check if user manages the kabupaten where this sekolah is located
            return $this->kabupaten()->where('kabupaten.id', $sekolah->id_kabupaten)->exists();
        }

        // Akun sekolah has no direct access via this method
        return false;
    }

    /**
     * Scope to filter users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->role($role);
    }

    /**
     * Get the editor lists associated with the user.
     */
    public function editorLists()
    {
        return $this->hasMany(EditorList::class, 'id_user');
    }

    /**
     * Get the first role assigned to the user.
     */
    public function getFirstRole(): ?string
    {
        $roles = $this->getRoleNames();
        return $roles->first() ?: null;
    }
}
