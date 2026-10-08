<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanAnggaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanAnggaranController extends Controller
{
    private const KATEGORI_ITEMS = [
        'pendapatan' => [
            'Pendapatan Asli Desa',
            'Dana Desa (APBN)',
            'Alokasi Dana Desa (APBD)',
            'Bagi Hasil Pajak/Retribusi',
            'Bantuan Keuangan',
            'Pendapatan Lain-lain',
        ],
        'pembiayaan' => [
            'Penerimaan Pembiayaan',
            'Pengeluaran Pembiayaan',
            'Selisih Pembiayaan',
        ],
        'belanja' => [
            'Penyelenggaraan Pemerintahan Desa',
            'Pelaksanaan Pembangunan Desa',
            'Pembinaan Kemasyarakatan',
            'Pemberdayaan Masyarakat Desa',
            'Penanggulangan Bencana, Darurat & Mendesak',
        ],
    ];
    private const TEMPLATES = ['infografis', 'merah-putih', 'nusantara', 'dashboard', 'poster-batik', 'rincian'];

    public function index(): View
    {
        $laporans = LaporanAnggaran::withCount('items')
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.anggaran.index', compact('laporans'));
    }

    public function create(): View
    {
        return view('admin.anggaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateLaporan($request);
        $items = $data['items'];
        unset($data['items']);

        DB::transaction(function () use ($data, $items): void {
            $laporan = LaporanAnggaran::create($data);
            $laporan->items()->createMany($items);
        });

        return redirect()->route('admin.anggaran.index')->with('success', 'Laporan anggaran berhasil ditambahkan.');
    }

    public function edit(LaporanAnggaran $anggaran): View
    {
        $anggaran->load('items');

        return view('admin.anggaran.edit', ['laporan' => $anggaran]);
    }

    public function update(Request $request, LaporanAnggaran $anggaran): RedirectResponse
    {
        $data = $this->validateLaporan($request);
        $items = $data['items'];
        unset($data['items']);

        DB::transaction(function () use ($data, $items, $anggaran): void {
            $anggaran->update($data);
            $anggaran->items()->delete();
            $anggaran->items()->createMany($items);
        });

        return redirect()->route('admin.anggaran.index')->with('success', 'Laporan anggaran berhasil diperbarui.');
    }

    public function destroy(LaporanAnggaran $anggaran): RedirectResponse
    {
        $anggaran->delete();

        return redirect()->route('admin.anggaran.index')->with('success', 'Laporan anggaran berhasil dihapus.');
    }

    private function validateLaporan(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:180'],
            'tahun' => ['required', 'integer', 'between:2000,2100'],
            'jenis_laporan' => ['required', 'string', 'max:80'],
            'template' => ['required', 'in:' . implode(',', self::TEMPLATES)],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'is_published' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.jenis' => ['required', 'in:' . implode(',', array_keys(self::KATEGORI_ITEMS))],
            'items.*.kategori' => ['required', 'string', 'max:120'],
            'items.*.uraian' => ['nullable', 'string', 'max:255'],
            'items.*.jumlah' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['items'] = collect($data['items'])->values()->map(function (array $item, int $index): array {
            if (! in_array($item['kategori'], self::KATEGORI_ITEMS[$item['jenis']], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "items.$index.kategori" => 'Kategori anggaran tidak valid.',
                ]);
            }

            $item['uraian'] = $item['jenis'] === 'belanja'
                ? (($item['uraian'] ?? null) ?: $item['kategori'])
                : $item['kategori'];
            $item['urutan'] = $index;

            return $item;
        })->all();

        return $data;
    }
}
