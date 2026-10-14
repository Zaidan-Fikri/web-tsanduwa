@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Jabatan' : 'Tambah Jabatan')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Jabatan' : 'Tambah Jabatan'" subtitle="Struktur organisasi" icon="fa-sitemap" />

    <x-admin.form-card :action="$item->exists ? route('admin.struktur.update', $item) : route('admin.struktur.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.struktur.index')">
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="position" label="Jabatan" :value="$item->position" required placeholder="Contoh: Waka Kurikulum" />
            <x-admin.input name="name" label="Nama Pejabat" :value="$item->name" required placeholder="Contoh: Yuli Asfiah, S.Pd" />
            <x-admin.select name="level" label="Tingkat" :options="[1 => 'Tingkat 1 — Pimpinan (paling atas)', 2 => 'Tingkat 2 — Wakil Kepala', 3 => 'Tingkat 3 — Staf / Koordinator', 4 => 'Tingkat 4', 5 => 'Tingkat 5']" :value="$item->level" required />
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$item->sort_order ?? 0" help="Urutan dari kiri ke kanan dalam satu tingkat." />
        </div>
    </x-admin.form-card>
@endsection
