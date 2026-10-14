@extends('layouts.app')

@section('title', 'Prestasi')

@section('content')
    <!-- Banner -->
    <section class="bg-pattern relative overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <i class="fa-solid fa-trophy absolute right-6 -bottom-10 text-[220px] text-gold-400/10 hidden md:block"></i>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <span class="text-gold-400 font-bold text-sm tracking-widest uppercase">Kebanggaan Madrasah</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold mt-2">Prestasi Siswa & Madrasah</h1>
                <p class="text-tosca-100 mt-3 max-w-xl">Deretan capaian membanggakan siswa MTs. Darul Ulum Waru di bidang akademik, keagamaan, seni, dan olahraga.</p>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><div class="text-3xl font-extrabold text-gold-400">{{ $achievements->count() }}</div><div class="text-xs text-tosca-100 mt-1">Total Prestasi</div></div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><div class="text-3xl font-extrabold text-gold-400">{{ $firstPlaces }}</div><div class="text-xs text-tosca-100 mt-1">Juara 1</div></div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><div class="text-3xl font-extrabold text-gold-400">{{ $counts->count() }}</div><div class="text-xs text-tosca-100 mt-1">Tingkat Lomba</div></div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Sorotan -->
        @if ($featured)
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-50 via-white to-tosca-50 border border-gold-400/40 shadow-lg p-6 sm:p-8 mb-12 flex flex-col md:flex-row items-center gap-6">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-gold-400/15"></div>
                <span class="relative w-24 h-24 shrink-0 rounded-full bg-gradient-to-br from-amber-300 via-gold-400 to-gold-600 text-slate-950 flex items-center justify-center text-4xl shadow-xl ring-8 ring-gold-400/20"><i class="fa-solid fa-medal"></i></span>
                <div class="relative flex-1 text-center md:text-left">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider bg-gold-500 text-slate-950 px-3 py-1 rounded-full"><i class="fa-solid fa-star"></i> Prestasi Unggulan</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-3">{{ $featured->rank }} {{ $featured->title }} Tingkat {{ $featured->levelLabel() }}</h2>
                    <p class="text-slate-600 mt-1">Diraih oleh <strong class="text-tosca-800">{{ $featured->winner }}</strong> pada tahun {{ $featured->year }}, bukti kualitas pembinaan siswa di madrasah.</p>
                </div>
                @if ($olimpiade)
                    <a href="{{ route('program', $olimpiade) }}" class="relative shrink-0 bg-tosca-700 hover:bg-tosca-800 text-white font-bold text-sm px-5 py-3 rounded-xl shadow-md transition inline-flex items-center gap-2">Academic Squad <i class="fa-solid fa-arrow-right text-gold-400"></i></a>
                @endif
            </div>
        @endif

        <!-- Filter tingkat -->
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <button type="button" onclick="filterPrestasi('semua')" data-tk="semua" class="ekskul-filter prestasi-filter active">Semua <span>{{ $achievements->count() }}</span></button>
            @foreach (\App\Models\Achievement::LEVELS as $key => $label)
                @if ($counts->has($key))
                    <button type="button" onclick="filterPrestasi('{{ $key }}')" data-tk="{{ $key }}" class="ekskul-filter prestasi-filter">{{ $label }} <span>{{ $counts[$key] }}</span></button>
                @endif
            @endforeach
        </div>

        <!-- Daftar prestasi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($achievements as $achievement)
                <div class="prestasi-item group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 overflow-hidden flex flex-col" data-tk="{{ $achievement->level }}">
                    <div class="relative h-36 bg-gradient-to-br from-tosca-600 via-tosca-800 to-tosca-950 overflow-hidden flex items-center justify-center">
                        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:16px_16px] opacity-20"></div>
                        <i class="fa-solid {{ $achievement->icon }} absolute -left-3 -bottom-5 text-8xl text-white/10"></i>
                        <span class="absolute top-3 right-3 text-[10.5px] font-bold uppercase tracking-wider bg-white/15 text-white px-2.5 py-1 rounded-full ring-1 ring-white/20">{{ $achievement->levelLabel() }}</span>
                        <div class="relative flex flex-col items-center">
                            <span class="w-16 h-16 rounded-full bg-gradient-to-br {{ $achievement->medalClass() }} flex items-center justify-center text-2xl shadow-lg ring-4 ring-white/20 group-hover:scale-110 transition duration-300"><i class="fa-solid fa-trophy"></i></span>
                            <span class="mt-2 text-xs font-extrabold uppercase tracking-widest text-gold-400">{{ $achievement->rank }}</span>
                        </div>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <span class="self-start text-[10.5px] font-bold uppercase tracking-wider text-tosca-700 bg-tosca-50 px-2.5 py-1 rounded-full">{{ $achievement->category }}</span>
                        <h3 class="font-extrabold text-slate-900 mt-3 leading-snug flex-1">{{ $achievement->title }}</h3>
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-600">
                            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-user text-tosca-600"></i>{{ $achievement->winner }}</span>
                            <span class="inline-flex items-center gap-1.5"><i class="fa-regular fa-calendar text-gold-500"></i>{{ $achievement->year }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-slate-500">Belum ada data prestasi.</p>
            @endforelse
        </div>
    </div>
@endsection
