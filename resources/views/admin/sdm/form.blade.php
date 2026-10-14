@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Data Guru/Tendik' : 'Tambah Guru/Tendik')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Data' : 'Tambah Guru / Tendik'" subtitle="Tenaga pendidik & kependidikan" icon="fa-chalkboard-user" />

    <x-admin.form-card :action="$item->exists ? route('admin.sdm.update', $item) : route('admin.sdm.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.sdm.index')" :files="true">
        <x-admin.image :max-size="800" name="photo" label="Foto" :value="$item->photo" help="Foto potret (tegak), perbandingan 3:4." />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="name" label="Nama Lengkap & Gelar" :value="$item->name" required />
            <x-admin.input name="position" label="Jabatan / Mata Pelajaran" :value="$item->position" required placeholder="Contoh: Guru Matematika" />
            <x-admin.select name="type" label="Jenis" :options="collect(\App\Models\Staff::TYPES)->map(fn ($t) => $t[0])->all()" :value="$item->type" required />
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" />
        </div>
        <x-admin.toggle name="show_on_home" label="Tampilkan di slider Beranda" :checked="$item->show_on_home" help="Berlaku untuk jenis Guru (slider Tenaga Pendidik) dan Tendik (slider Tenaga Kependidikan)." />
    </x-admin.form-card>
@endsection
