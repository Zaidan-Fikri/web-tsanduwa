@extends('layouts.admin')

@section('title', 'Slider Utama')

@section('content')
    <x-admin.page-header title="Slider Utama" subtitle="Gambar besar bergeser di bagian paling atas Beranda." icon="fa-images">
        <x-admin.add-button :href="route('admin.slider.create')" label="Tambah Slide" />
    </x-admin.page-header>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Belum ada slide." :href="route('admin.slider.create')" label="Tambah Slide" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th class="w-16">Urutan</th><th>Slide</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="font-bold text-slate-500 text-center">{{ $item->sort_order }}</td>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <x-admin.thumb :src="$item->image" size="w-28 h-16" />
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800">{{ $item->title }}</p>
                                            <p class="text-xs text-slate-500 line-clamp-1">{{ $item->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-tosca-100 text-tosca-800">Tampil</span>
                                    @else
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">Disembunyikan</span>
                                    @endif
                                </td>
                                <td><x-admin.row-actions :edit="route('admin.slider.edit', $item)" :destroy="route('admin.slider.destroy', $item)" :name="'slide '.$item->title" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
