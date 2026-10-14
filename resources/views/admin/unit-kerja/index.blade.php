@extends('layouts.admin')

@section('title', 'Unit Kerja')

@section('content')
    <x-admin.page-header title="Unit Kerja" subtitle="Bidang kerja Wakil Kepala Madrasah." icon="fa-briefcase">
        <x-admin.add-button :href="route('admin.unit-kerja.create')" label="Tambah Unit" />
    </x-admin.page-header>

    @if ($items->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty message="Belum ada unit kerja." :href="route('admin.unit-kerja.create')" label="Tambah Unit" />
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-5">
            @foreach ($items as $item)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="relative h-28 bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-950 flex items-end overflow-hidden">
                        <i class="fa-solid {{ $item->icon }} absolute -right-3 -top-3 text-8xl text-white/10"></i>
                        <div class="h-full w-24 ml-4 pt-3 shrink-0">
                            @if ($item->photo)<img src="{{ media_url($item->photo) }}" alt="" class="w-full h-full object-cover object-top">@endif
                        </div>
                        <div class="self-center px-4 text-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gold-400">{{ $item->position }}</span>
                            <p class="font-extrabold leading-tight">{{ $item->person_name }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="font-bold text-slate-800 text-sm">{{ $item->field_title }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ count($item->programs ?? []) }} program utama · urutan {{ $item->sort_order }}</p>
                        <div class="mt-4"><x-admin.row-actions :edit="route('admin.unit-kerja.edit', $item)" :destroy="route('admin.unit-kerja.destroy', $item)" :name="'unit '.$item->position" /></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
