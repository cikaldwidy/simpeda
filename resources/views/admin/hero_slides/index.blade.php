@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Slider Baner')

@section('content')
<section class="min-h-screen bg-slate-100 py-8">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 tracking-[1px] md:text-left text-center">Konten Slider
                        Baner</h1>
                    </h1>
                    <p class="mt-1 text-base text-slate-600 md:text-left text-center">Kelola konten slider utama di
                        landing page.</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Gagal menyimpan data:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="rounded-md border border-gray-100 bg-white shadow-sm">
                <div class="mb-4 flex justify-end">
                    <button type="button" data-open-modal="createSlideModal"
                        class="inline-flex rounded-lg bg-emerald-700 px-4 py-1.5 text-sm font-semibold text-white hover:bg-emerald-600">
                        + Tambah Slide
                    </button>
                </div>
                <div class="w-full max-w-full overflow-x-auto">
                    <table class="min-w-[980px] w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Urutan</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Gambar</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Judul</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Deskripsi</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">
                                    Status</th>
                                <th
                                    class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($slides as $s)
                            <tr class="group transition hover:bg-gray-50/50">
                                <td class="px-4 py-4 text-sm font-medium tracking-[.5px] text-gray-800">
                                    {{ $s->sort_order }}</td>
                                <td class="px-4 py-4">
                                    <img class="h-14 w-28 rounded-lg object-cover border border-slate-200"
                                        src="{{ Storage::url($s->image_path) }}" alt="{{ $s->title }}">
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold tracking-[.5px] text-gray-800">
                                    {{ $s->title }}</td>
                                <td class="px-4 py-4 text-sm tracking-[.5px] text-gray-700">{{ $s->description ?: '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md border px-2 py-1 text-xs font-semibold tracking-[.5px] {{ $s->is_active ? 'border-emerald-200 bg-emerald-100 text-emerald-800' : 'border-slate-200 bg-slate-100 text-slate-800' }}">
                                        {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-open-modal="editSlideModal-{{ $s->id }}"
                                            class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500 tracking-[.5px]">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.hero-slides.destroy', $s->id) }}"
                                            onsubmit="return confirm('Hapus slide ini?')">
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
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada data hero slide.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="createSlideModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Tambah Slide Konten</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form class="space-y-4 px-5 py-5" method="POST" action="{{ route('admin.hero-slides.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Judul</label>
                    <input name="title" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <input name="description" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan</label>
                        <input type="number" min="1" name="sort_order" value="1"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Gambar</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm" required>

                    <input id="create_is_active" type="checkbox" name="is_active" value="1" class="h-4 w-4" checked>
                    <label for="create_is_active" class="text-sm font-semibold text-slate-700 mt-5">Aktif</label>

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

@foreach($slides as $s)
<div id="editSlideModal-{{ $s->id }}" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Edit Slide</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form class="space-y-4 px-5 py-5" method="POST" action="{{ route('admin.hero-slides.update', $s->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Judul</label>
                    <input name="title" value="{{ $s->title }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <input name="description" value="{{ $s->description }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan</label>
                        <input type="number" min="1" name="sort_order" value="{{ $s->sort_order }}"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Gambar Saat Ini</label>
                    <img class="h-20 w-36 rounded-md border border-slate-200 object-cover"
                        src="{{ Storage::url($s->image_path) }}" alt="{{ $s->title }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Ganti Gambar (opsional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <input id="edit_is_active_{{ $s->id }}" type="checkbox" name="is_active" value="1" class="h-4 w-4"
                        @checked($s->is_active)>
                    <label for="edit_is_active_{{ $s->id }}"
                        class="text-sm font-semibold text-slate-700 mt-5">Aktif</label>
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
        document.querySelectorAll('[id$="Modal"], [id^="editSlideModal-"]').forEach((el) => {
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
        btn.addEventListener('click', () => {
            closeAll();
        });
    });

    document.querySelectorAll('[id$="Modal"], [id^="editSlideModal-"]').forEach((modal) => {
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