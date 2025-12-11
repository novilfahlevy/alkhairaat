<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Lembaga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $provinsiCount = Provinsi::count();
        $kabupatenCount = Kabupaten::count();
        $lembagaCount = Lembaga::count();

        return view('pages.dashboard', [
            'title' => 'E-commerce Dashboard',
            'provinsiCount' => $provinsiCount,
            'kabupatenCount' => $kabupatenCount,
            'lembagaCount' => $lembagaCount,
        ]);
    }
}
