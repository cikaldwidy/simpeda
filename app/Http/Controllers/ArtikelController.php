<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\ContentView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $articles = Artikel::where('is_published', true)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('judul', 'like', "%{$q}%")
                        ->orWhere('isi', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('landing.artikel', compact('articles', 'q'));
    }

    public function show($slug)
    {
        $artikel = Artikel::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        if (Auth::check() && Schema::hasColumn('artikels', 'view_count')) {
            $isNewView = ContentView::insertOrIgnore([
                'user_id' => Auth::id(),
                'viewable_type' => Artikel::class,
                'viewable_id' => $artikel->id,
                'viewed_at' => now(),
            ]);

            if ($isNewView > 0) {
                $artikel->increment('view_count');
            }
        }

        $previousArticle = Artikel::where('is_published', true)
            ->where('id', '<', $artikel->id)
            ->orderByDesc('id')
            ->first();

        $nextArticle = Artikel::where('is_published', true)
            ->where('id', '>', $artikel->id)
            ->orderBy('id')
            ->first();

        $recentPosts = Artikel::where('is_published', true)
            ->where('id', '!=', $artikel->id)
            ->latest()
            ->take(3)
            ->get();

        $comments = $artikel->comments()
            ->where('is_approved', true)
            ->latest()
            ->get();

        return view('landing.detail-artikel', compact('artikel', 'previousArticle', 'nextArticle', 'recentPosts', 'comments'));
    }

    public function adminIndex(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', 'all');

        $artikel = Artikel::query()
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
            ->paginate(10)
            ->withQueryString();

        return view('admin.artikel.index', compact('artikel', 'q', 'status'));
    }

    public function create()
    {
        return view('admin.artikel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('artikel', 'public');
        }

        Artikel::create([
            'judul' => $request->judul,
            'slug' => Artikel::generateUniqueSlug($request->judul),
            'ringkasan' => $request->ringkasan,
            'isi' => $request->isi,
            'gambar' => $gambar,
            'is_published' => $request->is_published ?? true,
        ]);

        $routePrefix = ($request->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.artikel.index')
            ->with('success', 'Artikel berhasil ditambahkan');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('artikel', 'public');
            $artikel->gambar = $gambar;
        }

        $artikel->update([
            'judul' => $request->judul,
            'slug' => Artikel::generateUniqueSlug($request->judul, $artikel->id),
            'ringkasan' => $request->ringkasan,
            'isi' => $request->isi,
            'is_published' => $request->is_published ?? true,
        ]);

        $routePrefix = ($request->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.artikel.index')
            ->with('success', 'Artikel berhasil diupdate');
    }

    public function destroy($id)
    {
        Artikel::findOrFail($id)->delete();

        $routePrefix = (request()->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.artikel.index')
            ->with('success', 'Artikel berhasil dihapus');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:artikels,id'],
        ]);

        $artikel = Artikel::whereKey($data['ids'])->get();
        foreach ($artikel as $item) {
            $item->delete();
        }
        $routePrefix = ($request->user()?->role ?? null) === 'petugas' ? 'petugas' : 'admin';

        return redirect()->route($routePrefix . '.artikel.index')
            ->with('success', $artikel->count() . ' artikel berhasil dihapus.');
    }
}
