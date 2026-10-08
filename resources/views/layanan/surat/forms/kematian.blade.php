<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama yang
            Meninggal</label>
        <input type="text" name="nama_meninggal" value="{{ old('nama_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Jenis
            Kelamin</label>
        <select name="jenis_kelamin_meninggal"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih --</option>
            <option value="laki-laki" @selected(old('jenis_kelamin_meninggal')==='laki-laki' )>Laki-laki
            </option>
            <option value="perempuan" @selected(old('jenis_kelamin_meninggal')==='perempuan' )>Perempuan
            </option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat Lahir</label>
        <input type="text" name="tempat_lahir_meninggal" value="{{ old('tempat_lahir_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Tulungagung" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir_meninggal" value="{{ old('tanggal_lahir_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama Orang tua
            (Bapak/Ibu)</label>
        <input type="text" name="nama_ortu_meninggal" value="{{ old('nama_ortu_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Bapak siapa/Ibu siapa" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Usia Meninggal</label>
        <input type="number" name="usia_meninggal" value="{{ old('usia_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            min="0" max="130" required>
    </div>


    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal
            Meninggal</label>
        <input type="date" name="tanggal_meninggal" value="{{ old('tanggal_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div class="sm:col-span-2 rounded-md border border-gray-200 bg-white p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat Domisili</p>
        <p class="mt-2 text-sm font-medium text-gray-800 tracking-[.5px]">
            {{ $domisiliAlamat }}
        </p>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat
            Meninggal</label>
        <input type="text" name="lokasi_meninggal" value="{{ old('lokasi_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Wonorejo" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Penyebab
            Meninggal</label>
        <input type="text" name="sebab_meninggal" value="{{ old('sebab_meninggal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Sakit" required>
    </div>
    <div class="sm:col-span-2 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
        Catatan: Surat kematian ini hanya untuk satu anggota keluarga dan tidak diperbolehkan untuk yang
        bukan anggota keluarga.
    </div>
</div>