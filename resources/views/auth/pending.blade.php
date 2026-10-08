@extends('layouts.auth')

@section('title', config('app.name') . ' | Status Pendaftaran')

@section('content')
@include('partials.nav')

<div class="flex min-h-screen flex-col bg-black">
    <section
        class="relative overflow-hidden pt-24 pb-12 sm:pt-28 sm:pb-16 min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
        style="background-image: url({{ asset('img/bg-motif.jpg') }});">
        <div class="pointer-events-none absolute inset-0 bg-black/50"></div>
        <div class="relative mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div
                class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-white/95 p-6 shadow-2xl backdrop-blur sm:p-8">
                @if(!empty($approvedMessage))
                <div
                    class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50 px-4 py-3">
                    <span
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">Verifikasi Berhasil</p>
                        <p class="text-xs text-emerald-700">Akun Anda telah disetujui oleh pihak Desa.</p>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-900">Akun Berhasil Disetujui</h1>
                <p class="mt-3 text-gray-600">Akun Anda sudah aktif. Silakan lanjut login untuk masuk ke sistem.</p>

                <a href="{{ route('login') }}"
                    class="mt-6 inline-flex text-sm font-medium text-gray-500 transition underline hover:text-orange-500">
                    Login Sekarang
                </a>
                @else
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-amber-200/70 bg-amber-50 px-4 py-3">
                    <span
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                        <i class="fa-solid fa-spinner animate-spin text-lg"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-amber-900">Proses Verifikasi Sedang Berjalan</p>
                        <p class="text-xs text-amber-700">Mohon tunggu, permintaan Anda sedang diproses admin.</p>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-900">Akun Masih Menunggu Persetujuan</h1>
                <p class="mt-3 text-gray-600">
                    Data pendaftaran Anda sedang diverifikasi oleh pihak Desa.
                    Silakan tunggu sampai akun disetujui untuk dapat mengakses layanan digital.
                </p>

                @if(auth()->check() && auth()->user()->approval_status === 'rejected')
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-900">
                    <strong>Pendaftaran ditolak.</strong><br>
                    Alasan: {{ auth()->user()->rejection_reason ?? '-' }}
                </div>
                @endif


                @endif
            </div>
        </div>
    </section>
</div>
@endsection