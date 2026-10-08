<div class="grid gap-4 sm:grid-cols-2 mb-3">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Perihal</label>
        <input type="text" name="perihal" value="{{ old('perihal') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Keperluan</label>
        <input type="text" name="keperluan" value="{{ old('keperluan') }}"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0"
            required>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Catatan
            (opsional)</label>
        <textarea name="catatan" rows="4"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-0">{{ old('catatan') }}</textarea>
    </div>
</div>

