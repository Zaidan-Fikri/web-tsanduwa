@extends('layouts.admin')

@section('title', 'Struktur Organisasi')

@section('content')
    <x-admin.page-header title="Struktur Organisasi" subtitle="Bagan jabatan madrasah. Tingkat 1 tampil paling atas." icon="fa-sitemap">
        <x-admin.add-button :href="route('admin.struktur.create')" label="Tambah Jabatan" />
    </x-admin.page-header>

    @forelse ($items->groupBy('level') as $level => $members)
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Tingkat {{ $level }} @if ($level == 1)(Pimpinan)@endif</h2>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-100">
                @foreach ($members as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4">
                        <span class="w-10 h-10 rounded-xl {{ $level == 1 ? 'bg-tosca-900 text-gold-400' : 'bg-tosca-50 text-tosca-700' }} flex items-center justify-center shrink-0"><i class="fa-solid fa-user-tie"></i></span>
                        <div class="flex-1">
                            <p class="text-[11px] font-bold uppercase text-tosca-700">{{ $item->position }}</p>
                            <p class="font-bold text-slate-800">{{ $item->name }}</p>
                        </div>
                        <x-admin.row-actions :edit="route('admin.struktur.edit', $item)" :destroy="route('admin.struktur.destroy', $item)" :name="'jabatan '.$item->position" />
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty message="Belum ada struktur organisasi." :href="route('admin.struktur.create')" label="Tambah Jabatan" />
        </div>
    @endforelse
@endsection
