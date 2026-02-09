<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<div class="mx-auto max-w-2xl px-4 py-10">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Tambah Slide</h1>
    <a href="{{ route('admin.hero-slides.index') }}" class="text-sm font-semibold text-emerald-800">Kembali</a>
  </div>

  <form class="mt-6 space-y-4 rounded-2xl border border-emerald-100 bg-white p-6"
        method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
    @csrf

    <div>
      <label class="text-sm font-semibold">Title</label>
      <input name="title" class="mt-2 w-full rounded-xl border border-emerald-200 px-3 py-2">
    </div>

    <div>
      <label class="text-sm font-semibold">Deskripsi</label>
      <input name="description" class="mt-2 w-full rounded-xl border border-emerald-200 px-3 py-2">
    </div>

    <div class="grid gap-4 md:grid-cols-2">
      <div>
        <label class="text-sm font-semibold">Urutan</label>
        <input type="number" min="1" name="sort_order" value="1"
               class="mt-2 w-full rounded-xl border border-emerald-200 px-3 py-2">
      </div>
      <div class="flex items-end gap-3">
        <input id="is_active" type="checkbox" name="is_active" value="1" class="h-4 w-4" checked>
        <label for="is_active" class="text-sm font-semibold">Aktif</label>
      </div>
    </div>

    <div>
      <label class="text-sm font-semibold">Gambar</label>
      <input type="file" name="image" accept="image/*"
             class="mt-2 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2">
    </div>

    <div class="flex justify-end gap-2 pt-2">
      <a href="{{ route('admin.hero-slides.index') }}"
         class="rounded-full border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-800">Batal</a>
      <button class="rounded-full bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800" type="submit">
        Simpan
      </button>
    </div>
  </form>
</div>
