<?php

namespace App\Http\Controllers;
use App\Models\Berita;
use App\Models\HeroSlide;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;

class PerangkatDesaController extends Controller
{
    public function index()
    {
        $perangkat = PerangkatDesa::orderBy('urutan', 'asc')->get();
        return view('admin.perangkat_desa.index', compact('perangkat'));
    }

    public function create()
    {
        return view('admin.perangkat_desa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $foto = $request->file('foto')->store('perangkat', 'public');

        PerangkatDesa::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'foto' => $foto,
            'urutan' => $request->urutan ?? 0
        ]);

        return redirect()->route('admin.perangkat.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);
        return view('admin.perangkat_desa.edit', compact('perangkat'));
    }

    public function update(Request $request, $id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required'
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('perangkat', 'public');
            $perangkat->foto = $foto;
        }

        $perangkat->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'urutan' => $request->urutan ?? 0
        ]);

        return redirect()->route('admin.perangkat.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);
        $perangkat->delete();

        return redirect()->route('admin.perangkat.index')->with('success', 'Data berhasil dihapus');
    }

    public function indexPublic()
    {
        $heroSlides = HeroSlide::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $perangkat = PerangkatDesa::orderBy('urutan', 'asc')->get();
        $berita = Berita::where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        return view('landing.index', compact('heroSlides', 'perangkat', 'berita'));
    }
}
