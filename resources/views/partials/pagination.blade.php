@php($base = 'w-10 h-10 rounded-xl text-sm font-bold flex items-center justify-center transition')
<nav class="flex items-center gap-1.5" aria-label="Halaman">
    @if ($paginator->onFirstPage())
        <span aria-hidden="true" class="{{ $base }} border border-slate-200 bg-white text-slate-600 opacity-40"><i class="fa-solid fa-chevron-left"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" class="{{ $base }} border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700"><i class="fa-solid fa-chevron-left"></i></a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="{{ $base }} text-slate-400">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span aria-current="page" class="{{ $base }} bg-tosca-700 text-white shadow-md">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="{{ $base }} border border-slate-200 bg-white text-slate-700 hover:border-tosca-600 hover:text-tosca-700">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya" class="{{ $base }} border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700"><i class="fa-solid fa-chevron-right"></i></a>
    @else
        <span aria-hidden="true" class="{{ $base }} border border-slate-200 bg-white text-slate-600 opacity-40"><i class="fa-solid fa-chevron-right"></i></span>
    @endif
</nav>
