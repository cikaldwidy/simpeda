@extends('layouts.dashboard')

@php
$namaSurat = match($selectedJenis) {
\App\Models\SuratPengajuan::JENIS_DOMISILI => 'Surat Keterangan Domisili',
\App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU => 'Surat Keterangan Tidak Mampu',
\App\Models\SuratPengajuan::JENIS_KEMATIAN => 'Surat Kematian',
\App\Models\SuratPengajuan::JENIS_KELAHIRAN => 'Surat Kelahiran',
default => 'Pengajuan Surat',
};
@endphp

@section('title', config('app.name') . ' | ' . $namaSurat)
@section('dashboard_title', $namaSurat)
@section('dashboard_subtitle', 'Lengkapi data pengajuan untuk ' . $namaSurat . '.')

@section('dashboard_content')
<div class="bg-gray-100">
    @if($errors->any())
    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc space-y-1 pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <h3 class="text-lg font-bold text-gray-800 tracking-[.5px] uppercase text-center md:text-left underline">
        {{ $namaSurat }}</h3>
    <p class=" mt-1 text-sm text-gray-600 text-center md:text-left">Harap memastikan data yang dimasukkan sudah sesuai
        sebelum
        melanjutkan pengajuan surat.</p>
    <div class="rounded-md border border-slate-200 bg-gradient-to-r from-slate-300 to-slate-200 p-5 mt-3">
        <form method="POST" action="{{ route('layanan.pengajuan.store') }}" class="">
            @csrf
            <input type="hidden" name="jenis_surat" value="{{ $selectedJenis }}">
            @if($selectedJenis !== \App\Models\SuratPengajuan::JENIS_KEMATIAN)
            <div class="mb-3">
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-md border border-gray-200 bg-white px-3 py-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">NIK / Nama</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 tracking-[.5px]">{{ $user->nik }} -
                            {{ $user->name }}</p>
                    </div>

                    <div class="rounded-md border border-gray-200 bg-white px-3 py-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Tempat, Tanggal Lahir</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 tracking-[.5px]">{{ $ttlFormatted }}</p>
                    </div>

                    <div class="rounded-md border border-gray-200 bg-white px-3 py-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Jenis Kelamin</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 tracking-[.5px]">
                            {{ ucfirst((string) $user->jenis_kelamin) }}
                        </p>
                    </div>

                    <div class="rounded-md border border-gray-200 bg-white px-3 py-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">No. HP</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 tracking-[.5px]">{{ $user->no_hp }}</p>
                    </div>

                    <div class="sm:col-span-2 rounded-md border border-gray-200 bg-white px-3 py-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Alamat Domisili</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 tracking-[.5px]">{{ $domisiliAlamat }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($selectedJenis === \App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU)
            <div class="grid gap-4 sm:grid-cols-2 mb-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Status
                        Perkawinan</label>
                    <select name="status_perkawinan"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
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
                    <label
                        class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Keperluan
                        Surat</label>
                    <select id="keperluan_sktm" name="keperluan"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
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
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        placeholder="Tulis keperluan lain">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat
                        Tujuan</label>
                    <input type="text" name="digunakan_di" value="{{ old('digunakan_di') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        placeholder="Opsional">
                </div>
            </div>
            @elseif($selectedJenis === \App\Models\SuratPengajuan::JENIS_KEMATIAN)
            <div class="grid gap-4 sm:grid-cols-2 mb-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama</label>
                    <input type="text" name="nama_meninggal" value="{{ old('nama_meninggal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Jenis
                        Kelamin</label>
                    <select name="jenis_kelamin_meninggal"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        required>
                        <option value="">-- Pilih --</option>
                        <option value="laki-laki" @selected(old('jenis_kelamin_meninggal')==='laki-laki' )>Laki-laki
                        </option>
                        <option value="perempuan" @selected(old('jenis_kelamin_meninggal')==='perempuan' )>Perempuan
                        </option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Usia</label>
                    <input type="number" name="usia_meninggal" value="{{ old('usia_meninggal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        min="0" max="130" required>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal
                        Meninggal</label>
                    <input type="date" name="tanggal_meninggal" value="{{ old('tanggal_meninggal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        required>
                </div>
                <div class="sm:col-span-2 rounded-md border border-gray-200 bg-white p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat Lengkap</p>
                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label
                                class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Provinsi</label>
                            <select id="km_provinsi"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                                required>
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                            <input type="hidden" name="provinsi_meninggal" id="km_provinsi_nama"
                                value="{{ old('provinsi_meninggal') }}">
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Kabupaten/Kota</label>
                            <select id="km_kabupaten"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                                disabled required>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                            <input type="hidden" name="kabupaten_meninggal" id="km_kabupaten_nama"
                                value="{{ old('kabupaten_meninggal') }}">
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Kecamatan</label>
                            <select id="km_kecamatan"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                                disabled required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            <input type="hidden" name="kecamatan_meninggal" id="km_kecamatan_nama"
                                value="{{ old('kecamatan_meninggal') }}">
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Desa/Kelurahan</label>
                            <select id="km_desa"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                                disabled required>
                                <option value="">-- Pilih Desa/Kelurahan --</option>
                            </select>
                            <input type="hidden" name="desa_meninggal" id="km_desa_nama"
                                value="{{ old('desa_meninggal') }}">
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">RT/RW</label>
                            <input type="text" name="rt_rw_meninggal" value="{{ old('rt_rw_meninggal') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                                placeholder="xxx/xxx">
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Dusun</label>
                            <input type="text" name="dusun_meninggal" value="{{ old('dusun_meninggal') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat
                        Meninggal</label>
                    <input type="text" name="lokasi_meninggal" value="{{ old('lokasi_meninggal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        placeholder="Contoh: Wonorejo" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Penyebab
                        Meninggal</label>
                    <input type="text" name="sebab_meninggal" value="{{ old('sebab_meninggal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        placeholder="Contoh: Sakit" required>
                </div>


            </div>
            @elseif($selectedJenis !== \App\Models\SuratPengajuan::JENIS_DOMISILI)
            <div class="grid gap-4 sm:grid-cols-2 mb-3">
                <div>
                    <label
                        class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Perihal</label>
                    <input type="text" name="perihal" value="{{ old('perihal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        required>
                </div>
                <div>
                    <label
                        class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Keperluan</label>
                    <input type="text" name="keperluan" value="{{ old('keperluan') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
                        required>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Catatan
                        (opsional)</label>
                    <textarea name="catatan" rows="4"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0">{{ old('catatan') }}</textarea>
                </div>
            </div>
            @endif

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                    class="inline-flex rounded-md bg-orange-500 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-400 tracking-[.5px]">
                    Kirim Pengajuan
                </button>

            </div>
        </form>
    </div>
</div>

@if($selectedJenis === \App\Models\SuratPengajuan::JENIS_TIDAK_MAMPU)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('keperluan_sktm');
    const wrap = document.getElementById('keperluan_lainnya_wrap');
    if (!select || !wrap) return;

    function toggleKeperluanLainnya() {
        wrap.classList.toggle('hidden', select.value !== 'lainnya');
    }

    select.addEventListener('change', toggleKeperluanLainnya);
    toggleKeperluanLainnya();
});
</script>
@endif

