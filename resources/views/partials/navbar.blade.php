@php
    $navBtn = 'nav-btn px-2.5 2xl:px-3.5 py-2 rounded-lg hover:text-tosca-700 hover:bg-tosca-50 transition font-semibold';
    $navActive = ' text-tosca-700 bg-tosca-50';
    $dropItem = 'flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-tosca-50 hover:text-tosca-700';

    $profilMenu = [
        ['profil.sambutan', [], 'fa-user-tie', 'Sambutan Kepala Madrasah'],
        ['profil.identitas', [], 'fa-id-card', 'Profil & Identitas Sekolah'],
        ['profil.visimisi', [], 'fa-bullseye', 'Visi & Misi'],
        ['profil.sejarah', [], 'fa-landmark', 'Sejarah Madrasah'],
        ['profil.struktur', [], 'fa-sitemap', 'Struktur Organisasi'],
        ['profil.unitkerja', $navUnit ? [$navUnit] : [], 'fa-briefcase', 'Unit Kerja'],
        ['profil.sdm', [], 'fa-chalkboard-user', 'Tenaga Pendidik & Kependidikan'],
    ];
    $infoMenu = [
        ['berita', 'fa-newspaper', 'Berita'],
        ['prestasi', 'fa-trophy', 'Prestasi'],
        ['galeri', 'fa-images', 'Galeri'],
    ];
