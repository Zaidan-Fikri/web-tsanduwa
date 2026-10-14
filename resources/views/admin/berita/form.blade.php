@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Berita' : 'Tulis Berita')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Berita' : 'Tulis Berita'" subtitle="Berita & pengumuman madrasah" icon="fa-newspaper">
        @if ($item->exists && $item->is_published)
            <a href="{{ route('berita.show', $item) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-eye"></i> Lihat Berita</a>
        @endif
    </x-admin.page-header>

    <x-admin.form-card :action="$item->exists ? route('admin.berita.update', $item) : route('admin.berita.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.berita.index')" :files="true" :submit="$item->exists ? 'Simpan Perubahan' : 'Simpan Berita'">
        <x-admin.input name="title" label="Judul Berita" :value="$item->title" required maxlength="200" />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.select name="category" label="Kategori" :options="collect(\App\Models\Post::CATEGORIES)->map(fn ($c) => $c[0])->all()" :value="$item->category" required />
            <x-admin.input name="published_at" label="Tanggal Terbit" type="date" :value="$item->published_at?->format('Y-m-d')" required help="Tanggal di masa depan = berita terjadwal." />
        </div>
        <x-admin.image name="image" label="Foto Utama" :value="$item->image" />
        <x-admin.textarea name="excerpt" label="Ringkasan" :value="$item->excerpt" rows="2" maxlength="300" help="Opsional. Tampil di kartu daftar berita. Jika kosong diambil dari awal isi berita." />
        <x-admin.textarea name="content" label="Isi Berita" :value="$item->content" rows="14" required help="Pisahkan paragraf dengan satu baris kosong (tekan Enter dua kali)." />
        <x-admin.toggle name="is_published" label="Terbitkan" :checked="$item->is_published" help="Matikan untuk menyimpan sebagai draf (tidak tampil di website)." />
    </x-admin.form-card>
@endsection
