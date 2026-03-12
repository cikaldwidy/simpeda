@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Dokumen Saya')
@section('dashboard_title', 'Dokumen Saya')
@section('dashboard_subtitle', 'Daftar surat yang sudah disetujui dan siap diunduh.')

@section('dashboard_content')
<div class="space-y-6 md:space-y-8">

    <div>
        <h3 class=" mb-3 text-lg font-bold text-gray-800 tracking-[.5px] uppercase text-center md:text-left underline">
            Daftar Dokumen Saya</h3>

        <div class="rounded-md border border-gray-100 bg-white shadow-sm">


            <div class="hidden">
                @forelse($dokumen as $item)
                @php
                $regDesNomor = '474.1/' . ($item->nomor_urut ?? '-') . '/' . (auth()->user()->desa_id ?? '-') . '/' .
                ($item->tahun ?? optional($item->tanggal_surat)->format('Y'));
                @endphp
                <div class="rounded-md border border-gray-200 bg-white p-3">
                    <p class="text-[11px] font-bold uppercase tracking-[1px] text-gray-500">Tanggal</p>
                    <p class="mt-1 text-xs font-medium tracking-[.5px] text-gray-800">
                        {{ optional($item->created_at)->format('d M Y H:i') }}
                    </p>

                    <p class="mt-3 text-[11px] font-bold uppercase tracking-[1px] text-gray-500">Jenis Surat</p>
                    <p class="mt-1 text-xs font-medium tracking-[.5px] text-gray-800">
                        {{ \App\Models\SuratPengajuan::jenisOptions()[$item->jenis_surat] ?? ucfirst(str_replace('_', ' ', $item->jenis_surat)) }}
                    </p>

                    <p class="mt-3 text-[11px] font-bold uppercase tracking-[1px] text-gray-500">Nomor Surat</p>
                    <p class="mt-1 text-xs font-mono font-medium tracking-[.5px] text-gray-800">
                        {{ $regDesNomor }}
                    </p>

                    <div class="mt-3 flex justify-end gap-2">
                        <a href="{{ route('layanan.pengajuan.show', $item) }}"
                            class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-gray-600 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-500 tracking-[.5px]">
                            <i class="fa-solid fa-eye"></i>
                            <span>Lihat</span>
                        </a>
                        <a href="{{ route('layanan.pengajuan.download', $item) }}"
                            class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500 tracking-[.5px]">
                            <i class="fa-solid fa-download"></i>
                            <span>Unduh</span>
                        </a>
                        <form method="POST" action="{{ route('layanan.pengajuan.destroy', $item) }}"
                            onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-md border border-red-300 bg-red-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500"
                                title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center">
                    <p class="text-sm font-medium text-gray-500">Belum ada dokumen surat tersedia</p>
                </div>
                @endforelse
            </div>

            <div class="w-full max-w-full overflow-x-auto">
                <table class="min-w-[760px] w-full">
                    <thead>
                        <tr
                            class="border-b border-slate-50 md:border-b md:border-slate-100 bg-gradient-to-r from-slate-800 to-slate-700">
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white sm:px-6">
                                Tanggal
                            </th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white sm:px-6">
                                Jenis Surat
                            </th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white sm:px-6">
                                Nomor Surat
                            </th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white sm:px-6">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($dokumen as $item)
                        @php
                        $regDesNomor = '474.1/' . ($item->nomor_urut ?? '-') . '/' . (auth()->user()->desa_id ?? '-') .
                        '/' . ($item->tahun ?? optional($item->tanggal_surat)->format('Y'));
                        @endphp
                        <tr class="group transition hover:bg-gray-50/50">
                            <td class="whitespace-nowrap px-4 py-4 sm:px-4">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-day text-sm text-gray-500"></i>
                                    <span class="text-md font-medium tracking-[.5px] text-gray-800">
                                        {{ optional($item->created_at)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="mt-0.5 text-xs font-medium tracking-[.5px] text-gray-500">
                                    {{ optional($item->created_at)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-2 py-4 sm:px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-md font-medium tracking-[.5px] text-gray-800">
                                        {{ \App\Models\SuratPengajuan::jenisOptions()[$item->jenis_surat] ?? ucfirst(str_replace('_', ' ', $item->jenis_surat)) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 py-4 sm:px-4">
                                <span class="text-md font-medium tracking-[.5px] text-gray-800">
                                    {{ $regDesNomor }}
                                </span>
                            </td>
                            <td class="px-2 py-4 text-center sm:px-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('layanan.pengajuan.show', $item) }}"
                                        class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-gray-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-500 tracking-[.5px]">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Lihat</span>
                                    </a>
                                    <a href="{{ route('layanan.pengajuan.download', $item) }}"
                                        class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500 tracking-[.5px]">
                                        <i class="fa-solid fa-download"></i>
                                        <span>Unduh</span>
                                    </a>
                                    <form method="POST" action="{{ route('layanan.pengajuan.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md border border-red-300 bg-red-600 px-2 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center sm:px-6 sm:py-16">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                        <i class="fa-solid fa-inbox text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Belum ada dokumen surat tersedia</p>
                                    <p class="text-xs text-gray-400">Dokumen akan muncul setelah surat disetujui admin
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($dokumen->hasPages())
            <div class="border-t border-gray-100 bg-gray-50/30 px-4 py-4 sm:px-6">
                {{ $dokumen->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection