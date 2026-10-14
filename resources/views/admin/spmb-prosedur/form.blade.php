@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Langkah' : 'Tambah Langkah')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Langkah' : 'Tambah Langkah'" subtitle="Prosedur pendaftaran SPMB" icon="fa-list-ol" />

    <x-admin.form-card :action="$item->exists ? route('admin.spmb-prosedur.update', $item) : route('admin.spmb-prosedur.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.spmb-prosedur.index')">
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="title" label="Judul Langkah" :value="$item->title" required placeholder="Contoh: Pembelian Formulir" />
            <x-admin.icon-picker name="icon" label="Ikon" :value="$item->icon" />
        </div>
        <x-admin.textarea name="description" label="Keterangan" :value="$item->description" rows="3" maxlength="500" />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="badge" label="Label Jam (opsional)" :value="$item->badge" placeholder="Contoh: 07.00 – 12.00 WIB" />
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" />
        </div>
        <x-admin.toggle name="is_highlight" label="Warna emas (sorot)" :checked="$item->is_highlight" help="Biasanya dipakai untuk langkah terakhir." />
    </x-admin.form-card>
@endsection
