@extends('layouts.admin')

@section('title', 'Persyaratan Pendaftaran')

@section('content')
    <x-admin.page-header title="Persyaratan Pendaftaran" subtitle="Daftar berkas yang dicentang calon siswa di halaman SPMB." icon="fa-folder-open">
        <x-admin.add-button :href="route('admin.spmb-persyaratan.create')" label="Tambah Persyaratan" />
    </x-admin.page-header>

    @forelse ($items->groupBy(fn ($i) => $i->is_required ? 'Wajib' : 'Tambahan (bila ada)') as $group => $rows)
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">{{ $group }}</h2>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-100">
                @foreach ($rows as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4">
                        <span class="w-7 h-7 shrink-0 rounded-lg {{ $item->is_required ? 'bg-tosca-600 text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-xs"><i class="fa-solid fa-check"></i></span>
                        <p class="flex-1 font-medium text-slate-700">{{ $item->name }}</p>
                        <x-admin.row-actions :edit="route('admin.spmb-persyaratan.edit', $item)" :destroy="route('admin.spmb-persyaratan.destroy', $item)" name="persyaratan ini" />
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty message="Belum ada persyaratan." :href="route('admin.spmb-persyaratan.create')" label="Tambah Persyaratan" />
        </div>
    @endforelse
@endsection
