@extends('layouts.admin')

@section('title', 'Ekstrakurikuler')

@section('content')
    <x-admin.page-header title="Ekstrakurikuler" subtitle="Kegiatan pengembangan minat dan bakat siswa." icon="fa-person-running">
        <x-admin.add-button :href="route('admin.ekskul.create')" label="Tambah Ekskul" />
    </x-admin.page-header>

    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.ekskul.index') }}" class="ekskul-filter {{ ! request('category') ? 'active' : '' }}">Semua</a>
        @foreach (\App\Models\Extracurricular::CATEGORIES as $key => [$label, $icon])
            <a href="{{ route('admin.ekskul.index', ['category' => $key]) }}" class="ekskul-filter {{ request('category') === $key ? 'active' : '' }}"><i class="fa-solid {{ $icon }}"></i>{{ $label }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Belum ada ekstrakurikuler." :href="route('admin.ekskul.create')" label="Tambah Ekskul" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th>Ekstrakurikuler</th><th>Kategori</th><th>Jadwal</th><th>Beranda</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <x-admin.thumb :src="$item->image" :icon="$item->icon" size="w-12 h-12" />
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->name }}</p>
                                            <p class="text-xs text-slate-500 line-clamp-1 max-w-xs">{{ $item->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10.5px] font-bold uppercase tracking-wider text-tosca-700 bg-tosca-50 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $item->categoryLabel() }}</span></td>
                                <td class="text-xs text-slate-600 whitespace-nowrap">{{ $item->schedule ?: '-' }}</td>
                                <td>{!! $item->show_on_home ? '<i class="fa-solid fa-circle-check text-tosca-600"></i>' : '<span class="text-xs text-slate-400">-</span>' !!}</td>
                                <td><x-admin.row-actions :edit="route('admin.ekskul.edit', $item)" :destroy="route('admin.ekskul.destroy', $item)" :name="'ekskul '.$item->name" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
