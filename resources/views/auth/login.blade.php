<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk Admin | MTs. Darul Ulum Waru</title>

    @include('partials.head-assets')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">
    <div class="min-h-screen grid lg:grid-cols-2">
        <!-- Panel kiri: identitas madrasah -->
        <section class="bg-pattern relative overflow-hidden text-white hidden lg:flex flex-col justify-between p-12">
            <div class="absolute inset-0 bg-gradient-to-br from-tosca-950/95 via-tosca-900/80 to-tosca-800/60"></div>
            <div class="absolute -left-24 -bottom-24 w-96 h-96 rounded-full bg-tosca-400/20 blur-3xl"></div>
            <div class="absolute right-0 top-0 w-[28rem] h-[28rem] rounded-full bg-gold-500/15 blur-3xl"></div>
            <img src="{{ asset('img/logo.png') }}" alt="" class="absolute -right-20 -bottom-20 w-[26rem] opacity-[0.07]">

            <a href="{{ route('home') }}" class="relative flex items-center gap-3 group w-max">
                <img src="{{ asset('img/logo.png') }}" alt="Logo MTs. Darul Ulum Waru" class="w-14 h-14 object-contain drop-shadow-md group-hover:scale-105 transition">
                <div>
                    <span class="block text-xl font-extrabold tracking-tight">MTs. DARUL ULUM WARU</span>
                    <span class="block text-xs font-semibold text-gold-400 tracking-wider uppercase">Beriman • Berilmu • Berprestasi</span>
                </div>
            </a>

            <div class="relative max-w-lg">
                <span class="inline-flex items-center gap-2 bg-gold-500 text-slate-950 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider"><i class="fa-solid fa-shield-halved"></i> Area Terbatas</span>
                <h1 class="text-4xl xl:text-5xl font-extrabold mt-5 leading-tight">Panel Admin <span class="text-gold-400">Website Madrasah</span></h1>
                <p class="text-tosca-100 mt-4 text-lg leading-relaxed">Kelola berita, prestasi, galeri, data guru, hingga informasi SPMB dengan mudah dari satu tempat.</p>
                <div class="grid grid-cols-3 gap-3 mt-8">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><i class="fa-solid fa-newspaper text-gold-400 text-2xl"></i><div class="text-xs text-tosca-100 mt-2">Berita</div></div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><i class="fa-solid fa-trophy text-gold-400 text-2xl"></i><div class="text-xs text-tosca-100 mt-2">Prestasi</div></div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center ring-1 ring-white/15"><i class="fa-solid fa-user-plus text-gold-400 text-2xl"></i><div class="text-xs text-tosca-100 mt-2">SPMB</div></div>
                </div>
            </div>

            <p class="relative text-xs text-tosca-200/70">&copy; {{ date('Y') }} MTs. Darul Ulum Waru Sidoarjo</p>
        </section>

        <!-- Panel kanan: form login -->
        <section class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-20 h-20 object-contain mx-auto drop-shadow-md">
                    <p class="text-lg font-extrabold text-tosca-900 mt-3">MTs. DARUL ULUM WARU</p>
                    <p class="text-xs font-semibold text-gold-600 tracking-wider uppercase">Beriman • Berilmu • Berprestasi</p>
                </div>

                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-10">
                    <div class="w-16 h-16 bg-tosca-100 text-tosca-700 rounded-2xl flex items-center justify-center text-3xl mb-5">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Selamat Datang</h2>
                    <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk mengelola website madrasah.</p>

                    @if (session('status'))
                        <div class="mt-5 p-3 rounded-xl bg-tosca-50 border border-tosca-200 text-tosca-800 text-sm font-semibold"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold" role="alert">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('admin.login.attempt') }}" method="POST" class="mt-6 space-y-5">
                        @csrf
                        <div>
                            <label for="username" class="block text-xs font-bold text-slate-600 mb-1.5">Username</label>
                            <div class="relative">
                                <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username" maxlength="50" placeholder="Masukkan username"
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-tosca-600 focus:border-tosca-600 focus:outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-600 mb-1.5">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••"
                                    class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-tosca-600 focus:border-tosca-600 focus:outline-none transition">
                                <button type="button" onclick="togglePassword(this)" aria-label="Tampilkan password" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg text-slate-400 hover:text-tosca-700 hover:bg-tosca-50 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded border-slate-300 text-tosca-700 focus:ring-tosca-600">
                            Ingat saya di perangkat ini
                        </label>
                        <button type="submit" class="w-full bg-tosca-700 hover:bg-tosca-800 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition text-sm inline-flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            <i class="fa-solid fa-right-to-bracket text-gold-400"></i> Masuk
                        </button>
                    </form>
                </div>

                <a href="{{ route('home') }}" class="mt-6 flex items-center justify-center gap-2 text-sm font-semibold text-slate-500 hover:text-tosca-700 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke website
                </a>
            </div>
        </section>
    </div>

    <script>
        function togglePassword(btn) {
            const input = document.getElementById('password');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        }
    </script>
</body>
</html>
