@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Unit Kerja' : 'Tambah Unit Kerja')

@php($programs = old('programs', $item->programs ?? []))

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Unit Kerja' : 'Tambah Unit Kerja'" subtitle="Unit kerja Wakil Kepala Madrasah" icon="fa-briefcase" />

    <x-admin.form-card :action="$item->exists ? route('admin.unit-kerja.update', $item) : route('admin.unit-kerja.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.unit-kerja.index')" :files="true">
        <x-admin.image name="photo" label="Foto Waka" :value="$item->photo" help="Disarankan PNG latar transparan, potret (tegak)." />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="position" label="Nama Jabatan (tab)" :value="$item->position" required placeholder="Contoh: Waka Kurikulum" />
            <x-admin.input name="person_name" label="Nama Pejabat" :value="$item->person_name" required />
            <x-admin.input name="field_title" label="Judul Bidang" :value="$item->field_title" required placeholder="Contoh: Bidang Kurikulum & Pembelajaran" />
            <x-admin.icon-picker name="icon" label="Ikon Latar" :value="$item->icon" />
        </div>
        <x-admin.textarea name="description" label="Deskripsi Tugas" :value="$item->description" rows="3" />

        <!-- Program utama -->
        <div data-repeater class="rounded-2xl border border-slate-200 p-4 sm:p-5 bg-slate-50/50">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Program Utama</p>
                    <p class="text-xs text-slate-500">Ditampilkan sebagai kotak centang di halaman Unit Kerja.</p>
                </div>
                <button type="button" data-repeater-add class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold bg-tosca-700 text-white hover:bg-tosca-800 transition shadow-md hover:shadow-lg hover:-translate-y-0.5"><i class="fa-solid fa-plus"></i> Tambah</button>
            </div>
            <div data-repeater-list class="space-y-2">
                @foreach ($programs as $key => $program)
                    <div data-repeater-item class="flex items-center gap-2">
                        <span data-repeater-no class="w-7 h-7 shrink-0 rounded-lg bg-tosca-700 text-gold-400 text-xs font-bold flex items-center justify-center"></span>
                        <input type="text" name="programs[{{ $key }}]" value="{{ $program }}" maxlength="200" class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                        <button type="button" data-repeater-remove title="Hapus baris" class="w-9 h-9 shrink-0 rounded-lg text-rose-500 hover:bg-rose-50 transition"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endforeach
            </div>
            <p data-repeater-empty class="text-xs text-slate-400 italic">Belum ada program. Klik "Tambah".</p>
            <template>
                <div data-repeater-item class="flex items-center gap-2">
                    <span data-repeater-no class="w-7 h-7 shrink-0 rounded-lg bg-tosca-700 text-gold-400 text-xs font-bold flex items-center justify-center"></span>
                    <input type="text" name="programs[__KEY__]" maxlength="200" class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                    <button type="button" data-repeater-remove title="Hapus baris" class="w-9 h-9 shrink-0 rounded-lg text-rose-500 hover:bg-rose-50 transition"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </template>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="slug" label="Alamat Halaman (opsional)" :value="$item->slug" help="Contoh: kurikulum → /profil/unit-kerja/kurikulum. Kosongkan untuk dibuat otomatis." />
            <x-admin.input name="sort_order" label="Urutan Tab" type="number" min="0" :value="$item->sort_order ?? 0" />
        </div>
    </x-admin.form-card>
@endsection
