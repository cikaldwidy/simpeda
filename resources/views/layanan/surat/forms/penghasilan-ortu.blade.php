 <div class="mb-5">
     <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Universitas Anda</label>
     <input type="text" name="universitas_anak" value="{{ old('universitas_anak') }}"
         class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
         placeholder="Contoh: Universitas XYZ" required>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ayah = document.getElementById('penghasilan_ayah');
    const ayahWrap = document.getElementById('penghasilan_ayah_lainnya_wrap');
    const ibu = document.getElementById('penghasilan_ibu');
    const ibuWrap = document.getElementById('penghasilan_ibu_lainnya_wrap');
    if (!ayah || !ayahWrap || !ibu || !ibuWrap) return;

    function toggleAyah() {
        ayahWrap.classList.toggle('hidden', ayah.value !== 'lainnya');
    }

    function toggleIbu() {
        ibuWrap.classList.toggle('hidden', ibu.value !== 'lainnya');
    }

    ayah.addEventListener('change', toggleAyah);
    ibu.addEventListener('change', toggleIbu);
    toggleAyah();
    toggleIbu();
});
</script>
 <div class="grid gap-4 sm:grid-cols-2 mb-3">
     <div class="sm:col-span-2 text-sm font-semibold uppercase tracking-wide text-gray-600">Data Ayah</div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama</label>
         <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat Lahir</label>
         <input type="text" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             placeholder="Tempat lahir ayah" required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Lahir</label>
         <input type="date" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">NIK</label>
         <input type="text" name="nik_ayah" value="{{ old('nik_ayah') }}" maxlength="16" minlength="16"
             inputmode="numeric" pattern="[0-9]{16}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
         <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div class="sm:col-span-2">
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat</label>
         <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800">
             {{ $domisiliAlamat }}
         </div>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Penghasilan
             Perbulan</label>
        <select id="penghasilan_ayah" name="penghasilan_ayah"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
             <option value="">-- Pilih Penghasilan --</option>
             <option value="500000" @selected(old('penghasilan_ayah')=='500000' )>Rp. 500.000</option>
             <option value="1000000" @selected(old('penghasilan_ayah')=='1000000' )>Rp. 1.000.000</option>
             <option value="1500000" @selected(old('penghasilan_ayah')=='1500000' )>Rp. 1.500.000</option>
             <option value="2000000" @selected(old('penghasilan_ayah')=='2000000' )>Rp. 2.000.000</option>
             <option value="2500000" @selected(old('penghasilan_ayah')=='2500000' )>Rp. 2.500.000</option>
             <option value="3000000" @selected(old('penghasilan_ayah')=='3000000' )>Rp. 3.000.000</option>
             <option value="3500000" @selected(old('penghasilan_ayah')=='3500000' )>Rp. 3.500.000</option>
             <option value="4000000" @selected(old('penghasilan_ayah')=='4000000' )>Rp. 4.000.000</option>
             <option value="4500000" @selected(old('penghasilan_ayah')=='4500000' )>Rp. 4.500.000</option>
            <option value="5000000" @selected(old('penghasilan_ayah')=='5000000' )>Rp. 5.000.000</option>
            <option value="lainnya" @selected(old('penghasilan_ayah')=='lainnya' )>Lainnya</option>
        </select>
    </div>
    <div id="penghasilan_ayah_lainnya_wrap" class="{{ old('penghasilan_ayah') === 'lainnya' ? '' : 'hidden' }}">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Penghasilan Lainnya Ayah</label>
        <input type="text" name="penghasilan_ayah_lainnya" value="{{ old('penghasilan_ayah_lainnya') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: 750.000">
    </div>

     <div class="sm:col-span-2 text-sm font-semibold uppercase tracking-wide text-gray-600 mt-2">Data Ibu</div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Nama</label>
         <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tempat Lahir</label>
         <input type="text" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             placeholder="Tempat lahir ibu" required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Lahir</label>
         <input type="date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">NIK</label>
         <input type="text" name="nik_ibu" value="{{ old('nik_ibu') }}" maxlength="16" minlength="16"
             inputmode="numeric" pattern="[0-9]{16}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
         <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}"
             class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
             required>
     </div>
     <div class="sm:col-span-2">
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Alamat</label>
         <div class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800">
             {{ $domisiliAlamat }}
         </div>
     </div>
     <div>
         <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Penghasilan
             Perbulan</label>
        <select id="penghasilan_ibu" name="penghasilan_ibu"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
             <option value="">-- Pilih Penghasilan --</option>
             <option value="500000" @selected(old('penghasilan_ibu')=='500000' )>Rp. 500.000</option>
             <option value="1000000" @selected(old('penghasilan_ibu')=='1000000' )>Rp. 1.000.000</option>
             <option value="1500000" @selected(old('penghasilan_ibu')=='1500000' )>Rp. 1.500.000</option>
             <option value="2000000" @selected(old('penghasilan_ibu')=='2000000' )>Rp. 2.000.000</option>
             <option value="2500000" @selected(old('penghasilan_ibu')=='2500000' )>Rp. 2.500.000</option>
             <option value="3000000" @selected(old('penghasilan_ibu')=='3000000' )>Rp. 3.000.000</option>
             <option value="3500000" @selected(old('penghasilan_ibu')=='3500000' )>Rp. 3.500.000</option>
             <option value="4000000" @selected(old('penghasilan_ibu')=='4000000' )>Rp. 4.000.000</option>
             <option value="4500000" @selected(old('penghasilan_ibu')=='4500000' )>Rp. 4.500.000</option>
            <option value="5000000" @selected(old('penghasilan_ibu')=='5000000' )>Rp. 5.000.000</option>
            <option value="lainnya" @selected(old('penghasilan_ibu')=='lainnya' )>Lainnya</option>
        </select>
    </div>
    <div id="penghasilan_ibu_lainnya_wrap" class="{{ old('penghasilan_ibu') === 'lainnya' ? '' : 'hidden' }}">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Penghasilan Lainnya Ibu</label>
        <input type="text" name="penghasilan_ibu_lainnya" value="{{ old('penghasilan_ibu_lainnya') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: 750.000">
    </div>


 </div>
