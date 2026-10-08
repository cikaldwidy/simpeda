@extends('layouts.dashboard')

@php
$namaSurat = match($selectedJenis) {
\App\Models\SuratPengajuan::JENIS_DOMISILI => 'Surat Keterangan Domisili',
\App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU => 'Surat Keterangan Tidak Mampu',
\App\Models\SuratPengajuan::JENIS_KEMATIAN => 'Surat Kematian',
\App\Models\SuratPengajuan::JENIS_KELAHIRAN => 'Surat Kelahiran',
\App\Models\SuratPengajuan::JENIS_USAHA => 'Surat Keterangan Usaha',
\App\Models\SuratPengajuan::JENIS_BELUM_MENIKAH => 'Surat Keterangan Belum Menikah',
\App\Models\SuratPengajuan::JENIS_KEHILANGAN => 'Surat Keterangan Kehilangan',
\App\Models\SuratPengajuan::JENIS_PENGHASILAN_ORTU => 'Surat Keterangan Penghasilan Orang Tua',
default => 'Pengajuan Surat',
};
@endphp

@section('title', config('app.name') . ' | ' . $namaSurat)
@section('dashboard_title', $namaSurat)
@section('dashboard_subtitle', 'Lengkapi data pengajuan untuk ' . $namaSurat . '.')

@section('dashboard_content')
<div class="bg-gray-100">
    @if($errors->any())
    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc space-y-1 pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <h3 class="text-lg font-bold text-gray-800 tracking-[.5px] uppercase text-center md:text-left underline">
        {{ $namaSurat }}</h3>
    <p class=" mt-1 text-sm text-gray-600 text-center md:text-left">Harap memastikan data yang dimasukkan sudah sesuai
        sebelum
        melanjutkan pengajuan surat.</p>
    <div class="rounded-md border border-slate-200 bg-gradient-to-r from-slate-300 to-slate-200 p-5 mt-3">
        <form method="POST" action="{{ route('layanan.pengajuan.store') }}" class="">
            @csrf
            <input type="hidden" name="jenis_surat" value="{{ $selectedJenis }}">
            @if(!in_array($selectedJenis, [\App\Models\SuratPengajuan::JENIS_KEMATIAN, \App\Models\SuratPengajuan::JENIS_KELAHIRAN], true))
            @include('layanan.surat.forms.identitas')
            @endif

            @if($selectedJenis === \App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU)
            @include('layanan.surat.forms.tidak-mampu')
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_KEMATIAN)
            @include('layanan.surat.forms.kematian')
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_KELAHIRAN)
            @include('layanan.surat.forms.kelahiran')
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_USAHA)
            @include('layanan.surat.forms.usaha')
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_BELUM_MENIKAH)
            @include('layanan.surat.forms.belum-menikah')
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_KEHILANGAN)
            @include('layanan.surat.forms.kehilangan')
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_PENGHASILAN_ORTU)
            @include('layanan.surat.forms.penghasilan-ortu')
            @endif

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                    class="inline-flex rounded-md bg-orange-500 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-400 tracking-[.5px]">
                    Kirim Pengajuan
                </button>

            </div>
        </form>
    </div>
</div>

@if($selectedJenis === \App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('keperluan_sktm');
    const wrap = document.getElementById('keperluan_lainnya_wrap');
    if (!select || !wrap) return;

    function toggleKeperluanLainnya() {
        wrap.classList.toggle('hidden', select.value !== 'lainnya');
    }

    select.addEventListener('change', toggleKeperluanLainnya);
    toggleKeperluanLainnya();
});
</script>
@endif

@endsection
