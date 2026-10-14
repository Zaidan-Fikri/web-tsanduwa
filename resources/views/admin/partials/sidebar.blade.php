@php
    // Menu admin mengikuti urutan menu navbar website.
    $menu = [
        ['Dashboard', null, [
            ['Dashboard', 'fa-gauge-high', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
        ]],
        ['Beranda', 'fa-house', [
            ['Slider Utama', 'fa-images', route('admin.slider.index'), request()->routeIs('admin.slider.*')],
            ['Agenda', 'fa-calendar-days', route('admin.agenda.index'), request()->routeIs('admin.agenda.*')],
            ['Fasilitas', 'fa-building', route('admin.fasilitas.index'), request()->routeIs('admin.fasilitas.*')],
            ['Statistik & Video', 'fa-chart-simple', route('admin.settings.edit', 'beranda'), request()->is(admin_path().'/pengaturan/beranda')],
        ]],
        ['Profil', 'fa-id-card', [
            ['Sambutan Kepala', 'fa-user-tie', route('admin.settings.edit', 'sambutan'), request()->is(admin_path().'/pengaturan/sambutan')],
            ['Identitas Sekolah', 'fa-id-card', route('admin.settings.edit', 'identitas'), request()->is(admin_path().'/pengaturan/identitas')],
            ['Visi & Misi', 'fa-bullseye', route('admin.misi.index'), request()->routeIs('admin.misi.*')],
            ['Sejarah', 'fa-landmark', route('admin.settings.edit', 'sejarah'), request()->is(admin_path().'/pengaturan/sejarah')],
            ['Struktur Organisasi', 'fa-sitemap', route('admin.struktur.index'), request()->routeIs('admin.struktur.*')],
            ['Unit Kerja', 'fa-briefcase', route('admin.unit-kerja.index'), request()->routeIs('admin.unit-kerja.*')],
            ['Guru & Tendik', 'fa-chalkboard-user', route('admin.sdm.index'), request()->routeIs('admin.sdm.*')],
        ]],
        ['Program Madrasah', 'fa-book-quran', [
            ['Program Madrasah', 'fa-book-quran', route('admin.program.index'), request()->routeIs('admin.program.*')],
        ]],
        ['Ekstrakurikuler', 'fa-person-running', [
            ['Ekstrakurikuler', 'fa-person-running', route('admin.ekskul.index'), request()->routeIs('admin.ekskul.*')],
        ]],
        ['Informasi', 'fa-newspaper', [
            ['Berita', 'fa-newspaper', route('admin.berita.index'), request()->routeIs('admin.berita.*')],
            ['Prestasi', 'fa-trophy', route('admin.prestasi.index'), request()->routeIs('admin.prestasi.*')],
            ['Galeri', 'fa-photo-film', route('admin.galeri.index'), request()->routeIs('admin.galeri.*')],
        ]],
        ['SPMB', 'fa-user-plus', [
            ['Info SPMB & Brosur', 'fa-bullhorn', route('admin.settings.edit', 'spmb'), request()->is(admin_path().'/pengaturan/spmb')],
            ['Prosedur Pendaftaran', 'fa-list-ol', route('admin.spmb-prosedur.index'), request()->routeIs('admin.spmb-prosedur.*')],
            ['Persyaratan', 'fa-folder-open', route('admin.spmb-persyaratan.index'), request()->routeIs('admin.spmb-persyaratan.*')],
        ]],
        ['Pengaturan', 'fa-gear', array_values(array_filter([
            ['Kontak & Media Sosial', 'fa-address-book', route('admin.settings.edit', 'kontak'), request()->is(admin_path().'/pengaturan/kontak')],
            auth()->user()->isSuperAdmin() ? ['Pengguna Admin', 'fa-users-gear', route('admin.pengguna.index'), request()->routeIs('admin.pengguna.*')] : null,
            ['Akun Saya', 'fa-user-shield', route('admin.account.edit'), request()->routeIs('admin.account.*')],
        ]))],
    ];
@endphp
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-tosca-950 text-slate-300 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-6 h-20 shrink-0 border-b border-tosca-900">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-11 h-11 object-contain shrink-0">
        <div class="leading-tight">
            <span class="block text-sm font-extrabold text-white tracking-tight">MTs. DARUL ULUM WARU</span>
            <span class="block text-[10px] font-bold text-gold-400 tracking-widest uppercase">Panel Admin</span>
        </div>
    </a>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 admin-scroll">
        @foreach ($menu as [$group, $groupIcon, $items])
            <div>
                @if ($groupIcon)
                    <p class="px-3 mb-1.5 text-[10.5px] font-bold uppercase tracking-widest text-tosca-300/60">{{ $group }}</p>
                @endif
                <div class="space-y-0.5">
                    @foreach ($items as [$label, $icon, $url, $active])
                        <a href="{{ $url }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $active ? 'bg-tosca-700 text-white shadow-lg shadow-tosca-950/50' : 'hover:bg-tosca-900 hover:text-gold-400' }}">
                            <i class="fa-solid {{ $icon }} w-5 text-center {{ $active ? 'text-gold-400' : 'text-tosca-400' }}"></i>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="p-4 border-t border-tosca-900 shrink-0">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-slate-900 text-sm font-bold transition shadow-md hover:shadow-lg hover:-translate-y-0.5">
            <i class="fa-solid fa-globe"></i> Lihat Website
        </a>
    </div>
</aside>
<div id="admin-sidebar-backdrop" onclick="toggleSidebar(false)" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden"></div>
