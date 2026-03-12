@extends('layouts.app')

@section('title', config('app.name') . ' | Daftar Diri')

@section('content')
@include('partials.nav')

<section
    class="relative overflow-hidden bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 pt-24 pb-12 sm:pt-28 sm:pb-16">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-20 left-1/4 h-52 w-52 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 right-1/4 h-56 w-56 rounded-full bg-gray-300/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/95 shadow-2xl backdrop-blur">
            <div class="grid grid-cols-1 lg:grid-cols-5">
                <div class="order-2 bg-gray-900 px-6 py-7 text-white sm:px-8 lg:order-1 lg:col-span-2 lg:min-h-full">
                    <p class="hidden lg:inline-flex items-center text-lg font-semibold tracking-[1px] text-white">
                        PENDAFTARAN AKUN
                    </p>
                    <p class="mt-4 text-sm leading-relaxed text-gray-300">
                        Pastikan data identitas serta alamat sudah sesuai domisili agar proses verifikasi berjalan
                        lancar.
                    </p>

                    <ul class="mt-6 space-y-3 text-sm text-gray-200">
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500/10 text-md font-bold text-orange-500">1</span>
                            <span>NIK dan nomor HP aktif wajib valid.</span>
                        </li>
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500/10 text-md font-bold text-orange-500">2</span>
                            <span>Wilayah alamat harus sesuai data domisili.</span>
                        </li>
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500/10 text-md font-bold text-orange-500">3</span>
                            <span>Akun menunggu persetujuan admin desa.</span>
                        </li>
                    </ul>

                    <div class="mt-7 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-gray-300">Sudah punya akun?</p>
                        <a href="{{ route('login') }}"
                            class="mt-1 inline-flex items-center text-sm font-semibold text-orange-500 transition hover:text-orange-400">
                            Masuk sekarang
                            <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="order-1 px-5 py-6 sm:px-8 sm:py-8 lg:order-2 lg:col-span-3 lg:px-10">
                    <div class="mb-6">
                        <h1 class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl tracking-[1px]">FORM PENDAFTARAN
                        </h1>
                        <p class="mt-2 text-md text-gray-600">Isi data berikut dengan benar untuk membuat akun Anda.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih --
                                </option>
                                <option value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                            <x-text-input id="tempat_lahir"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="text" name="tempat_lahir" :value="old('tempat_lahir')" required />
                            <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                            <div class="mt-1">
                                <x-text-input id="tanggal_lahir"
                                    class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                            </div>
                            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                        </div>


                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="nik" :value="__('NIK')" />
                                <x-text-input id="nik"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="nik" :value="old('nik')" required autocomplete="nik"
                                    inputmode="numeric" maxlength="16" pattern="[0-9]{1,16}" />
                                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="no_hp" :value="__('No. HP')" />
                                <x-text-input id="no_hp"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="no_hp" :value="old('no_hp')" required autocomplete="tel" />
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                            </div>
                        </div>


                        <div class="rounded-2xl border border-gray-200/80 bg-gray-200 p-4 sm:p-5">
                            <p class="text-sm font-semibold text-gray-800">Alamat Wilayah</p>
                            <p class="mt-1 text-xs text-gray-500">Pilih alamat mulai dari provinsi hingga
                                desa/kelurahan.</p>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="provinsi" :value="__('Provinsi')" />
                                    <select id="provinsi" name="provinsi_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-5000">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kabupaten" :value="__('Kabupaten/Kota')" />
                                    <select id="kabupaten" name="kabupaten_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        disabled>
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kecamatan" :value="__('Kecamatan')" />
                                    <select id="kecamatan" name="kecamatan_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        disabled>
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="desa" :value="__('Desa/Kelurahan')" />
                                    <select id="desa" name="desa_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        disabled>
                                        <option value="">-- Pilih Desa/Kelurahan --</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('desa_id')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <x-input-label for="rt_rw" :value="__('RT/RW')" />
                                    <x-text-input id="rt_rw"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="rt/rw" :value="old('rt/rw')" maxlength="7"
                                        placeholder="xxx/xxx" />
                                    <x-input-error :messages="$errors->get('rt/rw')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="dusun" :value="__('Dusun')" />
                                    <x-text-input id="dusun"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="dusun" :value="old('dusun')" maxlength="100"
                                        placeholder="Nama dusun" />
                                    <x-input-error :messages="$errors->get('dusun')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kode_pos" :value="__('Kode Pos')" />
                                    <x-text-input id="kode_pos"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="kode_pos" :value="old('kode_pos')" maxlength="5" />
                                    <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="alamat_detail" value="Detail Alamat (Jalan/Dusun)" />
                                <x-text-input id="alamat_detail"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="alamat_detail" :value="old('alamat_detail')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                            <a class="text-sm text-slate-600 underline underline-offset-4 transition hover:text-slate-900"
                                href="{{ route('login') }}">
                                {{ __('Sudah Mendaftar?') }}
                            </a>

                            <x-primary-button
                                class="w-full justify-center rounded-xl bg-orange-500 px-6 py-3 text-sm font-semibold uppercase tracking-[1px] hover:bg-orange-400 sm:w-auto">
                                {{ __('Daftar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@include('partials.footer')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const base = "{{ url('/wilayah') }}";

    const prov = document.getElementById('provinsi');
    const kab = document.getElementById('kabupaten');
    const kec = document.getElementById('kecamatan');
    const des = document.getElementById('desa');

    const oldProv = "{{ old('provinsi_id') }}";
    const oldKab = "{{ old('kabupaten_id') }}";
    const oldKec = "{{ old('kecamatan_id') }}";
    const oldDes = "{{ old('desa_id') }}";

    function resetSelect(el, placeholder) {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled = true;
    }

    function fillSelect(el, items, placeholder, selectedValue = '') {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(it => {
            const selected = selectedValue && selectedValue === it.code ? 'selected' : '';
            el.insertAdjacentHTML('beforeend',
                `<option value="${it.code}" ${selected}>${it.name}</option>`);
        });
        el.disabled = false;
    }

    async function fetchJson(url) {
        const res = await fetch(url);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    try {
        const provinces = await fetchJson(`${base}/provinces`);
        fillSelect(prov, provinces.data, "-- Pilih Provinsi --", oldProv);

        if (oldProv) {
            const regencies = await fetchJson(`${base}/regencies/${oldProv}`);
            fillSelect(kab, regencies.data, "-- Pilih Kabupaten/Kota --", oldKab);
        }

        if (oldKab) {
            const districts = await fetchJson(`${base}/districts/${oldKab}`);
            fillSelect(kec, districts.data, "-- Pilih Kecamatan --", oldKec);
        }

        if (oldKec) {
            const villages = await fetchJson(`${base}/villages/${oldKec}`);
            fillSelect(des, villages.data, "-- Pilih Desa/Kelurahan --", oldDes);
        }
    } catch (err) {
        console.error('Gagal memuat data wilayah:', err);
        prov.innerHTML = `<option value="">Gagal memuat provinsi (cek Console)</option>`;
        return;
    }

    prov.addEventListener('change', async () => {
        resetSelect(kab, "-- Pilih Kabupaten/Kota --");
        resetSelect(kec, "-- Pilih Kecamatan --");
        resetSelect(des, "-- Pilih Desa/Kelurahan --");
        if (!prov.value) return;

        try {
            const res = await fetchJson(`${base}/regencies/${prov.value}`);
            fillSelect(kab, res.data, "-- Pilih Kabupaten/Kota --");
        } catch (err) {
            console.error('Gagal memuat kabupaten/kota:', err);
        }
    });

    kab.addEventListener('change', async () => {
        resetSelect(kec, "-- Pilih Kecamatan --");
        resetSelect(des, "-- Pilih Desa/Kelurahan --");
        if (!kab.value) return;

        try {
            const res = await fetchJson(`${base}/districts/${kab.value}`);
            fillSelect(kec, res.data, "-- Pilih Kecamatan --");
        } catch (err) {
            console.error('Gagal memuat kecamatan:', err);
        }
    });

    kec.addEventListener('change', async () => {
        resetSelect(des, "-- Pilih Desa/Kelurahan --");
        if (!kec.value) return;

        try {
            const res = await fetchJson(`${base}/villages/${kec.value}`);
            fillSelect(des, res.data, "-- Pilih Desa/Kelurahan --");
        } catch (err) {
            console.error('Gagal memuat desa/kelurahan:', err);
        }
    });
});
</script>

@endsection
