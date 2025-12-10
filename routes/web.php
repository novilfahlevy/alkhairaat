<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
    Route::get('/', function () {
        return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
    })->name('dashboard');

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

// Super Admin routes - can access and manage everything
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Lembaga CRUD
    Route::resource('lembaga', App\Http\Controllers\LembagaController::class);
    Route::get('/lembaga/kabupaten/{provinsi_id}', [App\Http\Controllers\LembagaController::class, 'getKabupaten'])->name('lembaga.kabupaten');
    
    // User sekolah management
    Route::get('/users-sekolah', function () {
        return 'Manage User Sekolah';
    })->middleware('permission:manage_user_sekolah')->name('users-sekolah.index');
    
    // Reports and export
    Route::get('/reports', function () {
        return 'Admin Reports';
    })->middleware('permission:view_reports')->name('reports');
    
    Route::get('/export', function () {
        return 'Admin Export Data';
    })->middleware('permission:export_data')->name('export');
});

// Wilayah routes - can view reports, export data, and manage user sekolah
Route::middleware(['auth', 'role:wilayah,super_admin'])->prefix('wilayah')->name('wilayah.')->group(function () {
    // Reports (limited to their kabupaten)
    Route::get('/reports', function () {
        return 'Wilayah Reports';
    })->middleware('permission:view_reports')->name('reports');
    
    // Export data (limited to their kabupaten)
    Route::get('/export', function () {
        return 'Wilayah Export Data';
    })->middleware('permission:export_data')->name('export');
    
    // User sekolah management (in their kabupaten only)
    Route::get('/users-sekolah', function () {
        return 'Wilayah Manage User Sekolah';
    })->middleware('permission:manage_user_sekolah')->name('users-sekolah.index');
});

// Sekolah routes - can access and manage santri/alumni, view reports, export data
Route::middleware(['auth', 'role:sekolah,super_admin,wilayah'])->prefix('sekolah')->name('sekolah.')->group(function () {
    // Santri management
    Route::get('/santri', function () {
        return 'Sekolah Santri List';
    })->middleware('permission:access_santri')->name('santri.index');
    
    Route::post('/santri', function () {
        return 'Sekolah Create Santri';
    })->middleware('permission:manage_santri')->name('santri.store');
    
    // Alumni management
    Route::get('/alumni', function () {
        return 'Sekolah Alumni List';
    })->middleware('permission:access_alumni')->name('alumni.index');
    
    Route::post('/alumni', function () {
        return 'Sekolah Create Alumni';
    })->middleware('permission:manage_alumni')->name('alumni.store');
    
    // Reports (limited to their lembaga)
    Route::get('/reports', function () {
        return 'Sekolah Reports';
    })->middleware('permission:view_reports')->name('reports');
    
    // Export data (limited to their lembaga)
    Route::get('/export', function () {
        return 'Sekolah Export Data';
    })->middleware('permission:export_data')->name('export');
});

/*
|--------------------------------------------------------------------------
| Test Routes for Role and Permission System
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('test')->name('test.')->group(function () {
    // Test role and permission information
    Route::get('/roles', [App\Http\Controllers\TestController::class, 'testRoles'])->name('roles');
    
    // Test role-based access
    Route::get('/super-admin', [App\Http\Controllers\TestController::class, 'superAdminOnly'])
        ->middleware('role:super_admin')
        ->name('super-admin');
    
    Route::get('/sekolah', [App\Http\Controllers\TestController::class, 'sekolahOnly'])
        ->middleware('role:sekolah')
        ->name('sekolah');
    
    // Test new permission-based access
    Route::get('/access-lembaga', function () {
        return 'Can access lembaga data';
    })->middleware('permission:access_lembaga')->name('access-lembaga');
    
    Route::get('/manage-lembaga', function () {
        return 'Can manage lembaga data';
    })->middleware('permission:manage_lembaga')->name('manage-lembaga');
    
    Route::get('/access-santri', function () {
        return 'Can access santri data';
    })->middleware('permission:access_santri')->name('access-santri');
    
    Route::get('/manage-santri', function () {
        return 'Can manage santri data';
    })->middleware('permission:manage_santri')->name('manage-santri');
    
    Route::get('/access-alumni', function () {
        return 'Can access alumni data';
    })->middleware('permission:access_alumni')->name('access-alumni');
    
    Route::get('/manage-alumni', function () {
        return 'Can manage alumni data';
    })->middleware('permission:manage_alumni')->name('manage-alumni');
    
    Route::get('/view-reports', function () {
        return 'Can view reports';
    })->middleware('permission:view_reports')->name('view-reports');
    
    Route::get('/export-data', function () {
        return 'Can export data';
    })->middleware('permission:export_data')->name('export-data');
    
    Route::get('/manage-user-sekolah', function () {
        return 'Can manage user sekolah';
    })->middleware('permission:manage_user_sekolah')->name('manage-user-sekolah');
});

// error pages (public)
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');




















