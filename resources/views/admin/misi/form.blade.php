@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Misi' : 'Tambah Misi')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Misi' : 'Tambah Misi'" subtitle="Misi madrasah" icon="fa-bullseye" />

    <x-admin.form-card :action="$item->exists ? route('admin.misi.update', $item) : route('admin.misi.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.misi.index')">
        <x-admin.textarea name="content" label="Isi Misi" :value="$item->content" rows="3" required maxlength="1000" />
        <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" class="md:w-1/3" />
    </x-admin.form-card>
@endsection
