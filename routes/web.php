<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\UiElementController;
use App\Http\Controllers\ChannelDownloaderController;
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
    Route::get('/ui-elements', [UiElementController::class, 'index'])->name('ui-elements');
    Route::post('/ui-elements', [UiElementController::class, 'store'])->name('ui-elements.store');
    Route::delete('/ui-elements/{id}', [UiElementController::class, 'destroy'])->name('ui-elements.destroy');

    // Channel Downloader
    Route::get('/channel-downloader', [ChannelDownloaderController::class, 'index'])->name('channel-downloader.index');
    Route::post('/channel-downloader', [ChannelDownloaderController::class, 'store'])->name('channel-downloader.store');
    Route::get('/channel-downloader/{channel}', [ChannelDownloaderController::class, 'show'])->name('channel-downloader.show');
    Route::post('/channel-downloader/{channel}/retry', [ChannelDownloaderController::class, 'retry'])->name('channel-downloader.retry');
    Route::get('/videos/{video}/download-subtitle', [ChannelDownloaderController::class, 'downloadSubtitle'])->name('videos.download-subtitle');
});

// Serve Dynamic CSS for UI Elements (Publicly accessible)
Route::get('/css/custom-ui.css', [UiElementController::class, 'customCss'])->name('custom-ui.css');
