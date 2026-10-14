@php([$dateMain, $dateSub] = $agenda->dateParts())
<article class="flex items-center gap-4 sm:gap-6 bg-white border border-slate-200 rounded-2xl p-4 hover:-translate-y-1 hover:shadow-lg transition">
    <span class="w-24 shrink-0 text-center rounded-xl {{ $agenda->dateClass() }} py-3"><b class="block text-lg leading-none">{{ $dateMain }}</b><i class="not-italic text-xs">{{ $dateSub }}</i></span>
    <h5 class="flex-1 font-semibold text-slate-800">{{ $agenda->title }}</h5>
    <span class="hidden sm:inline-block text-xs font-bold px-3 py-1 rounded-full {{ $agenda->badgeClass() }}">{{ $agenda->categoryLabel() }}</span>
</article>
