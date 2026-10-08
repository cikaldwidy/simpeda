@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Perangkat Desa')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-3xl font-bold tracking-[1px] text-slate-800">Data Perangkat Desa</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola data perangkat desa, jabatan, foto, dan urutan tampilan.</p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex justify-end">
            <button type="button" data-open-modal="createPerangkatModal"
                class="inline-flex items-center justify-center rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600">
                + Tambah Data
            </button>
        </div>
        <div class="rounded-md border border-gray-100 bg-white shadow-sm" data-bulk-selection>
            @include('partials.admin-bulk-delete-toolbar', [
                'formId' => 'bulk-delete-perangkat',
                'action' => route('admin.perangkat.bulk-destroy'),
                'title' => 'Hapus perangkat desa terpilih?',
                'message' => 'Data perangkat desa yang dipilih tidak dapat dikembalikan.',
            ])
            <div class="w-full max-w-full overflow-x-auto">
                <table class="min-w-[980px] w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                                <th data-bulk-selection-cell class="hidden px-4 py-3 text-center">
                                    <input type="checkbox" data-bulk-select-all disabled aria-label="Pilih semua perangkat di halaman ini"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                </th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Foto</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Nama</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Jabatan</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Tempat, Tanggal Lahir</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Urutan</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($perangkat as $item)
                            <tr class="group transition hover:bg-gray-50/50">
                                <td data-bulk-selection-cell class="hidden px-4 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" form="bulk-delete-perangkat"
                                        data-bulk-select-row aria-label="Pilih perangkat {{ $item->nama }}"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                </td>
                                <td class="px-4 py-4">
                                    <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}"
                                        class="h-14 w-14 rounded-lg border border-slate-200 object-cover">
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold tracking-[.5px] text-gray-800">
                                    {{ $item->nama }}</td>
                                <td class="px-4 py-4 text-sm tracking-[.5px] text-gray-700">{{ $item->jabatan }}</td>
                                    <td class="px-4 py-4 text-sm tracking-[.5px] text-gray-700">
                                        @if($item->tempat_lahir || $item->tanggal_lahir)
                                            {{ $item->tempat_lahir }}{{ $item->tempat_lahir && $item->tanggal_lahir ? ', ' : '' }}{{ $item->tanggal_lahir?->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium tracking-[.5px] text-gray-800">
                                        {{ $item->urutan }}</td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-open-modal="editPerangkatModal-{{ $item->id }}"
                                            class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500 tracking-[.5px]">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.perangkat.destroy', $item->id) }}" method="POST"
                                            data-delete-title="Hapus data perangkat desa?"
                                            data-delete-message="Data perangkat desa yang dihapus tidak dapat dikembalikan.">
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
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">Belum ada data perangkat
                                    desa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('partials.admin-pagination-footer', ['paginator' => $perangkat, 'label' => 'perangkat desa'])
</div>

<div id="createPerangkatModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Tambah Perangkat Desa</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form action="{{ route('admin.perangkat.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 px-5 py-5">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Foto</label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-close-modal
                        class="rounded-md bg-red-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-red-400">Batal</button>
                    <button type="submit"
                        class="rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-orange-400">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($perangkat as $item)
<div id="editPerangkatModal-{{ $item->id }}"
    class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Edit Perangkat Desa</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form action="{{ route('admin.perangkat.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 px-5 py-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Nama</label>
                    <input type="text" name="nama" value="{{ $item->nama }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ $item->jabatan }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ $item->tempat_lahir }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ $item->tanggal_lahir?->format('Y-m-d') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Foto Saat Ini</label>
                    <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}"
                        class="h-20 w-20 rounded-md border border-slate-200 object-cover">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan</label>
                    <input type="number" name="urutan" value="{{ $item->urutan }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-close-modal
                        class="rounded-md bg-red-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-red-400">Batal</button>
                    <button type="submit"
                        class="rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-orange-400">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
(() => {
    const openBtns = document.querySelectorAll('[data-open-modal]');
    const closeBtns = document.querySelectorAll('[data-close-modal]');

    const closeAll = () => {
        document.querySelectorAll('[id$="Modal"], [id^="editPerangkatModal-"]').forEach((el) => {
            el.classList.add('hidden');
            el.classList.remove('flex');
        });
    };

    openBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-open-modal');
            const modal = targetId ? document.getElementById(targetId) : null;
            if (!modal) return;
            closeAll();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    closeBtns.forEach((btn) => {
        btn.addEventListener('click', closeAll);
    });

    document.querySelectorAll('[id$="Modal"], [id^="editPerangkatModal-"]').forEach((modal) => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeAll();
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });
})();
</script>
@endpush
