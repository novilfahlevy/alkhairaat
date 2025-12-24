<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $id_user
 * @property int $id_sekolah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sekolah $sekolah
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EditorList extends Model
{
    protected $table = 'editor_lists';

    protected $fillable = [
        'id_user',
        'id_sekolah',
    ];

    /**
     * 
     * Menentukan user mana yang memiliki hak akses naungan terhadap sekolah.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * 
     * Menentukan sekolah mana yang dinaungi oleh user.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
