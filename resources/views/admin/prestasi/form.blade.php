@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Prestasi' : 'Tambah Prestasi')

@section('content')
    <x-admin.page-header :title="$item->exists ? 'Ubah Prestasi' : 'Tambah Prestasi'" subtitle="Prestasi siswa & madrasah" icon="fa-trophy" />

    <x-admin.form-card :action="$item->exists ? route('admin.prestasi.update', $item) : route('admin.prestasi.store')" :method="$item->exists ? 'PUT' : 'POST'" :cancel="route('admin.prestasi.index')" :files="true">
        <x-admin.input name="title" label="Nama Lomba / Kejuaraan" :value="$item->title" required placeholder="Contoh: Kompetisi Sains Madrasah (KSM) Matematika" />
        <div class="grid md:grid-cols-2 gap-5">
            <x-admin.input name="winner" label="Diraih Oleh" :value="$item->winner" required placeholder="Nama siswa / tim" />
            <x-admin.input name="rank" label="Peringkat" :value="$item->rank" required placeholder="Juara 1, Juara 2, Harapan 1, ..." list="rank-options" />
            <x-admin.select name="level" label="Tingkat" :options="\App\Models\Achievement::LEVELS" :value="$item->level" required />
            <x-admin.input name="category" label="Bidang" :value="$item->category" required placeholder="Akademik, Keagamaan, Olahraga, Seni, ..." list="category-options" />
            <x-admin.input name="year" label="Tahun" type="number" min="1980" :max="now()->year + 1" :value="$item->year" required />
            <x-admin.icon-picker name="icon" label="Ikon Bidang" :value="$item->icon" />
        </div>
        <x-admin.image name="image" label="Foto (untuk kartu di Beranda)" :value="$item->image" help="Opsional." />
        <div class="grid md:grid-cols-2 gap-4">
            <x-admin.toggle name="show_on_home" label="Tampilkan di Beranda" :checked="$item->show_on_home" help="Beranda menampilkan maksimal 3 prestasi terbaru." />
            <x-admin.toggle name="is_featured" label="Jadikan Prestasi Unggulan" :checked="$item->is_featured" help="Disorot di atas halaman Prestasi (hanya satu)." />
        </div>

        <datalist id="rank-options">
            @foreach (['Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1', 'Harapan 2', 'Harapan 3', 'Finalis', 'Medali Emas', 'Medali Perak', 'Medali Perunggu'] as $opt)
                <option value="{{ $opt }}">
            @endforeach
        </datalist>
        <datalist id="category-options">
            @foreach (['Akademik', 'Keagamaan', 'Olahraga', 'Seni', 'Bela Diri', 'Kepramukaan'] as $opt)
                <option value="{{ $opt }}">
            @endforeach
        </datalist>
    </x-admin.form-card>
@endsection
