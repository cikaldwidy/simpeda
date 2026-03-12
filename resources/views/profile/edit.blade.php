@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Profil Pengguna')
@section('dashboard_title', 'Profil Pengguna')
@section('dashboard_subtitle', 'Kelola data akun dan informasi profil Anda.')

@section('dashboard_content')
<div class="space-y-6">
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900">Keterangan Profil</h3>
        <p class="mt-1 text-sm text-gray-500">Data ini diambil dari hasil pendaftaran warga.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Nama Lengkap</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->name ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">NIK</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->nik ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Email</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->email ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">No. HP</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->no_hp ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Jenis Kelamin</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->jenis_kelamin ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Tempat, Tanggal Lahir</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $ttlFormatted }}</p>
            </div>
        </div>

        <div class="mt-4 rounded-lg border border-gray-100 bg-gray-50 p-4">
            <p class="text-xs uppercase tracking-wide text-gray-500">Alamat Domisili</p>
            @if(count($alamatDomisiliLines))
                @foreach($alamatDomisiliLines as $line)
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $line }}</p>
                @endforeach
            @else
                <p class="mt-1 text-sm font-semibold text-gray-900">-</p>
            @endif
            @if(!empty($user->alamat_detail))
                <p class="mt-1 text-sm text-gray-700">{{ $user->alamat_detail }}</p>
            @endif
            @if(!empty($user->kode_pos))
                <p class="mt-1 text-sm text-gray-700">Kode Pos {{ $user->kode_pos }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="rounded-xl border border-red-200 bg-white p-5 shadow-sm sm:p-6">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
