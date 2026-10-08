@extends('layouts.auth')

@section('title', config('app.name') . ' | Login Petugas')

@section('content')
<section class="relative overflow-hidden pt-24 pb-12 sm:pt-28 sm:pb-16 min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url({{ asset('img/bg-motif.jpg') }});">
    <div class="pointer-events-none absolute inset-0 bg-black/50"></div>
    <div class="relative mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-lg border border-white/10 bg-white/95 shadow-2xl backdrop-blur">
            <div class="grid grid-cols-1 lg:grid-cols-5">
                <div
                    class="order-1 min-w-0 bg-slate-800 px-6 py-7 text-white sm:px-8 lg:order-1 lg:col-span-2 lg:min-h-full">
                    <img src="{{ asset('img/login-img.png') }}" alt="Login Petugas"
                        class="mb-5 h-42 w-auto object-contain mx-auto" />
                    <p class="mb-3 text-xs text-center font-semibold uppercase tracking-[2px] text-white/80">
                        Sistem Pelayanan Desa Wonorejo
                    </p>

                </div>

                <div class="order-2 min-w-0 px-5 py-6 sm:px-8 sm:py-8 lg:order-2 lg:col-span-3 lg:px-10">
                    <div class="mb-6">
                        <h1 class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl tracking-[1px]">FORM LOGIN PETUGAS
                        </h1>
                        <p class="mt-2 text-md text-gray-600">Masukkan kredensial petugas untuk melanjutkan.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('petugas.login.store') }}" class="space-y-4 sm:space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" required />
                            <x-text-input id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" required />
                            <div class="relative">
                                <x-text-input id="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 pr-10"
                                    type="password" name="password" required autocomplete="current-password" />
                                <button type="button"
                                    class="password-toggle absolute inset-y-0 right-2 flex items-center text-gray-500"
                                    data-target="password" aria-label="Toggle password visibility">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded border-gray-300 text-orange-500 shadow-sm focus:ring-orange-500"
                                    name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                                <a href="{{ route('login') }}"
                                    class="text-sm text-slate-600 underline underline-offset-4 transition hover:text-slate-900">
                                    Login pengguna
                                </a>
                                <a href="{{ route('admin.login') }}"
                                    class="text-sm text-slate-600 underline underline-offset-4 transition hover:text-slate-900">
                                    Login admin
                                </a>
                            </div>
                            <x-primary-button
                                class="w-full justify-center rounded-md bg-orange-500 px-6 py-3 text-sm font-semibold uppercase tracking-[1px] hover:bg-orange-400 sm:w-auto">
                                {{ __('MASUK') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

