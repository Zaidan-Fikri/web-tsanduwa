@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Agenda' : 'Tambah Agenda')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Agenda' : 'Tambah Agenda'" subtitle="Agenda kegiatan madrasah" icon="fa-calendar-days" />

    <x-admin.form-card :action="$item->exists ? route('admin.agenda.update', $item) : route('admin.agenda.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.agenda.index')">
        <x-admin.input name="title" label="Nama Kegiatan" :value="$item->title" required maxlength="200" placeholder="Contoh: Penilaian Akhir Semester Ganjil" />
        <div class="grid md:grid-cols-3 gap-5">
            <x-admin.input name="start_date" label="Tanggal Mulai" type="date" :value="$item->start_date?->format('Y-m-d')" required />
            <x-admin.input name="end_date" label="Tanggal Selesai" type="date" :value="$item->end_date?->format('Y-m-d')" help="Kosongkan jika hanya satu hari." />
            <x-admin.select name="category" label="Kategori" :options="collect(\App\Models\Agenda::CATEGORIES)->map(fn ($c) => $c[0])->all()" :value="$item->category" required />
        </div>
    </x-admin.form-card>
@endsection
