<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ListUserController;

/*
|--------------------------------------------------------------------------
| 1. GUEST ROUTES (Hanya bisa diakses kalau BELUM login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| 2. PROTECTED ROUTES (Hanya bisa diakses kalau SUDAH login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Home)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/status', [DashboardController::class, 'status'])->name('status');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/history', [DashboardController::class, 'history'])->name('history');

    // Admin Area (Prefix: /admin/...)
    Route::prefix('admin')->name('admin.')->group(function () {

        // 1. FITUR Trash dan Restore (Soft Delete)
        Route::get('/activities/trash', [ActivityController::class, 'trash'])->name('activities.trash');
        Route::post('/activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');
        Route::delete('/activities/{id}/force-delete', [ActivityController::class, 'forceDelete'])->name('activities.forceDelete');

        // 2. KELOLA CRUD Utama (Secara Lengkap)
        Route::resource('activities', ActivityController::class);

        // ==========================================
        // MODULE 2: DATA LIST USER (PLAYER)
        // ==========================================
        // Route Trash/Banned (Wajib ditaruh ATAS sebelum Resource)
        Route::get('/list-users/trash', [ListUserController::class, 'trash'])->name('list_users.trash');

        // Route Restore (Un-banned User)
        Route::post('/list-users/{id}/restore', [ListUserController::class, 'restore'])->name('list_users.restore');

        // Route Utama (Index & Destroy/Banned)
        Route::resource('list_users', ListUserController::class)->only(['index', 'destroy']);
    });

    // Member Area (Monitoring)
    Route::middleware('role:member')->group(function () {
        // Kita buat group prefix 'monitor' dan name 'monitor.'
        Route::prefix('monitor')->name('monitor.')->group(function () {

            // Pastikan nama routenya adalah 'logs' sehingga menjadi 'monitor.logs'
            Route::get('/logs', [App\Http\Controllers\MonitorController::class, 'index'])->name('logs');

        });
    });

});