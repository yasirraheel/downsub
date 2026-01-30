<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SystemLogController;
use App\Http\Middleware\IsAdmin;

// Redirect root to admin login or dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Authentication
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware([IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.updateGeneral');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');

    Route::get('/system-logs', [SystemLogController::class, 'index'])->name('system-logs');
    Route::post('/system-logs/clear', [SystemLogController::class, 'clear'])->name('system-logs.clear');

    // UI Elements
    Route::get('/ui-elements', function () {
        return view('admin.ui-elements');
    })->name('ui-elements');
});
