@extends('layouts.auth')

@section('title', config('app.name') . ' | Lupa Password')

@section('content')
@include('partials.nav')

<section class="relative overflow-hidden pt-24 sm:pt-28 min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url({{ asset('img/bg-motif.jpg') }});">
    <div class="pointer-events-none absolute inset-0 bg-black/50"></div>


    <div class="relative mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8 py-10">
        <div class="overflow-hidden rounded-xl border border-white/10 bg-white/95 shadow-2xl backdrop-blur">
            <div class="order-1 min-w-0 px-5 py-6 sm:px-8 sm:py-8 lg:order-2 lg:col-span-3 lg:px-10">
                <div class="mb-6">
                    <h1 class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl tracking-[1px]">RESET PASSWORD</h1>
                    <p class="mt-2 text-md text-gray-600">Kami akan mengirimkan tautan reset ke email Anda.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('login') }}"
                            class="text-sm text-slate-600 underline underline-offset-4 transition hover:text-slate-900">
                            Kembali ke login
                        </a>
                        <x-primary-button
                            class="w-full justify-center rounded-md bg-orange-500 px-6 py-3 text-sm font-semibold uppercase tracking-[1px] hover:bg-orange-400 sm:w-auto">
                            Kirim Tautan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>

@endsection