@props(['href', 'label' => 'Tambah'])

<a href="{{ $href }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-gold-500 hover:bg-gold-600 text-slate-900 shadow-md hover:shadow-lg transition hover:-translate-y-0.5">
    <i class="fa-solid fa-plus"></i> {{ $label }}
</a>
