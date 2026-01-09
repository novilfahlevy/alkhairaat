<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MuridController;
use App\Models\User;
use App\Models\Murid;

// API public: cek NIK murid (untuk validasi alumni, tanpa auth)
Route::get('/api/cari-nik-murid', [\App\Http\Controllers\ValidasiAlumniController::class, 'cariNik'])->name('api.cari-nik-murid');

// Validasi Alumni (public, tanpa login)
Route::get('/validasi-alumni', [\App\Http\Controllers\ValidasiAlumniController::class, 'form'])->name('validasi-alumni.form');
Route::post('/validasi-alumni', [\App\Http\Controllers\ValidasiAlumniController::class, 'store'])->name('validasi-alumni.store');

// Validasi Alumni Management (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/manajemen/validasi-alumni', [\App\Http\Controllers\ValidasiAlumniController::class, 'index'])->name('validasi-alumni.index');
    Route::post('/manajemen/validasi-alumni/{validasi}/approve', [\App\Http\Controllers\ValidasiAlumniController::class, 'approve'])->name('validasi-alumni.approve');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout route (only accessible when logged in)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Main Application Routes (require authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    // Calendar
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    // Form Elements
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    // Tables
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    // Blank Page
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // Charts
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    // UI Elements
    Route::get('/alerts', function () {
        return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
    })->name('alerts');

    Route::get('/avatars', function () {
        return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
    })->name('avatars');

    Route::get('/badge', function () {
        return view('pages.ui-elements.badges', ['title' => 'Badges']);
    })->name('badges');

    Route::get('/buttons', function () {
        return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
    })->name('buttons');

    Route::get('/image', function () {
        return view('pages.ui-elements.images', ['title' => 'Images']);
    })->name('images');

    Route::get('/videos', function () {
        return view('pages.ui-elements.videos', ['title' => 'Videos']);
    })->name('videos');
});

