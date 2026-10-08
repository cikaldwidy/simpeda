@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Detail Surat')
@section('dashboard_title', 'Detail Surat')
@section('dashboard_subtitle', 'Lihat detail hasil pengajuan surat Anda.')

@section('dashboard_content')
<div class="space-y-4 print:space-y-0">
    <div class="print:hidden mt-5">
        <p class="mb-2 text-sm font-medium capitalize tracking-[.5px] text-gray-800">
            <a href="{{ route('layanan.pengajuan.create', ['jenis' => $surat->jenis_surat]) }}"
                class=" transition hover:underline">{{ $jenisLabel }}</a>
            <span>&rsaquo;</span>
            <span class="underline">Pengajuan</span>
        </p>
    </div>

    <div class="p-6 md:p-10">
        <div class="flex justify-end flex-wrap gap-2 print:hidden">
            <a href="{{ route('layanan.pengajuan.create', ['jenis' => $surat->jenis_surat]) }}"
                class="inline-flex items-center rounded-md border border-orange-300 bg-orange-600 px-4 py-2 text-xs font-semibold text-white hover:bg-orange-500">
                <span class="mr-1 text-sm leading-none">+</span> Buat Pengajuan Baru
            </a>
            @if($canViewDocument)
            <a href="{{ route('layanan.pengajuan.download', $surat) }}"
                class="inline-flex items-center border border-blue-300 rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-500">
                <i class="fa-solid fa-download mr-2"></i> Unduh PDF
            </a>
            @endif
        </div>
        @php
        $statusKey = strtolower((string) $surat->status);
        $statusStyle = match ($statusKey) {
        'disetujui' => 'border-green-200 bg-green-50 text-green-700',
        'ditolak' => 'border-red-200 bg-red-50 text-red-700',
        default => 'border-amber-200 bg-amber-50 text-amber-700',
        };
        $statusCardStyle = match ($statusKey) {
        'disetujui' => 'border-green-200 bg-gradient-to-br from-green-50 via-white to-green-100/60',
        'ditolak' => 'border-red-200 bg-gradient-to-br from-red-50 via-white to-red-100/60',
        default => 'border-amber-200 bg-gradient-to-br from-amber-50 via-white to-amber-100/60',
        };
        @endphp
        <div class="mt-3 rounded-xl border p-6 text-center shadow-sm {{ $statusCardStyle }}">
            <p class="text-xs font-semibold uppercase tracking-[2px] text-slate-500">Status Surat</p>
            <p
                class="mt-3 inline-flex items-center rounded-full border px-4 py-1 text-3xl font-extrabold uppercase tracking-[2px] md:text-4xl {{ $statusStyle }}">
                {{ strtoupper($surat->status) }}
            </p>
            <p class="mx-auto mt-4 max-w-2xl text-sm text-gray-600">
                @if($canViewDocument)
                Surat sudah disetujui. Anda dapat melihat dan mengunduh dokumen PDF di bawah.
                @else
                Pengajuan Anda sedang menunggu persetujuan admin. Surat akan tersedia setelah status menjadi
                <strong>DISETUJUI</strong>.
                @endif
            </p>
            <div class="mt-5 rounded-lg border border-gray-200 bg-white p-4 text-left">
                <p class="text-xs font-semibold uppercase tracking-[1px] text-gray-700">Catatan Admin/Petugas</p>
                <p class="mt-2 text-sm text-gray-800 tracking-[.5px]">
                    {{ $surat->admin_note ? $surat->admin_note : 'Belum ada catatan.' }}
                </p>
            </div>
        </div>

        @if($canViewDocument)
        @php
        $pdfViewerUrl = route('layanan.pengajuan.preview', $surat) . '?v=' . ($surat->updated_at?->timestamp ?? time());
        @endphp
        <div class="mt-4 rounded-md border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-700">
            <span class="inline-flex items-center gap-2 font-semibold">
                <i class="fa-solid fa-bullhorn"></i>
                Informasi Penting:
            </span>
            <span class="ml-1">Nomor surat sudah diisi admin/petugas. Silakan datang ke kantor desa untuk tanda tangan dan pengambilan surat.</span>
        </div>
        <div class="mt-3 overflow-hidden rounded-md bg-gray-50">
            <object data="{{ $pdfViewerUrl }}#toolbar=1&navpanes=0&scrollbar=1" type="application/pdf"
                class="h-[78vh] w-full">
                <embed src="{{ $pdfViewerUrl }}#toolbar=1&navpanes=0&scrollbar=1" type="application/pdf"
                    class="h-[78vh] w-full" />
                <iframe src="{{ $pdfViewerUrl }}#toolbar=1&navpanes=0&scrollbar=1" class="h-[78vh] w-full bg-white"
                    title="Preview PDF {{ $surat->nomor_surat }}">
                </iframe>
                <div class="p-4 text-sm text-gray-600">
                    Preview PDF tidak tersedia di browser ini.
                    <a href="{{ route('layanan.pengajuan.preview', $surat) }}" target="_blank"
                        class="font-semibold text-orange-600 hover:underline">Buka Preview</a>
                </div>
            </object>
        </div>
        @endif
    </div>
</div>
@endsection
