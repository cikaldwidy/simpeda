<div class="flex flex-col gap-3 border-b border-gray-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
    <button type="button" data-bulk-selection-toggle aria-pressed="false"
        class="inline-flex items-center gap-2 self-start rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-orange-500 hover:text-orange-600">
        <i class="fa-solid fa-check-to-slot"></i>
        <span data-bulk-selection-toggle-label>Pilih</span>
    </button>
    <div data-bulk-selection-controls class="hidden flex items-center gap-3">
        <form id="{{ $formId }}" method="POST" action="{{ $action }}"
            data-delete-title="{{ $title }}"
            data-delete-message="{{ $message }}">
            @csrf
            @method('DELETE')
            <button type="submit" data-bulk-delete-button disabled
                class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-500 disabled:cursor-not-allowed disabled:opacity-50">
                <i class="fa-solid fa-trash"></i>
                <span data-bulk-delete-label>Hapus (0)</span>
            </button>
        </form>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bulk-selection]').forEach((container) => {
        const toggle = container.querySelector('[data-bulk-selection-toggle]');
        const toggleLabel = container.querySelector('[data-bulk-selection-toggle-label]');
        const controls = container.querySelector('[data-bulk-selection-controls]');
        const cells = container.querySelectorAll('[data-bulk-selection-cell]');
        const selectAll = container.querySelector('[data-bulk-select-all]');
        const rows = Array.from(container.querySelectorAll('[data-bulk-select-row]'));
        const deleteButton = container.querySelector('[data-bulk-delete-button]');
        const deleteLabel = container.querySelector('[data-bulk-delete-label]');

        if (!toggle || !toggleLabel || !controls || !selectAll || !deleteButton || !deleteLabel) return;

        const refreshSelection = () => {
            const selectedCount = rows.filter((checkbox) => checkbox.checked).length;

            deleteButton.disabled = selectedCount === 0;
            deleteLabel.textContent = `Hapus (${selectedCount})`;
            selectAll.checked = rows.length > 0 && selectedCount === rows.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < rows.length;
        };

        const setSelectionMode = (enabled) => {
            toggle.setAttribute('aria-pressed', String(enabled));
            toggleLabel.textContent = enabled ? 'Batal pilih' : 'Pilih';
            controls.classList.toggle('hidden', !enabled);
            cells.forEach((cell) => cell.classList.toggle('hidden', !enabled));
            selectAll.disabled = !enabled;

            if (!enabled) {
                rows.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }

            refreshSelection();
        };

        toggle.addEventListener('click', () => {
            setSelectionMode(toggle.getAttribute('aria-pressed') !== 'true');
        });
        selectAll.addEventListener('change', () => {
            rows.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            });
            refreshSelection();
        });
        rows.forEach((checkbox) => checkbox.addEventListener('change', refreshSelection));
        setSelectionMode(false);
    });
});
</script>
@endpush
@endonce
