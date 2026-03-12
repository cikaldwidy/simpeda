@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Status Surat')
@section('dashboard_title', 'Status Surat')
@section('dashboard_subtitle', 'Pantau status seluruh pengajuan surat Anda.')

@section('dashboard_content')
<div class="space-y-6 md:space-y-8">

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">
        <!-- Total Pengajuan -->
        <div
            class="group rounded-md border border-gray-100 bg-gray-300 p-4 shadow-sm transition hover:shadow-md sm:p-6">
            <div class="mb-3 flex items-center gap-3 sm:justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100/50">
                    <i class="fa-solid fa-file-lines text-gray-700"></i>
                </div>
                <span class="text-xl font-bold tracking-[.5px] text-gray-800 sm:text-2xl">{{ $totalPengajuan }}</span>
            </div>
            <p class="text-sm font-medium text-gray-800 tracking-[.5px]">Total Pengajuan</p>
        </div>

        <!-- Menunggu -->
        <div
            class="group rounded-md border border-yellow-100 bg-yellow-300 p-4 shadow-sm transition hover:shadow-md sm:p-6">
            <div class="mb-3 flex items-center gap-3 sm:justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100/50">
                    <i class="fa-solid fa-clock text-yellow-700"></i>
                </div>
                <span class="text-xl font-bold tracking-[.5px] text-yellow-800 sm:text-2xl">{{ $totalMenunggu }}</span>
            </div>
            <p class="text-sm font-medium text-yellow-800 tracking-[.5px]">Menunggu</p>
        </div>

        <!-- Disetujui -->
        <div
            class="group rounded-md border border-green-100 bg-green-300 p-4 shadow-sm transition hover:shadow-md sm:p-6">
            <div class="mb-3 flex items-center gap-3 sm:justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100/50">
                    <i class="fa-solid fa-circle-check text-green-700"></i>
                </div>
                <span class="text-xl font-bold tracking-[.5px] text-green-800 sm:text-2xl">{{ $totalDisetujui }}</span>
            </div>
            <p class="text-sm font-medium text-green-800 tracking-[.5px]">Disetujui</p>
        </div>

        <!-- Ditolak -->
        <div class="group rounded-md border border-red-100 bg-red-300 p-4 shadow-sm transition hover:shadow-md sm:p-6">
            <div class="mb-3 flex items-center gap-3 sm:justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100/50">
                    <i class="fa-solid fa-circle-xmark text-red-700"></i>
                </div>
                <span class="text-xl font-bold tracking-[.5px] text-red-800 sm:text-2xl">{{ $totalDitolak }}</span>
            </div>
            <p class="text-sm font-medium text-red-800 tracking-[.5px]">Ditolak</p>
        </div>
    </div>

    <!-- Table Section -->
    <div>
        <h3 class=" mb-3 text-lg font-bold text-gray-800 tracking-[.5px] uppercase text-center md:text-left underline">
            Daftar
            Status
            Pengajuan Surat</h3>

        <div class="rounded-md border border-gray-200 bg-white shadow-sm">
            <!-- Table Header -->

            <div class="hidden">
                @forelse($statusRows as $item)
                @php
                $regDesNomor = '474.1/' . ($item->nomor_urut ?? '-') . '/' . (auth()->user()->desa_id ?? '-') . '/' .
                ($item->tahun ?? optional($item->tanggal_surat)->format('Y'));
                $statusConfig = match($item->status) {
                'disetujui' => [
                'bg' => 'bg-green-50',
                'text' => 'text-green-700',
                'border' => 'border-green-200',
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
                <div class="rounded-md border border-gray-100 bg-white p-3">
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

                    <div class="mt-3 flex items-center justify-between gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-md border {{ $statusConfig['border'] }} {{ $statusConfig['bg'] }} px-2 py-1 text-xs font-medium tracking-[.5px] {{ $statusConfig['text'] }}">
                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                            {{ ucfirst($item->status) }}
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('layanan.pengajuan.show', $item) }}"
                                class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-gray-600 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-500 tracking-[.5px]">
                                <i class="fa-solid fa-eye"></i>
                                <span>Detail</span>
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
                </div>
                @empty
                <div class="py-8 text-center">
                    <p class="text-sm font-medium text-gray-500">Belum ada data status surat</p>
                </div>
                @endforelse
            </div>

            <!-- Table Content Desktop -->
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
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white sm:px-6">
                                Status
                            </th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white sm:px-6">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($statusRows as $item)
                        @php
                        $regDesNomor = '474.1/' . ($item->nomor_urut ?? '-') . '/' . (auth()->user()->desa_id ?? '-') .
                        '/' . ($item->tahun ?? optional($item->tanggal_surat)->format('Y'));
                        $statusConfig = match($item->status) {
                        'disetujui' => [
                        'bg' => 'bg-green-50',
                        'text' => 'text-green-700',
                        'border' => 'border-green-200',
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
                        <tr class="group transition">
                            <td class="whitespace-nowrap px-2 py-4 sm:px-4">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-day text-sm text-gray-500"></i>
                                    <span class="text-md font-medium text-gray-800 tracking-[.5px]">
                                        {{ optional($item->created_at)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="mt-0.5 text-sm font-medium text-gray-500 tracking-[.5px]">
                                    {{ optional($item->created_at)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-2 py-4 sm:px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-md font-medium text-gray-800 tracking-[.5px]">
                                        {{ \App\Models\SuratPengajuan::jenisOptions()[$item->jenis_surat] ?? ucfirst(str_replace('_', ' ', $item->jenis_surat)) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 py-4 sm:px-4">
                                <span class="text-sm font-medium text-gray-800 tracking-[.5px]">
                                    {{ $regDesNomor }}
                                </span>
                            </td>
                            <td class="px-2 py-4 sm:px-4">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-md border {{ $statusConfig['border'] }} {{ $statusConfig['bg'] }} px-2 py-1 text-sm font-medium tracking-[.5px] {{ $statusConfig['text'] }}">
                                    <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-2 py-4 text-center sm:px-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('layanan.pengajuan.show', $item) }}"
                                        class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-gray-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-500 tracking-[.5px]">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Lihat</span>
                                    </a>
                                    <form method="POST" action="{{ route('layanan.pengajuan.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md border border-red-300 bg-red-600 px-2 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash text-md"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center sm:px-6 sm:py-16">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                        <i class="fa-solid fa-inbox text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Belum ada data status surat</p>
                                    <p class="text-xs text-gray-400">Pengajuan surat Anda akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if needed) -->
            @if($statusRows->hasPages())
            <div class="border-t border-gray-100 bg-gray-50/30 px-4 py-4 sm:px-6">
                {{ $statusRows->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection