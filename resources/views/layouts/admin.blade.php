<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') | Admin MTs. Darul Ulum Waru</title>

    @include('partials.head-assets')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    @include('admin.partials.sidebar')

    <div class="lg:pl-72 min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm">
            <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" onclick="toggleSidebar(true)" aria-label="Buka menu" class="lg:hidden p-2 -ml-2 rounded-lg text-slate-700 hover:text-tosca-700 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <span class="hidden sm:inline text-xs font-bold uppercase tracking-widest text-tosca-700">Beriman • Berilmu • Berprestasi</span>
                </div>

                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-toggle class="flex items-center gap-3 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 transition">
                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-tosca-500 to-tosca-800 text-white font-bold flex items-center justify-center text-sm">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="hidden sm:block text-left leading-tight">
                            <span class="block text-sm font-bold text-slate-800">{{ auth()->user()->name }}</span>
                            <span class="block text-[11px] text-slate-500">{{ auth()->user()->roleLabel() }}</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div data-dropdown-menu class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                        <a href="{{ route('admin.account.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-tosca-50 hover:text-tosca-700"><i class="fa-solid fa-user-shield w-4 text-tosca-600"></i> Akun Saya</a>
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-tosca-50 hover:text-tosca-700"><i class="fa-solid fa-globe w-4 text-tosca-600"></i> Lihat Website</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50"><i class="fa-solid fa-right-from-bracket w-4"></i> Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            <div class="max-w-6xl mx-auto">
                @yield('content')
            </div>
        </main>

        <footer class="px-4 sm:px-6 lg:px-8 py-5 text-xs text-slate-400 border-t border-slate-100 text-center sm:text-left">
            &copy; {{ date('Y') }} MTs. Darul Ulum Waru · Panel Admin Website
        </footer>
    </div>

    <!-- Notifikasi -->
    <div id="toast-container" class="fixed top-20 right-4 z-[70] space-y-2 w-[calc(100%-2rem)] max-w-sm">
        @foreach (['success' => ['bg-tosca-700', 'fa-circle-check'], 'error' => ['bg-rose-600', 'fa-circle-exclamation']] as $type => [$bg, $icon])
            @if (session($type))
                <div data-toast class="flex items-start gap-3 {{ $bg }} text-white px-4 py-3 rounded-xl shadow-2xl transition duration-300">
                    <i class="fa-solid {{ $icon }} mt-0.5 {{ $type === 'success' ? 'text-gold-400' : '' }}"></i>
                    <p class="flex-1 text-sm font-semibold">{{ session($type) }}</p>
                    <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Modal konfirmasi hapus -->
    <div id="confirm-modal" class="fixed inset-0 z-[80] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-7 shadow-2xl text-center">
            <span class="w-16 h-16 mx-auto rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-3xl"><i class="fa-solid fa-trash-can"></i></span>
            <h3 class="text-lg font-extrabold text-slate-900 mt-4">Yakin ingin menghapus?</h3>
            <p id="confirm-message" class="text-sm text-slate-500 mt-2"></p>
            <div class="mt-6 grid grid-cols-2 gap-2">
                <button type="button" data-confirm-cancel class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                <button type="button" data-confirm-ok class="px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 transition">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}?v=2"></script>
    @stack('scripts')
</body>
</html>
