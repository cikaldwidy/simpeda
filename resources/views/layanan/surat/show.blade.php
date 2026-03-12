@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Detail Surat')
@section('dashboard_title', 'Detail Surat')
@section('dashboard_subtitle', 'Lihat detail hasil pengajuan surat Anda.')

@section('dashboard_content')
<div class="space-y-4 print:space-y-0">
    @if(session('status'))
    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 print:hidden">
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 print:hidden">
        {{ session('error') }}
    </div>
    @endif

    <div class="print:hidden mt-5">
        <p class="mb-2 text-xs font-medium capitalize tracking-[.5px] text-gray-800">
            <a href="{{ route('layanan.pengajuan.create', ['jenis' => $surat->jenis_surat]) }}"
                class="hover:text-orange-500 transition hover:underline">{{ $jenisLabel }}</a>
            <span>&rsaquo;</span>
            <span>Pengajuan</span>
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
        @if(!$canViewDocument)
        <div class="mt-3 rounded-md border border-amber-200 bg-amber-50 p-5 text-amber-800">
            <p class="text-sm font-semibold">Pengajuan Anda sedang menunggu persetujuan admin.</p>
            <p class="mt-2 text-sm">
                Surat akan tersedia untuk dilihat dan diunduh setelah status berubah menjadi <strong>DISETUJUI</strong>.
            </p>
            <p class="mt-3 text-xs text-amber-700">
                Status saat ini: {{ strtoupper($surat->status) }}
            </p>
        </div>
        @else
        @php
        $pdfViewerUrl = route('layanan.pengajuan.preview', $surat) . '?v=' . ($surat->updated_at?->timestamp ?? time());
        @endphp
        <div class=" mt-3 overflow-hidden rounded-md bg-gray-50">
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