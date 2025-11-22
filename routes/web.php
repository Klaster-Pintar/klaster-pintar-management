<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClusterController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Settings Routes
        Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');
        Route::post('/settings', [ProfileController::class, 'update'])->name('settings.update');
        Route::post('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/avatar', [ProfileController::class, 'uploadAvatar'])->name('settings.avatar');

        // User Management Routes
        Route::resource('users', UserController::class);

        // Cluster Management Routes
        Route::resource('clusters', ClusterController::class);
    });
});

// Fallback route for 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});