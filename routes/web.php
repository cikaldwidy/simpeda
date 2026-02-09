<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Models\HeroSlide;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('hero-slides', HeroSlideController::class);
});

Route::get('/', function () {
    $heroSlides = HeroSlide::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    return view('landing.index', compact('heroSlides'));
});

Route::get('/menunggu-persetujuan', fn () => view('auth.pending'))
    ->middleware('auth')
    ->name('account.pending');

if (! function_exists('fetchWilayah')) {
    function fetchWilayah(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            return ['data' => []];
        }

        return json_decode($body, true) ?? ['data' => []];
    }
}

Route::prefix('wilayah')->group(function () {
    Route::get('/provinces', function () {
        $data = fetchWilayah('https://wilayah.id/api/provinces.json');
        if (empty($data['data'])) {
            return response()->json(['data' => []], 502);
        }

        return response()->json($data);
    });

    Route::get('/regencies/{province}', function (string $province) {
        $data = fetchWilayah("https://wilayah.id/api/regencies/{$province}.json");
        if (empty($data['data'])) {
            return response()->json(['data' => []], 502);
        }

        return response()->json($data);
    });

    Route::get('/districts/{regency}', function (string $regency) {
        $data = fetchWilayah("https://wilayah.id/api/districts/{$regency}.json");
        if (empty($data['data'])) {
            return response()->json(['data' => []], 502);
        }

        return response()->json($data);
    });

    Route::get('/villages/{district}', function (string $district) {
        $data = fetchWilayah("https://wilayah.id/api/villages/{$district}.json");
        if (empty($data['data'])) {
            return response()->json(['data' => []], 502);
        }

        return response()->json($data);
    });
});

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