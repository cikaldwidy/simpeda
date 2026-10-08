<section>
    <header>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Informasi Akun</h2>
            <p class="mt-1 text-sm leading-5 text-slate-600">Perbarui alamat email dan nomor HP akun Anda.</p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <p class="flex items-start gap-2 text-sm text-amber-800">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span>Email Anda belum terverifikasi. Periksa email atau kirim ulang tautan verifikasi.</span>
                    </p>
                    <button form="send-verification" class="mt-2 text-sm font-semibold text-orange-700 underline decoration-orange-300 underline-offset-2 transition hover:text-orange-800">
                            Kirim ulang email verifikasi
                        </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-semibold text-emerald-700">
                            Tautan verifikasi baru sudah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="no_hp" :value="__('Nomor HP')" />
            <x-text-input id="no_hp" name="no_hp" type="tel"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                :value="old('no_hp', $user->no_hp)" required autocomplete="tel" inputmode="tel" maxlength="20" />
            <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
        </div>

        <div class="border-t border-slate-100 pt-4">
            <x-primary-button class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600 focus:ring-orange-500">
                {{ __('Simpan Perubahan') }}
            </x-primary-button>
        </div>
    </form>
</section>
