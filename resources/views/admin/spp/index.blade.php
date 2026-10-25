@extends('layouts.admin')

@section('title', 'Tagihan Pembayaran Sekolah')

@section('content')
    <x-admin.page-header title="Tagihan Pembayaran Sekolah" subtitle="Data tagihan siswa yang bisa dicek wali murid di menu Informasi → Pembayaran." icon="fa-file-invoice-dollar">
        <a href="{{ route('pembayaran') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-eye"></i> Lihat Halaman</a>
        <a href="{{ route('admin.spp.codes', request('kelas') ? ['kelas' => request('kelas')] : []) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-print"></i> Cetak Kode Akses</a>
        @if (auth()->user()->canAccess('spp-import'))
        <a href="{{ route('admin.spp.import') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-gold-500 hover:bg-gold-600 text-slate-900 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition"><i class="fa-solid fa-file-arrow-up"></i> Upload Excel</a>
        @endif
    </x-admin.page-header>

    @if ($lastImport)
        <p class="-mt-3 mb-5 text-xs text-slate-500">
            <i class="fa-solid fa-clock-rotate-left text-tosca-600 mr-1"></i>
            Update terakhir: <b class="text-slate-700">{{ $lastImport->created_at->translatedFormat('d M Y H:i') }}</b>
            @if ($lastImport->user) oleh {{ $lastImport->user->name }} @endif
        </p>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
        @foreach ([
            ['Siswa terdata', number_format($stats['students'], 0, ',', '.'), 'fa-users', 'text-tosca-700 bg-tosca-50'],
            ['Masih ada tagihan', number_format($stats['with_bill'], 0, ',', '.'), 'fa-hourglass-half', 'text-rose-700 bg-rose-50'],
            ['Total tagihan', rupiah($stats['total']), 'fa-money-bill-wave', 'text-gold-600 bg-amber-50'],
            ['Total tagihan PPDB', rupiah($stats['ppdb']), 'fa-user-plus', 'text-sky-700 bg-sky-50'],
            ['Total tagihan SPP', rupiah($stats['spp']), 'fa-calendar-check', 'text-emerald-700 bg-emerald-50'],
        ] as [$label, $value, $icon, $color])
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <span class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center"><i class="fa-solid {{ $icon }}"></i></span>
                <div class="text-lg font-extrabold text-slate-900 mt-3 leading-tight">{{ $value }}</div>
                <div class="text-xs font-semibold text-slate-500">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    @if ($stats['students'] === 0)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <x-admin.empty message="Belum ada data tagihan. Upload file Excel tagihan untuk memulai." :href="auth()->user()->canAccess('spp-import') ? route('admin.spp.import') : null" label="Upload Excel" />
        </div>
    @else
        <!-- Filter -->
        <form method="GET" action="{{ route('admin.spp.index') }}" class="mb-4 flex flex-col lg:flex-row gap-2" data-live-search>
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Ketik nama siswa atau NISN..." maxlength="100" autocomplete="off"
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
            </div>
            <select name="kelas" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                <option value="">Semua kelas</option>
                @foreach ($classes as $class)
                    <option value="{{ $class }}" @selected(request('kelas') === $class)>Kelas {{ $class }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                <option value="">Semua status</option>
                <option value="tagihan" @selected(request('status') === 'tagihan')>Masih ada tagihan</option>
                <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
            </select>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-bold transition shadow-md hover:shadow-lg hover:-translate-y-0.5">Cari</button>
            <a href="{{ route('admin.spp.index') }}" data-live-reset class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 text-center transition {{ request('q') || request('kelas') || request('status') ? '' : 'hidden' }}">Reset</a>
        </form>

        <div data-live-results>

        @if (request('kelas'))
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-2xl bg-tosca-50 border border-tosca-100">
                <p class="text-sm text-tosca-900">Kelas <b>{{ request('kelas') }}</b>: {{ $items->total() }} siswa · total <b>{{ rupiah($stats['filtered_total']) }}</b></p>
                <form action="{{ route('admin.spp.destroy-class', request('kelas')) }}" method="POST" data-confirm="Hapus SEMUA data tagihan kelas {{ request('kelas') }}?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 transition"><i class="fa-solid fa-trash-can mr-1"></i> Hapus data kelas ini</button>
                </form>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            @if ($items->isEmpty())
                <x-admin.empty message="Data tidak ditemukan." />
            @else
                <div class="overflow-x-auto">
                    <table class="admin-table w-full">
                        <thead><tr><th>Siswa</th><th>Kelas</th><th>Kode Akses</th><th>Rincian</th><th class="text-right">Total</th><th>Data Per</th><th class="text-right">Aksi</th></tr></thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <p class="font-bold text-slate-800">{{ $item->name }}</p>
                                        <p class="text-xs text-slate-500 font-mono">{{ $item->nisn }}</p>
                                    </td>
                                    <td><span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-tosca-50 text-tosca-700">{{ $item->class_name }}</span></td>
                                    <td><span class="font-mono text-xs font-bold tracking-wider text-slate-700 bg-amber-50 border border-gold-400/40 px-2 py-1 rounded-lg whitespace-nowrap">{{ $item->accessCodeLabel() }}</span></td>
                                    <td class="text-xs text-slate-600">
                                        @forelse ($item->outstandingItems() as $part)
                                            <span class="block whitespace-nowrap">{{ $part['label'] }}@if (! empty($part['months'])) ({{ $part['months'] }} bln)@endif: <b>{{ number_format($part['amount'], 0, ',', '.') }}</b></span>
                                        @empty
                                            <span class="text-slate-400">—</span>
                                        @endforelse
                                    </td>
                                    <td class="text-right whitespace-nowrap">
                                        @if ($item->isPaidOff())
                                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700">Lunas</span>
                                        @else
                                            <span class="font-extrabold text-tosca-800">{{ rupiah($item->total) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-xs text-slate-500 whitespace-nowrap">{{ $item->billing_date?->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td>
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.spp.edit', $item) }}" title="Edit / catat pembayaran" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-tosca-700 bg-tosca-50 hover:bg-tosca-100 transition whitespace-nowrap">
                                                <i class="fa-solid fa-pen-to-square"></i><span class="hidden md:inline">Edit</span>
                                            </a>
                                            <form action="{{ route('admin.spp.destroy', $item) }}" method="POST" data-confirm="Hapus data tagihan {{ $item->name }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-4">{{ $items->links('admin.partials.pagination') }}</div>
        </div>
    @endif
@endsection
