@extends('layouts.admin')

@section('title', 'Visi & Misi')

@section('content')
    <x-admin.page-header title="Visi & Misi" subtitle="Arah dan tujuan madrasah." icon="fa-bullseye">
        <a href="{{ route('profil.visimisi') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-eye"></i> Lihat Halaman</a>
    </x-admin.page-header>

    <!-- Visi -->
    <h2 class="font-extrabold text-slate-900 mb-3 flex items-center gap-2"><i class="fa-solid fa-eye text-tosca-600"></i> Visi Utama</h2>
    <x-admin.form-card :action="route('admin.settings.update', 'visi')" method="PUT" submit="Simpan Visi">
        <x-admin.textarea name="vision" label="Visi" :value="setting('vision')" rows="3" required />
    </x-admin.form-card>

    <!-- Misi -->
    <div class="flex items-center justify-between gap-4 mt-8 mb-3">
        <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-list-ol text-tosca-600"></i> Daftar Misi</h2>
        <x-admin.add-button :href="route('admin.misi.create')" label="Tambah Misi" />
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Belum ada misi." :href="route('admin.misi.create')" label="Tambah Misi" />
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($items as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4">
                        <span class="w-8 h-8 rounded-full bg-tosca-700 text-white flex items-center justify-center font-bold text-sm shrink-0">{{ $loop->iteration }}</span>
                        <p class="flex-1 text-sm text-slate-700">{{ $item->content }}</p>
                        <x-admin.row-actions :edit="route('admin.misi.edit', $item)" :destroy="route('admin.misi.destroy', $item)" name="misi ini" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
