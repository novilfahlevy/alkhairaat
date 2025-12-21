<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $provinsiCount = Provinsi::count();
        $kabupatenCount = Kabupaten::count();
        $sekolahCount = Sekolah::count();

        return view('pages.dashboard', [
            'title' => 'Dasbor',
            'provinsiCount' => $provinsiCount,
            'kabupatenCount' => $kabupatenCount,
            'sekolahCount' => $sekolahCount,
            'sekolahCount' => $sekolahCount, // backward compatibility
        ]);
    }
}
