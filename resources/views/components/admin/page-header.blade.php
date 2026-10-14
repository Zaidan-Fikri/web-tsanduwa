@props(['title', 'subtitle' => null, 'icon' => null])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        @if ($icon)
            <span class="w-12 h-12 shrink-0 rounded-2xl bg-gradient-to-br from-tosca-600 to-tosca-800 text-gold-400 flex items-center justify-center text-xl shadow-md"><i class="fa-solid {{ $icon }}"></i></span>
        @endif
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if (trim($slot))
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
