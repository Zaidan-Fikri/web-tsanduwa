@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <x-admin.page-header title="Galeri" subtitle="Dokumentasi foto dan video kegiatan madrasah." icon="fa-photo-film">
        <x-admin.add-button :href="route('admin.galeri.create', ['type' => $type])" :label="$type === 'video' ? 'Tambah Video' : 'Upload Foto'" />
    </x-admin.page-header>

    <div class="flex gap-2 mb-5">
        @foreach (['foto' => ['Galeri Foto', 'fa-image'], 'video' => ['Galeri Video', 'fa-video']] as $key => [$label, $icon])
            <a href="{{ route('admin.galeri.index', ['type' => $key]) }}" class="px-5 py-2 rounded-full font-bold text-sm transition {{ $type === $key ? 'bg-tosca-700 text-white shadow' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
                <i class="fa-solid {{ $icon }} mr-1"></i> {{ $label }} <span class="ml-1 text-xs opacity-75">({{ $counts[$key] ?? 0 }})</span>
            </a>
        @endforeach
    </div>

    @if ($items->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty :message="$type === 'video' ? 'Belum ada video.' : 'Belum ada foto.'" :href="route('admin.galeri.create', ['type' => $type])" :label="$type === 'video' ? 'Tambah Video' : 'Upload Foto'" />
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($items as $item)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="relative aspect-video bg-slate-900">
                        @if ($item->type === 'video')
                            <img src="https://i.ytimg.com/vi/{{ $item->youtube_id }}/hqdefault.jpg" alt="" loading="lazy" class="w-full h-full object-cover opacity-80">
                            <span class="absolute inset-0 flex items-center justify-center"><span class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center"><i class="fa-solid fa-play ml-0.5"></i></span></span>
                        @elseif ($item->image)
                            <img src="{{ media_url($item->image) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <p class="font-semibold text-sm text-slate-800 flex-1">{{ $item->title }}</p>
                        <div class="mt-3"><x-admin.row-actions :edit="route('admin.galeri.edit', $item)" :destroy="route('admin.galeri.destroy', $item)" name="item galeri ini" /></div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $items->links('admin.partials.pagination') }}</div>
    @endif
@endsection
