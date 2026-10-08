<section class="space-y-5">
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <i class="fa-solid fa-user-xmark"></i>
            </span>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Hapus Akun</h2>
                <p class="mt-1 max-w-2xl text-sm leading-5 text-slate-600">
                    Penghapusan akun bersifat permanen dan semua data terkait akan hilang. Pastikan Anda sudah menyimpan data penting.
                </p>
            </div>
        </div>
        <span
            class="inline-flex items-center gap-1.5 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[1px] text-red-700">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Tindakan Permanen
        </span>
    </header>

    <x-danger-button class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700"
        x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        <i class="fa-solid fa-trash"></i>
        Hapus Akun
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                Setelah akun dihapus, semua data akan hilang permanen. Masukkan password untuk konfirmasi.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Password" class="sr-only" />

                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-3/4 rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500"
                    placeholder="Password" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Hapus Akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>