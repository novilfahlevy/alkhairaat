<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class GuruSekolahNauanganScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * Filter guru berdasarkan sekolah yang dinaungi user:
     * - Superuser & Pengurus Besar: akses semua guru
     * - User dengan mapping EditorList: hanya guru di sekolah yang dimapping
     * - User tanpa mapping EditorList: akses semua guru
     * - User dengan role sekolah: hanya guru di sekolah yang dimapping
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // Jika tidak ada user yang login, tidak ada data yang ditampilkan
        if (!$user) {
            $builder->whereNull('id');
            return;
        }

        // Superuser dan Pengurus Besar dapat melihat semua guru
        if ($user->isSuperuser() || $user->isPengurusBesar()) {
            return;
        }

        // User dengan role sekolah hanya bisa melihat guru di sekolah yang dimapping
        if ($user->isSekolah()) {
            $builder->whereHas('jabatanGuru.sekolah.editorLists', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
            return;
        }

        // User lainnya (Komisariat Wilayah, Komisariat Daerah)
        // Cek apakah ada mapping di EditorList
        $hasMapping = $user->editorLists()->exists();

        if ($hasMapping) {
            // Jika ada mapping, hanya tampilkan guru yang mengajar di sekolah yang dimapping
            $builder->whereHas('jabatanGuru.sekolah.editorLists', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
        }
        // Jika tidak ada mapping, tampilkan semua guru (scope tidak filter apapun)
    }
}
