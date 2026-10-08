<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            placeholder="Contoh: Pelajar/Mahasiswa" required>
    </div>
    <div class="sm:col-span-2">
        <input type="hidden" name="status_perkawinan" value="belum_kawin">
    </div>
</div>

