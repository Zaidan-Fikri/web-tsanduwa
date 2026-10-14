@extends('layouts.admin')

@section('title', 'Fasilitas')

@section('content')
    <x-admin.page-header title="Fasilitas Madrasah" subtitle="Kartu fasilitas yang bergeser di Beranda." icon="fa-building">
        <x-admin.add-button :href="route('admin.fasilitas.create')" label="Tambah Fasilitas" />
    </x-admin.page-header>

    @if ($items->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty message="Belum ada fasilitas." :href="route('admin.fasilitas.create')" label="Tambah Fasilitas" />
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($items as $item)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="relative aspect-video bg-tosca-800">
                        @if ($item->image)<img src="{{ media_url($item->image) }}" alt="" loading="lazy" class="w-full h-full object-cover">@endif
                        <span class="absolute top-3 left-3 text-[11px] font-bold bg-white/90 text-slate-700 px-2.5 py-1 rounded-full">Urutan {{ $item->sort_order }}</span>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-slate-900">{{ $item->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 flex-1">{{ $item->description }}</p>
                        <div class="mt-4"><x-admin.row-actions :edit="route('admin.fasilitas.edit', $item)" :destroy="route('admin.fasilitas.destroy', $item)" :name="'fasilitas '.$item->name" /></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
