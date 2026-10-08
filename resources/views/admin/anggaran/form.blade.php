@php
$pendapatanCategories = [
    'Pendapatan Asli Desa',
    'Dana Desa (APBN)',
    'Alokasi Dana Desa (APBD)',
    'Bagi Hasil Pajak/Retribusi',
    'Bantuan Keuangan',
    'Pendapatan Lain-lain',
];
$pembiayaanCategories = [
    'Penerimaan Pembiayaan',
    'Pengeluaran Pembiayaan',
    'Selisih Pembiayaan',
];
$belanjaCategories = [
    'Penyelenggaraan Pemerintahan Desa',
    'Pelaksanaan Pembangunan Desa',
    'Pembinaan Kemasyarakatan',
    'Pemberdayaan Masyarakat Desa',
    'Penanggulangan Bencana, Darurat & Mendesak',
];
$savedItems = old('items', isset($laporan) ? $laporan->items->map(fn ($item) => [
    'jenis' => $item->jenis,
    'kategori' => $item->kategori,
    'uraian' => $item->uraian,
    'jumlah' => $item->jumlah,
])->all() : []);
$getFixedRow = function (string $jenis, string $kategori) use ($savedItems): array {
    $matchingItems = collect($savedItems)
        ->filter(fn ($row) => ($row['jenis'] ?? null) === $jenis && ($row['kategori'] ?? null) === $kategori);

    return [
        'jenis' => $jenis,
        'kategori' => $kategori,
        'uraian' => $kategori,
        'jumlah' => $matchingItems->isNotEmpty()
            ? $matchingItems->sum(fn ($item) => (float) ($item['jumlah'] ?? 0))
            : '0',
    ];
};
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6" data-budget-form>
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Informasi Laporan</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label for="judul" class="mb-1 block text-sm font-semibold text-slate-700">Judul laporan</label>
                <input id="judul" name="judul" value="{{ old('judul', $laporan->judul ?? '') }}" required maxlength="180"
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
                @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="jenis_laporan" class="mb-1 block text-sm font-semibold text-slate-700">Jenis laporan</label>
                <input id="jenis_laporan" name="jenis_laporan" value="{{ old('jenis_laporan', $laporan->jenis_laporan ?? 'APBDes') }}" required maxlength="80" placeholder="APBDes / Perubahan APBDes"
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
                @error('jenis_laporan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tahun" class="mb-1 block text-sm font-semibold text-slate-700">Tahun anggaran</label>
                <input id="tahun" type="number" name="tahun" value="{{ old('tahun', $laporan->tahun ?? now()->year) }}" min="2000" max="2100" required
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
                @error('tahun') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label for="keterangan" class="mb-1 block text-sm font-semibold text-slate-700">Keterangan (opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" maxlength="2000"
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">{{ old('keterangan', $laporan->keterangan ?? '') }}</textarea>
            </div>
        </div>
        <label class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $laporan->is_published ?? false))
                class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
            Publikasikan laporan di halaman beranda dan arsip publik
        </label>
    </section>

    @php $selectedTemplate = old('template', $laporan->template ?? 'infografis'); @endphp
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Pilih Template Publik</h2>
            <p class="mt-1 text-sm text-slate-500">Pilih satu desain yang akan digunakan untuk menampilkan laporan ini.</p>
        </div>
        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <label class="cursor-pointer">
                <input class="peer sr-only" type="radio" name="template" value="infografis" required @checked($selectedTemplate === 'infografis')>
                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 transition peer-checked:border-orange-500 peer-checked:ring-2 peer-checked:ring-orange-100 hover:border-orange-300">
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-2">
                        <div class="flex h-7 items-center gap-1 bg-sky-900 px-2">
                            <span class="h-4 w-4 rounded-full bg-white"></span><span class="h-1.5 w-16 rounded bg-white/80"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 p-1">
                            <div class="h-10 rounded bg-emerald-100"></div><div class="h-10 rounded bg-cyan-100"></div>
                            <div class="col-span-2 flex gap-1"><span class="h-8 w-8 rounded-full bg-orange-400"></span><span class="h-2 flex-1 rounded bg-sky-700"></span></div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-orange-500"></i>
                        <div><p class="text-sm font-bold text-slate-800">Infografis Desa</p><p class="mt-1 text-xs leading-5 text-slate-500">Susunan seperti poster contoh, dengan ringkasan dan grafik alokasi.</p></div>
                    </div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input class="peer sr-only" type="radio" name="template" value="merah-putih" required @checked($selectedTemplate === 'merah-putih')>
                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 transition peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-100 hover:border-red-300">
                    <div class="relative overflow-hidden rounded-lg border border-red-100 bg-white p-2">
                        <div class="absolute -right-4 -top-5 h-12 w-12 rounded-full bg-red-100"></div>
                        <div class="relative flex h-7 items-center gap-1 bg-red-700 px-2">
                            <span class="h-4 w-4 rounded-full bg-white"></span><span class="h-1.5 w-16 rounded bg-white/80"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 p-1">
                            <div class="h-10 rounded border-l-4 border-red-500 bg-red-50"></div><div class="h-10 rounded border-l-4 border-slate-300 bg-slate-50"></div>
                            <div class="col-span-2 h-8 rounded bg-gradient-to-r from-red-600 via-white to-red-600"></div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-red-600"></i>
                        <div><p class="text-sm font-bold text-slate-800">Merah Putih</p><p class="mt-1 text-xs leading-5 text-slate-500">Warna nasional dengan aksen geometris dan angka yang tegas.</p></div>
                    </div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input class="peer sr-only" type="radio" name="template" value="nusantara" required @checked($selectedTemplate === 'nusantara')>
                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 transition peer-checked:border-amber-500 peer-checked:ring-2 peer-checked:ring-amber-100 hover:border-amber-300">
                    <div class="relative overflow-hidden rounded-lg border border-emerald-100 bg-emerald-50 p-2">
                        <div class="absolute -bottom-5 -left-4 h-16 w-16 rounded-full border-4 border-amber-300/70"></div>
                        <div class="relative flex h-7 items-center gap-1 bg-emerald-900 px-2">
                            <span class="h-4 w-4 rounded-full bg-amber-300"></span><span class="h-1.5 w-16 rounded bg-white/80"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 p-1">
                            <div class="h-10 rounded bg-white"></div><div class="h-10 rounded bg-amber-100"></div>
                            <div class="col-span-2 h-8 rounded bg-gradient-to-r from-emerald-700 via-amber-400 to-emerald-700"></div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-amber-600"></i>
                        <div><p class="text-sm font-bold text-slate-800">Nusantara</p><p class="mt-1 text-xs leading-5 text-slate-500">Nuansa hijau dan emas dengan ornamen lengkung terinspirasi motif daerah.</p></div>
                    </div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input class="peer sr-only" type="radio" name="template" value="dashboard" required @checked($selectedTemplate === 'dashboard')>
                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 transition peer-checked:border-orange-500 peer-checked:ring-2 peer-checked:ring-orange-100 hover:border-orange-300">
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-2">
                        <div class="h-7 rounded bg-gradient-to-r from-slate-900 to-blue-800"></div>
                        <div class="grid grid-cols-3 gap-1 p-1"><span class="h-8 rounded bg-emerald-100"></span><span class="h-8 rounded bg-orange-100"></span><span class="h-8 rounded bg-blue-100"></span></div>
                        <div class="space-y-1 px-1 pb-1"><span class="block h-2 rounded bg-slate-300"></span><span class="block h-2 w-3/4 rounded bg-slate-300"></span></div>
                    </div>
                    <div class="mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-orange-500"></i>
                        <div><p class="text-sm font-bold text-slate-800">Ringkasan Modern</p><p class="mt-1 text-xs leading-5 text-slate-500">Kartu angka dan visual perbandingan yang nyaman dibaca di layar.</p></div>
                    </div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input class="peer sr-only" type="radio" name="template" value="poster-batik" required @checked($selectedTemplate === 'poster-batik')>
                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 transition peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-100 hover:border-rose-300">
                    <div class="relative overflow-hidden rounded-lg border border-rose-100 bg-rose-50 p-2">
                        <div class="absolute -right-3 -top-3 h-12 w-12 rotate-45 border-4 border-rose-200"></div>
                        <div class="relative flex h-7 items-center gap-1 bg-gradient-to-r from-rose-800 to-red-600 px-2">
                            <span class="h-4 w-4 rounded-full bg-amber-300"></span><span class="h-1.5 w-16 rounded bg-white/80"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-1 p-1">
                            <div class="h-10 rounded bg-white"></div><div class="h-10 rounded border border-rose-200 bg-rose-100"></div>
                            <div class="col-span-2 h-8 rounded border-b-4 border-amber-400 bg-rose-800"></div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-rose-600"></i>
                        <div><p class="text-sm font-bold text-slate-800">Batik Merah Putih</p><p class="mt-1 text-xs leading-5 text-slate-500">Poster nasional dengan ornamen geometris dan sentuhan emas.</p></div>
                    </div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input class="peer sr-only" type="radio" name="template" value="rincian" required @checked($selectedTemplate === 'rincian')>
                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 transition peer-checked:border-orange-500 peer-checked:ring-2 peer-checked:ring-orange-100 hover:border-orange-300">
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white p-2">
                        <div class="h-7 rounded bg-slate-800"></div>
                        <div class="mt-1 space-y-1"><span class="block h-3 rounded bg-slate-100"></span><span class="block h-3 rounded bg-white"></span><span class="block h-3 rounded bg-slate-100"></span><span class="block h-3 rounded bg-white"></span></div>
                    </div>
                    <div class="mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-orange-500"></i>
                        <div><p class="text-sm font-bold text-slate-800">Tabel Rincian</p><p class="mt-1 text-xs leading-5 text-slate-500">Tampilan formal untuk membaca nilai setiap pos secara lengkap.</p></div>
                    </div>
                </div>
            </label>
        </div>
        @error('template') <p class="mt-3 text-xs text-red-600">{{ $message }}</p> @enderror
    </section>

    <section class="rounded-lg border border-emerald-200 bg-white p-5 shadow-sm">
        <div class="border-b border-emerald-100 pb-3">
            <h2 class="text-xl font-bold text-emerald-800">Pendapatan</h2>
            <div class="mt-1 flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm text-slate-500">Isi nominal untuk setiap sumber pendapatan desa.</p>
                <p class="text-sm font-bold text-emerald-800">Total Pendapatan: Rp <span data-budget-total="pendapatan">0</span></p>
            </div>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            @foreach($pendapatanCategories as $category)
            @php $item = $getFixedRow('pendapatan', $category); $index = $loop->index; @endphp
            <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                <input type="hidden" name="items[{{ $index }}][jenis]" value="pendapatan">
                <input type="hidden" name="items[{{ $index }}][kategori]" value="{{ $category }}">
                <input type="hidden" name="items[{{ $index }}][uraian]" value="{{ $category }}">
                <label for="income-{{ $index }}" class="mb-1 block text-sm font-semibold text-slate-700">{{ $category }}</label>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-500">Rp</span>
                    <input id="income-{{ $index }}" name="items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] ?? '' }}" type="number" min="0" step="0.01" required placeholder="0" data-budget-amount="pendapatan"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                </div>
                @error("items.$index.jumlah") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-lg border border-blue-200 bg-white p-5 shadow-sm">
        <div class="border-b border-blue-100 pb-3">
            <h2 class="text-xl font-bold text-blue-800">Pembiayaan</h2>
            <p class="mt-1 text-sm text-slate-500">Isi penerimaan, pengeluaran, dan selisih pembiayaan.</p>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-3">
            @foreach($pembiayaanCategories as $category)
            @php $item = $getFixedRow('pembiayaan', $category); $index = count($pendapatanCategories) + $loop->index; @endphp
            <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                <input type="hidden" name="items[{{ $index }}][jenis]" value="pembiayaan">
                <input type="hidden" name="items[{{ $index }}][kategori]" value="{{ $category }}">
                <input type="hidden" name="items[{{ $index }}][uraian]" value="{{ $category }}">
                <label for="financing-{{ $index }}" class="mb-1 block text-sm font-semibold text-slate-700">{{ $category }}</label>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-500">Rp</span>
                    <input id="financing-{{ $index }}" name="items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] ?? '' }}" type="number" min="0" step="0.01" required placeholder="0"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                @error("items.$index.jumlah") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-lg border border-orange-200 bg-white p-5 shadow-sm">
        <div class="border-b border-orange-100 pb-3">
            <h2 class="text-xl font-bold text-orange-800">Belanja</h2>
            <div class="mt-1 flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm text-slate-500">Isi total belanja pada setiap bidang kegiatan desa.</p>
                <p class="text-sm font-bold text-orange-800">Total Belanja: Rp <span data-budget-total="belanja">0</span></p>
            </div>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            @foreach($belanjaCategories as $category)
            @php $item = $getFixedRow('belanja', $category); $index = count($pendapatanCategories) + count($pembiayaanCategories) + $loop->index; @endphp
            <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                <input type="hidden" name="items[{{ $index }}][jenis]" value="belanja">
                <input type="hidden" name="items[{{ $index }}][kategori]" value="{{ $category }}">
                <input type="hidden" name="items[{{ $index }}][uraian]" value="{{ $category }}">
                <label for="expense-{{ $index }}" class="mb-1 flex flex-wrap items-center justify-between gap-1 text-sm font-semibold text-slate-700">
                    <span>{{ $category }}</span>
                    <span class="text-xs font-bold text-orange-700"><span data-budget-share="{{ $index }}">0</span>%</span>
                </label>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-500">Rp</span>
                    <input id="expense-{{ $index }}" name="items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] }}" type="number" min="0" step="0.01" required placeholder="0" data-budget-amount="belanja" data-budget-category-index="{{ $index }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-100">
                </div>
                @error("items.$index.jumlah") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            @endforeach
        </div>
        @error('items') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
    </section>

    <div class="flex flex-wrap justify-end gap-2">
        <a href="{{ route('admin.anggaran.index') }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
        <button type="submit" class="rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">Simpan Laporan</button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-budget-form]');
    if (!form) return;

    const formatAmount = (amount) => new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0,
    }).format(amount);

    const updateBudgetSummary = () => {
        const totals = { pendapatan: 0, belanja: 0 };
        form.querySelectorAll('[data-budget-amount]').forEach((input) => {
            totals[input.dataset.budgetAmount] += Number(input.value) || 0;
        });

        Object.entries(totals).forEach(([kind, total]) => {
            const output = form.querySelector(`[data-budget-total="${kind}"]`);
            if (output) output.textContent = formatAmount(total);
        });

        form.querySelectorAll('[data-budget-category-index]').forEach((input) => {
            const share = totals.belanja > 0 ? (Number(input.value || 0) / totals.belanja) * 100 : 0;
            const output = form.querySelector(`[data-budget-share="${input.dataset.budgetCategoryIndex}"]`);
            if (output) output.textContent = share.toFixed(1).replace('.', ',');
        });
    };

    form.addEventListener('input', (event) => {
        if (event.target.matches('[data-budget-amount]')) updateBudgetSummary();
    });
    updateBudgetSummary();
});
</script>
@endpush
