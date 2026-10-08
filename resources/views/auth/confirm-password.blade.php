@extends('layouts.auth')

@section('title', config('app.name') . ' | Konfirmasi Password')

@section('content')
@include('partials.nav')

<section
    class="relative overflow-hidden pt-24 pb-12 sm:pt-28 sm:pb-16 min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url({{ asset('img/bg-motif.jpg') }});">
    <div class="pointer-events-none absolute inset-0 bg-black/50"></div>


    <div class="relative mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-white/10 bg-white/95 shadow-2xl backdrop-blur">
            <div class="grid grid-cols-1 lg:grid-cols-5">
                <div class="order-2 min-w-0 bg-gray-900 px-6 py-7 text-white sm:px-8 lg:order-1 lg:col-span-2">
                    <p class="hidden lg:inline-flex items-center text-lg font-semibold tracking-[1px] text-white">
                        KONFIRMASI PASSWORD
                    </p>
                    <p class="mt-4 text-sm leading-relaxed text-gray-300">
                        Area ini membutuhkan konfirmasi password untuk keamanan.
                    </p>

                    <ul class="mt-6 space-y-3 text-sm text-gray-200">
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500/10 text-md font-bold text-orange-500">1</span>
                            <span>Masukkan password akun Anda saat ini.</span>
                        </li>
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500/10 text-md font-bold text-orange-500">2</span>
                            <span>Data Anda tetap aman dan terlindungi.</span>
                        </li>
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500/10 text-md font-bold text-orange-500">3</span>
                            <span>Konfirmasi hanya untuk aksi sensitif.</span>
                        </li>
                    </ul>
                </div>

                <div class="order-1 min-w-0 px-5 py-6 sm:px-8 sm:py-8 lg:order-2 lg:col-span-3 lg:px-10">
                    <div class="mb-6">
                        <h1 class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl tracking-[1px]">KONFIRMASI AKUN</h1>
                        <p class="mt-2 text-md text-gray-600">Masukkan password untuk melanjutkan.</p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4 sm:space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('login') }}"
                                class="text-sm text-slate-600 underline underline-offset-4 transition hover:text-slate-900">
                                Kembali ke login
                            </a>
                            <x-primary-button
                                class="w-full justify-center rounded-md bg-orange-500 px-6 py-3 text-sm font-semibold uppercase tracking-[1px] hover:bg-orange-400 sm:w-auto">
                                Konfirmasi
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@endsection