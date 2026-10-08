@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Manajemen Artikel')

@section('content')
@php
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
@endphp
<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-3xl font-bold tracking-[1px] text-slate-800">Manajemen Artikel</h1>
        <p class="mt-2 text-sm text-slate-600">Kelola artikel publik desa dari satu halaman.</p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route($routePrefix . '.artikel.index') }}"
            class="grid grid-cols-1 gap-3 md:grid-cols-12">
            <div class="md:col-span-7">
                <label for="q"
                    class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Pencarian
                    Artikel</label>
                <input id="q" type="text" name="q" value="{{ $q ?? '' }}"
                    placeholder="Cari judul, ringkasan, isi, atau slug..."
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
            </div>
            <div class="md:col-span-3">
                <label for="status"
                    class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Filter
                    Status</label>
                <select id="status" name="status"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                    <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="published" {{ ($status ?? 'all') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="draft" {{ ($status ?? 'all') === 'draft' ? 'selected' : '' }}>Draf</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm" data-bulk-selection>
        <div class="mb-4 flex justify-end">
            <button type="button" data-open-modal="createArtikelModal"
                class="inline-flex items-center justify-center rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600">
                + Tambah Artikel
            </button>
        </div>
        @include('partials.admin-bulk-delete-toolbar', [
            'formId' => 'bulk-delete-artikel',
            'action' => route($routePrefix . '.artikel.bulk-destroy'),
            'title' => 'Hapus artikel terpilih?',
            'message' => 'Artikel yang dipilih tidak dapat dikembalikan.',
        ])
        <div class="rounded-md border border-gray-100 bg-white shadow-sm">
            <div class="w-full max-w-full overflow-x-auto">
                <table class="min-w-[1080px] w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                                <th data-bulk-selection-cell class="hidden px-4 py-3 text-center">
                                    <input type="checkbox" data-bulk-select-all disabled aria-label="Pilih semua artikel di halaman ini"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                </th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Gambar</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Judul</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Ringkasan</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Status</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Tanggal</th>
                                <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($artikel as $item)
                            <tr class="group transition hover:bg-gray-50/50">
                                <td data-bulk-selection-cell class="hidden px-4 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" form="bulk-delete-artikel"
                                        data-bulk-select-row aria-label="Pilih artikel {{ $item->judul }}"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                </td>
                                <td class="px-4 py-4">
                                    @if($item->gambar)
                                    <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->judul }}"
                                        class="h-14 w-24 rounded-lg border border-slate-200 object-cover">
                                    @else
                                    <div class="flex h-14 w-24 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-xs text-slate-500">No Image</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold tracking-[.5px] text-gray-800">{{ $item->judul }}</td>
                                <td class="px-4 py-4 text-sm tracking-[.5px] text-gray-700">{{ \Illuminate\Support\Str::limit($item->ringkasan, 80) ?: '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md border px-2 py-1 text-xs font-semibold tracking-[.5px] {{ $item->is_published ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-700' }}">
                                        {{ $item->is_published ? 'Dipublikasikan' : 'Draf' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium tracking-[.5px] text-gray-800">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-open-modal="editArtikelModal-{{ $item->id }}"
                                            class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500 tracking-[.5px]">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </button>

                                        <form action="{{ route($routePrefix . '.artikel.destroy', $item->id) }}" method="POST"
                                            data-delete-title="Hapus artikel ini?"
                                            data-delete-message="Artikel yang dihapus tidak dapat dikembalikan.">
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
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">Belum ada artikel.</td>
                            </tr>
                            @endforelse
                        </tbody>
                </table>
            </div>
        </div>

    </div>

    @include('partials.admin-pagination-footer', ['paginator' => $artikel, 'label' => 'artikel'])
</div>

<div id="createArtikelModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Tambah Artikel</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form action="{{ route($routePrefix . '.artikel.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 px-5 py-5">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Judul</label>
                    <input type="text" name="judul"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Ringkasan</label>
                    <textarea name="ringkasan" rows="3"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Isi Artikel</label>
                    <textarea id="create_isi_artikel" name="isi" rows="8"
                        class="js-artikel-editor w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Gambar</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input id="create_artikel_is_published" type="checkbox" name="is_published" value="1"
                        class="h-4 w-4" checked>
                    <label for="create_artikel_is_published" class="text-sm font-semibold text-slate-700">Publikasikan
                        sekarang</label>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-close-modal
                        class="rounded-md bg-red-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-red-400">Batal</button>
                    <button type="submit"
                        class="relative z-[80] rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-orange-400">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($artikel as $item)
<div id="editArtikelModal-{{ $item->id }}" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Edit Artikel</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form action="{{ route($routePrefix . '.artikel.update', $item->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-4 px-5 py-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Judul</label>
                    <input type="text" name="judul" value="{{ $item->judul }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Ringkasan</label>
                    <textarea name="ringkasan" rows="3"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">{{ $item->ringkasan }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Isi Artikel</label>
                    <textarea id="edit_isi_artikel_{{ $item->id }}" name="isi" rows="8"
                        class="js-artikel-editor w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">{{ $item->isi }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Gambar Saat Ini</label>
                    @if($item->gambar)
                    <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->judul }}"
                        class="h-20 w-32 rounded-md border border-slate-200 object-cover">
                    @else
                    <p class="text-sm text-slate-500">Belum ada gambar.</p>
                    @endif
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input id="edit_artikel_is_published_{{ $item->id }}" type="checkbox" name="is_published"
                        value="1" class="h-4 w-4" @checked($item->is_published)>
                    <label for="edit_artikel_is_published_{{ $item->id }}"
                        class="text-sm font-semibold text-slate-700">Publikasikan</label>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-close-modal
                        class="rounded-md bg-red-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-red-400">Batal</button>
                    <button type="submit"
                        class="relative z-[80] rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-orange-400">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(() => {
    const initArtikelEditors = () => {
        if (typeof tinymce === 'undefined') return;

        tinymce.remove('.js-artikel-editor');
        tinymce.init({
            selector: '.js-artikel-editor',
            height: 320,
            menubar: 'file edit insert view format table tools',
            plugins: 'lists link image table code preview wordcount fullscreen',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | removeformat | code preview fullscreen',
            branding: false,
            promotion: false,
            statusbar: true,
            z_index: 50,
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }'
        });
    };

    initArtikelEditors();

    const openBtns = document.querySelectorAll('[data-open-modal]');
    const closeBtns = document.querySelectorAll('[data-close-modal]');

    const closeAll = () => {
        document.querySelectorAll('[id$="Modal"], [id^="editArtikelModal-"]').forEach((el) => {
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
            setTimeout(() => {
                initArtikelEditors();
            }, 0);
        });
    });

    closeBtns.forEach((btn) => {
        btn.addEventListener('click', closeAll);
    });

    document.querySelectorAll('[id$="Modal"], [id^="editArtikelModal-"]').forEach((modal) => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeAll();
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });

    document.querySelectorAll('form').forEach((form) => {
        if (form.querySelector('.js-artikel-editor')) {
            form.setAttribute('novalidate', '');
        }
        form.addEventListener('submit', () => {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    });
})();
</script>
@endpush
