@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Admin Pengajuan Surat')

@section('content')
@php
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
@endphp
<div class="space-y-6">
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-[1px]">Pengajuan Surat Warga</h1>
                <p class="mt-2 text-sm text-slate-600">Kelola status pengajuan surat warga dan kode nomor surat per kategori.</p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <form method="GET" class="flex flex-1 flex-col gap-3 md:flex-row md:items-end">
                <div class="w-full md:max-w-xl">
                    <label for="q"
                        class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Pencarian
                        Pengajuan</label>
                    <input id="q" type="text" name="q" value="{{ $search ?? '' }}"
                        placeholder="Cari Nama, NIK, Nomor Surat, Jenis Surat, atau Status..."
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-500 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
                </div>
                <button type="submit"
                    class="rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                    Cari
                </button>
            </form>
            <button type="button" data-nomor-settings-open
                class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-300 bg-white text-slate-700 shadow-sm transition hover:border-orange-500 hover:bg-orange-500 hover:text-white"
                title="Pengaturan nomor surat" aria-label="Pengaturan nomor surat">
                <i class="fa-solid fa-gear"></i>
            </button>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-gray-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col items-start gap-2">
                <button type="button" data-toggle-request-selection aria-pressed="false"
                    class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-orange-500 hover:text-orange-600">
                    <i class="fa-solid fa-check-to-slot"></i>
                    <span data-selection-toggle-label>Pilih</span>
                </button>
            </div>
            <div data-bulk-selection-controls class="hidden flex-col gap-3 sm:flex-row sm:items-center">
                <form id="bulkDeleteRequestsForm" method="POST"
                    action="{{ route($routePrefix . '.surat-pengajuan.bulk-destroy') }}"
                    data-delete-title="Hapus pengajuan terpilih?"
                    data-delete-message="Semua pengajuan surat yang dipilih akan dihapus dan tidak dapat dikembalikan.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" data-delete-selected-requests disabled
                        class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-500 disabled:cursor-not-allowed disabled:opacity-50">
                        <i class="fa-solid fa-trash"></i>
                        <span data-delete-selected-label>Hapus (0)</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px]">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                                <th data-request-selection-cell class="hidden w-12 px-4 py-3 text-center">
                                    <input type="checkbox" data-select-all-requests disabled
                                        aria-label="Pilih semua pengajuan pada halaman ini"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                </th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Jenis Surat</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Status Surat</th>
                                <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pengajuans as $item)
                            <tr class="group transition hover:bg-gray-50/50">
                                <td data-request-selection-cell class="hidden px-4 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        form="bulkDeleteRequestsForm" data-request-selection
                                        aria-label="Pilih pengajuan surat {{ $item->user?->name ?? '' }}"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                </td>
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
                                    <div class="space-y-1">
                                        <span
                                            class="block text-sm font-medium tracking-[.5px] text-gray-800">{{ $item->user?->name ?? '-' }}</span>
                                        <span class="block text-xs text-gray-500">
                                            No. HP: {{ $item->user?->no_hp ?: '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="text-sm font-medium tracking-[.5px] text-gray-800">{{ $jenisOptions[$item->jenis_surat] ?? '-' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    @php
                                    $statusStyle = match ($item->status) {
                                        'disetujui' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                        'ditolak' => 'border-red-200 bg-red-50 text-red-700',
                                        default => 'border-amber-200 bg-amber-50 text-amber-700',
                                    };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 rounded-md border px-2.5 py-1 text-xs font-semibold {{ $statusStyle }}">
                                        <i class="fa-solid {{ $item->status === 'disetujui' ? 'fa-circle-check' : ($item->status === 'ditolak' ? 'fa-circle-xmark' : 'fa-clock') }}"></i>
                                        {{ ucfirst($item->status === 'diajukan' ? 'menunggu' : $item->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route($routePrefix . '.surat-pengajuan.show', $item) }}"
                                            class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500">
                                            <i class="fa-solid fa-eye"></i>
                                            Lihat
                                        </a>
                                        <form method="POST"
                                            action="{{ route($routePrefix . '.surat-pengajuan.destroy', $item) }}"
                                            data-delete-title="Hapus pengajuan surat ini?"
                                            data-delete-message="Pengajuan surat yang dihapus tidak dapat dikembalikan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-md border border-red-300 bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500">
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

    @include('partials.admin-pagination-footer', ['paginator' => $pengajuans, 'label' => 'pengajuan surat'])
</div>

<div id="nomorSettingsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" aria-hidden="true">
    <div class="w-full max-w-4xl rounded-lg border border-gray-200 bg-white shadow-xl">
        <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Pengaturan Nomor Surat</h2>
                <p class="mt-1 text-xs text-slate-500">Format nomor: kode klasifikasi / nomor urut surat keluar / kode wilayah / tahun terbit.</p>
            </div>
            <button type="button" data-nomor-settings-close
                class="inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
                aria-label="Tutup pengaturan nomor surat">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route($routePrefix . '.surat-pengajuan.nomor-settings') }}">
            @csrf
            @method('PATCH')
            <div class="max-h-[70vh] overflow-y-auto p-5">
                <div class="overflow-x-auto">
                    <table class="min-w-[680px] w-full">
                        <thead>
                            <tr class="border-y border-slate-200 bg-slate-50">
                                <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-[1px] text-slate-600">Kategori Surat</th>
                                <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-[1px] text-slate-600">Kode Klasifikasi Arsip</th>
                                <th class="px-3 py-2 text-left text-xs font-bold uppercase tracking-[1px] text-slate-600">Kode Wilayah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($nomorSettings as $jenis => $setting)
                            <tr>
                                <td class="px-3 py-3">
                                    <input type="hidden" name="settings[{{ $jenis }}][jenis_surat]" value="{{ $jenis }}">
                                    <span class="text-sm font-semibold text-slate-800">{{ $setting['label'] }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    <input type="text" name="settings[{{ $jenis }}][kode_klasifikasi]"
                                        value="{{ old('settings.' . $jenis . '.kode_klasifikasi', $setting['kode_klasifikasi']) }}"
                                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold uppercase text-slate-700 focus:border-orange-500 focus:ring-orange-500">
                                </td>
                                <td class="px-3 py-3">
                                    <input type="text" name="settings[{{ $jenis }}][kode_wilayah]"
                                        value="{{ old('settings.' . $jenis . '.kode_wilayah', $setting['kode_wilayah']) }}"
                                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold uppercase text-slate-700 focus:border-orange-500 focus:ring-orange-500">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($errors->any())
                <p class="mt-3 text-xs font-semibold text-red-600">{{ $errors->first() }}</p>
                @endif
            </div>
            <div class="flex justify-end gap-2 border-t border-gray-200 px-5 py-4">
                <button type="button" data-nomor-settings-close
                    class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Kode Nomor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const selectionToggle = document.querySelector('[data-toggle-request-selection]');
    const selectionToggleLabel = document.querySelector('[data-selection-toggle-label]');
    const selectionControls = document.querySelector('[data-bulk-selection-controls]');
    const selectionCells = document.querySelectorAll('[data-request-selection-cell]');
    const selectAll = document.querySelector('[data-select-all-requests]');
    const selections = Array.from(document.querySelectorAll('[data-request-selection]'));
    const deleteSelectedButton = document.querySelector('[data-delete-selected-requests]');
    const deleteSelectedLabel = document.querySelector('[data-delete-selected-label]');

    if (selectionToggle && selectionToggleLabel && selectionControls && selectAll && deleteSelectedButton && deleteSelectedLabel) {
        const setSelectionMode = (enabled) => {
            selectionToggle.setAttribute('aria-pressed', String(enabled));
            selectionToggleLabel.textContent = enabled ? 'Batal pilih' : 'Pilih';
            selectionControls.classList.toggle('hidden', !enabled);
            selectionCells.forEach((cell) => cell.classList.toggle('hidden', !enabled));
            selectAll.disabled = !enabled;

            if (!enabled) {
                selections.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }

            refreshSelection();
        };

        const refreshSelection = () => {
            const checkedCount = selections.filter((checkbox) => checkbox.checked).length;

            deleteSelectedLabel.textContent = `Hapus (${checkedCount})`;
            deleteSelectedButton.disabled = checkedCount === 0;
            selectAll.checked = selections.length > 0 && checkedCount === selections.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < selections.length;
        };

        selectionToggle.addEventListener('click', () => {
            const enabled = selectionToggle.getAttribute('aria-pressed') !== 'true';
            setSelectionMode(enabled);
        });
        selectAll.addEventListener('change', () => {
            selections.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            });
            refreshSelection();
        });
        selections.forEach((checkbox) => checkbox.addEventListener('change', refreshSelection));
        setSelectionMode(false);
        refreshSelection();
    }

    const modal = document.getElementById('nomorSettingsModal');
    const openButton = document.querySelector('[data-nomor-settings-open]');
    const closeButtons = document.querySelectorAll('[data-nomor-settings-close]');

    if (!modal || !openButton) return;

    const open = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    };

    openButton.addEventListener('click', open);
    closeButtons.forEach((button) => button.addEventListener('click', close));
    modal.addEventListener('click', (event) => {
        if (event.target === modal) close();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
    });
})();
</script>
@endpush
