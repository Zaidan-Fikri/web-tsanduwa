@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-sm text-slate-500">Menampilkan <b class="text-slate-800">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</b> dari <b class="text-slate-800">{{ $paginator->total() }}</b> data</p>
        @include('partials.pagination')
    </div>
@endif
