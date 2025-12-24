<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class MuridNauanganScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * Filter murid berdasarkan sekolah yang dinaungi user:
     * - Superuser & Pengurus Besar: akses semua murid
     * - User dengan role sekolah: hanya murid di sekolah yang dimapping
     * - User dengan mapping EditorList: hanya murid di sekolah yang dimapping
     * - User tanpa mapping EditorList: akses semua murid
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // Jika tidak ada user yang login, tidak ada data yang ditampilkan
        if (!$user) {
            $builder->whereNull('id');
            return;
        }

        // Superuser dan Pengurus Besar dapat melihat semua murid
        if ($user->isSuperuser() || $user->isPengurusBesar()) {
            return;
        }

        // User dengan role sekolah hanya bisa melihat murid di sekolah yang dimapping
        if ($user->isSekolah()) {
            $builder->whereHas('sekolahMurid.sekolah.editorLists', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
            return;
        }

        // User lainnya (Komisariat Wilayah, Komisariat Daerah)
        // Cek apakah ada mapping di EditorList
        $hasMapping = $user->editorLists()->exists();

        if ($hasMapping) {
            // Jika ada mapping, hanya tampilkan murid dari sekolah yang dimapping
            $builder->whereHas('sekolahMurid.sekolah.editorLists', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
        }
        // Jika tidak ada mapping, tampilkan semua murid (scope tidak filter apapun)
    }
}