@endphp
    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-md border-b border-slate-100 transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-8 2xl:px-10">
            <div class="flex justify-between items-center h-20 gap-10">
                <!-- Logo & School Name -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo {{ setting('school_name', 'MTs. Darul Ulum Waru') }}" class="w-14 h-14 object-contain shrink-0 drop-shadow-md group-hover:scale-105 transition">
                    <div>
                        <span class="text-xl xl:text-lg 2xl:text-xl font-extrabold text-tosca-900 tracking-tight block whitespace-nowrap">MTs. DARUL ULUM WARU</span>
                        <span class="text-xs font-semibold text-gold-600 tracking-wider uppercase block">Beriman • Berilmu • Berprestasi</span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden xl:flex items-center gap-1 2xl:gap-1.5 font-medium text-slate-700 text-sm whitespace-nowrap">
                    <a href="{{ route('home') }}" class="{{ $navBtn }}{{ request()->routeIs('home') ? $navActive : '' }}">Beranda</a>

                    <!-- Dropdown Profil -->
                    <div class="relative group">
                        <button type="button" class="{{ $navBtn }} flex items-center space-x-1{{ request()->routeIs('profil.*') ? $navActive : '' }}">
                            <span>Profil</span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-focus-within:opacity-100 group-focus-within:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="py-2">
                                @foreach ($profilMenu as [$route, $params, $icon, $label])
                                    <a href="{{ route($route, $params) }}" class="{{ $dropItem }}"><i class="fa-solid {{ $icon }} w-4 text-tosca-600"></i>{{ $label }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Program Madrasah -->
                    <div class="relative group">
                        <button type="button" class="{{ $navBtn }} flex items-center space-x-1{{ request()->routeIs('program') ? $navActive : '' }}">
                            <span>Program Madrasah</span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-focus-within:opacity-100 group-focus-within:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="py-2">
                                @foreach ($navPrograms as $navProgram)
                                    <a href="{{ route('program', $navProgram) }}" class="{{ $dropItem }}"><i class="fa-solid {{ $navProgram->icon }} w-4 text-tosca-600"></i>{{ $navProgram->menu_name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('ekskul') }}" class="{{ $navBtn }}{{ request()->routeIs('ekskul') ? $navActive : '' }}">Ekstrakurikuler</a>
                    <!-- Dropdown Informasi -->
                    <div class="relative group">
                        <button type="button" class="{{ $navBtn }} flex items-center space-x-1{{ request()->routeIs('berita*', 'prestasi', 'galeri', 'agenda') ? $navActive : '' }}">
                            <span>Informasi</span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-focus-within:opacity-100 group-focus-within:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="py-2">
                                @foreach ($infoMenu as [$route, $icon, $label])
                                    <a href="{{ route($route) }}" class="{{ $dropItem }}"><i class="fa-solid {{ $icon }} w-4 text-tosca-600"></i>{{ $label }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <a href="{{ route('spmb') }}" class="bg-gold-500 hover:bg-gold-600 text-slate-900 font-bold px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition flex items-center space-x-2 ml-2">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                        <span>SPMB</span>
                    </a>

                    @if (setting('rdm_url'))
                        <a href="{{ setting('rdm_url') }}" target="_blank" rel="noopener" class="bg-tosca-700 hover:bg-tosca-800 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition flex items-center space-x-2 border border-tosca-600">
                            <i class="fa-solid fa-laptop-code text-gold-400"></i>
                            <span>Portal RDM</span>
                        </a>
                    @else
                        <button type="button" onclick="openRDMModal()" class="bg-tosca-700 hover:bg-tosca-800 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition flex items-center space-x-2 border border-tosca-600">
                            <i class="fa-solid fa-laptop-code text-gold-400"></i>
                            <span>Portal RDM</span>
                        </button>
                    @endif
                </nav>

                <!-- Mobile menu button -->
                <div class="flex items-center xl:hidden">
                    <button id="mobile-menu-btn" type="button" onclick="toggleMobileMenu()" aria-label="Menu" class="p-2 rounded-lg text-slate-700 hover:text-tosca-700 hover:bg-slate-100 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Navigation -->
        <div id="mobile-menu" class="hidden xl:hidden bg-white border-b border-slate-200 px-4 pt-2 pb-6 space-y-3 shadow-xl max-h-[calc(100vh-5rem)] overflow-y-auto">
            <a href="{{ route('home') }}" class="block w-full text-left font-semibold text-slate-800 py-2 border-b border-slate-100">Beranda</a>
            <div>
                <span class="block text-xs font-bold uppercase text-slate-400 tracking-wider mb-1">Profil</span>
                <div class="pl-3 space-y-2 text-sm">
                    @foreach ($profilMenu as [$route, $params, $icon, $label])
                        <a href="{{ route($route, $params) }}" class="block w-full text-left py-1 text-slate-600">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase text-slate-400 tracking-wider mb-1">Program Madrasah</span>
                <div class="pl-3 space-y-2 text-sm">
                    @foreach ($navPrograms as $navProgram)
                        <a href="{{ route('program', $navProgram) }}" class="block w-full text-left py-1 text-slate-600">{{ $navProgram->menu_name }}</a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('ekskul') }}" class="block w-full text-left font-semibold text-slate-800 py-2 border-b border-slate-100">Ekstrakurikuler</a>
            <div>
                <span class="block text-xs font-bold uppercase text-slate-400 tracking-wider mb-1">Informasi</span>
                <div class="pl-3 space-y-2 text-sm">
                    <a href="{{ route('berita') }}" class="block w-full text-left py-1 text-slate-600">Berita</a>
                    <a href="{{ route('prestasi') }}" class="block w-full text-left py-1 text-slate-600">Prestasi</a>
                    <a href="{{ route('galeri') }}" class="block w-full text-left py-1 text-slate-600">Galeri Foto & Video</a>
                </div>
            </div>
            <div class="pt-2 grid grid-cols-2 gap-2">
                <a href="{{ route('spmb') }}" class="w-full bg-gold-500 text-slate-900 font-bold py-2 rounded-lg text-center shadow">SPMB</a>
                @if (setting('rdm_url'))
                    <a href="{{ setting('rdm_url') }}" target="_blank" rel="noopener" class="w-full bg-tosca-700 text-white font-semibold py-2 rounded-lg text-center shadow">Rapor Digital (RDM)</a>
                @else
                    <button type="button" onclick="openRDMModal(); toggleMobileMenu();" class="w-full bg-tosca-700 text-white font-semibold py-2 rounded-lg text-center shadow">Rapor Digital (RDM)</button>
                @endif
            </div>
        </div>
    </header>
