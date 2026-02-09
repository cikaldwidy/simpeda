<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<div class="mx-auto max-w-6xl px-4 py-10">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Hero Slides</h1>
    <a href="{{ route('admin.hero-slides.create') }}"
       class="rounded-full bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
      Tambah Slide
    </a>
  </div>

  @if(session('success'))
    <div class="mt-4 rounded-2xl bg-emerald-100 px-4 py-3 text-emerald-900">
      {{ session('success') }}
    </div>
  @endif

  <div class="mt-6 overflow-hidden rounded-2xl border border-emerald-100 bg-white">
    <table class="w-full text-sm">
      <thead class="bg-emerald-50">
        <tr>
          <th class="px-4 py-3 text-left">Urutan</th>
          <th class="px-4 py-3 text-left">Gambar</th>
          <th class="px-4 py-3 text-left">Judul</th>
          <th class="px-4 py-3 text-left">Deskripsi</th>
          <th class="px-4 py-3 text-left">Status</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($slides as $s)
          <tr class="border-t border-emerald-100">
            <td class="px-4 py-3">{{ $s->sort_order }}</td>
            <td class="px-4 py-3">
              <img class="h-12 w-24 rounded-lg object-cover" src="{{ Storage::url($s->image_path) }}" alt="">
            </td>
            <td class="px-4 py-3 font-semibold">{{ $s->title }}</td>
            <td class="px-4 py-3 text-emerald-700">{{ $s->description }}</td>
            <td class="px-4 py-3">
              <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $s->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <a class="rounded-full border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-800"
                 href="{{ route('admin.hero-slides.edit', $s->id) }}">Edit</a>

              <form class="inline" method="POST" action="{{ route('admin.hero-slides.destroy', $s->id) }}"
                    onsubmit="return confirm('Hapus slide ini?')">
                @csrf @method('DELETE')
                <button class="ml-2 rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700" type="submit">
                  Hapus
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
