@props([
    'paginator',
    'label' => 'data',
])

<div class="flex flex-col gap-3 rounded-md border border-slate-200 bg-white px-4 py-3 shadow-sm md:flex-row md:items-center md:justify-between">
    <p class="text-sm font-medium text-slate-600">
        Menampilkan {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} {{ $label }}
        <span class="text-slate-400">| Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    </p>

    <div>
        {{ $paginator->links() }}
    </div>
</div>
