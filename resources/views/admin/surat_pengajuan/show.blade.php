@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Detail Pengajuan Surat')

@section('content')
@php
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
$isApproved = $suratPengajuan->status === 'disetujui';
$isRejected = $suratPengajuan->status === 'ditolak';
$statusStyle = $isApproved
    ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
    : ($isRejected ? 'border-red-200 bg-red-50 text-red-700' : 'border-amber-200 bg-amber-50 text-amber-700');
$previewUrl = route('layanan.pengajuan.preview', $suratPengajuan) . '?v=' . ($suratPengajuan->updated_at?->timestamp ?? time());
@endphp

<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <a href="{{ route($routePrefix . '.surat-pengajuan.index') }}"
            class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-orange-600">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke daftar pengajuan
        </a>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-[.5px] text-slate-800">Detail Pengajuan Surat</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $suratPengajuan->user?->name ?? '-' }} · {{ $suratPengajuan->jenisLabel() }}</p>
            </div>
            <span class="inline-flex items-center rounded-md border px-3 py-1.5 text-xs font-semibold {{ $statusStyle }}">
                {{ ucfirst($suratPengajuan->status === 'diajukan' ? 'menunggu' : $suratPengajuan->status) }}
            </span>
        </div>
    </div>

    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-base font-bold text-slate-800">Pratinjau Surat</h2>
            <p class="mt-1 text-xs text-slate-500">Diajukan pada {{ optional($suratPengajuan->created_at)->format('d M Y, H:i') }}</p>
        </div>

        @if($canPreview)
        <iframe src="{{ $previewUrl }}#toolbar=1&navpanes=0&scrollbar=1"
            class="h-[70vh] min-h-[520px] w-full bg-slate-100"
            title="Pratinjau {{ $suratPengajuan->jenisLabel() }} untuk {{ $suratPengajuan->user?->name ?? 'warga' }}">
        </iframe>
        @else
        <div class="p-8 text-center">
            <i class="fa-solid fa-file-circle-xmark text-3xl text-slate-400"></i>
            <p class="mt-3 text-sm font-semibold text-slate-700">Pratinjau surat tidak tersedia</p>
            <p class="mt-1 text-sm text-slate-500">Pengajuan ini berstatus ditolak.</p>
        </div>
        @endif

    </section>

    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-5">
            <h2 class="text-base font-bold text-slate-800">Pengaturan Surat</h2>
            <p class="mt-1 text-sm text-slate-500">Atur nomor surat, status, dan catatan untuk warga.</p>
        </div>

        <form id="update-status-{{ $suratPengajuan->id }}" method="POST"
            action="{{ route($routePrefix . '.surat-pengajuan.update-status', $suratPengajuan) }}"
            class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="nomor_urut" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor Surat</label>
                    <div class="flex min-w-0 items-stretch overflow-hidden rounded-md border border-slate-300 bg-white focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100">
                        <span class="flex shrink-0 items-center border-r border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-500">{{ $nomorSetting['kode_klasifikasi'] }}/</span>
                        <input id="nomor_urut" type="text" name="nomor_urut"
                            value="{{ old('nomor_urut', $suratPengajuan->nomorUrutDisplay()) }}"
                            inputmode="numeric" pattern="[0-9]*" placeholder="001"
                            class="min-w-0 w-20 flex-1 border-0 px-3 py-2.5 text-sm font-semibold text-slate-800 focus:ring-0">
                        <span class="flex shrink-0 items-center border-l border-slate-200 bg-slate-50 px-3 text-sm text-slate-500">/{{ $nomorSuratSuffix }}</span>
                    </div>
                    @error('nomor_urut')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-sm font-semibold text-slate-700">Status Surat</label>
                    <select id="status" name="status"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-orange-500 focus:ring-orange-500">
                        <option value="menunggu" @selected(old('status', $suratPengajuan->status) === 'menunggu' || old('status', $suratPengajuan->status) === 'diajukan')>Menunggu</option>
                        <option value="disetujui" @selected(old('status', $suratPengajuan->status) === 'disetujui')>Disetujui</option>
                        <option value="ditolak" @selected(old('status', $suratPengajuan->status) === 'ditolak')>Ditolak</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="admin_note" class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan Petugas</label>
                <textarea id="admin_note" name="admin_note" rows="4" maxlength="2000"
                    placeholder="Tulis catatan untuk warga (opsional)"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-orange-500 focus:ring-orange-500">{{ old('admin_note', $suratPengajuan->admin_note) }}</textarea>
                @error('admin_note')
                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </form>

        <div class="flex flex-wrap items-center gap-2 border-t border-slate-200 pt-5">
            <button type="submit" form="update-status-{{ $suratPengajuan->id }}"
                class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan
            </button>

            @if($isApproved)
            <a href="{{ route('layanan.pengajuan.download', $suratPengajuan) }}"
                class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                <i class="fa-solid fa-download"></i>
                Unduh
            </a>
            @if($suratPengajuan->whatsappUrl())
            <a href="{{ $suratPengajuan->whatsappUrl() }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                <i class="fa-brands fa-whatsapp"></i>
                Kirim WA
            </a>
            @else
            <span class="text-xs text-slate-500">Nomor WhatsApp warga belum tersedia.</span>
            @endif
            @else
            <span class="text-xs text-slate-500">Unduh dan Kirim WA tersedia setelah surat disetujui.</span>
            @endif
        </div>
    </section>
</div>
@endsection
