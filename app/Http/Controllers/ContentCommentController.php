<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContentCommentController extends Controller
{
    public function storeBerita(Request $request, string $slug): RedirectResponse
    {
        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return $this->storeComment($request, $berita, 'berita.show', $berita->slug);
    }

    public function storeArtikel(Request $request, string $slug): RedirectResponse
    {
        $artikel = Artikel::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return $this->storeComment($request, $artikel, 'artikel.show', $artikel->slug);
    }

    private function storeComment(Request $request, Berita|Artikel $content, string $routeName, string $slug): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
            'g-recaptcha-response' => ['required'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'comment.required' => 'Komentar wajib diisi.',
            'comment.min' => 'Komentar minimal 5 karakter.',
            'comment.max' => 'Komentar maksimal 2000 karakter.',
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

        $content->comments()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'comment' => $validated['comment'],
            'is_approved' => true,
        ]);

        return redirect()
            ->route($routeName, $slug)
            ->with('comment_success', 'Komentar berhasil dikirim.')
            ->withFragment('komentar');
    }
}
