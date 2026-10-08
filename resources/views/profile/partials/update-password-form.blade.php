<section>
    <header>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Keamanan Akun</h2>
            <p class="mt-1 text-sm leading-5 text-slate-600">Gunakan password yang kuat dan jangan membagikannya kepada siapa pun.</p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Password Baru')" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="border-t border-slate-100 pt-4">
            <x-primary-button class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600 focus:ring-orange-500">
                {{ __('Perbarui Password') }}
            </x-primary-button>
        </div>
    </form>
</section>
