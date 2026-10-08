<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Status
            Perkawinan</label>
        <select name="status_perkawinan"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih Status --</option>
            <option value="belum_kawin" {{ old('status_perkawinan') === 'belum_kawin' ? 'selected' : '' }}>
                Belum Kawin</option>
            <option value="kawin" {{ old('status_perkawinan') === 'kawin' ? 'selected' : '' }}>Kawin
            </option>
            <option value="cerai_hidup" {{ old('status_perkawinan') === 'cerai_hidup' ? 'selected' : '' }}>
                Cerai Hidup</option>
            <option value="cerai_mati" {{ old('status_perkawinan') === 'cerai_mati' ? 'selected' : '' }}>
                Cerai Mati</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Keperluan
            Surat</label>
        <select id="keperluan_sktm" name="keperluan"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih Keperluan --</option>
            <option value="Pengajuan bantuan biaya berobat"
                {{ old('keperluan') === 'Pengajuan bantuan biaya berobat' ? 'selected' : '' }}>Pengajuan
                bantuan biaya berobat</option>
            <option value="Pengajuan bantuan pendidikan/beasiswa"
                {{ old('keperluan') === 'Pengajuan bantuan pendidikan/beasiswa' ? 'selected' : '' }}>
                Pengajuan bantuan pendidikan/beasiswa</option>
            <option value="Pengajuan bantuan sosial"
                {{ old('keperluan') === 'Pengajuan bantuan sosial' ? 'selected' : '' }}>Pengajuan bantuan
                sosial</option>
            <option value="Pengajuan keringanan biaya rumah sakit"
                {{ old('keperluan') === 'Pengajuan keringanan biaya rumah sakit' ? 'selected' : '' }}>
                Pengajuan keringanan biaya rumah sakit</option>
            <option value="Persyaratan administrasi sekolah/kuliah"
                {{ old('keperluan') === 'Persyaratan administrasi sekolah/kuliah' ? 'selected' : '' }}>
                Persyaratan administrasi sekolah/kuliah</option>
            <option value="Persyaratan pengajuan BPJS PBI"
                {{ old('keperluan') === 'Persyaratan pengajuan BPJS PBI' ? 'selected' : '' }}>Persyaratan
                pengajuan BPJS PBI</option>
            <option value="Persyaratan bantuan rehab rumah"
                {{ old('keperluan') === 'Persyaratan bantuan rehab rumah' ? 'selected' : '' }}>Persyaratan
                bantuan rehab rumah</option>
            <option value="lainnya" {{ old('keperluan') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
    </div>
    <div id="keperluan_lainnya_wrap" class="{{ old('keperluan') === 'lainnya' ? '' : 'hidden' }}">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Keperluan
            Lainnya</label>
        <input type="text" name="keperluan_lainnya" value="{{ old('keperluan_lainnya') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Tulis keperluan lain">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat
            Tujuan</label>
        <input type="text" name="digunakan_di" value="{{ old('digunakan_di') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Opsional">
    </div>
</div>

