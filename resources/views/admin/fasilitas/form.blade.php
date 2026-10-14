@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Fasilitas' : 'Tambah Fasilitas')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Fasilitas' : 'Tambah Fasilitas'" subtitle="Fasilitas madrasah" icon="fa-building" />

    <x-admin.form-card :action="$item->exists ? route('admin.fasilitas.update', $item) : route('admin.fasilitas.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.fasilitas.index')" :files="true">
        <x-admin.image :max-size="1280" name="image" label="Foto Fasilitas" :value="$item->image" :required="! $item->exists" :removable="false" />
        <x-admin.input name="name" label="Nama Fasilitas" :value="$item->name" required maxlength="150" placeholder="Contoh: Laboratorium Komputer" />
        <x-admin.textarea name="description" label="Keterangan Singkat" :value="$item->description" rows="3" maxlength="500" help="Opsional." />
        <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" class="md:w-1/3" help="Angka kecil tampil lebih dulu." />
    </x-admin.form-card>
@endsection
