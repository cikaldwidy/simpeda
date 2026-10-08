<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AspirasiController extends Controller
{
    public function index(): View
    {
        return view('landing.aspirasi');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
            'isi_aspirasi' => ['required', 'string', 'max:5000'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'g-recaptcha-response' => ['required'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'isi_aspirasi.required' => 'Aspirasi atau keluhan wajib diisi.',
            'gambar.image' => 'File lampiran harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib diisi.',
        ]);

        $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (! $recaptcha->ok() || ! ($recaptcha->json('success') ?? false)) {
            return back()
                ->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal.'])
                ->withInput();
        }

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('aspirasi', 'public');
        }

        Aspirasi::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'isi_aspirasi' => $validated['isi_aspirasi'],
            'gambar' => $gambar,
        ]);

        return redirect()
            ->route('aspirasi')
            ->with('status', 'Pesan Anda berhasil dikirim. terima kasih atas partisipasi Anda!');
    }
}
