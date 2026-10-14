@extends('layouts.admin')

@section('title', 'Guru & Tendik')

@section('content')
    <x-admin.page-header title="Tenaga Pendidik & Kependidikan" subtitle="Data pimpinan, guru, dan tenaga kependidikan." icon="fa-chalkboard-user">
        <x-admin.add-button :href="route('admin.sdm.create')" label="Tambah Data" />
    </x-admin.page-header>

    @include('admin.partials.search', [
        'placeholder' => 'Cari nama atau jabatan...',
        'filterName' => 'type',
        'filterAll' => 'Semua jenis',
        'filterOptions' => collect(\App\Models\Staff::TYPES)->map(fn ($t) => $t[0])->all(),
    ])

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Data tidak ditemukan." :href="route('admin.sdm.create')" label="Tambah Data" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th>Nama</th><th>Jenis</th><th>Tampil di Beranda</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <x-admin.thumb :src="$item->photo" icon="fa-user" size="w-11 h-14" />
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $item->position }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full {{ $item->badgeClass() }}">{{ $item->typeLabel() }}</span></td>
                                <td>
                                    @if ($item->show_on_home)
                                        <i class="fa-solid fa-circle-check text-tosca-600"></i> <span class="text-xs text-slate-600">Ya</span>
                                    @else
                                        <span class="text-xs text-slate-400">Tidak</span>
                                    @endif
                                </td>
                                <td><x-admin.row-actions :edit="route('admin.sdm.edit', $item)" :destroy="route('admin.sdm.destroy', $item)" :name="$item->name" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">{{ $items->links('admin.partials.pagination') }}</div>
@endsection
