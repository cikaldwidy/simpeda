<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Jenis Kelamin -->
        <div class="mt-4">
            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />

            <select id="jenis_kelamin" name="jenis_kelamin"
                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih --</option>
                <option value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'selected' : '' }}>Laki-laki
                </option>
                <option value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'selected' : '' }}>Perempuan
                </option>
            </select>

            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
        </div>

        <!-- NIK -->
        <div class="mt-4">
            <x-input-label for="nik" :value="__('NIK')" />
            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')"
                required autocomplete="nik" />
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
        </div>

        <!-- No HP -->
        <div class="mt-4">
            <x-input-label for="no_hp" :value="__('No. HP')" />
            <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')"
                required autocomplete="tel" />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <!-- Provinsi -->
        <div class="mt-4">
            <x-input-label for="provinsi" :value="__('Provinsi')" />
            <select id="provinsi" name="provinsi_id"
                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Pilih Provinsi --</option>
            </select>
            <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
        </div>

        <!-- Kab/Kota -->
        <div class="mt-4">
            <x-input-label for="kabupaten" :value="__('Kabupaten/Kota')" />
            <select id="kabupaten" name="kabupaten_id"
                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                disabled>
                <option value="">-- Pilih Kabupaten/Kota --</option>
            </select>
            <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
        </div>

        <!-- Kecamatan -->
        <div class="mt-4">
            <x-input-label for="kecamatan" :value="__('Kecamatan')" />
            <select id="kecamatan" name="kecamatan_id"
                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                disabled>
                <option value="">-- Pilih Kecamatan --</option>
            </select>
            <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
        </div>

        <!-- Desa/Kelurahan -->
        <div class="mt-4">
            <x-input-label for="desa" :value="__('Desa/Kelurahan')" />
            <select id="desa" name="desa_id"
                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                disabled>
                <option value="">-- Pilih Desa/Kelurahan --</option>
            </select>
            <x-input-error :messages="$errors->get('desa_id')" class="mt-2" />
        </div>

        <!-- RT, RW, Kode Pos -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="rt/rw" :value="__('RT/RW')" />
                <x-text-input id="rt/rw" class="block mt-1 w-full" type="text" name="rt/rw" :value="old('rt/rw')"
                    maxlength="7" placeholder="001/002" />
                <x-input-error :messages="$errors->get('rt/rw')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="kode_pos" :value="__('Kode Pos')" />
                <x-text-input id="kode_pos" class="block mt-1 w-full" type="text" name="kode_pos" :value="old('kode_pos')"
                    maxlength="5" placeholder="66291" />
                <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
            </div>
        </div>

        <!-- Detail alamat -->
        <div class="mt-4">
            <x-input-label for="alamat_detail" value="Detail Alamat (Jalan/Dusun)" />
            <x-text-input id="alamat_detail" class="block mt-1 w-full" type="text" name="alamat_detail"
                :value="old('alamat_detail')" />
        </div>


        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
document.addEventListener('DOMContentLoaded', async () => {
  const base = @json(url('/wilayah'));

  const prov = document.getElementById('provinsi');
  const kab  = document.getElementById('kabupaten');
  const kec  = document.getElementById('kecamatan');
  const des  = document.getElementById('desa');

  function resetSelect(el, placeholder) {
    el.innerHTML = `<option value="">${placeholder}</option>`;
    el.disabled = true;
  }

  function fillSelect(el, items, placeholder) {
    el.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(it => {
      el.insertAdjacentHTML('beforeend', `<option value="${it.code}">${it.name}</option>`);
    });
    el.disabled = false;
  }

  // load provinsi
  try {
    const res = await fetch(`${base}/provinces`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    fillSelect(prov, json.data, "-- Pilih Provinsi --");
  } catch (err) {
    console.error("Gagal load provinsi:", err);
    prov.innerHTML = `<option value="">Gagal memuat provinsi (cek Console)</option>`;
    return;
  }

  prov.addEventListener('change', async () => {
    resetSelect(kab, "-- Pilih Kabupaten/Kota --");
    resetSelect(kec, "-- Pilih Kecamatan --");
    resetSelect(des, "-- Pilih Desa/Kelurahan --");
    if (!prov.value) return;

    const res = await fetch(`${base}/regencies/${prov.value}`);
    const json = await res.json();
    fillSelect(kab, json.data, "-- Pilih Kabupaten/Kota --");
  });

  kab.addEventListener('change', async () => {
    resetSelect(kec, "-- Pilih Kecamatan --");
    resetSelect(des, "-- Pilih Desa/Kelurahan --");
    if (!kab.value) return;

    const res = await fetch(`${base}/districts/${kab.value}`);
    const json = await res.json();
    fillSelect(kec, json.data, "-- Pilih Kecamatan --");
  });

  kec.addEventListener('change', async () => {
    resetSelect(des, "-- Pilih Desa/Kelurahan --");
    if (!kec.value) return;

    const res = await fetch(`${base}/villages/${kec.value}`);
    const json = await res.json();
    fillSelect(des, json.data, "-- Pilih Desa/Kelurahan --");
  });
});
</script>

</x-guest-layout>
