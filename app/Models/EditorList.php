<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditorList extends Model
{
    protected $table = 'editor_lists';

    protected $fillable = [
        'id_user',
        'id_sekolah',
    ];

    /**
     * Get the user that owns the editor list.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the sekolah associated with the editor list.
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
