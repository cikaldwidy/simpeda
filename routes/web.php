<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Models\Berita;
use App\Models\HeroSlide;
use App\Models\PerangkatDesa;
use App\Http\Controllers\PerangkatDesaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\Admin\SuratPengajuanAdminController;
use App\Http\Controllers\Admin\FaqAdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\PublicChatbotController;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin,petugas')
        ->name('dashboard');
    Route::get('/users', [UserManagementController::class, 'index'])
        ->middleware('role:admin,petugas')
        ->name('users.index');
    Route::patch('/users/{user}/approval', [UserManagementController::class, 'updateApproval'])
        ->middleware('role:admin,petugas')
        ->name('users.approval');
    Route::resource('hero-slides', HeroSlideController::class)
        ->middleware('role:admin');
});

Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/approval', [UserManagementController::class, 'updateApproval'])->name('users.approval');

    Route::get('/surat-pengajuan', [SuratPengajuanAdminController::class, 'index'])->name('surat-pengajuan.index');
    Route::patch('/surat-pengajuan/{suratPengajuan}/status', [SuratPengajuanAdminController::class, 'updateStatus'])->name('surat-pengajuan.update-status');
    Route::delete('/surat-pengajuan/{suratPengajuan}', [SuratPengajuanAdminController::class, 'destroy'])->name('surat-pengajuan.destroy');

    Route::get('/berita', [BeritaController::class, 'adminIndex'])->name('berita.index');
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    Route::get('/artikel', [ArtikelController::class, 'adminIndex'])->name('artikel.index');
    Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
    Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
    Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{id}', [ArtikelController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');
});


Route::get('/', function () {
    $heroSlides = HeroSlide::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    $perangkat = PerangkatDesa::orderBy('urutan', 'asc')->get();
    $berita = Berita::where('is_published', true)
        ->latest()
        ->take(6)
        ->get();

    return view('landing.index', compact('heroSlides', 'perangkat', 'berita'));
});

Route::get('/menunggu-persetujuan', function (\Illuminate\Http\Request $request) {
    $user = $request->user();

    if ($user && $user->approval_status === 'approved') {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('auth.pending', [
            'approvedMessage' => true,
        ]);
    }

    return view('auth.pending');
})->middleware('auth')->name('account.pending');

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

Route::get('/layanan', [LayananController::class, 'index'])->name('layanan');
Route::get('/contact', function () {
    return view('landing.contact');
})->name('contact');
Route::post('/chatbot/public/message', [PublicChatbotController::class, 'message'])
    ->name('chatbot.public.message');
Route::get('/profil', function () {
    return view('landing.profile');
})->name('profil');
Route::get('/layanan/mulai', [LayananController::class, 'mulaiPengajuan'])->name('layanan.mulai');

// Hanya user yang sudah login + sudah disetujui
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('dashboard.profile');
    Route::get('/dashboard/status-surat', [DashboardController::class, 'status'])->name('dashboard.status');
    Route::get('/dashboard/dokumen-saya', [DashboardController::class, 'dokumen'])->name('dashboard.dokumen');
    Route::get('/dashboard/riwayat-pengajuan', [DashboardController::class, 'riwayat'])->name('dashboard.riwayat');
    Route::get('/dashboard/chatbot', [DashboardController::class, 'chatbot'])->name('dashboard.chatbot');
    Route::post('/dashboard/chatbot/message', [DashboardController::class, 'chatbotMessage'])
        ->name('dashboard.chatbot.message');

    Route::get('/dashboard/pengajuan/create', [PengajuanSuratController::class, 'create'])
        ->name('layanan.pengajuan.create');
    Route::post('/dashboard/pengajuan', [PengajuanSuratController::class, 'store'])
        ->name('layanan.pengajuan.store');
    Route::delete('/dashboard/pengajuan/{suratPengajuan}', [PengajuanSuratController::class, 'destroy'])
        ->name('layanan.pengajuan.destroy');
    Route::get('/dashboard/pengajuan/{suratPengajuan}', [PengajuanSuratController::class, 'show'])
        ->name('layanan.pengajuan.show');
    Route::get('/dashboard/pengajuan/{suratPengajuan}/preview', [PengajuanSuratController::class, 'preview'])
        ->name('layanan.pengajuan.preview');
    Route::get('/dashboard/pengajuan/{suratPengajuan}/download', [PengajuanSuratController::class, 'download'])
        ->name('layanan.pengajuan.download');

    // Backward compatibility for profile route lama
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/sotk', [PerangkatDesaController::class, 'indexPublic'])
    ->name('sotk');
    
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::resource('perangkat', PerangkatDesaController::class)
            ->middleware('role:admin');
        Route::get('/surat-pengajuan', [SuratPengajuanAdminController::class, 'index'])
            ->middleware('role:admin,petugas')
            ->name('surat-pengajuan.index');
        Route::patch('/surat-pengajuan/{suratPengajuan}/status', [SuratPengajuanAdminController::class, 'updateStatus'])
            ->middleware('role:admin,petugas')
            ->name('surat-pengajuan.update-status');
        Route::delete('/surat-pengajuan/{suratPengajuan}', [SuratPengajuanAdminController::class, 'destroy'])
            ->middleware('role:admin,petugas')
            ->name('surat-pengajuan.destroy');
    });

Route::get('/berita', [BeritaController::class, 'index'])
    ->name('berita');

Route::get('/berita/{slug}', [BeritaController::class, 'show'])
    ->name('berita.show');

Route::get('/artikel', [ArtikelController::class, 'index'])
    ->name('artikel');

Route::get('/artikel/{slug}', [ArtikelController::class, 'show'])
    ->name('artikel.show');


/* ========= ADMIN ========= */

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/berita', [BeritaController::class, 'adminIndex'])
            ->middleware('role:admin,petugas')
            ->name('berita.index');

        Route::get('/berita/create', [BeritaController::class, 'create'])
            ->middleware('role:admin,petugas')
            ->name('berita.create');

        Route::post('/berita', [BeritaController::class, 'store'])
            ->middleware('role:admin,petugas')
            ->name('berita.store');

        Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])
            ->middleware('role:admin,petugas')
            ->name('berita.edit');

        Route::put('/berita/{id}', [BeritaController::class, 'update'])
            ->middleware('role:admin,petugas')
            ->name('berita.update');

        Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])
            ->middleware('role:admin,petugas')
            ->name('berita.destroy');

        Route::get('/artikel', [ArtikelController::class, 'adminIndex'])
            ->middleware('role:admin,petugas')
            ->name('artikel.index');

        Route::get('/artikel/create', [ArtikelController::class, 'create'])
            ->middleware('role:admin,petugas')
            ->name('artikel.create');

        Route::post('/artikel', [ArtikelController::class, 'store'])
            ->middleware('role:admin,petugas')
            ->name('artikel.store');

        Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])
            ->middleware('role:admin,petugas')
            ->name('artikel.edit');

        Route::put('/artikel/{id}', [ArtikelController::class, 'update'])
            ->middleware('role:admin,petugas')
            ->name('artikel.update');

        Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])
            ->middleware('role:admin,petugas')
            ->name('artikel.destroy');

        Route::resource('faq', FaqAdminController::class)
            ->middleware('role:admin')
            ->except(['show']);
    });

