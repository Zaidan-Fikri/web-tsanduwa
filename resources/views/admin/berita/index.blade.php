@extends('layouts.admin')

@section('title', 'Berita')

@section('content')
    <x-admin.page-header title="Berita" subtitle="Kabar, pengumuman, dan kegiatan madrasah." icon="fa-newspaper">
        <x-admin.add-button :href="route('admin.berita.create')" label="Tulis Berita" />
    </x-admin.page-header>

    @include('admin.partials.search', [
        'placeholder' => 'Cari judul berita...',
        'filterName' => 'category',
        'filterAll' => 'Semua kategori',
        'filterOptions' => collect(\App\Models\Post::CATEGORIES)->map(fn ($c) => $c[0])->all(),
    ])

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($items->isEmpty())
            <x-admin.empty message="Berita tidak ditemukan." :href="route('admin.berita.create')" label="Tulis Berita" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <x-admin.thumb :src="$item->image" icon="fa-newspaper" />
                                        <p class="font-bold text-slate-800 max-w-sm">{{ $item->title }}</p>
                                    </div>
                                </td>
                                <td><span class="{{ $item->badgeClass() }} text-[11px] font-bold px-2.5 py-1 rounded-full">{{ $item->categoryLabel() }}</span></td>
                                <td class="text-xs text-slate-600 whitespace-nowrap">{{ $item->dateLabel() }}</td>
                                <td>
                                    @if (! $item->is_published)
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">Draf</span>
                                    @elseif ($item->published_at->isFuture())
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">Terjadwal</span>
                                    @else
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-tosca-100 text-tosca-800">Terbit</span>
                                    @endif
                                </td>
                                <td><x-admin.row-actions :edit="route('admin.berita.edit', $item)" :destroy="route('admin.berita.destroy', $item)" name="berita ini" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">{{ $items->links('admin.partials.pagination') }}</div>
@endsection
