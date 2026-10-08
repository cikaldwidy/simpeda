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
