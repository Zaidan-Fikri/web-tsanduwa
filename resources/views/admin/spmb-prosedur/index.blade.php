@extends('layouts.admin')

@section('title', 'Prosedur Pendaftaran')

@section('content')
    <x-admin.page-header title="Prosedur Pendaftaran" subtitle="Langkah-langkah alur SPMB di halaman SPMB." icon="fa-list-ol">
        <x-admin.add-button :href="route('admin.spmb-prosedur.create')" label="Tambah Langkah" />
    </x-admin.page-header>

    @if ($items->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty message="Belum ada langkah pendaftaran." :href="route('admin.spmb-prosedur.create')" label="Tambah Langkah" />
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 pt-5">
            @foreach ($items as $item)
                <div class="relative bg-white rounded-2xl p-5 pt-9 shadow-sm border border-slate-100 flex flex-col">
                    <span class="absolute -top-5 left-5 w-10 h-10 rounded-full {{ $item->is_highlight ? 'bg-gold-500 text-slate-950' : 'bg-tosca-700 text-white' }} font-extrabold flex items-center justify-center ring-4 ring-slate-50">{{ $loop->iteration }}</span>
                    <div class="w-12 h-12 rounded-xl {{ $item->is_highlight ? 'bg-amber-50 text-gold-600' : 'bg-tosca-50 text-tosca-700' }} flex items-center justify-center text-xl mb-3"><i class="fa-solid {{ $item->icon }}"></i></div>
                    <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wide">{{ $item->title }}</h3>
                    <p class="text-xs text-slate-600 mt-1 flex-1">{{ $item->description }}</p>
                    <div class="mt-4"><x-admin.row-actions :edit="route('admin.spmb-prosedur.edit', $item)" :destroy="route('admin.spmb-prosedur.destroy', $item)" name="langkah ini" /></div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
