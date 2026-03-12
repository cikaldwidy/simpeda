@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Admin Pengajuan Surat')

@section('content')
@php
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
@endphp
<section class="min-h-screen py-8">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 tracking-[1px] md:text-left text-center">Pengajuan
                        Surat Warga</h1>
                    <p class="mt-1 text-base text-slate-600 md:text-left text-center">Kelola status pengajuan surat
                        warga</p>
                </div>
            </div>

            @if(session('status'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
            @endif

            <div class="mb-4 rounded-md border border-slate-200 bg-slate-50 p-4 shadow-sm">
                <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-end">
                    <div class="w-full md:max-w-xl">
                        <label for="q"
                            class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Pencarian
                            Pengajuan</label>
                        <input id="q" type="text" name="q" value="{{ $search ?? '' }}"
                            placeholder="Cari Nama, NIK, Nomor Surat, Jenis Surat, atau Status..."
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-500 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-md border border-gray-100 bg-white shadow-sm">
                <div class="w-full max-w-full overflow-x-auto">
                    <table class="min-w-[980px] w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Tanggal</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Nama</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Jenis Surat</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Nomor Surat</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Status</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pengajuans as $item)
                            @php
                            $regDesNomor = '474.1/' . ($item->nomor_urut ?? '-') . '/' . ($item->user?->desa_id ?? '-')
                            . '/' . ($item->tahun ?? optional($item->tanggal_surat)->format('Y'));
                            $statusConfig = match($item->status) {
                            'disetujui' => [
                            'bg' => 'bg-emerald-50',
                            'text' => 'text-emerald-700',
                            'border' => 'border-emerald-200',
                            'icon' => 'fa-circle-check'
                            ],
                            'ditolak' => [
                            'bg' => 'bg-red-50',
                            'text' => 'text-red-700',
                            'border' => 'border-red-200',
                            'icon' => 'fa-circle-xmark'
                            ],
                            default => [
                            'bg' => 'bg-yellow-50',
                            'text' => 'text-yellow-700',
                            'border' => 'border-yellow-200',
                            'icon' => 'fa-clock'
                            ],
                            };
                            @endphp
                            <tr class="group transition hover:bg-gray-50/50">
                                <td class="whitespace-nowrap px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-day text-sm text-gray-500"></i>
                                        <span class="text-sm font-medium tracking-[.5px] text-gray-800">
                                            {{ optional($item->created_at)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="mt-0.5 text-xs font-medium tracking-[.5px] text-gray-500">
                                        {{ optional($item->created_at)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="text-sm font-medium tracking-[.5px] text-gray-800">{{ $item->user?->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="text-sm font-medium tracking-[.5px] text-gray-800">{{ $jenisOptions[$item->jenis_surat] ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="text-sm font-medium tracking-[.5px] text-gray-800">{{ $regDesNomor }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border {{ $statusConfig['border'] }} {{ $statusConfig['bg'] }} px-2 py-1 text-xs font-medium tracking-[.5px] {{ $statusConfig['text'] }}">
                                        <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST"
                                            action="{{ route($routePrefix . '.surat-pengajuan.update-status', $item) }}"
                                            class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status"
                                                class="min-w-[108px] rounded-md border border-gray-300 bg-white pl-2 pr-7 py-1.5 text-[11px] font-semibold text-gray-700 focus:border-orange-500 focus:ring-orange-500">
                                                <option value="menunggu" @selected($item->status === 'menunggu' ||
                                                    $item->status === 'diajukan')>MENUNGGU</option>
                                                <option value="disetujui" @selected($item->status ===
                                                    'disetujui')>DISETUJUI</option>
                                                <option value="ditolak" @selected($item->status === 'ditolak')>DITOLAK
                                                </option>
                                            </select>
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-md border border-orange-300 bg-orange-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-orange-600 tracking-[.5px]">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                                Simpan
                                            </button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route($routePrefix . '.surat-pengajuan.destroy', $item) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-md border border-red-300 bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500 tracking-[.5px]">
                                                <i class="fa-solid fa-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    Belum ada pengajuan surat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>
</section>
@endsection