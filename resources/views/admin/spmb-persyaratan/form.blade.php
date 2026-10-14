@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Persyaratan' : 'Tambah Persyaratan')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Persyaratan' : 'Tambah Persyaratan'" subtitle="Persyaratan pendaftaran SPMB" icon="fa-folder-open" />

    <x-admin.form-card :action="$item->exists ? route('admin.spmb-persyaratan.update', $item) : route('admin.spmb-persyaratan.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.spmb-persyaratan.index')">
        <x-admin.input name="name" label="Nama Berkas" :value="$item->name" required placeholder="Contoh: Fotokopi Kartu Keluarga" />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" />
            <x-admin.toggle name="is_required" label="Berkas wajib" :checked="$item->is_required" help="Matikan jika berkas hanya tambahan (bila ada)." class="self-end" />
        </div>
    </x-admin.form-card>
@endsection
