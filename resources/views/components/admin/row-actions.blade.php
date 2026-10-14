@props(['edit', 'destroy', 'name' => 'data ini'])

<div class="flex items-center justify-end gap-1.5">
    <a href="{{ $edit }}" title="Ubah" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-tosca-700 bg-tosca-50 hover:bg-tosca-100 transition">
        <i class="fa-solid fa-pen-to-square"></i><span class="hidden md:inline">Ubah</span>
    </a>
    <form action="{{ $destroy }}" method="POST" data-confirm="Hapus {{ $name }}? Data yang dihapus tidak dapat dikembalikan.">
        @csrf
        @method('DELETE')
        <button type="submit" title="Hapus" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">
            <i class="fa-solid fa-trash-can"></i><span class="hidden md:inline">Hapus</span>
        </button>
    </form>
</div>
