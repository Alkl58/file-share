<?php

use App\Http\Controllers\ShareController;
use App\Http\Controllers\DownloadController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('admin', function () {
        return view('admin');
    })->name('admin.index');
});


//Download Route
Route::get('/download/{filePathHash}/{fileNameHash}', [DownloadController::class, 'download'])->name('download.file');
Route::get('/share/{uuid}', [ShareController::class, 'index'])->name('share.index');
Route::get('/share-download/{uuid}', [DownloadController::class, 'downloadShare'])->name('share.download');


require __DIR__ . '/auth.php';
