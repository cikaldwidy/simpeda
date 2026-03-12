@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Dashboard')
@section('dashboard_title', 'Dashboard Pengguna')
@section('dashboard_subtitle', 'Kelola semua layanan surat dari satu tempat.')

@section('dashboard_content')

<div class="space-y-6">

    {{-- Welcome Card --}}
    <div class="rounded-lg border border-gray-200 bg-gradient-to-r from-orange-600 to-orange-500 p-6 text-white shadow">
        <h3 class="text-2xl md:text-3xl font-bold uppercase text-center">
            Selamat Datang, {{ auth()->user()->name }} 👋
        </h3>
        <p class="mt-2 text-md text-orange-100 text-center">
            Kelola pengajuan surat, pantau status surat, dan lihat riwayat layanan Anda dengan mudah melalui dashboard
            ini.
        </p>
    </div>
</div>

@endsection