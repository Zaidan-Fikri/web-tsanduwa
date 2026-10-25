<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Kode Akses {{ $class ? 'Kelas '.$class : '' }} | Admin MTs. Darul Ulum Waru</title>
    @include('partials.head-assets')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @page { size: A4; margin: 10mm; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .sheet { box-shadow: none !important; padding: 0 !important; max-width: none !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        .slip { break-inside: avoid; page-break-inside: avoid; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <!-- Toolbar (tidak ikut dicetak) -->
    <div class="no-print sticky top-0 z-10 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex flex-col sm:flex-row sm:items-center gap-3">
            <a href="{{ route('admin.spp.index', $class ? ['kelas' => $class] : []) }}" class="text-sm font-semibold text-slate-600 hover:text-tosca-700"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali</a>
            <h1 class="font-extrabold text-slate-900 flex-1"><i class="fa-solid fa-key text-gold-500 mr-1"></i> Cetak Kode Akses Wali Murid</h1>
            <form method="GET" class="flex gap-2">
                <select name="kelas" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                    <option value="">Pilih kelas...</option>
                    @foreach ($classes as $option)
                        <option value="{{ $option }}" @selected($class === $option)>Kelas {{ $option }}</option>
                    @endforeach
                </select>
            </form>
            @if ($bills->isNotEmpty())
                <button type="button" onclick="window.print()" class="px-5 py-2 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center justify-center gap-2"><i class="fa-solid fa-print text-gold-400"></i> Cetak</button>
            @endif
        </div>
    </div>

    <div class="sheet max-w-5xl mx-auto bg-white my-6 p-6 shadow-sm">
        @if ($bills->isEmpty())
            <div class="py-20 text-center text-slate-500">
                <i class="fa-solid fa-hand-pointer text-4xl text-tosca-300"></i>
                <p class="mt-4">Pilih kelas di atas untuk menampilkan slip kode akses.</p>
            </div>
        @else
            <p class="no-print text-xs text-slate-500 mb-4"><i class="fa-solid fa-scissors mr-1"></i> Kelas {{ $class }} · {{ $bills->count() }} siswa. Gunting mengikuti garis putus-putus, lalu bagikan ke masing-masing wali murid. Kode bersifat rahasia.</p>
            <div class="grid grid-cols-2 gap-0">
                @foreach ($bills as $bill)
                    <div class="slip border border-dashed border-slate-400 p-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                            <img src="{{ asset('img/logo.png') }}" alt="" class="w-8 h-8 object-contain">
                            <div class="leading-tight">
                                <p class="text-[11px] font-extrabold text-tosca-900">MTs. DARUL ULUM WARU</p>
                                <p class="text-[9px] font-bold uppercase tracking-wider text-gold-600">Kode Akses Cek Tagihan</p>
                            </div>
                        </div>
                        <table class="w-full text-[11px] mt-2">
                            <tr><td class="text-slate-500 w-14 align-top">Nama</td><td class="font-bold text-slate-800">{{ $bill->name }}</td></tr>
                            <tr><td class="text-slate-500">Kelas</td><td class="font-semibold">{{ $bill->class_name }}</td></tr>
                            <tr><td class="text-slate-500">NISN</td><td class="font-mono font-semibold">{{ $bill->nisn }}</td></tr>
                        </table>
                        <div class="mt-2 rounded-lg bg-tosca-50 border border-tosca-200 text-center py-2">
                            <p class="text-[9px] font-bold uppercase tracking-widest text-tosca-700">Kode Akses</p>
                            <p class="font-mono text-2xl font-extrabold tracking-[0.25em] text-slate-900">{{ $bill->accessCodeLabel() }}</p>
                        </div>
                        <p class="text-[9.5px] text-slate-600 mt-2 leading-snug">
                            Cek tagihan di <b>{{ preg_replace('#^https?://#', '', url('/pembayaran')) }}</b> (menu Informasi → Pembayaran), masukkan NISN & kode akses. <b>Jangan bagikan kode ini ke orang lain.</b>
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
