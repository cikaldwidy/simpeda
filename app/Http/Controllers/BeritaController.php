<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $news = Berita::where('is_published', true)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('judul', 'like', "%{$q}%")
                        ->orWhere('isi', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('landing.berita', compact('news', 'q'));
    }

    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        if (Schema::hasColumn('beritas', 'view_count')) {
            $berita->increment('view_count');
        }

        $previousArticle = Berita::where('is_published', true)
            ->where('id', '<', $berita->id)
            ->orderByDesc('id')
            ->first();

        $nextArticle = Berita::where('is_published', true)
            ->where('id', '>', $berita->id)
            ->orderBy('id')
            ->first();

        $recentPosts = Berita::where('is_published', true)
            ->where('id', '!=', $berita->id)
            ->latest()
            ->take(3)
            ->get();

        return view('landing.detail-berita', compact('berita', 'previousArticle', 'nextArticle', 'recentPosts'));
    }

    /* =========================
       ADMIN
    ========================== */

    public function adminIndex(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', 'all');

        $berita = Berita::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('judul', 'like', "%{$q}%")
                        ->orWhere('ringkasan', 'like', "%{$q}%")
                        ->orWhere('isi', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%");
                });
            })
            ->when(in_array($status, ['published', 'draft'], true), function ($query) use ($status) {
                $query->where('is_published', $status === 'published');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.berita.index', compact('berita', 'q', 'status'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable|image|max:2048'
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                              ->store('berita', 'public');
        }

        Berita::create([
            'judul' => $request->judul,
            'slug' => Berita::generateUniqueSlug($request->judul),
            'ringkasan' => $request->ringkasan,
            'isi' => $request->isi,
            'gambar' => $gambar,
            'is_published' => $request->is_published ?? true
        ]);

        $routePrefix = ($request->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.berita.index')
                         ->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'isi' => 'required'
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                              ->store('berita', 'public');
            $berita->gambar = $gambar;
        }

        $berita->update([
            'judul' => $request->judul,
            'slug' => Berita::generateUniqueSlug($request->judul, $berita->id),
            'ringkasan' => $request->ringkasan,
            'isi' => $request->isi,
            'is_published' => $request->is_published ?? true
        ]);

        $routePrefix = ($request->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.berita.index')
                         ->with('success', 'Berita berhasil diupdate');
    }

    public function destroy($id)
    {
        Berita::findOrFail($id)->delete();

        $routePrefix = (request()->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.berita.index')
                         ->with('success', 'Berita berhasil dihapus');
    }
}
