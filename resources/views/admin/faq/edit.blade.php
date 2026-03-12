@extends('layouts.admin-dashboard')

@section('content')
<div class="p-8">
    <h1 class="mb-6 text-2xl font-bold text-slate-800">Edit FAQ Chatbot</h1>

    <form action="{{ route('admin.faq.update', $faq->id_faq) }}" method="POST" class="space-y-4 rounded-xl bg-white p-6 shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Pertanyaan</label>
            <input type="text" name="pertanyaan" value="{{ old('pertanyaan', $faq->pertanyaan) }}" class="w-full rounded border border-slate-300 px-3 py-2" required>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Jawaban</label>
            <textarea name="jawaban" rows="5" class="w-full rounded border border-slate-300 px-3 py-2" required>{{ old('jawaban', $faq->jawaban) }}</textarea>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-slate-700">Kategori</label>
            <input type="text" name="kategori" value="{{ old('kategori', $faq->kategori) }}" class="w-full rounded border border-slate-300 px-3 py-2" required>
        </div>

        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_aktif" value="1" {{ (int) old('is_aktif', $faq->is_aktif) === 1 ? 'checked' : '' }}>
            Aktifkan FAQ
        </label>

        <div class="space-x-2">
            <button type="submit" class="rounded bg-orange-500 px-4 py-2 text-white hover:bg-orange-600">Update</button>
            <a href="{{ route('admin.faq.index') }}" class="rounded bg-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-400">Kembali</a>
        </div>
    </form>
</div>
@endsection

