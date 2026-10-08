@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Kelola User')

@section('content')
@php
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
@endphp
<div class="space-y-6">
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-[1px] md:text-left text-center">Kelola Pengguna
                </h1>
                <p class="mt-1 text-base text-slate-600 md:text-left text-center">Daftar seluruh pengguna beserta
                    identitas dan status persetujuan akun.</p>
            </div>
        </div>

    </div>

    <div class="rounded-md border border-slate-200 bg-slate-50 p-4 shadow-sm">
        <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <div class="md:col-span-2">
                <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Cari
                    Pengguna</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Nama, Email, NIK, No HP"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-500 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label for="status"
                    class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-700">Filter
                    Pengguna</label>
                <select id="status" name="status"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <option value="all" @selected($status==='all' )>Semua</option>
                    <option value="approved" @selected($status==='approved' )>Disetujui</option>
                    <option value="pending" @selected($status==='pending' )>Menunggu</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <div class="space-y-3">
            @forelse($users as $user)
            @php
            $initials = collect(explode(' ', trim($user->name)))
            ->filter()
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');
            @endphp
            <div
                class="rounded-md border border-slate-200 bg-slate-50 p-4 transition hover:bg-slate-100 hover:shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div
                            class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white">
                            {{ $initials ?: 'U' }}
                        </div>
                        <div class="min-w-0">
                            <p class="tracking-[1px] text-sm font-bold uppercase text-slate-700">{{ $user->name }}
                            </p>
                            <p class="tracking-[.5px] text-sm text-slate-500">NIK. {{ $user->nik ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ ($user->approval_status ?? 'pending') === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ($user->approval_status ?? 'pending') === 'approved' ? 'Disetujui' : 'Menunggu' }}
                        </span>
                        <button type="button" data-open-detail data-target="detail-template-{{ $user->id }}"
                            class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-gray-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-500 tracking-[.5px]">
                            <i class="fa-solid fa-eye"></i>
                            Detail
                        </button>
                    </div>
                </div>
            </div>

            <template id="detail-template-{{ $user->id }}">
                <div class="space-y-4 text-sm text-slate-700">
                    <div
                        class="rounded-md border border-orange-200 bg-gradient-to-r from-orange-50 to-white p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white">
                                {{ $initials ?: 'U' }}
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-slate-700 tracking-[1px] uppercase">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs uppercase tracking-[1px] text-slate-600">NIK {{ $user->nik ?: '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="mb-3 text-sm font-bold uppercase tracking-[1px] text-slate-700">Kontak & Akun</p>
                            @php
                            $roleValue = strtolower(trim((string) ($user->role ?? 'warga')));
                            $roleLabel = match ($roleValue) {
                            'admin' => 'Admin',
                            'petugas' => 'Petugas',
                            'warga' => 'Warga',
                            default => ucfirst($roleValue),
                            };
                            @endphp
                            <div class="space-y-2 text-base text-slate-800">
                                <p><span class="font-semibold text-slate-700">Email:</span> {{ $user->email ?: '-' }}
                                </p>
                                <p><span class="font-semibold text-slate-700">No HP:</span> {{ $user->no_hp ?: '-' }}
                                </p>
                                <p><span class="font-semibold text-slate-700">Role:</span> {{ $roleLabel }}</p>
                                <p><span class="font-semibold text-slate-700">Status:</span>
                                    <span
                                        class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ ($user->approval_status ?? 'pending') === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ($user->approval_status ?? 'pending') === 'approved' ? 'Disetujui' : 'Menunggu' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="mb-3 text-sm font-bold uppercase tracking-[1px] text-slate-700">Identitas Diri</p>
                            <div class="space-y-2 text-base text-slate-800">
                                <p><span class="font-semibold text-slate-700">Jenis Kelamin:</span>
                                    {{ $user->jenis_kelamin ?: '-' }}</p>
                                <p><span class="font-semibold text-slate-700">Tempat Lahir:</span>
                                    {{ $user->tempat_lahir ?: '-' }}</p>
                                <p><span class="font-semibold text-slate-700">Tanggal Lahir:</span>
                                    @if($user->tanggal_lahir && $user->bulan_lahir && $user->tahun_lahir)
                                    {{ str_pad((string) $user->tanggal_lahir, 2, '0', STR_PAD_LEFT) }}-{{ str_pad((string) $user->bulan_lahir, 2, '0', STR_PAD_LEFT) }}-{{ $user->tahun_lahir }}
                                    @else
                                    -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="mb-3 text-sm font-bold uppercase tracking-[1px] text-slate-700">Alamat / Domisili</p>
                        <div class="grid grid-cols-1 gap-1.5 md:grid-cols-2 text-base text-slate-800">
                            <p><span class="font-semibold text-slate-700">Provinsi:</span>
                                {{ $user->provinsi_display ?? ($user->provinsi_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-700">Kabupaten:</span>
                                {{ $user->kabupaten_display ?? ($user->kabupaten_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-700">Kecamatan:</span>
                                {{ $user->kecamatan_display ?? ($user->kecamatan_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-700">Desa:</span>
                                {{ $user->desa_display ?? ($user->desa_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-700">Dusun:</span> {{ $user->dusun ?: '-' }}</p>
                            <p><span class="font-semibold text-slate-700">RT/RW:</span> {{ $user->{'rt/rw'} ?: '-' }}
                            </p>
                            <p><span class="font-semibold text-slate-700">Kode Pos:</span> {{ $user->kode_pos ?: '-' }}
                            </p>
                            <p><span class="font-semibold text-slate-700">Alamat Detail:</span>
                                {{ $user->alamat_detail ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm text-base space-y-2">
                        <p class="mb-3 text-sm font-bold uppercase tracking-[1px] text-slate-700">Aktivitas Akun</p>
                        <p><span class="font-semibold text-slate-700 ">Tanggal Daftar:</span>
                            {{ $user->created_at?->format('d-m-Y H:i') }}</p>
                        <p><span class="font-semibold text-slate-700 ">Total Pengajuan Surat:</span>
                            {{ number_format($user->surat_pengajuans_count) }}</p>
                        @if($user->approved_at)
                        <p><span class="font-semibold text-slate-700 ">Disetujui Pada:</span>
                            {{ $user->approved_at->format('d-m-Y H:i') }}</p>
                        @endif
                    </div>

                    @if(($user->role ?? 'warga') === 'warga')
                    <form method="POST" action="{{ route($routePrefix . '.users.approval', $user) }}"
                        class="space-y-3 rounded-md border border-slate-200 bg-white p-4">
                        @csrf
                        @method('PATCH')
                        <label class="block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Perbarui
                            Persetujuan</label>
                        <select name="approval_status"
                            class="w-full rounded-lg border border-slate-300 px-2 py-2 text-xs font-semibold">
                            <option value="pending" @selected($user->approval_status === 'pending')>MENUNGGU</option>
                            <option value="approved" @selected($user->approval_status === 'approved')>DISETUJUI</option>
                        </select>
                        <button type="submit"
                            class="mt-2 rounded-md bg-slate-700 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-slate-600">
                            Simpan
                        </button>
                    </form>
                    @endif

                    @if(auth()->user()?->role === 'admin' || ($user->role ?? 'warga') === 'warga')
                    <form method="POST" action="{{ route($routePrefix . '.users.password', $user) }}"
                        class="space-y-3 rounded-md border border-slate-200 bg-white p-4"
                        onsubmit="return confirm('Yakin ingin mereset password pengguna ini?')">
                        @csrf
                        @method('PATCH')
                        <p class="text-sm font-bold uppercase tracking-[1px] text-slate-700">Reset Password</p>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div>
                                <label
                                    class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Password
                                    Baru</label>
                                <input type="password" name="password" minlength="8" autocomplete="new-password"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500"
                                    required>
                            </div>
                            <div>
                                <label
                                    class="mb-1 block text-xs font-semibold uppercase tracking-[1px] text-slate-600">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation" minlength="8"
                                    autocomplete="new-password"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500"
                                    required>
                            </div>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-md bg-slate-700 px-4 py-1.5 text-sm font-semibold text-white hover:bg-slate-600">
                            <i class="fa-solid fa-key"></i>
                            Reset Password
                        </button>
                    </form>
                    @endif
                </div>
            </template>
            @empty
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-8 text-center text-slate-500">
                Data user belum tersedia.
            </div>
            @endforelse
        </div>
    </div>

    <div class="flex flex-col gap-3 rounded-md border border-slate-200 bg-white px-4 py-3 shadow-sm md:flex-row md:items-center md:justify-between">
        <p class="text-sm font-medium text-slate-600">
            Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </p>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>

<div id="userDetailModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm">
    <div class="w-full max-w-3xl overflow-hidden rounded-xl border border-slate-300 bg-gray-50 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-700 bg-gray-800 px-5 py-4">
            <h3 class="text-xl font-bold text-white">Detail Identitas Pengguna</h3>
            <button type="button" id="closeUserDetailModal"
                class="rounded-md px-2 py-1 text-slate-300 transition hover:bg-white/10 hover:text-white"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="userDetailModalBody" class="max-h-[75vh] overflow-y-auto px-5 py-5"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const modal = document.getElementById('userDetailModal');
    const modalBody = document.getElementById('userDetailModalBody');
    const closeBtn = document.getElementById('closeUserDetailModal');

    const closeModal = () => {
        if (!modal || !modalBody) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modalBody.innerHTML = '';
    };

    document.addEventListener('click', (event) => {
        const btn = event.target.closest('[data-open-detail]');
        if (!btn || !modal || !modalBody) return;

        const targetId = btn.getAttribute('data-target');
        const template = targetId ? document.getElementById(targetId) : null;
        if (!template) return;
        modalBody.innerHTML = template.innerHTML;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    closeBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    document.querySelectorAll('[data-open-detail]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const template = targetId ? document.getElementById(targetId) : null;
            if (!template || !modal || !modalBody) return;
            modalBody.innerHTML = template.innerHTML;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });
})();
</script>
@endpush
