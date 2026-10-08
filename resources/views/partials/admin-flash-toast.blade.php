@php
$flashToasts = collect();

if (session('success')) {
    $flashToasts->push([
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => session('success'),
    ]);
}

if (session('status')) {
    $statusMessage = match (session('status')) {
        'profile-updated' => 'Informasi akun berhasil diperbarui.',
        'password-updated' => 'Password berhasil diperbarui.',
        default => session('status'),
    };
    $flashToasts->push([
        'type' => 'success',
        'title' => 'Berhasil',
        'message' => $statusMessage,
    ]);
}

if (session('status_error')) {
    $flashToasts->push([
        'type' => 'error',
        'title' => 'Gagal',
        'message' => session('status_error'),
    ]);
}

if (session('error')) {
    $flashToasts->push([
        'type' => 'error',
        'title' => 'Gagal',
        'message' => session('error'),
    ]);
}

if ($errors->any()) {
    $flashToasts->push([
        'type' => 'error',
        'title' => 'Peringatan',
        'message' => $errors->first(),
    ]);
}
@endphp

@if($flashToasts->isNotEmpty())
<div class="fixed right-4 top-20 z-[100] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3 sm:right-6" data-admin-flash-wrapper>
    @foreach($flashToasts as $toast)
    @php
    $isSuccess = $toast['type'] === 'success';
    $accentClass = $isSuccess ? 'bg-emerald-500' : 'bg-red-500';
    $iconClass = $isSuccess ? 'fa-circle-check' : 'fa-circle-exclamation';
    $iconBgClass = $isSuccess ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white';
    @endphp
    <div class="admin-flash-toast relative overflow-hidden rounded-md border border-slate-200 bg-white px-4 py-3 pr-10 text-slate-700 shadow-lg ring-1 ring-black/5 transition duration-200"
        role="alert">
        <div class="flex items-start gap-3">
            <span class="{{ $iconBgClass }} mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[11px]">
                <i class="fa-solid {{ $iconClass }}"></i>
            </span>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-900">{{ $toast['title'] }}</p>
                <p class="mt-0.5 break-words text-xs leading-5 text-slate-600">{{ $toast['message'] }}</p>
            </div>
        </div>
        <button type="button"
            class="absolute right-2 top-2 inline-flex h-6 w-6 items-center justify-center rounded-sm text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
            aria-label="Tutup peringatan" data-admin-flash-close>
            <i class="fa-solid fa-xmark text-[11px]"></i>
        </button>
        <span class="{{ $accentClass }} admin-flash-progress absolute bottom-0 left-0 h-0.5 w-full"></span>
    </div>
    @endforeach
</div>

@once
<style>
@keyframes admin-flash-progress {
    from { width: 100%; }
    to { width: 0%; }
}

.admin-flash-progress {
    animation: admin-flash-progress 4s linear forwards;
}
</style>

@push('scripts')
<script>
(() => {
    const removeToast = (toast) => {
        if (!toast) return;
        toast.classList.add('opacity-0', 'translate-x-3');
        setTimeout(() => toast.remove(), 180);
    };

    document.querySelectorAll('[data-admin-flash-close]').forEach((button) => {
        button.addEventListener('click', () => removeToast(button.closest('.admin-flash-toast')));
    });

    document.querySelectorAll('.admin-flash-toast').forEach((toast) => {
        setTimeout(() => removeToast(toast), 4200);
    });
})();
</script>
@endpush
@endonce
@endif
