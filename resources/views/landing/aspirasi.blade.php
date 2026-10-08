@extends('layouts.app')

@section('title', config('app.name') . ' | Aspirasi Warga')

@push('styles')
<style>
.reveal,
.reveal-item {
    opacity: 0;
    transform: translateY(50px) rotateX(15deg) scale(0.95);
    transform-origin: top center;
    transition:
        opacity .9s cubic-bezier(0.16, 1, 0.3, 1),
        transform .9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal.is-visible,
.reveal-item.is-visible {
    opacity: 1;
    transform: translateY(0) rotateX(0deg) scale(1);
}

.reveal-left {
    opacity: 0;
    transform: translateX(-60px) rotateY(20deg) scale(0.94);
    transform-origin: left center;
    transition:
        opacity .9s cubic-bezier(0.16, 1, 0.3, 1),
        transform .9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal-right {
    opacity: 0;
    transform: translateX(60px) rotateY(-20deg) scale(0.94);
    transform-origin: right center;
    transition:
        opacity .9s cubic-bezier(0.16, 1, 0.3, 1),
        transform .9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal-left.is-visible,
.reveal-right.is-visible {
    opacity: 1;
    transform: translateX(0) rotateY(0deg) scale(1);
}

.reveal-delay-1 {
    transition-delay: .05s;
}

.reveal-delay-2 {
    transition-delay: .2s;
}

.reveal-delay-3 {
    transition-delay: .35s;
}

@media (prefers-reduced-motion: reduce) {

    .reveal,
    .reveal-left,
    .reveal-right,
    .reveal-item {
        opacity: 1;
        transform: none;
        transition: none;
    }
}
</style>
@endpush

@section('content')
@include('partials.nav')

<div class="bg-gray-100">
    <header class="sticky top-0 z-0 h-[260px] sm:h-[290px]">
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ asset('img/aspirasi-img.jpg') }}" alt="Aspirasi Warga"
                class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-slate-900/70"></div>
        </div>
        <div
            class="relative z-10 mx-auto flex h-full max-w-6xl flex-col items-center justify-center px-2 text-center md:px-4">
            <div class="mt-16 sm:mt-20 reveal">
                <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Aspirasi Warga</h1>
                <p class="text-sm md:text-base text-white/80">
                    Ruang penyampaian masukan, usulan, kritik, dan saran untuk Desa Wonorejo.
                </p>
            </div>
        </div>
    </header>

    <main class="relative z-10 -mt-[60px] sm:-mt-[50px]">
        <div class="relative h-16 w-full overflow-hidden">
            <div class="absolute -top-px left-1/2 h-[65px] w-[150%] -translate-x-1/2 rounded-t-[50%] bg-gray-100">
            </div>
        </div>

        <div class="bg-gray-100 pb-16">
            <div class="relative z-10 mx-auto w-full md:px-12">
                <div class="bg-gray-100 p-5">
                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
                        <section
                            class="reveal-left xl:col-span-5 rounded-xl border border-orange-200/70 bg-white p-7 shadow-lg">
                            <p class="text-xs font-bold uppercase tracking-[2px] text-orange-600">Tentang Layanan</p>
                            <h2 class="mt-3 text-2xl font-bold uppercase tracking-[1px] text-slate-900">
                                Sampaikan aspirasi secara tertib dan jelas
                            </h2>
                            <p class="mt-4 text-sm leading-7 text-slate-600">
                                Halaman ini disediakan untuk membantu warga menyampaikan usulan pembangunan, keluhan
                                pelayanan, saran perbaikan, atau masukan lain yang berkaitan dengan penyelenggaraan
                                desa.
                            </p>

                            <div class="mt-6 space-y-3">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-800">Aspirasi yang bisa disampaikan</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Infrastruktur lingkungan, pelayanan administrasi, kegiatan sosial, keamanan,
                                        kebersihan, dan usulan program desa.
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-800">Agar mudah ditindaklanjuti</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Tulis lokasi, kejadian, waktu, dan penjelasan singkat yang spesifik.
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-800">Tindak lanjut</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Aspirasi akan dipilah sesuai bidang pelayanan dan diteruskan kepada pihak desa
                                        terkait.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <section
                            class="reveal-right xl:col-span-7 rounded-xl border border-slate-200 bg-white p-7 shadow-lg">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[2px] text-orange-600">Form Aspirasi
                                    </p>
                                    <h2 class="mt-3 text-2xl font-bold uppercase tracking-[1px] text-slate-900">
                                        Saluran aspirasi warga
                                    </h2>
                                    <p class="mt-2 text-sm text-slate-600">
                                        Silakan isi data berikut untuk mengirim aspirasi atau keluhan Anda ke pihak
                                        desa.
                                    </p>
                                </div>
                                <div class="hidden rounded-full bg-orange-100 p-4 text-orange-600 md:block">
                                    <i class="fa-solid fa-comments text-2xl"></i>
                                </div>
                            </div>

                            @if(session('status'))
                            <div
                                class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('aspirasi.store') }}" enctype="multipart/form-data"
                                class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-2">
                                @csrf
                                <div class="md:col-span-2">
                                    <label for="nama_lengkap"
                                        class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Nama
                                        Lengkap <span class="text-red-500">*</span></label>
                                    <input id="nama_lengkap" name="nama_lengkap" type="text"
                                        placeholder="Masukkan nama lengkap"
                                        value="{{ old('nama_lengkap') }}"
                                        class="w-full rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:outline-none focus:ring-0">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="alamat_lengkap"
                                        class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Alamat
                                        Lengkap <span class="text-red-500">*</span></label>
                                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3"
                                        placeholder="Masukkan alamat lengkap Anda"
                                        class="w-full rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:outline-none focus:ring-0">{{ old('alamat_lengkap') }}</textarea>
                                </div>
                                <div class="md:col-span-1">
                                    <label for="jenis_kelamin"
                                        class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Jenis
                                        Kelamin <span class="text-red-500">*</span></label>
                                    <select id="jenis_kelamin" name="jenis_kelamin"
                                        class="w-full rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:outline-none focus:ring-0">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="laki-laki" @selected(old('jenis_kelamin')==='laki-laki' )>
                                            Laki-laki</option>
                                        <option value="perempuan" @selected(old('jenis_kelamin')==='perempuan' )>
                                            Perempuan</option>
                                    </select>
                                </div>
                                <div class="md:col-span-1">
                                    <label for="gambar"
                                        class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Gambar
                                        (Opsional)</label>
                                    <input id="gambar" name="gambar" type="file" accept="image/*"
                                        class="w-full rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:outline-none focus:ring-0">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="isi_aspirasi"
                                        class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Aspirasi
                                        / Keluhan Anda <span class="text-red-500">*</span></label>
                                    <textarea id="isi_aspirasi" name="isi_aspirasi" rows="7"
                                        placeholder="Tulis aspirasi atau keluhan Anda secara jelas"
                                        class="w-full rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:outline-none focus:ring-0">{{ old('isi_aspirasi') }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                </div>
                                <div class="md:col-span-2 flex flex-wrap items-center gap-3">
                                    <button type="submit"
                                        class="inline-flex items-center rounded-md bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:bg-orange-400">
                                        Kirim Aspirasi
                                    </button>
                                    <p class="text-xs text-slate-500">
                                        Gambar bersifat opsional dengan ukuran maksimal 2 MB.
                                    </p>
                                </div>
                            </form>
                        </section>
                    </div>

                    <section class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-3" data-reveal-group>
                        <div class="reveal-item rounded-xl border border-orange-200 bg-orange-50 p-5 shadow-sm">
                            <div
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white text-orange-600 shadow-sm">
                                <i class="fa-solid fa-lightbulb text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900">Usulan</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Sampaikan ide program atau kebutuhan lingkungan yang menurut warga penting untuk
                                diprioritaskan.
                            </p>
                        </div>
                        <div class="reveal-item rounded-xl border border-sky-200 bg-sky-50 p-5 shadow-sm">
                            <div
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white text-sky-600 shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900">Keluhan</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Gunakan bagian ini untuk melaporkan kendala pelayanan, fasilitas umum, atau persoalan
                                lapangan.
                            </p>
                        </div>
                        <div class="reveal-item rounded-xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                            <div
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                                <i class="fa-solid fa-handshake-angle text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900">Saran</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Masukan yang membangun akan membantu desa meningkatkan kualitas layanan dan komunikasi
                                publik.
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-item');
    if (!items.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -10% 0px',
    });

    const groups = document.querySelectorAll('[data-reveal-group]');
    groups.forEach((group) => {
        const groupItems = Array.from(group.querySelectorAll('.reveal-item'));
        groupItems.forEach((el, idx) => {
            el.style.transitionDelay = `${Math.min(6, idx) * 0.05}s`;
        });
    });

    items.forEach((el, i) => {
        if (!el.style.transitionDelay) {
            const delayClass = `reveal-delay-${Math.min(3, (i % 3) + 1)}`;
            el.classList.add(delayClass);
        }
        observer.observe(el);
    });
});
</script>
@endpush
@endsection
