@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Profil Pengguna')
@section('dashboard_title', 'Profil Pengguna')
@section('dashboard_subtitle', 'Kelola data akun dan informasi profil Anda.')

@section('dashboard_content')
<div class="space-y-6">
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-700 px-5 py-6 text-white sm:px-7">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-orange-500 text-2xl font-bold shadow-lg shadow-black/10">
                    {{ mb_strtoupper(mb_substr(trim($user->name), 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[1.5px] text-orange-300">Profil Warga</p>
                    <h2 class="mt-1 truncate text-2xl font-bold tracking-wide">{{ $user->name }}</h2>
                    <p class="mt-1 break-all text-sm text-slate-300">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-7">
            <div class="mb-4 flex items-center gap-2">
                <h3 class="text-sm font-bold uppercase tracking-[1px] text-slate-700">Informasi Identitas</h3>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[1px] text-slate-500">NIK</p>
                    <p class="mt-1.5 break-all text-sm font-semibold text-slate-800">{{ $user->nik ?: '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[1px] text-slate-500">Nomor HP</p>
                    <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $user->no_hp ?: '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[1px] text-slate-500">Jenis Kelamin</p>
                    <p class="mt-1.5 text-sm font-semibold capitalize text-slate-800">{{ $user->jenis_kelamin ?: '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:col-span-2 xl:col-span-1">
                    <p class="text-[11px] font-semibold uppercase tracking-[1px] text-slate-500">Tempat, Tanggal Lahir</p>
                    <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $ttlFormatted ?: '-' }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:col-span-2 xl:col-span-2">
                    <p class="text-[11px] font-semibold uppercase tracking-[1px] text-slate-500">Alamat Domisili</p>
                    @if(count($alamatDomisiliLines))
                    @foreach($alamatDomisiliLines as $line)
                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $line }}</p>
                    @endforeach
                    @else
                    <p class="mt-1.5 text-sm font-semibold text-slate-800">-</p>
                    @endif
                    @if(!empty($user->alamat_detail))
                    <p class="mt-1 text-sm text-slate-600">{{ $user->alamat_detail }}</p>
                    @endif
                    @if(!empty($user->kode_pos))
                    <p class="mt-1 text-xs text-slate-500">Kode Pos {{ $user->kode_pos }}</p>
                    @endif
                </div>
            </div>
            <p class="mt-4 text-xs leading-5 text-slate-500">
                Untuk perubahan, gunakan formulir Informasi Akun.
            </p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('profile.partials.update-password-form')
        </section>
    </div>
</div>
@endsection