@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Sambutan -->
    <section class="bg-pattern relative overflow-hidden rounded-3xl text-white p-6 sm:p-8 mb-6 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <img src="{{ asset('img/logo.png') }}" alt="" class="absolute -right-8 -bottom-10 w-56 opacity-10">
        <div class="relative">
            <span class="text-gold-400 font-bold text-xs tracking-widest uppercase">Panel Admin</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">Assalamu'alaikum, {{ auth()->user()->name }}!</h1>
            <p class="text-tosca-100 mt-2 max-w-2xl text-sm sm:text-base">Pilih menu di samping untuk mengubah isi website. Setiap perubahan langsung tampil di halaman publik.</p>
            @if (auth()->user()->last_login_at)
                <p class="text-xs text-tosca-200 mt-3"><i class="fa-regular fa-clock mr-1 text-gold-400"></i> Login terakhir: {{ auth()->user()->last_login_at->translatedFormat('l, d F Y H:i') }} WIB</p>
            @endif
        </div>
    </section>

    @if ($usesDefaultPassword)
        <div class="mb-6 p-5 rounded-2xl bg-amber-50 border border-gold-400/50 flex flex-col sm:flex-row sm:items-center gap-4">
            <span class="w-12 h-12 shrink-0 rounded-xl bg-gold-500 text-slate-900 flex items-center justify-center text-xl"><i class="fa-solid fa-triangle-exclamation"></i></span>
            <div class="flex-1">
                <p class="font-extrabold text-slate-900">Akun Anda masih memakai password bawaan</p>
                <p class="text-sm text-slate-600">Demi keamanan website, segera ganti password Anda.</p>
            </div>
            <a href="{{ route('admin.account.edit') }}#password" class="shrink-0 px-5 py-2.5 rounded-xl bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-bold transition text-center shadow-md hover:shadow-lg hover:-translate-y-0.5">Ganti Password</a>
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        @foreach ($stats as [$label, $count, $icon, $route])
            <a href="{{ route($route) }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:-translate-y-1 hover:shadow-lg transition group">
                <span class="w-11 h-11 rounded-xl bg-tosca-50 text-tosca-700 flex items-center justify-center text-lg group-hover:bg-tosca-700 group-hover:text-gold-400 transition"><i class="fa-solid {{ $icon }}"></i></span>
                <div class="text-2xl font-extrabold text-slate-900 mt-3">{{ $count }}</div>
                <div class="text-xs font-semibold text-slate-500">{{ $label }}</div>
            </a>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-5 gap-6">
        <!-- Aksi cepat -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-bolt text-gold-500"></i> Aksi Cepat</h2>
            <div class="grid grid-cols-2 gap-3 mt-4">
                @foreach ([
                    ['Tulis Berita', 'fa-pen-to-square', route('admin.berita.create')],
                    ['Tambah Prestasi', 'fa-trophy', route('admin.prestasi.create')],
                    ['Upload Foto', 'fa-camera', route('admin.galeri.create')],
                    ['Tambah Agenda', 'fa-calendar-plus', route('admin.agenda.create')],
                    ['Tambah Guru', 'fa-user-plus', route('admin.sdm.create')],
                    ['Info SPMB', 'fa-bullhorn', route('admin.settings.edit', 'spmb')],
                ] as [$label, $icon, $url])
                    <a href="{{ $url }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-slate-100 bg-slate-50 hover:bg-tosca-50 hover:border-tosca-200 hover:-translate-y-1 hover:shadow-lg text-center transition">
                        <i class="fa-solid {{ $icon }} text-tosca-700 text-lg"></i>
                        <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Berita terbaru -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-newspaper text-tosca-600"></i> Berita Terbaru</h2>
                <a href="{{ route('admin.berita.index') }}" class="text-xs font-bold text-tosca-700 hover:underline">Lihat semua &rarr;</a>
            </div>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($latestPosts as $post)
                    <a href="{{ route('admin.berita.edit', $post) }}" class="flex items-center gap-3 py-3 group">
                        <x-admin.thumb :src="$post->image" icon="fa-newspaper" size="w-14 h-11" />
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-slate-800 truncate group-hover:text-tosca-700">{{ $post->title }}</p>
                            <p class="text-xs text-slate-500">{{ $post->dateLabel() }} · {{ $post->categoryLabel() }}</p>
                        </div>
                        @unless ($post->is_published)
                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full bg-slate-100 text-slate-500">Draf</span>
                        @endunless
                    </a>
                @empty
                    <p class="py-6 text-sm text-slate-500 text-center">Belum ada berita.</p>
                @endforelse
            </div>
        </div>
    </div>

    @if ($upcoming->isNotEmpty())
        <div class="mt-6 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-extrabold text-slate-900 flex items-center gap-2 mb-4"><i class="fa-solid fa-calendar-days text-tosca-600"></i> Agenda Mendatang</h2>
            <div class="space-y-3">
                @foreach ($upcoming as $agenda)
                    @include('partials.agenda-item')
                @endforeach
            </div>
        </div>
    @endif
@endsection
