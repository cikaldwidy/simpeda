<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Jenis Dokumen
            Hilang</label>
        <select id="jenis_dokumen" name="jenis_dokumen"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih Dokumen --</option>
            <option value="kk" @selected(old('jenis_dokumen')==='kk' )>Kartu Keluarga (KK)</option>
            <option value="ktp" @selected(old('jenis_dokumen')==='ktp' )>Kartu Tanda Penduduk (KTP)</option>
            <option value="akta_kelahiran" @selected(old('jenis_dokumen')==='akta_kelahiran' )>Akta Kelahiran</option>
            <option value="lainnya" @selected(old('jenis_dokumen')==='lainnya' )>Lainnya</option>
        </select>

    </div>
    <div id="jenis_dokumen_lainnya_wrap" class="sm:col-span-2 {{ old('jenis_dokumen') === 'lainnya' ? '' : 'hidden' }}">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Jenis Dokumen
            Lainnya</label>
        <input type="text" name="jenis_dokumen_lainnya" value="{{ old('jenis_dokumen_lainnya') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Tulis jenis dokumen">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama Terkait di
            Dokumen</label>
        <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Nama bersangkutan di dokumen" required>
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
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Kehilangan</label>
        <input type="date" name="tanggal_kehilangan" value="{{ old('tanggal_kehilangan') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Lokasi Kehilangan</label>
        <select id="lokasi_kehilangan" name="lokasi_kehilangan"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
            <option value="">-- Pilih Lokasi --</option>
            <option value="rumah" @selected(old('lokasi_kehilangan')==='rumah' )>Kehilangan di rumah</option>
            <option value="jalan" @selected(old('lokasi_kehilangan')==='jalan' )>Kehilangan di jalan</option>
        </select>
    </div>
    <div id="lokasi_rumah_wrap"
        class="sm:col-span-2 {{ old('lokasi_kehilangan', 'rumah') === 'rumah' ? '' : 'hidden' }}">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Keterangan Lokasi
            (Rumah)</label>
        <input type="text" name="lokasi_rumah_detail" value="{{ old('lokasi_rumah_detail', $domisiliAlamat) }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Dusun Wonorejo RT 001 RW 002 Desa Wonorejo">
    </div>
    <div id="lokasi_jalan_wrap" class="sm:col-span-2 {{ old('lokasi_kehilangan') === 'jalan' ? '' : 'hidden' }}">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Rute Kehilangan
            (Jalan)</label>
        <div class="flex items-center gap-2">
            <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800">
                Wonorejo
            </div>
            <span class="text-sm text-gray-600">ke</span>
            <input type="text" name="lokasi_jalan_detail" value="{{ old('lokasi_jalan_detail') }}"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                placeholder="Lokasi tujuan anda sebelumnya">
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lokasi = document.getElementById('lokasi_kehilangan');
    const rumahWrap = document.getElementById('lokasi_rumah_wrap');
    const jalanWrap = document.getElementById('lokasi_jalan_wrap');
    const jenisDokumen = document.getElementById('jenis_dokumen');
    const jenisLainnyaWrap = document.getElementById('jenis_dokumen_lainnya_wrap');
    if (!lokasi || !rumahWrap || !jalanWrap || !jenisDokumen || !jenisLainnyaWrap) return;

    function toggleLokasi() {
        const isJalan = lokasi.value === 'jalan';
        rumahWrap.classList.toggle('hidden', isJalan);
        jalanWrap.classList.toggle('hidden', !isJalan);
    }

    function toggleJenisDokumen() {
        jenisLainnyaWrap.classList.toggle('hidden', jenisDokumen.value !== 'lainnya');
    }

    lokasi.addEventListener('change', toggleLokasi);
    jenisDokumen.addEventListener('change', toggleJenisDokumen);
    toggleLokasi();
    toggleJenisDokumen();
});
</script>
