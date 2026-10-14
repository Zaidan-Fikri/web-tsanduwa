@props(['message' => 'Belum ada data.', 'href' => null, 'label' => 'Tambah Data'])

<div class="py-14 text-center">
    <span class="w-16 h-16 mx-auto rounded-2xl bg-tosca-50 text-tosca-600 flex items-center justify-center text-2xl"><i class="fa-regular fa-folder-open"></i></span>
    <p class="text-slate-500 mt-4">{{ $message }}</p>
    @if ($href)
        <a href="{{ $href }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white transition shadow-md hover:shadow-lg hover:-translate-y-0.5"><i class="fa-solid fa-plus text-gold-400"></i> {{ $label }}</a>
    @endif
</div>
