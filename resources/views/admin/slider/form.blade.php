@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Slide' : 'Tambah Slide')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Slide' : 'Tambah Slide'" subtitle="Slider Utama Beranda" icon="fa-images" />

    <x-admin.form-card :action="$item->exists ? route('admin.slider.update', $item) : route('admin.slider.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.slider.index')" :files="true">
        <x-admin.image name="image" label="Gambar Slide" :value="$item->image" :required="! $item->exists" :removable="false" help="Gunakan foto landscape (mendatar), minimal lebar 1600 piksel." />
        <x-admin.input name="title" label="Judul" :value="$item->title" required maxlength="200" />
        <x-admin.textarea name="description" label="Keterangan Singkat" :value="$item->description" rows="3" maxlength="500" />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="button_text" label="Teks Tombol" :value="$item->button_text" placeholder="Selengkapnya" />
            <x-admin.input name="button_url" label="Link Tombol" :value="$item->button_url" placeholder="/spmb atau https://..." help="Kosongkan jika tidak perlu tombol. Contoh: /spmb, /berita, /profil/identitas" />
        </div>
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" help="Angka kecil tampil lebih dulu." />
            <x-admin.toggle name="is_active" label="Tampilkan di website" :checked="$item->is_active" class="self-end" />
        </div>
    </x-admin.form-card>
@endsection
