<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }
        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'warga'   => redirect()->route('warga.dashboard'),
            default   => abort(403, 'Role tidak dikenali.'),
        };
    })->name('dashboard');
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', fn () => view('admin.dashboard'))->name('admin.dashboard');
    });

    Route::middleware('role:petugas')->group(function () {
        Route::get('/petugas', fn () => view('petugas.dashboard'))->name('petugas.dashboard');
    });
    Route::middleware('role:warga')->group(function () {
        Route::get('/warga', fn () => view('warga.dashboard'))->name('warga.dashboard');
    });
});