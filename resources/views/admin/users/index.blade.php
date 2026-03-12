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

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Total Akun</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Akun Warga</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['warga']) }}</p>
            </div>
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Warga Disetujui</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['approved']) }}</p>
            </div>
            <div class="rrounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Warga Menunggu</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Admin/Petugas</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['adminPetugas']) }}</p>
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

    @if(session('status'))
    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('status') }}
    </div>
    @endif

    @if(session('status_error'))
    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('status_error') }}
    </div>
    @endif

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
                            <p class="tracking-[.5px] text-md font-semibold text-slate-700">{{ $user->name }}
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
                                <p class="text-lg font-bold text-slate-700 tracking-[1px]">{{ $user->name }}</p>
                                <p class="text-xs uppercase tracking-[1px] text-slate-500">NIK {{ $user->nik ?: '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="mb-3 text-xs font-bold uppercase tracking-[1px] text-slate-500">Kontak & Akun</p>
                            @php
                            $roleValue = strtolower(trim((string) ($user->role ?? 'warga')));
                            $roleLabel = match ($roleValue) {
                            'admin' => 'Admin',
                            'petugas' => 'Petugas',
                            'warga' => 'Warga',
                            default => ucfirst($roleValue),
                            };
                            @endphp
                            <div class="space-y-1.5">
                                <p><span class="font-semibold text-slate-600">Email:</span> {{ $user->email ?: '-' }}
                                </p>
                                <p><span class="font-semibold text-slate-600">No HP:</span> {{ $user->no_hp ?: '-' }}
                                </p>
                                <p><span class="font-semibold text-slate-600">Role:</span> {{ $roleLabel }}</p>
                                <p><span class="font-semibold text-slate-600">Status:</span>
                                    <span
                                        class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ ($user->approval_status ?? 'pending') === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ($user->approval_status ?? 'pending') === 'approved' ? 'Disetujui' : 'Menunggu' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="mb-3 text-xs font-bold uppercase tracking-[1px] text-slate-500">Identitas Diri</p>
                            <div class="space-y-1.5">
                                <p><span class="font-semibold text-slate-600">Jenis Kelamin:</span>
                                    {{ $user->jenis_kelamin ?: '-' }}</p>
                                <p><span class="font-semibold text-slate-600">Tempat Lahir:</span>
                                    {{ $user->tempat_lahir ?: '-' }}</p>
                                <p><span class="font-semibold text-slate-600">Tanggal Lahir:</span>
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
                        <p class="mb-3 text-xs font-bold uppercase tracking-[1px] text-slate-500">Alamat / Domisili</p>
                        <div class="grid grid-cols-1 gap-1.5 md:grid-cols-2">
                            <p><span class="font-semibold text-slate-600">Provinsi:</span>
                                {{ $user->provinsi_display ?? ($user->provinsi_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-600">Kabupaten:</span>
                                {{ $user->kabupaten_display ?? ($user->kabupaten_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-600">Kecamatan:</span>
                                {{ $user->kecamatan_display ?? ($user->kecamatan_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-600">Desa:</span>
                                {{ $user->desa_display ?? ($user->desa_id ?: '-') }}</p>
                            <p><span class="font-semibold text-slate-600">Dusun:</span> {{ $user->dusun ?: '-' }}</p>
                            <p><span class="font-semibold text-slate-600">RT/RW:</span> {{ $user->{'rt/rw'} ?: '-' }}
                            </p>
                            <p><span class="font-semibold text-slate-600">Kode Pos:</span> {{ $user->kode_pos ?: '-' }}
                            </p>
                            <p><span class="font-semibold text-slate-600">Alamat Detail:</span>
                                {{ $user->alamat_detail ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[1px] text-slate-500">Aktivitas Akun</p>
                        <p><span class="font-semibold text-slate-600">Tanggal Daftar:</span>
                            {{ $user->created_at?->format('d-m-Y H:i') }}</p>
                        <p><span class="font-semibold text-slate-600">Total Pengajuan Surat:</span>
                            {{ number_format($user->surat_pengajuans_count) }}</p>
                        @if($user->approved_at)
                        <p><span class="font-semibold text-slate-600">Disetujui Pada:</span>
                            {{ $user->approved_at->format('d-m-Y H:i') }}</p>
                        @endif
                    </div>

                    @if(($user->role ?? 'warga') === 'warga')
                    <form method="POST" action="{{ route($routePrefix . '.users.approval', $user) }}"
                        class="space-y-3 rounded-md border border-orange-200 bg-orange-50 p-4">
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
                            class="rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-orange-400 mt-2">
                            Simpan
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

    <div>
        {{ $users->links() }}
    </div>
</div>

<div id="userDetailModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-3xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
            <h3 class="text-xl font-bold text-slate-800">Detail Identitas Pengguna</h3>
            <button type="button" id="closeUserDetailModal"
                class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800"><i
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
    if (!modal || !modalBody || !closeBtn) return;

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modalBody.innerHTML = '';
    };

    document.querySelectorAll('[data-open-detail]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const template = targetId ? document.getElementById(targetId) : null;
            if (!template) return;
            modalBody.innerHTML = template.innerHTML;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
})();
</script>
@endpush