@extends('layouts.admin')

@section('title', 'Program Madrasah')

@section('content')
    <x-admin.page-header title="Program Madrasah" subtitle="Program unggulan yang tampil di menu dropdown Program Madrasah." icon="fa-book-quran">
        <x-admin.add-button :href="route('admin.program.create')" label="Tambah Program" />
    </x-admin.page-header>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Belum ada program." :href="route('admin.program.create')" label="Tambah Program" />
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($items as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4">
                        <span class="w-12 h-12 shrink-0 rounded-xl bg-gold-500 text-slate-950 flex items-center justify-center text-xl"><i class="fa-solid {{ $item->icon }}"></i></span>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800">{{ $item->menu_name }}</p>
                            <p class="text-xs text-slate-500">{{ $item->title }} · {{ count($item->activities ?? []) }} kegiatan · {{ count($item->infos ?? []) }} informasi</p>
                        </div>
                        <a href="{{ route('program', $item) }}" target="_blank" rel="noopener" class="text-xs font-bold text-slate-500 hover:text-tosca-700"><i class="fa-solid fa-eye"></i> Lihat</a>
                        <x-admin.row-actions :edit="route('admin.program.edit', $item)" :destroy="route('admin.program.destroy', $item)" :name="'program '.$item->menu_name" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
