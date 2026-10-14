@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Program' : 'Tambah Program')

@php
    $activities = old('activities', $item->activities ?? []);
    $infos = old('infos', $item->infos ?? []);
    $inputClass = 'w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none';
@endphp

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Program' : 'Tambah Program'" subtitle="Program Madrasah" icon="fa-book-quran" />

    <x-admin.form-card :action="$item->exists ? route('admin.program.update', $item) : route('admin.program.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.program.index')">
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="menu_name" label="Nama di Menu" :value="$item->menu_name" required placeholder="Contoh: BTQ & Tahfidz" />
            <x-admin.input name="title" label="Judul Lengkap" :value="$item->title" required placeholder="Contoh: Baca Tulis Al-Qur'an & Tahfidz" />
            <x-admin.icon-picker name="icon" label="Ikon Program" :value="$item->icon" />
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" />
        </div>
        <x-admin.textarea name="description" label="Deskripsi" :value="$item->description" rows="3" />

        <!-- Kegiatan -->
        <div data-repeater class="rounded-2xl border border-slate-200 p-4 sm:p-5 bg-slate-50/50">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Kegiatan</p>
                    <p class="text-xs text-slate-500">Kartu kegiatan (ikon, judul, keterangan).</p>
                </div>
                <button type="button" data-repeater-add class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold bg-tosca-700 text-white hover:bg-tosca-800 transition shadow-md hover:shadow-lg hover:-translate-y-0.5"><i class="fa-solid fa-plus"></i> Tambah Kegiatan</button>
            </div>
            <div data-repeater-list class="space-y-3">
                @foreach ($activities as $key => $row)
                    <div data-repeater-item class="bg-white rounded-xl border border-slate-200 p-3 grid sm:grid-cols-12 gap-3 items-start">
                        <x-admin.icon-picker :name="'activities['.$key.'][icon]'" :value="$row['icon'] ?? 'fa-star'" compact class="sm:col-span-4" />
                        <input type="text" name="activities[{{ $key }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="Judul kegiatan" maxlength="100" class="{{ $inputClass }} sm:col-span-7">
                        <button type="button" data-repeater-remove title="Hapus" class="sm:col-span-1 h-11 rounded-lg text-rose-500 hover:bg-rose-50 transition"><i class="fa-solid fa-trash-can"></i></button>
                        <textarea name="activities[{{ $key }}][description]" rows="2" placeholder="Keterangan singkat" maxlength="300" class="{{ $inputClass }} sm:col-span-12">{{ $row['description'] ?? '' }}</textarea>
                    </div>
                @endforeach
            </div>
            <p data-repeater-empty class="text-xs text-slate-400 italic">Belum ada kegiatan.</p>
            <template>
                <div data-repeater-item class="bg-white rounded-xl border border-slate-200 p-3 grid sm:grid-cols-12 gap-3 items-start">
                    <x-admin.icon-picker name="activities[__KEY__][icon]" value="fa-star" compact class="sm:col-span-4" />
                    <input type="text" name="activities[__KEY__][title]" placeholder="Judul kegiatan" maxlength="100" class="{{ $inputClass }} sm:col-span-7">
                    <button type="button" data-repeater-remove title="Hapus" class="sm:col-span-1 h-11 rounded-lg text-rose-500 hover:bg-rose-50 transition"><i class="fa-solid fa-trash-can"></i></button>
                    <textarea name="activities[__KEY__][description]" rows="2" placeholder="Keterangan singkat" maxlength="300" class="{{ $inputClass }} sm:col-span-12"></textarea>
                </div>
            </template>
        </div>

        <!-- Informasi -->
        <div data-repeater class="rounded-2xl border border-slate-200 p-4 sm:p-5 bg-slate-50/50">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Informasi</p>
                    <p class="text-xs text-slate-500">Label kecil seperti jadwal, peserta, atau tempat.</p>
                </div>
                <button type="button" data-repeater-add class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold bg-tosca-700 text-white hover:bg-tosca-800 transition shadow-md hover:shadow-lg hover:-translate-y-0.5"><i class="fa-solid fa-plus"></i> Tambah Informasi</button>
            </div>
            <div data-repeater-list class="space-y-3">
                @foreach ($infos as $key => $row)
                    <div data-repeater-item class="bg-white rounded-xl border border-slate-200 p-3 grid sm:grid-cols-12 gap-3 items-start">
                        <x-admin.icon-picker :name="'infos['.$key.'][icon]'" :value="$row['icon'] ?? 'fa-circle-info'" compact class="sm:col-span-4" />
                        <input type="text" name="infos[{{ $key }}][text]" value="{{ $row['text'] ?? '' }}" placeholder="Contoh: Setiap hari, 06.45 – 07.15 WIB" maxlength="150" class="{{ $inputClass }} sm:col-span-7">
                        <button type="button" data-repeater-remove title="Hapus" class="sm:col-span-1 h-11 rounded-lg text-rose-500 hover:bg-rose-50 transition"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                @endforeach
            </div>
            <p data-repeater-empty class="text-xs text-slate-400 italic">Belum ada informasi.</p>
            <template>
                <div data-repeater-item class="bg-white rounded-xl border border-slate-200 p-3 grid sm:grid-cols-12 gap-3 items-start">
                    <x-admin.icon-picker name="infos[__KEY__][icon]" value="fa-calendar-days" compact class="sm:col-span-4" />
                    <input type="text" name="infos[__KEY__][text]" placeholder="Contoh: Setiap hari, 06.45 – 07.15 WIB" maxlength="150" class="{{ $inputClass }} sm:col-span-7">
                    <button type="button" data-repeater-remove title="Hapus" class="sm:col-span-1 h-11 rounded-lg text-rose-500 hover:bg-rose-50 transition"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            </template>
        </div>

        <x-admin.input name="slug" label="Alamat Halaman (opsional)" :value="$item->slug" class="md:w-1/2" help="Contoh: btq → /program-madrasah/btq. Kosongkan untuk dibuat otomatis." />
    </x-admin.form-card>
@endsection
