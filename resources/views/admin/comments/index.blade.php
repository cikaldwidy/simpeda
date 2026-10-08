@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Moderasi Komentar')

@section('content')
@php
$isPetugas = request()->routeIs('petugas.*');
$routePrefix = $isPetugas ? 'petugas' : 'admin';
@endphp

<div class="space-y-6">
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-[1px] text-slate-800">Kelola Komentar</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Tinjau komentar dari halaman detail berita dan artikel.
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route($routePrefix . '.comments.index') }}"
            class="flex flex-col gap-3 md:flex-row md:items-end">
            <div class="w-full md:max-w-xl">
                <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Cari
                    Komentar</label>
                <input id="q" type="text" name="q" value="{{ $search }}"
                    placeholder="Cari nama, email, isi komentar, atau tipe konten..."
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-500 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <button type="submit"
                class="rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                Cari
            </button>
        </form>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-sm" data-bulk-selection>
        @include('partials.admin-bulk-delete-toolbar', [
            'formId' => 'bulk-delete-comments',
            'action' => route($routePrefix . '.comments.bulk-destroy'),
            'title' => 'Hapus komentar terpilih?',
            'message' => 'Komentar yang dipilih tidak dapat dikembalikan.',
        ])
        <div class="overflow-x-auto">
            <table class="min-w-[1080px] w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-800 to-slate-700">
                        <th data-bulk-selection-cell class="hidden px-4 py-3 text-center">
                            <input type="checkbox" data-bulk-select-all disabled aria-label="Pilih semua komentar di halaman ini"
                                class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Tanggal
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Pengirim
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Konten
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[1px] text-white">Komentar
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-[1px] text-white">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($comments as $item)
                    @php
                    $content = $item->commentable;
                    $isBeritaComment = $item->commentable_type === \App\Models\Berita::class;
                    $contentTitle = $content->judul ?? 'Konten sudah tidak tersedia';
                    $contentRoute = $content
                    ? ($isBeritaComment ? route('berita.show', $content->slug) : route('artikel.show', $content->slug))
                    : null;
                    @endphp
                    <tr class="hover:bg-gray-50/70">
                        <td data-bulk-selection-cell class="hidden px-4 py-4 text-center align-top">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" form="bulk-delete-comments"
                                data-bulk-select-row aria-label="Pilih komentar dari {{ $item->name }}"
                                class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </td>
                        <td class="px-4 py-4 align-top text-sm text-slate-700">
                            <div>{{ optional($item->created_at)->format('d-m-Y') }}</div>
                            <div class="text-xs text-slate-500">{{ optional($item->created_at)->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-4 align-top">
                            <p class="text-sm font-semibold text-slate-800">{{ $item->name }}</p>
                            <p class="text-xs text-slate-500">{{ $item->email }}</p>
                        </td>
                        <td class="px-4 py-4 align-top">
                            <span
                                class="inline-flex rounded-full px-2 py-1 text-[11px] font-semibold {{ $isBeritaComment ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $isBeritaComment ? 'Berita' : 'Artikel' }}
                            </span>
                            <div class="mt-2 text-sm text-slate-700">
                                @if($contentRoute)
                                <a href="{{ $contentRoute }}" target="_blank" rel="noopener noreferrer"
                                    class="font-semibold text-blue-700 underline hover:text-blue-500">
                                    {{ $contentTitle }}
                                </a>
                                @else
                                <span class="font-semibold text-slate-500">{{ $contentTitle }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 align-top">
                            <p class="max-w-3xl whitespace-pre-line text-sm leading-6 text-slate-700">
                                {{ $item->comment }}
                            </p>
                        </td>
                        <td class="px-4 py-4 align-top text-center">
                            <form action="{{ route($routePrefix . '.comments.destroy', $item) }}" method="POST"
                                data-delete-title="Hapus komentar ini?"
                                data-delete-message="Komentar yang dihapus tidak dapat dikembalikan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-red-300 bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500 tracking-[.5px]">
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">
                            Belum ada komentar masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.admin-pagination-footer', ['paginator' => $comments, 'label' => 'komentar'])
</div>
@endsection
