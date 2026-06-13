<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GuruSekolahQueryFilters
{
    /**
     * Apply search across columns shown in the guru list table.
     */
    public static function applySearch(Builder|BelongsToMany $query, ?string $search): Builder|BelongsToMany
    {
        if (blank($search)) {
            return $query;
        }

        $like = '%' . $search . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('guru.nama', 'like', $like)
                ->orWhere('guru.nik', 'like', $like)
                ->orWhere('guru.status_kepegawaian', 'like', $like)
                ->orWhere('guru.npk', 'like', $like)
                ->orWhere('guru.nuptk', 'like', $like)
                ->orWhere('guru.kontak_wa_hp', 'like', $like)
                ->orWhere('jabatan_guru.jenis_jabatan', 'like', $like)
                ->orWhere('jabatan_guru.keterangan_jabatan', 'like', $like);
        });
    }
}
