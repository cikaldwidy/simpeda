@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Anggaran Dana Desa')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-3xl font-bold tracking-[1px] text-slate-800">Anggaran Dana Desa</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola laporan anggaran, rincian kegiatan, dan publikasi di halaman beranda.</p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('admin.anggaran.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600">
                <i class="fa-solid fa-plus"></i>
                Tambah Laporan
            </a>
        </div>
        <div class="overflow-x-auto rounded-md border border-slate-200">
            <table class="min-w-[900px] w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-800 to-slate-700 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                        <th class="px-4 py-3">Tahun</th>
                        <th class="px-4 py-3">Laporan</th>
                        <th class="px-4 py-3">Template</th>
                        <th class="px-4 py-3">Rincian</th>
                        <th class="px-4 py-3">Publikasi</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $laporan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4 text-sm font-semibold text-slate-800">{{ $laporan->tahun }}</td>
                        <td class="px-4 py-4">
                            <p class="text-sm font-semibold text-slate-800">{{ $laporan->judul }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $laporan->jenis_laporan }}</p>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-700">
                            {{ [
                                'infografis' => 'Infografis Desa',
                                'merah-putih' => 'Merah Putih',
                                'nusantara' => 'Nusantara',
                                'dashboard' => 'Ringkasan Modern',
                                'poster-batik' => 'Batik Merah Putih',
                                'rincian' => 'Tabel Rincian',
                            ][$laporan->template] ?? 'Infografis Desa' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-700">{{ $laporan->items_count }} item</td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $laporan->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $laporan->is_published ? 'Dipublikasikan' : 'Draf' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.anggaran.edit', $laporan) }}"
                                    class="inline-flex items-center gap-1.5 rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-500">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.anggaran.destroy', $laporan) }}"
                                    data-delete-title="Hapus laporan anggaran?"
                                    data-delete-message="Laporan dan semua rincian di dalamnya akan dihapus.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-500">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">Belum ada laporan anggaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('partials.admin-pagination-footer', ['paginator' => $laporans, 'label' => 'laporan anggaran'])
</div>
@endsection
