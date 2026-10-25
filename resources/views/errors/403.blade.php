<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Akses Ditolak | MTs. Darul Ulum Waru</title>
    @include('partials.head-assets')
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-10 text-center">
        <span class="w-20 h-20 mx-auto rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-4xl"><i class="fa-solid fa-lock"></i></span>
        <p class="text-xs font-bold uppercase tracking-widest text-gold-600 mt-6">403 · Akses Ditolak</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-2">Anda tidak punya akses ke halaman ini</h1>
        <p class="text-sm text-slate-500 mt-3">{{ $exception->getMessage() ?: 'Halaman ini tidak tersedia untuk akun Anda.' }}</p>
        <div class="mt-7 flex flex-col sm:flex-row gap-2 justify-center">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center justify-center gap-2"><i class="fa-solid fa-gauge-high text-gold-400"></i> Ke Dashboard</a>
            @endauth
            <a href="{{ url('/') }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Ke Website</a>
        </div>
    </div>
</body>
</html>
