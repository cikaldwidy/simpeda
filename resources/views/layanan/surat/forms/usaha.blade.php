<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Wiraswasta" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Status Perkawinan</label>
        <select name="status_perkawinan"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih Status --</option>
            <option value="belum_kawin" {{ old('status_perkawinan') === 'belum_kawin' ? 'selected' : '' }}>
                Belum Kawin</option>
            <option value="kawin" {{ old('status_perkawinan') === 'kawin' ? 'selected' : '' }}>Kawin</option>
            <option value="cerai_hidup" {{ old('status_perkawinan') === 'cerai_hidup' ? 'selected' : '' }}>
                Cerai Hidup</option>
            <option value="cerai_mati" {{ old('status_perkawinan') === 'cerai_mati' ? 'selected' : '' }}>
                Cerai Mati</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Jenis/Nama Usaha</label>
        <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Toko Sembako" required>
    </div>
</div>
