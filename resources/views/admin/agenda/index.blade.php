@extends('layouts.admin')

@section('title', 'Agenda')

@section('content')
    <x-admin.page-header title="Agenda" subtitle="Kegiatan sesuai kalender pendidikan, tampil di Beranda dan halaman Agenda." icon="fa-calendar-days">
        <x-admin.add-button :href="route('admin.agenda.create')" label="Tambah Agenda" />
    </x-admin.page-header>

    @include('admin.partials.search', ['placeholder' => 'Cari judul agenda...'])

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Belum ada agenda." :href="route('admin.agenda.create')" label="Tambah Agenda" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Kategori</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <span class="font-bold text-slate-800">{{ $item->start_date->translatedFormat('d M Y') }}</span>
                                    @if ($item->end_date && ! $item->end_date->isSameDay($item->start_date))
                                        <span class="block text-xs text-slate-500">s.d. {{ $item->end_date->translatedFormat('d M Y') }}</span>
                                    @endif
                                </td>
                                <td class="font-semibold text-slate-800">{{ $item->title }}</td>
                                <td><span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $item->badgeClass() }}">{{ $item->categoryLabel() }}</span></td>
                                <td><x-admin.row-actions :edit="route('admin.agenda.edit', $item)" :destroy="route('admin.agenda.destroy', $item)" name="agenda ini" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">{{ $items->links('admin.partials.pagination') }}</div>
@endsection
