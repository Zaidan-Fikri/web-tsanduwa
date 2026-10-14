@extends('layouts.app')

@section('title', 'Ekstrakurikuler')

@section('content')
    <!-- Banner -->
    <section class="bg-pattern relative overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <span class="text-gold-400 font-bold text-sm tracking-widest uppercase">Pengembangan Minat & Bakat</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold mt-2">Ekstrakurikuler</h1>
                <p class="text-tosca-100 mt-3 max-w-xl">Wadah bagi siswa MTs. Darul Ulum Waru untuk menggali potensi, berprestasi, dan membentuk karakter di luar jam pelajaran.</p>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><div class="text-3xl font-extrabold text-gold-400">{{ $ekskuls->count() }}</div><div class="text-xs text-tosca-100 mt-1">Ekstrakurikuler</div></div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><div class="text-3xl font-extrabold text-gold-400">{{ $counts->count() }}</div><div class="text-xs text-tosca-100 mt-1">Kategori</div></div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><div class="text-3xl font-extrabold text-gold-400"><i class="fa-solid fa-trophy text-2xl"></i></div><div class="text-xs text-tosca-100 mt-1">Siap Berprestasi</div></div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filter kategori -->
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <button type="button" onclick="filterEkskul('semua')" data-cat="semua" class="ekskul-filter active">Semua <span>{{ $ekskuls->count() }}</span></button>
            @foreach (\App\Models\Extracurricular::CATEGORIES as $key => [$label, $icon])
                @if ($counts->has($key))
                    <button type="button" onclick="filterEkskul('{{ $key }}')" data-cat="{{ $key }}" class="ekskul-filter"><i class="fa-solid {{ $icon }}"></i>{{ $label }} <span>{{ $counts[$key] }}</span></button>
                @endif
            @endforeach
        </div>

        <!-- Daftar ekskul -->
        <div id="ekskul-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach ($ekskuls as $ekskul)
                <div class="ekskul-item group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 overflow-hidden flex flex-col" data-cat="{{ $ekskul->category }}">
                    <div class="relative h-32 bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-900 flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:16px_16px] opacity-25"></div>
                        <i class="fa-solid {{ $ekskul->icon }} absolute -right-3 -bottom-4 text-8xl text-white/10"></i>
                        <span class="absolute top-3 left-3 text-[11px] font-extrabold text-white/70">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="relative w-16 h-16 rounded-2xl bg-white/15 backdrop-blur ring-1 ring-white/30 text-gold-400 flex items-center justify-center text-3xl group-hover:scale-110 group-hover:bg-gold-500 group-hover:text-slate-950 transition duration-300"><i class="fa-solid {{ $ekskul->icon }}"></i></span>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <span class="self-start text-[10.5px] font-bold uppercase tracking-wider text-tosca-700 bg-tosca-50 px-2.5 py-1 rounded-full">{{ $ekskul->categoryLabel() }}</span>
                        <h3 class="text-lg font-extrabold text-slate-900 mt-3">{{ $ekskul->name }}</h3>
                        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed flex-1">{{ $ekskul->description }}</p>
                        @if ($ekskul->schedule)
                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-semibold text-slate-600"><i class="fa-regular fa-clock text-gold-500"></i>{{ $ekskul->schedule }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Ajakan -->
        <div class="mt-12 rounded-3xl bg-white border border-slate-100 shadow-md p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <span class="w-14 h-14 shrink-0 rounded-2xl bg-tosca-50 text-tosca-700 flex items-center justify-center text-2xl"><i class="fa-solid fa-circle-question"></i></span>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg">Ingin bergabung dengan ekstrakurikuler?</h3>
                    <p class="text-sm text-slate-500">Hubungi pembina atau bagian kesiswaan untuk informasi pendaftaran.</p>
                </div>
            </div>
            <a href="{{ route('profil.unitkerja', 'kesiswaan') }}" class="shrink-0 bg-tosca-700 hover:bg-tosca-800 text-white font-semibold px-6 py-3 rounded-xl transition inline-flex items-center gap-2">Waka Kesiswaan <i class="fa-solid fa-arrow-right text-gold-400"></i></a>
        </div>
    </div>
@endsection
