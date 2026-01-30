<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AutoReplyController;

use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\IsAdmin;

// Redirect root to admin login or dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Authentication
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

use App\Http\Controllers\AdminWhatsAppController;

// Admin Protected Routes
Route::middleware([IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // WhatsApp Accounts
    Route::get('/wa-accounts', [AdminWhatsAppController::class, 'index'])->name('wa-accounts');
    Route::post('/wa-accounts', [AdminWhatsAppController::class, 'store'])->name('wa-accounts.store');
    Route::delete('/wa-accounts/{id}', [AdminWhatsAppController::class, 'destroy'])->name('wa-accounts.destroy');

    // Proxy for QR Code (Client-side polling)
    Route::get('/api/qr/{sessionId}', [AdminWhatsAppController::class, 'getQr'])->name('api.qr');

    // Placeholders for other admin pages
    Route::get('/bots', function () { return view('admin.bots'); })->name('bots');
    Route::get('/campaigns', function () { return view('admin.campaigns'); })->name('campaigns');
    Route::get('/messages', function () { return view('admin.messages'); })->name('messages');
    Route::get('/templates', function () { return view('admin.templates'); })->name('templates');
    Route::get('/webhooks', function () { return view('admin.webhooks'); })->name('webhooks');
    Route::get('/system-logs', function () { return view('admin.system-logs'); })->name('system-logs');
    Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
});

// Old Routes (Keep for reference or refactor into Admin Controllers later)
// Route::get('/', [WhatsAppController::class, 'index'])->name('dashboard'); // Replaced

Route::get('/status', [WhatsAppController::class, 'getStatus'])->name('whatsapp.status');
Route::get('/qr', [WhatsAppController::class, 'getQr'])->name('whatsapp.qr');
Route::post('/logout', [WhatsAppController::class, 'logout'])->name('whatsapp.logout');

Route::get('/message', [WhatsAppController::class, 'showSendMessage'])->name('message.create');
Route::post('/message', [WhatsAppController::class, 'sendMessage'])->name('message.send');

Route::resource('campaigns', CampaignController::class);
Route::resource('autoreplies', AutoReplyController::class)->only(['index', 'store', 'destroy']);
