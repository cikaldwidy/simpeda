<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama Lengkap Anak</label>
        <input type="text" name="nama_bayi" value="{{ old('nama_bayi') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Jenis Kelamin</label>
        <select name="jenis_kelamin_bayi"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih --</option>
            <option value="laki-laki" @selected(old('jenis_kelamin_bayi')==='laki-laki' )>Laki-laki</option>
            <option value="perempuan" @selected(old('jenis_kelamin_bayi')==='perempuan' )>Perempuan</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat Lahir</label>
        <input type="text" name="tempat_lahir_bayi" value="{{ old('tempat_lahir_bayi') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Tulungagung" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir_bayi" value="{{ old('tanggal_lahir_bayi') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Anak Ke</label>
        <input type="number" name="anak_ke" value="{{ old('anak_ke') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            min="1" max="20" required>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat Lengkap
            Anak</label>
        <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800">
            {{ $domisiliAlamat }}
        </div>
    </div>
    <div class="sm:col-span-2 mt-2 text-sm font-semibold uppercase tracking-wide text-gray-600">Data Ayah</div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama Lengkap</label>
        <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Agama</label>
        <input type="text" name="agama_ayah" value="{{ old('agama_ayah') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Islam" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat Lahir</label>
        <input type="text" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat Lengkap</label>
        <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800">
            {{ $domisiliAlamat }}
        </div>
    </div>
    <div class="sm:col-span-2 mt-2 text-sm font-semibold uppercase tracking-wide text-gray-600">Data Ibu</div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama Lengkap</label>
        <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Agama</label>
        <input type="text" name="agama_ibu" value="{{ old('agama_ibu') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Islam" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat Lahir</label>
        <input type="text" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat Lengkap</label>
        <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800">
            {{ $domisiliAlamat }}
        </div>
    </div>
    <div class="sm:col-span-2 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
        Catatan: Surat kelahiran harus dibuat oleh orang tua dari anak yang bersangkutan.
    </div>
</div>