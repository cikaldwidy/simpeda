<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqAdminController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAccess($request);

        return view('admin.faq.index', [
            'faqs' => Faq::query()->orderBy('kategori')->orderBy('id_faq', 'desc')->paginate(20),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAccess($request);

        return view('admin.faq.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAccess($request);

        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'max:255'],
            'jawaban' => ['required', 'string'],
            'kategori' => ['required', 'string', 'max:50'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        Faq::create([
            'pertanyaan' => $validated['pertanyaan'],
            'jawaban' => $validated['jawaban'],
            'kategori' => $validated['kategori'],
            'is_aktif' => (int) ($request->boolean('is_aktif') ? 1 : 0),
        ]);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Request $request, Faq $faq): View
    {
        $this->authorizeAccess($request);

        return view('admin.faq.edit', [
            'faq' => $faq,
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $this->authorizeAccess($request);

        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'max:255'],
            'jawaban' => ['required', 'string'],
            'kategori' => ['required', 'string', 'max:50'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $faq->update([
            'pertanyaan' => $validated['pertanyaan'],
            'jawaban' => $validated['jawaban'],
            'kategori' => $validated['kategori'],
            'is_aktif' => (int) ($request->boolean('is_aktif') ? 1 : 0),
        ]);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Request $request, Faq $faq): RedirectResponse
    {
        $this->authorizeAccess($request);

        $faq->delete();

        return redirect()->route('admin.faq.index')->with('success', 'FAQ berhasil dihapus.');
    }

    private function authorizeAccess(Request $request): void
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);
    }
}
