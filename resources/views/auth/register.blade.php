@extends('layouts.auth')

@section('title', config('app.name') . ' | Daftar Diri')

@section('content')
@include('partials.nav')

<section
    class="relative overflow-hidden pt-24 pb-12 sm:pt-28 sm:pb-16 min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url({{ asset('img/bg-motif.jpg') }});">
    <div class="pointer-events-none absolute inset-0 bg-black/50"></div>


    <div class="relative mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-white/10 bg-white/95 shadow-2xl backdrop-blur">
            <div class="grid grid-cols-1 lg:grid-cols-5">
                <div
                    class="order-2 min-w-0 bg-gray-900 px-6 py-7 text-white sm:px-8 lg:order-1 lg:col-span-2 lg:min-h-full">
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

                    <div class="mt-7 rounded-xl border border-white/10 bg-white/5 p-4">
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

                    <form id="register-form" method="POST" action="{{ route('register') }}"
                        class="space-y-4 sm:space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" required />
                            <x-text-input id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" required />
                            <select id="jenis_kelamin" name="jenis_kelamin" required
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
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" required />
                                <x-text-input id="tempat_lahir"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="tempat_lahir" :value="old('tempat_lahir')" required />
                                <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" required />
                                <div class="mt-1">
                                    <x-text-input id="tanggal_lahir"
                                        class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                                </div>
                                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="no_hp" :value="__('No. HP')" required />
                                <x-text-input id="no_hp"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="no_hp" :value="old('no_hp')" required autocomplete="tel" />
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nik" :value="__('NIK')" required />
                                <x-text-input id="nik"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="nik" :value="old('nik')" required autocomplete="nik"
                                    inputmode="numeric" maxlength="16" pattern="[0-9]{1,16}" />
                                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                            </div>
                        </div>


                        <div class="rounded-xl border border-gray-200/80 bg-gray-200 p-4 sm:p-5">
                            <p class="text-sm font-semibold text-gray-800">Alamat Wilayah</p>
                            <p class="mt-1 text-xs text-gray-500">
                                Wilayah otomatis untuk warga Desa Wonorejo, Kecamatan Sumbergempol, Kabupaten
                                Tulungagung, Provinsi Jawa Timur.
                            </p>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="provinsi" :value="__('Provinsi')" />
                                    <x-text-input id="provinsi"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-gray-700 bg-gray-100 pointer-events-none select-none focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="provinsi_id" value="Jawa Timur" readonly />
                                    <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kabupaten" :value="__('Kabupaten/Kota')" />
                                    <x-text-input id="kabupaten"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-gray-700 bg-gray-100 pointer-events-none select-none focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="kabupaten_id" value="Kabupaten Tulungagung" readonly />
                                    <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kecamatan" :value="__('Kecamatan')" />
                                    <x-text-input id="kecamatan"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-gray-700 bg-gray-100 pointer-events-none select-none focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="kecamatan_id" value="Sumbergempol" readonly />
                                    <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="desa" :value="__('Desa/Kelurahan')" />
                                    <x-text-input id="desa"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-gray-700 bg-gray-100 pointer-events-none select-none focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="desa_id" value="Wonorejo" readonly />
                                    <x-input-error :messages="$errors->get('desa_id')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <x-input-label for="rt_rw" :value="__('RT/RW')" required />
                                    <x-text-input id="rt_rw"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="rt/rw" :value="old('rt/rw')" maxlength="7" required
                                        placeholder="xxx/xxx" />
                                    <x-input-error :messages="$errors->get('rt/rw')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="dusun" :value="__('Dusun')" required />
                                    <x-text-input id="dusun"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="dusun" :value="old('dusun')" maxlength="100" required
                                        placeholder="Nama dusun" />
                                    <x-input-error :messages="$errors->get('dusun')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kode_pos" :value="__('Kode Pos')" />
                                    <x-text-input id="kode_pos"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-gray-700 bg-gray-100 pointer-events-none select-none focus:border-orange-500 focus:ring-orange-500"
                                        type="text" name="kode_pos" :value="old('kode_pos', '66291')" maxlength="5"
                                        readonly />
                                    <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="alamat_detail" value="Detail Alamat (Opsional)" />
                                <x-text-input id="alamat_detail"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                    type="text" name="alamat_detail" :value="old('alamat_detail')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" required />
                            <x-text-input id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="password" :value="__('Password')" required />
                                <div class="relative">
                                    <x-text-input id="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 pr-10"
                                        type="password" name="password" required autocomplete="new-password" />
                                    <button type="button"
                                        class="password-toggle absolute inset-y-0 right-2 flex items-center text-gray-500"
                                        data-target="password" aria-label="Toggle password visibility">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <div id="password-strength" class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div id="strength-bar" class="h-full transition-all duration-300"></div>
                                        </div>
                                        <span id="strength-text" class="text-sm text-gray-600"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')"
                                    required />
                                <div class="relative">
                                    <x-text-input id="password_confirmation"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 pr-10"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" />
                                    <button type="button"
                                        class="password-toggle absolute inset-y-0 right-2 flex items-center text-gray-500"
                                        data-target="password_confirmation" aria-label="Toggle password visibility">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                            <a class="text-sm text-slate-600 underline underline-offset-4 transition hover:text-slate-900"
                                href="{{ route('login') }}">
                                {{ __('Sudah Mendaftar?') }}
                            </a>

                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>

                            <x-primary-button
                                class="w-full justify-center rounded-md bg-orange-500 px-6 py-3 text-sm font-semibold uppercase tracking-[1px] hover:bg-orange-400 sm:w-auto">
                                {{ __('Daftar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];

        if (password.length >= 8) strength++;
        else feedback.push('Minimal 8 karakter');

        if (/[a-z]/.test(password)) strength++;
        else feedback.push('Huruf kecil');

        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('Huruf besar');

        if (/[0-9]/.test(password)) strength++;
        else feedback.push('Angka');

        if (/[^A-Za-z0-9]/.test(password)) strength++;
        else feedback.push('Karakter khusus');

        return {
            strength,
            feedback
        };
    }

    function updateStrengthIndicator(password) {
        const {
            strength,
            feedback
        } = checkPasswordStrength(password);

        let width = (strength / 5) * 100;
        let color = '';
        let text = '';

        if (strength === 0) {
            color = 'bg-gray-300';
            text = '';
        } else if (strength <= 2) {
            color = 'bg-red-500';
            text = 'Lemah';
        } else if (strength <= 3) {
            color = 'bg-yellow-500';
            text = 'Sedang';
        } else if (strength <= 4) {
            color = 'bg-blue-500';
            text = 'Kuat';
        } else {
            color = 'bg-green-500';
            text = 'Sangat Kuat';
        }

        strengthBar.style.width = width + '%';
        strengthBar.className = 'h-full transition-all duration-300 ' + color;
        strengthText.textContent = text;
        strengthText.className = 'text-sm ' + (strength <= 2 ? 'text-red-600' : strength <= 3 ?
            'text-yellow-600' : 'text-green-600');
    }

    passwordInput.addEventListener('input', function() {
        updateStrengthIndicator(this.value);
    });


    // Initial check
    updateStrengthIndicator(passwordInput.value);
});
</script>
@endpush

@endsection