<div id="adminDeleteConfirmModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-start gap-3 px-5 py-5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h2 id="adminDeleteConfirmTitle" class="text-lg font-bold text-slate-900">Hapus data ini?</h2>
                <p id="adminDeleteConfirmMessage" class="mt-1 text-sm leading-6 text-slate-600">
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>
        <div class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
            <button type="button" id="adminDeleteCancel"
                class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Batal
            </button>
            <button type="button" id="adminDeleteConfirm"
                class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                <i class="fa-solid fa-trash"></i>
                Hapus
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const modal = document.getElementById('adminDeleteConfirmModal');
    const title = document.getElementById('adminDeleteConfirmTitle');
    const message = document.getElementById('adminDeleteConfirmMessage');
    const cancelButton = document.getElementById('adminDeleteCancel');
    const confirmButton = document.getElementById('adminDeleteConfirm');
    let pendingForm = null;

    if (!modal || !title || !message || !cancelButton || !confirmButton) return;

    const isDeleteForm = (form) => {
        const methodInput = form.querySelector('input[name="_method"]');
        return methodInput && methodInput.value.toUpperCase() === 'DELETE';
    };

    const openModal = (form) => {
        pendingForm = form;
        title.textContent = form.dataset.deleteTitle || 'Hapus data ini?';
        message.textContent = form.dataset.deleteMessage || 'Data yang sudah dihapus tidak dapat dikembalikan.';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        confirmButton.focus();
    };

    const closeModal = () => {
        pendingForm = null;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !isDeleteForm(form) || form.dataset.deleteConfirmed === 'true') {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        openModal(form);
    }, true);

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) return;
        pendingForm.dataset.deleteConfirmed = 'true';
        pendingForm.submit();
    });

    cancelButton.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });
})();
</script>
@endpush
