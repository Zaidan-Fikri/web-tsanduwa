@extends('layouts.admin')

@section('title', 'Prestasi')

@section('content')
    <x-admin.page-header title="Prestasi" subtitle="Capaian siswa dan madrasah di berbagai ajang." icon="fa-trophy">
        <x-admin.add-button :href="route('admin.prestasi.create')" label="Tambah Prestasi" />
    </x-admin.page-header>

    @include('admin.partials.search', [
        'placeholder' => 'Cari nama lomba atau pemenang...',
        'filterName' => 'level',
        'filterAll' => 'Semua tingkat',
        'filterOptions' => \App\Models\Achievement::LEVELS,
    ])

    <div data-live-results>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Prestasi tidak ditemukan." :href="route('admin.prestasi.create')" label="Tambah Prestasi" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th>Prestasi</th><th>Peringkat</th><th>Tingkat</th><th>Tahun</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="w-11 h-11 shrink-0 rounded-full bg-gradient-to-br {{ $item->medalClass() }} flex items-center justify-center"><i class="fa-solid fa-trophy"></i></span>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->title }}
                                                @if ($item->is_featured)<span class="ml-1 text-[10px] font-bold uppercase bg-gold-500 text-slate-950 px-2 py-0.5 rounded-full"><i class="fa-solid fa-star"></i> Unggulan</span>@endif
                                            </p>
                                            <p class="text-xs text-slate-500"><i class="fa-solid fa-user text-tosca-600 mr-1"></i>{{ $item->winner }} · {{ $item->category }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-bold text-slate-700 whitespace-nowrap">{{ $item->rank }}</td>
                                <td><span class="text-[11px] font-bold uppercase text-tosca-700 bg-tosca-50 px-2.5 py-1 rounded-full">{{ $item->levelLabel() }}</span></td>
                                <td class="text-slate-600">{{ $item->year }}</td>
                                <td><x-admin.row-actions :edit="route('admin.prestasi.edit', $item)" :destroy="route('admin.prestasi.destroy', $item)" name="prestasi ini" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">{{ $items->links('admin.partials.pagination') }}</div>
    </div>
@endsection