/*
|--------------------------------------------------------------------------
| Master Data Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Provinsi
    Route::get('/provinsi', [App\Http\Controllers\ProvinsiController::class, 'index'])->name('provinsi.index');
    Route::get('/provinsi/create', [App\Http\Controllers\ProvinsiController::class, 'create'])->name('provinsi.create');
    Route::post('/provinsi', [App\Http\Controllers\ProvinsiController::class, 'store'])->name('provinsi.store');
    Route::get('/provinsi/{provinsi}', [App\Http\Controllers\ProvinsiController::class, 'show'])->name('provinsi.show');
    Route::get('/provinsi/{provinsi}/edit', [App\Http\Controllers\ProvinsiController::class, 'edit'])->name('provinsi.edit');
    Route::put('/provinsi/{provinsi}', [App\Http\Controllers\ProvinsiController::class, 'update'])->name('provinsi.update');
    Route::delete('/provinsi/{provinsi}', [App\Http\Controllers\ProvinsiController::class, 'destroy'])->name('provinsi.destroy');
    
    // Kabupaten
    Route::get('/kabupaten', [App\Http\Controllers\KabupatenController::class, 'index'])->name('kabupaten.index');
    Route::get('/kabupaten/create', [App\Http\Controllers\KabupatenController::class, 'create'])->name('kabupaten.create');
    Route::post('/kabupaten', [App\Http\Controllers\KabupatenController::class, 'store'])->name('kabupaten.store');
    Route::get('/kabupaten/{kabupaten}', [App\Http\Controllers\KabupatenController::class, 'show'])->name('kabupaten.show');
    Route::get('/kabupaten/{kabupaten}/edit', [App\Http\Controllers\KabupatenController::class, 'edit'])->name('kabupaten.edit');
    Route::put('/kabupaten/{kabupaten}', [App\Http\Controllers\KabupatenController::class, 'update'])->name('kabupaten.update');
    Route::delete('/kabupaten/{kabupaten}', [App\Http\Controllers\KabupatenController::class, 'destroy'])->name('kabupaten.destroy');
});

/*
|--------------------------------------------------------------------------
| Sekolah Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Sekolah CRUD
    Route::post('/sekolah/check-kode', [App\Http\Controllers\SekolahController::class, 'checkKodeSekolah'])->name('sekolah.check-kode');
    Route::get('/sekolah', [App\Http\Controllers\SekolahController::class, 'index'])->name('sekolah.index');
    Route::get('/sekolah/create', [App\Http\Controllers\SekolahController::class, 'create'])->name('sekolah.create');
    Route::post('/sekolah', [App\Http\Controllers\SekolahController::class, 'store'])->name('sekolah.store');
    Route::get('/sekolah/kabupaten', [App\Http\Controllers\SekolahController::class, 'getKabupaten'])->name('sekolah.get_kabupaten');
    Route::get('/sekolah/{sekolah}', [App\Http\Controllers\SekolahController::class, 'show'])->name('sekolah.show');
    Route::get('/sekolah/{sekolah}/murid', [App\Http\Controllers\SekolahController::class, 'showMurid'])->name('sekolah.show-murid');
    Route::get('/sekolah/{sekolah}/guru', [App\Http\Controllers\SekolahController::class, 'showGuru'])->name('sekolah.show-guru');
    Route::get('/sekolah/{sekolah}/edit', [App\Http\Controllers\SekolahController::class, 'edit'])->name('sekolah.edit');
    Route::put('/sekolah/{sekolah}', [App\Http\Controllers\SekolahController::class, 'update'])->name('sekolah.update');
    Route::delete('/sekolah/{sekolah}', [App\Http\Controllers\SekolahController::class, 'destroy'])->name('sekolah.destroy');

    // Murid Management
    Route::get('/sekolah/{sekolah}/tambah-murid', [App\Http\Controllers\SekolahController::class, 'createMurid'])->name('sekolah.create-murid');
    Route::post('/sekolah/{sekolah}/tambah-murid', [App\Http\Controllers\SekolahController::class, 'storeMurid'])->name('sekolah.store-murid');
    Route::post('/sekolah/{sekolah}/check-nisn', [App\Http\Controllers\SekolahController::class, 'checkNisn'])->name('sekolah.check-nisn');
    Route::get('/sekolah/{sekolah}/get-existing-murid', [App\Http\Controllers\SekolahController::class, 'getExistingMurid'])->name('sekolah.get-existing-murid');
    Route::post('/sekolah/{sekolah}/store-existing-murid', [App\Http\Controllers\SekolahController::class, 'storeExistingMurid'])->name('sekolah.store-existing-murid');
    Route::post('/sekolah/{sekolah}/tambah-murid-file', [App\Http\Controllers\SekolahController::class, 'storeMuridFile'])->name('sekolah.store-murid-file');
    Route::get('/sekolah/template/download', [App\Http\Controllers\SekolahController::class, 'downloadTemplate'])->name('sekolah.download-template');
    Route::get('/sekolah/{sekolah}/murid/{murid}', [App\Http\Controllers\SekolahController::class, 'showDetailMurid'])->name('sekolah.show-detail-murid');
    Route::get('/sekolah/{sekolah}/murid/{murid}/edit', [App\Http\Controllers\SekolahController::class, 'editMurid'])->name('sekolah.edit-murid');
    Route::put('/sekolah/{sekolah}/murid/{murid}', [App\Http\Controllers\SekolahController::class, 'updateMurid'])->name('sekolah.update-murid');
    Route::delete('/sekolah/{sekolah}/murid/{murid}', [App\Http\Controllers\SekolahController::class, 'deleteMurid'])->name('sekolah.delete-murid');
    
    // Guru Management
    Route::get('/sekolah/{sekolah}/tambah-guru', [App\Http\Controllers\SekolahController::class, 'createGuru'])->name('sekolah.create-guru');
    Route::post('/sekolah/check-nik-guru', [App\Http\Controllers\SekolahController::class, 'checkNikGuru'])->name('sekolah.check-nik-guru');
    Route::post('/sekolah/{sekolah}/tambah-guru', [App\Http\Controllers\SekolahController::class, 'storeGuru'])->name('sekolah.store-guru');
    Route::get('/sekolah/{sekolah}/get-existing-guru', [App\Http\Controllers\SekolahController::class, 'getExistingGuru'])->name('sekolah.get-existing-guru');
    Route::post('/sekolah/{sekolah}/store-existing-guru', [App\Http\Controllers\SekolahController::class, 'storeExistingGuru'])->name('sekolah.store-existing-guru');
    Route::post('/sekolah/{sekolah}/tambah-guru-file', [App\Http\Controllers\SekolahController::class, 'storeGuruFile'])->name('sekolah.store-guru-file');
    Route::get('/sekolah/template-guru/download', [App\Http\Controllers\SekolahController::class, 'downloadGuruTemplate'])->name('sekolah.download-guru-template');
    Route::get('/sekolah/{sekolah}/guru/{guru}', [App\Http\Controllers\SekolahController::class, 'showDetailGuru'])->name('sekolah.show-detail-guru');
    Route::get('/sekolah/{sekolah}/guru/{guru}/edit', [App\Http\Controllers\SekolahController::class, 'editGuru'])->name('sekolah.edit-guru');
    Route::put('/sekolah/{sekolah}/guru/{guru}', [App\Http\Controllers\SekolahController::class, 'updateGuru'])->name('sekolah.update-guru');
    Route::delete('/sekolah/{sekolah}/guru/{guru}', [App\Http\Controllers\SekolahController::class, 'deleteGuru'])->name('sekolah.delete-guru');
    
    // Guru Jabatan Management
    Route::post('/sekolah/{sekolah}/guru/{guru}/jabatan', [App\Http\Controllers\SekolahController::class, 'addJabatanGuru'])->name('sekolah.add-jabatan-guru');
    Route::delete('/sekolah/{sekolah}/jabatan-guru/{jabatanGuru}', [App\Http\Controllers\SekolahController::class, 'deleteJabatanGuru'])->name('sekolah.delete-jabatan-guru');
});

Route::middleware(['auth'])->group(function () {
    // Guru CRUD
    Route::resource('guru', App\Http\Controllers\GuruController::class);
});

/*
|--------------------------------------------------------------------------
| External School Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Sekolah External CRUD
    Route::get('/sekolah-external', [App\Http\Controllers\SekolahExternalController::class, 'index'])->name('sekolah-external.index');
    Route::get('/sekolah-external/create', [App\Http\Controllers\SekolahExternalController::class, 'create'])->name('sekolah-external.create');
    Route::post('/sekolah-external', [App\Http\Controllers\SekolahExternalController::class, 'store'])->name('sekolah-external.store');
    Route::get('/sekolah-external/{sekolahExternal}', [App\Http\Controllers\SekolahExternalController::class, 'show'])->name('sekolah-external.show');
    Route::get('/sekolah-external/{sekolahExternal}/edit', [App\Http\Controllers\SekolahExternalController::class, 'edit'])->name('sekolah-external.edit');
    Route::put('/sekolah-external/{sekolahExternal}', [App\Http\Controllers\SekolahExternalController::class, 'update'])->name('sekolah-external.update');
    Route::delete('/sekolah-external/{sekolahExternal}', [App\Http\Controllers\SekolahExternalController::class, 'destroy'])->name('sekolah-external.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('murid', MuridController::class);
});

/*
|--------------------------------------------------------------------------
| Alumni Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Alumni CRUD
    Route::get('/alumni', [App\Http\Controllers\AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/create', [App\Http\Controllers\AlumniController::class, 'create'])->name('alumni.create');
    Route::post('/alumni', [App\Http\Controllers\AlumniController::class, 'store'])->name('alumni.store');
    Route::post('/alumni/import-file', [App\Http\Controllers\AlumniController::class, 'storeFile'])->name('alumni.storeFile');
    Route::get('/alumni/template/download', [App\Http\Controllers\AlumniController::class, 'downloadTemplate'])->name('alumni.download-template');
    Route::get('/alumni/{alumni}', [App\Http\Controllers\AlumniController::class, 'show'])->name('alumni.show');
    Route::get('/alumni/{alumni}/edit', [App\Http\Controllers\AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}', [App\Http\Controllers\AlumniController::class, 'update'])->name('alumni.update');
    Route::delete('/alumni/{alumni}', [App\Http\Controllers\AlumniController::class, 'destroy'])->name('alumni.destroy');
});



/*
|--------------------------------------------------------------------------
| Role-based Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // User management (superuser only)
    Route::middleware('role:'.User::ROLE_SUPERUSER)->group(function () {
        Route::resource('user', App\Http\Controllers\UserController::class);
    });

    // Manajemen Komwil (oleh Pengurus Besar)
    Route::middleware('role:'.User::ROLE_PENGURUS_BESAR)->group(function () {
        Route::resource('manajemen/komwil', App\Http\Controllers\KomwilController::class, [
            'as' => 'manajemen'
        ]);
    });

    // Manajemen Komda (oleh Komwil)
    Route::middleware('role:'.User::ROLE_KOMISARIAT_WILAYAH)->group(function () {
        Route::resource('manajemen/komda', App\Http\Controllers\KomdaController::class, [
            'as' => 'manajemen'
        ]);
    });

    // Manajemen Akun Sekolah (oleh Komda)
    Route::middleware('role:'.User::ROLE_KOMISARIAT_DAERAH)->group(function () {
        Route::resource('manajemen/akun-sekolah', App\Http\Controllers\AkunSekolahController::class, [
            'as' => 'manajemen'
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Reports and Export Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/reports', function () {
        return 'Admin Reports';
    })->name('reports');
    
    Route::get('/export', function () {
        return 'Admin Export Data';
    })->name('export');
});

/*
|--------------------------------------------------------------------------
| Error Pages (public)
|--------------------------------------------------------------------------
*/

Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');