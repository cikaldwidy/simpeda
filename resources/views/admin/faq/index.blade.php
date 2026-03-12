@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | FAQ Chatbot')

@section('content')
@php
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
@endphp
<section class="min-h-screen bg-slate-100 py-8">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-[1px] text-slate-800 md:text-left text-center">Manajemen
                        FAQ Chatbot</h1>
                    <p class="mt-1 text-base text-slate-600 md:text-left text-center">Kelola pertanyaan dan jawaban
                        untuk chatbot layanan desa.</p>
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
                <div class="mb-4 flex justify-end px-4 pt-4">
                    <button type="button" data-open-modal="createFaqModal"
                        class="inline-flex rounded-lg bg-emerald-700 px-4 py-1.5 text-sm font-semibold text-white hover:bg-emerald-600">
                        + Tambah FAQ
                    </button>
                </div>
                <div class="w-full max-w-full overflow-x-auto">
                    <table class="min-w-[980px] w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Pertanyaan</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Jawaban</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Kategori</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Status</th>
                                <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($faqs as $item)
                            <tr class="group transition hover:bg-gray-50/50">
                                <td class="px-4 py-4 text-sm font-semibold tracking-[.5px] text-gray-800">{{ $item->pertanyaan }}</td>
                                <td class="px-4 py-4 text-sm tracking-[.5px] text-gray-700">{{ \Illuminate\Support\Str::limit($item->jawaban, 120) }}</td>
                                <td class="px-4 py-4 text-sm font-medium tracking-[.5px] text-gray-800">{{ $item->kategori }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md border px-2 py-1 text-xs font-semibold tracking-[.5px] {{ (int)$item->is_aktif === 1 ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-700' }}">
                                        {{ (int)$item->is_aktif === 1 ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-open-modal="editFaqModal-{{ $item->id_faq }}"
                                            class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500 tracking-[.5px]">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </button>

                                        <form action="{{ route($routePrefix . '.faq.destroy', $item->id_faq) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus FAQ ini?')">
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
                                <td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada FAQ.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>
</section>

<div id="createFaqModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Tambah FAQ</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form action="{{ route($routePrefix . '.faq.store') }}" method="POST" class="space-y-4 px-5 py-5">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Pertanyaan</label>
                    <input type="text" name="pertanyaan" value="{{ old('pertanyaan') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Jawaban</label>
                    <textarea name="jawaban" rows="5"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>{{ old('jawaban') }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', 'umum') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_aktif" value="0">
                    <input id="create_faq_is_aktif" type="checkbox" name="is_aktif" value="1" class="h-4 w-4" checked>
                    <label for="create_faq_is_aktif" class="text-sm font-semibold text-slate-700">Aktifkan FAQ</label>
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

@foreach($faqs as $item)
<div id="editFaqModal-{{ $item->id_faq }}" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Edit FAQ</h3>
            <button type="button" data-close-modal
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="max-h-[calc(90vh-72px)] overflow-y-auto">
            <form action="{{ route($routePrefix . '.faq.update', $item->id_faq) }}" method="POST"
                class="space-y-4 px-5 py-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Pertanyaan</label>
                    <input type="text" name="pertanyaan" value="{{ $item->pertanyaan }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Jawaban</label>
                    <textarea name="jawaban" rows="5"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>{{ $item->jawaban }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Kategori</label>
                    <input type="text" name="kategori" value="{{ $item->kategori }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                        required>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_aktif" value="0">
                    <input id="edit_faq_is_aktif_{{ $item->id_faq }}" type="checkbox" name="is_aktif" value="1"
                        class="h-4 w-4" @checked((int)$item->is_aktif === 1)>
                    <label for="edit_faq_is_aktif_{{ $item->id_faq }}" class="text-sm font-semibold text-slate-700">Aktifkan FAQ</label>
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
        document.querySelectorAll('[id$="Modal"], [id^="editFaqModal-"]').forEach((el) => {
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

    document.querySelectorAll('[id$="Modal"], [id^="editFaqModal-"]').forEach((modal) => {
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
