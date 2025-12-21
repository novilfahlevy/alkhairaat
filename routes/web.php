<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;

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
| Protected Routes (require authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // dashboard pages
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // calender pages
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    // profile pages
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    // form pages
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    // tables pages
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    // pages
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // chart pages
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    // ui elements pages
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
| Role-based Routes 
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Provinsi CRUD with specific permissions
    Route::get('/provinsi', [App\Http\Controllers\ProvinsiController::class, 'index'])->name('provinsi.index');
    Route::get('/provinsi/create', [App\Http\Controllers\ProvinsiController::class, 'create'])->name('provinsi.create');
    Route::post('/provinsi', [App\Http\Controllers\ProvinsiController::class, 'store'])->name('provinsi.store');
    Route::get('/provinsi/{provinsi}', [App\Http\Controllers\ProvinsiController::class, 'show'])->name('provinsi.show');
    Route::get('/provinsi/{provinsi}/edit', [App\Http\Controllers\ProvinsiController::class, 'edit'])->name('provinsi.edit');
    Route::put('/provinsi/{provinsi}', [App\Http\Controllers\ProvinsiController::class, 'update'])->name('provinsi.update');
    Route::delete('/provinsi/{provinsi}', [App\Http\Controllers\ProvinsiController::class, 'destroy'])->name('provinsi.destroy');
    
    // Kabupaten CRUD with specific permissions
    Route::get('/kabupaten', [App\Http\Controllers\KabupatenController::class, 'index'])->name('kabupaten.index');
    Route::get('/kabupaten/create', [App\Http\Controllers\KabupatenController::class, 'create'])->name('kabupaten.create');
    Route::post('/kabupaten', [App\Http\Controllers\KabupatenController::class, 'store'])->name('kabupaten.store');
    Route::get('/kabupaten/{kabupaten}', [App\Http\Controllers\KabupatenController::class, 'show'])->name('kabupaten.show');
    Route::get('/kabupaten/{kabupaten}/edit', [App\Http\Controllers\KabupatenController::class, 'edit'])->name('kabupaten.edit');
    Route::put('/kabupaten/{kabupaten}', [App\Http\Controllers\KabupatenController::class, 'update'])->name('kabupaten.update');
    Route::delete('/kabupaten/{kabupaten}', [App\Http\Controllers\KabupatenController::class, 'destroy'])->name('kabupaten.destroy');
    
    // Sekolah CRUD
    Route::get('/sekolah', [App\Http\Controllers\SekolahController::class, 'index'])->name('sekolah.index');
    Route::get('/sekolah/create', [App\Http\Controllers\SekolahController::class, 'create'])->name('sekolah.create');
    Route::post('/sekolah', [App\Http\Controllers\SekolahController::class, 'store'])->name('sekolah.store');
    Route::get('/sekolah/kabupaten', [App\Http\Controllers\SekolahController::class, 'getKabupaten'])->name('sekolah.get_kabupaten');
    Route::get('/sekolah/{sekolah}', [App\Http\Controllers\SekolahController::class, 'show'])->name('sekolah.show');
    Route::get('/sekolah/{sekolah}/edit', [App\Http\Controllers\SekolahController::class, 'edit'])->name('sekolah.edit');
    Route::put('/sekolah/{sekolah}', [App\Http\Controllers\SekolahController::class, 'update'])->name('sekolah.update');
    Route::delete('/sekolah/{sekolah}', [App\Http\Controllers\SekolahController::class, 'destroy'])->name('sekolah.destroy');
    
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

    // Manajemen Guru (oleh Komda)
    Route::middleware('role:'.User::ROLE_KOMISARIAT_DAERAH)->group(function () {
        Route::resource('manajemen/guru', App\Http\Controllers\GuruController::class, [
            'as' => 'manajemen'
        ]);
    });
    
    // Reports and export
    Route::get('/reports', function () {
        return 'Admin Reports';
    })->name('reports');
    
    Route::get('/export', function () {
        return 'Admin Export Data';
    })->name('export');
});

// error pages (public)
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');




















