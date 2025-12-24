<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class NauanganSekolahScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * Filter sekolah berdasarkan hak akses user:
     * - Superuser & Pengurus Besar: akses semua sekolah
     * - User dengan mapping EditorList: hanya sekolah yang dimapping
     * - User tanpa mapping EditorList: akses semua sekolah
     * - User dengan role sekolah: hanya sekolah yang dimapping
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // Jika tidak ada user yang login, tidak ada data yang ditampilkan
        if (!$user) {
            $builder->whereNull('id');
            return;
        }

        // Superuser dan Pengurus Besar dapat melihat semua sekolah
        if ($user->isSuperuser() || $user->isPengurusBesar()) {
            return;
        }

        // User dengan role sekolah hanya bisa melihat sekolah yang dimapping
        if ($user->isSekolah()) {
            $builder->whereHas('editorLists', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
            return;
        }

        // User lainnya (Komisariat Wilayah, Komisariat Daerah)
        // Cek apakah ada mapping di EditorList
        $hasMapping = $user->editorLists()->exists();

        if ($hasMapping) {
            // Jika ada mapping, hanya tampilkan sekolah yang dimapping
            $builder->whereHas('editorLists', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
        }
        // Jika tidak ada mapping, tampilkan semua sekolah (scope tidak filter apapun)
    }
}