@if($selectedJenis === \App\Models\SuratPengajuan::JENIS_KEMATIAN)
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const base = "{{ url('/wilayah') }}";
    const prov = document.getElementById('km_provinsi');
    const kab = document.getElementById('km_kabupaten');
    const kec = document.getElementById('km_kecamatan');
    const des = document.getElementById('km_desa');

    const hProv = document.getElementById('km_provinsi_nama');
    const hKab = document.getElementById('km_kabupaten_nama');
    const hKec = document.getElementById('km_kecamatan_nama');
    const hDes = document.getElementById('km_desa_nama');

    if (!prov || !kab || !kec || !des) return;

    function fillSelect(el, items, placeholder) {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(item => {
            el.insertAdjacentHTML('beforeend',
                `<option value="${item.code}">${item.name}</option>`);
        });
        el.disabled = false;
    }

    function resetSelect(el, placeholder) {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled = true;
    }

    function copySelectedName(selectEl, hiddenEl) {
        if (!selectEl || !hiddenEl) return;
        hiddenEl.value = selectEl.options[selectEl.selectedIndex]?.text || '';
    }

    async function fetchJson(url) {
        const res = await fetch(url);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    try {
        const provinces = await fetchJson(`${base}/provinces`);
        fillSelect(prov, provinces.data || [], "-- Pilih Provinsi --");
    } catch (e) {
        console.error(e);
    }

    prov.addEventListener('change', async () => {
        copySelectedName(prov, hProv);
        resetSelect(kab, "-- Pilih Kabupaten/Kota --");
        resetSelect(kec, "-- Pilih Kecamatan --");
        resetSelect(des, "-- Pilih Desa/Kelurahan --");
        hKab.value = '';
        hKec.value = '';
        hDes.value = '';
        if (!prov.value) return;
        const data = await fetchJson(`${base}/regencies/${prov.value}`);
        fillSelect(kab, data.data || [], "-- Pilih Kabupaten/Kota --");
    });

    kab.addEventListener('change', async () => {
        copySelectedName(kab, hKab);
        resetSelect(kec, "-- Pilih Kecamatan --");
        resetSelect(des, "-- Pilih Desa/Kelurahan --");
        hKec.value = '';
        hDes.value = '';
        if (!kab.value) return;
        const data = await fetchJson(`${base}/districts/${kab.value}`);
        fillSelect(kec, data.data || [], "-- Pilih Kecamatan --");
    });

    kec.addEventListener('change', async () => {
        copySelectedName(kec, hKec);
        resetSelect(des, "-- Pilih Desa/Kelurahan --");
        hDes.value = '';
        if (!kec.value) return;
        const data = await fetchJson(`${base}/villages/${kec.value}`);
        fillSelect(des, data.data || [], "-- Pilih Desa/Kelurahan --");
    });

    des.addEventListener('change', () => copySelectedName(des, hDes));
});
</script>
@endif
@endsection