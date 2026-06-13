<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class MuridSekolahQueryFilters
{
    /**
     * Apply search across columns shown in the murid list table.
     */
    public static function applySearch(Builder|BelongsToMany $query, ?string $search): Builder|BelongsToMany
    {
        if (blank($search)) {
            return $query;
        }

        $like = '%' . $search . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('murid.nama', 'like', $like)
                ->orWhere('murid.nisn', 'like', $like)
                ->orWhere('murid.nik', 'like', $like)
                ->orWhere('murid.jenis_kelamin', 'like', $like)
                ->orWhere('sekolah_murid.tahun_masuk', 'like', $like)
                ->orWhere('sekolah_murid.kelas', 'like', $like)
                ->orWhere('sekolah_murid.status_kelulusan', 'like', $like)
                ->orWhereRaw(
                    "CASE murid.jenis_kelamin WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' ELSE murid.jenis_kelamin END LIKE ?",
                    [$like]
                )
                ->orWhereRaw(
                    "CASE sekolah_murid.status_kelulusan WHEN 'ya' THEN 'Lulus' WHEN 'tidak' THEN 'Tidak Lulus' ELSE 'Belum Lulus' END LIKE ?",
                    [$like]
                );
        });
    }

    /**
     * Apply dropdown filters (jenis kelamin, status kelulusan, tahun masuk).
     */
    public static function applyFilters(Builder|BelongsToMany $query, Request $request, bool $usesPivot = false): Builder|BelongsToMany
    {
        if ($request->filled('jenis_kelamin')) {
            $query->where('murid.jenis_kelamin', $request->input('jenis_kelamin'));
        }

        if ($request->filled('status_kelulusan')) {
            $status = $request->input('status_kelulusan');

            if ($status === 'belum') {
                if ($usesPivot) {
                    $query->wherePivotNull('status_kelulusan');
                } else {
                    $query->whereNull('sekolah_murid.status_kelulusan');
                }
            } elseif ($usesPivot) {
                $query->wherePivot('status_kelulusan', $status);
            } else {
                $query->where('sekolah_murid.status_kelulusan', $status);
            }
        }

        if ($request->filled('tahun_masuk')) {
            if ($usesPivot) {
                $query->wherePivot('tahun_masuk', $request->input('tahun_masuk'));
            } else {
                $query->where('sekolah_murid.tahun_masuk', $request->input('tahun_masuk'));
            }
        }

        return $query;
    }
}
