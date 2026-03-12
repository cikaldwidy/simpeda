@extends('layouts.admin-dashboard')

@section('content')
<div class="p-8 max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold text-slate-800 mb-6">
        Edit Artikel
    </h1>

    <form action="{{ route('admin.artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-2 font-medium">Judul</label>
            <input type="text" name="judul" value="{{ $artikel->judul }}" class="w-full border rounded-lg p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Ringkasan</label>
            <textarea name="ringkasan" rows="3" class="w-full border rounded-lg p-3">{{ $artikel->ringkasan }}</textarea>
        </div>

        <div>
            <label class="block mb-2 font-medium">Isi Artikel</label>
            <div class="border rounded-lg overflow-hidden">
                <div class="flex flex-wrap gap-2 p-3 bg-slate-50 border-b">
                    <button type="button" data-editor-command="bold" class="px-3 py-1.5 text-sm font-semibold border rounded hover:bg-slate-100">Bold</button>
                    <button type="button" data-editor-command="italic" class="px-3 py-1.5 text-sm italic border rounded hover:bg-slate-100">Italic</button>
                    <button type="button" data-editor-command="insertOrderedList" class="px-3 py-1.5 text-sm border rounded hover:bg-slate-100">1. List</button>
                    <button type="button" data-editor-command="insertUnorderedList" class="px-3 py-1.5 text-sm border rounded hover:bg-slate-100">Bullet List</button>
                </div>
                <div id="isi-editor" contenteditable="true" class="min-h-[220px] p-3 focus:outline-none [&_ol]:list-decimal [&_ul]:list-disc [&_ol]:pl-6 [&_ul]:pl-6 [&_li]:mb-1"></div>
            </div>
            <textarea id="isi" name="isi" class="hidden">{{ old('isi', $artikel->isi) }}</textarea>
            @error('isi')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-2 font-medium">Gambar Saat Ini</label>
            @if($artikel->gambar)
            <img src="{{ asset('storage/'.$artikel->gambar) }}" class="w-40 rounded mb-3">
            @endif
            <input type="file" name="gambar" class="w-full border rounded-lg p-2">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1" {{ $artikel->is_published ? 'checked' : '' }}>
            <label>Publish</label>
        </div>

        <div class="flex gap-3">
            <button class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                Update
            </button>

            <a href="{{ route('admin.artikel.index') }}" class="px-6 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('isi-editor');
    const textarea = document.getElementById('isi');
    if (!editor || !textarea) return;

    editor.innerHTML = textarea.value || '';

    const syncEditor = () => {
        textarea.value = editor.innerHTML.trim();
    };

    document.querySelectorAll('[data-editor-command]').forEach((button) => {
        button.addEventListener('click', () => {
            const command = button.dataset.editorCommand;
            editor.focus();
            document.execCommand(command, false);
            syncEditor();
        });
    });

    editor.addEventListener('input', syncEditor);
    editor.closest('form')?.addEventListener('submit', syncEditor);
});
</script>
@endpush


