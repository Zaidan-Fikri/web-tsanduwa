<div class="text-center {{ $margin ?? 'mb-10' }}">
    <span class="text-tosca-700 font-bold text-sm tracking-widest uppercase">{{ $eyebrow }}</span>
    <h1 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $title }}</h1>
    @isset($subtitle)
        <p class="text-slate-500 mt-2 text-sm">{{ $subtitle }}</p>
    @endisset
    <div class="w-20 h-1 bg-gold-500 mx-auto mt-3 rounded-full"></div>
</div>
