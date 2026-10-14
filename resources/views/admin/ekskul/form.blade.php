@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Ekstrakurikuler' : 'Tambah Ekstrakurikuler')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Ekstrakurikuler' : 'Tambah Ekstrakurikuler'" subtitle="Ekstrakurikuler" icon="fa-person-running" />

    <x-admin.form-card :action="$item->exists ? route('admin.ekskul.update', $item) : route('admin.ekskul.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.ekskul.index')" :files="true">
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="name" label="Nama Ekstrakurikuler" :value="$item->name" required />
            <x-admin.select name="category" label="Kategori" :options="collect(\App\Models\Extracurricular::CATEGORIES)->map(fn ($c) => $c[0])->all()" :value="$item->category" required />
            <x-admin.icon-picker name="icon" label="Ikon" :value="$item->icon" />
            <x-admin.input name="schedule" label="Jadwal" :value="$item->schedule" placeholder="Contoh: Jumat, 13.00 WIB" />
        </div>
        <x-admin.textarea name="description" label="Deskripsi Singkat" :value="$item->description" rows="3" maxlength="500" />
        <x-admin.image name="image" label="Foto (untuk slider Beranda)" :value="$item->image" help="Opsional. Jika kosong, kartu di Beranda memakai ikon." />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" />
            <x-admin.toggle name="show_on_home" label="Tampilkan di slider Beranda" :checked="$item->show_on_home" class="self-end" />
        </div>
    </x-admin.form-card>
@endsection
