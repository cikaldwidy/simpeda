<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/menunggu-persetujuan', fn () => view('auth.pending'))
    ->middleware('auth')
    ->name('account.pending');

// Hanya user yang sudah login + sudah disetujui
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified') // opsional (boleh dihapus kalau nggak perlu verifikasi email)
      ->name('dashboard');

    // Profile (kalau kamu ingin hanya user approved yang bisa akses profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
