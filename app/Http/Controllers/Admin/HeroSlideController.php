<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->paginate(10);
        return view('admin.hero_slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero_slides.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string','max:255'],
            'image'       => ['required','image','mimes:jpg,jpeg,png,webp','max:3072'],
            'sort_order'  => ['required','integer','min:1'],
            'is_active'   => ['nullable','boolean'],
        ]);

        $path = $request->file('image')->store('hero', 'public');

        HeroSlide::create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'image_path'  => $path,
            'sort_order'  => $data['sort_order'],
            'is_active'   => (bool) $request->input('is_active', 0),
        ]);

        return redirect()->route('admin.hero-slides.index')->with('success', 'Slide tersimpan.');
    }

    public function edit(string $id)
    {
        $slide = HeroSlide::findOrFail($id);
        return view('admin.hero_slides.edit', compact('slide'));
    }

    public function update(Request $request, string $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string','max:255'],
            'image'       => ['nullable','image','mimes:jpg,jpeg,png,webp','max:3072'],
            'sort_order'  => ['required','integer','min:1'],
            'is_active'   => ['nullable','boolean'],
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slide->image_path);
            $slide->image_path = $request->file('image')->store('hero', 'public');
        }

        $slide->title = $data['title'];
        $slide->description = $data['description'] ?? null;
        $slide->sort_order = $data['sort_order'];
        $slide->is_active = (bool) $request->input('is_active', 0);
        $slide->save();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Slide diperbarui.');
    }

    public function destroy(string $id)
    {
        $slide = HeroSlide::findOrFail($id);

        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Slide dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:hero_slides,id'],
        ]);

        $slides = HeroSlide::whereKey($data['ids'])->get();

        foreach ($slides as $slide) {
            Storage::disk('public')->delete($slide->image_path);
            $slide->delete();
        }

        return redirect()->route('admin.hero-slides.index')
            ->with('success', $slides->count() . ' slide berhasil dihapus.');
    }
}
