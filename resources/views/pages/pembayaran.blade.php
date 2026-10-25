@extends('layouts.app')

@section('title', 'Pembayaran')
@section('meta_description', 'Cek tagihan pembayaran siswa MTs. Darul Ulum Waru berdasarkan NISN.')

@push('head')
    <meta name="robots" content="noindex, nofollow">
@endpush

@php
    $callCenter = setting('spp_call_center');
    $bankAccount = setting('spp_bank_account');
    $notes = setting('spp_notes');
@endphp

@section('content')
    <!-- Banner -->
    <section class="bg-pattern relative overflow-hidden text-white">
        <div class="absolute inset-0 bg-gradient-to-r from-tosca-950/90 via-tosca-900/70 to-tosca-900/30"></div>
        <i class="fa-solid fa-wallet absolute right-6 -bottom-10 text-[220px] text-gold-400/10 hidden md:block"></i>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-28 text-center">
            <span class="text-gold-400 font-bold text-sm tracking-widest uppercase">Layanan Wali Murid</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold mt-2">Informasi Pembayaran</h1>
            <p class="text-tosca-100 mt-3 max-w-xl mx-auto">Masukkan NISN dan kode akses putra-putri Anda untuk melihat rincian tagihan SPP dan biaya lainnya.</p>
            <div class="w-20 h-1 bg-gold-500 mx-auto mt-4 rounded-full"></div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10 space-y-8">
        <!-- Form NISN -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-10">
            <form action="{{ route('pembayaran.check') }}" method="POST" id="bill-form" class="flex flex-col sm:flex-row sm:items-end gap-3" autocomplete="off" novalidate>
                @csrf
                <div class="flex-1 min-w-0">
                    <label for="nisn" class="block text-xs font-bold text-slate-600 mb-1.5">NISN Siswa</label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-tosca-600"></i>
                        <input id="nisn" name="nisn" type="text" inputmode="numeric" maxlength="14" autofocus
                            value="{{ old('nisn', $searched ?? '') }}" placeholder="10 digit, contoh: 0123456789"
                            class="w-full pl-11 pr-4 py-3.5 rounded-xl border text-base font-semibold tracking-wider focus:ring-2 focus:ring-tosca-600 focus:border-tosca-600 focus:outline-none {{ $errors->has('nisn') ? 'border-rose-400 bg-rose-50/40' : 'border-slate-200' }}">
                    </div>
                </div>
                <div class="shrink-0">
                    <button type="button" onclick="openCodeModal()" class="w-full sm:w-auto whitespace-nowrap border border-transparent bg-tosca-700 hover:bg-tosca-800 text-white font-bold px-6 py-3.5 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-gold-400"></i> Cek Pembayaran
                    </button>
                </div>

                <!-- Pop-up kode akses -->
                <div id="code-modal" onclick="if (event.target === this) closeCodeModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm {{ $errors->has('access_code') && ! $errors->has('nisn') ? 'flex' : 'hidden' }} items-center justify-center p-4">
                    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 relative">
                        <button type="button" onclick="closeCodeModal()" aria-label="Tutup" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-amber-50 text-gold-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-3">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <h3 class="text-2xl font-extrabold text-slate-900">Masukkan Kode Akses</h3>
                            <p class="text-sm text-slate-500 mt-1">Untuk NISN <b class="text-slate-800 font-mono" id="code-modal-nisn">{{ old('nisn') }}</b></p>
                        </div>
                        <label for="access_code" class="block text-xs font-bold text-slate-600 mb-1.5">Kode Akses</label>
                        <div class="relative">
                            <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gold-500"></i>
                            <input id="access_code" name="access_code" type="text" maxlength="9" autocapitalize="characters" spellcheck="false"
                                placeholder="contoh: K7X-2MP"
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border text-lg font-bold tracking-[0.25em] uppercase placeholder:normal-case placeholder:tracking-normal placeholder:font-medium placeholder:text-base focus:ring-2 focus:ring-tosca-600 focus:border-tosca-600 focus:outline-none {{ $errors->has('access_code') ? 'border-rose-400 bg-rose-50/40' : 'border-slate-200' }}">
                        </div>
                        <p id="code-error" class="text-sm font-semibold text-rose-600 mt-3 {{ $errors->has('access_code') ? '' : 'hidden' }}"><i class="fa-solid fa-circle-exclamation mr-1"></i><span>{{ $errors->first('access_code') }}</span></p>
                        <p class="text-xs text-slate-500 mt-3"><i class="fa-solid fa-circle-info text-tosca-600 mr-1"></i>Kode akses 6 huruf/angka dari bagian keuangan madrasah.</p>
                        @if ($callCenter)
                            @php($contactName = preg_match('/\(([^)]+)\)/', $callCenter, $m) ? trim($m[1]) : 'Bagian Keuangan')
                            <a id="code-wa-link" href="{{ wa_link($callCenter) }}" data-wa-base="{{ wa_link($callCenter) }}" target="_blank" rel="noopener noreferrer"
                                class="mt-4 flex items-center gap-3 p-3 rounded-xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 hover:border-emerald-200 transition group">
                                <span class="w-10 h-10 shrink-0 rounded-xl bg-[#25D366] text-white flex items-center justify-center text-xl"><i class="fa-brands fa-whatsapp"></i></span>
                                <span class="flex-1 text-left">
                                    <span class="block text-sm font-bold text-emerald-800">Belum punya / lupa kode akses?</span>
                                    <span class="block text-xs text-emerald-700">Tanyakan ke {{ $contactName }} via WhatsApp</span>
                                </span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-emerald-600 text-xs group-hover:translate-x-0.5 transition"></i>
                            </a>
                        @endif
                        <div class="grid grid-cols-2 gap-2 mt-6">
                            <button type="button" onclick="closeCodeModal()" class="px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="px-4 py-3 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center justify-center gap-2"><i class="fa-solid fa-unlock text-gold-400"></i> Lihat Tagihan</button>
                        </div>
                    </div>
                </div>
            </form>
            <p id="nisn-error" class="text-sm font-semibold text-rose-600 mt-3 {{ $errors->has('nisn') ? '' : 'hidden' }}"><i class="fa-solid fa-circle-exclamation mr-1"></i><span>{{ $errors->first('nisn') }}</span></p>
            <p class="text-xs text-slate-500 mt-3 leading-relaxed"><i class="fa-solid fa-shield-halved text-tosca-600 mr-1"></i>Demi menjaga privasi, tagihan hanya bisa dilihat dengan <b>NISN</b> dan <b>kode akses</b> siswa. Kode akses dibagikan oleh bagian keuangan madrasah. Jangan bagikan kode akses kepada orang lain.</p>
        </div>

        <!-- Hasil -->
        @isset($searched)
            @if (! $bill)
                <div class="rounded-3xl bg-amber-50 border border-gold-400/50 p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                    <span class="w-14 h-14 shrink-0 rounded-2xl bg-gold-500 text-slate-950 flex items-center justify-center text-2xl"><i class="fa-solid fa-magnifying-glass-minus"></i></span>
                    <div>
                        <h2 class="font-extrabold text-slate-900 text-lg">Data tagihan tidak ditemukan</h2>
                        <p class="text-sm text-slate-600 mt-1">Tidak ada data untuk NISN <b class="text-slate-800">{{ $searched }}</b>. Pastikan NISN sudah benar, atau hubungi call center pembayaran.</p>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    <!-- Identitas siswa -->
                    <div class="relative bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-950 text-white px-6 sm:px-10 py-8 overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:18px_18px] opacity-25"></div>
                        <i class="fa-solid fa-user-graduate absolute -right-4 -bottom-6 text-[150px] text-white/10"></i>
                        <div class="relative flex items-center gap-5">
                            <span class="w-16 h-16 shrink-0 rounded-2xl bg-gold-500 text-slate-950 flex items-center justify-center text-3xl shadow-lg"><i class="fa-solid fa-user-graduate"></i></span>
                            <div class="min-w-0">
                                <span class="inline-block bg-white/15 text-gold-400 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Kelas {{ $bill->class_name }}</span>
                                <h2 class="text-2xl sm:text-3xl font-extrabold mt-2 leading-tight">{{ $bill->name }}</h2>
                                <p class="text-tosca-100 text-sm mt-1">NISN {{ $bill->nisn }} @if ($bill->homeroom) &nbsp;•&nbsp; Wali kelas: {{ $bill->homeroom }} @endif</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 sm:p-10">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">
                                Rincian Tagihan @if ($bill->school_year) · TA {{ $bill->school_year }} @endif @if ($bill->semester) Semester {{ $bill->semester }} @endif
                            </h3>
                            @if ($bill->billingDateLabel())
                                <span class="inline-flex items-center gap-1.5 bg-tosca-50 text-tosca-800 text-xs font-bold px-3 py-1.5 rounded-full"><i class="fa-regular fa-clock"></i> Data per {{ $bill->billingDateLabel() }}</span>
                            @endif
                        </div>

                        @if ($bill->isPaidOff())
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-6 text-center">
                                <span class="w-16 h-16 mx-auto rounded-full bg-emerald-500 text-white flex items-center justify-center text-3xl shadow-lg"><i class="fa-solid fa-check"></i></span>
                                <p class="text-xl font-extrabold text-emerald-800 mt-4">Tidak Ada Tagihan</p>
                                <p class="text-sm text-emerald-700 mt-1">Alhamdulillah, seluruh pembayaran sudah lunas. Terima kasih.</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-100 rounded-2xl border border-slate-100 overflow-hidden">
                                @foreach ($bill->outstandingItems() as $item)
                                    <div class="flex items-center justify-between gap-4 px-5 py-4 bg-white">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="w-9 h-9 shrink-0 rounded-xl bg-tosca-50 text-tosca-700 flex items-center justify-center"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $item['label'] }}</p>
                                                @if (! empty($item['months']))
                                                    <p class="text-xs text-slate-500">{{ $item['months'] }} bulan</p>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-900 whitespace-nowrap">{{ rupiah($item['amount']) }}</span>
                                    </div>
                                @endforeach
                                <div class="flex items-center justify-between gap-4 px-5 py-5 bg-gradient-to-r from-amber-50 to-tosca-50">
                                    <span class="font-extrabold text-slate-900 uppercase tracking-wide">Total Tagihan</span>
                                    <span class="text-2xl sm:text-3xl font-extrabold text-tosca-800 whitespace-nowrap">{{ rupiah($bill->total) }}</span>
                                </div>
                            </div>
                            @if ($bill->note)
                                <p class="text-sm text-slate-600 mt-4"><i class="fa-solid fa-note-sticky text-gold-500 mr-1"></i> Keterangan: {{ $bill->note }}</p>
                            @endif
                        @endif

                        @php($recentPayments = $bill->paymentsSinceImport()->take(10)->get())
                        @if ($recentPayments->isNotEmpty())
                            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-8 mb-3">Pembayaran yang Sudah Diterima</h3>
                            <div class="space-y-2">
                                @foreach ($recentPayments as $payment)
                                    <div class="flex items-center justify-between gap-4 px-5 py-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="w-9 h-9 shrink-0 rounded-xl bg-emerald-500 text-white flex items-center justify-center"><i class="fa-solid fa-check"></i></span>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-slate-800 text-sm">{{ $payment->paid_at->translatedFormat('d F Y') }}</p>
                                                <p class="text-xs text-slate-500 truncate">{{ collect($payment->allocations)->pluck('label')->implode(', ') }}</p>
                                            </div>
                                        </div>
                                        <span class="font-bold text-emerald-700 whitespace-nowrap">{{ rupiah($payment->amount) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <p class="text-xs text-slate-500 mt-5"><i class="fa-solid fa-circle-info text-tosca-600 mr-1"></i>Jika sudah membayar namun masih tercantum, kemungkinan data belum diperbarui. Silakan konfirmasi ke call center pembayaran.</p>
                    </div>
                </div>
            @endif
        @endisset

        <!-- Cara pembayaran -->
        @if ($callCenter || $bankAccount || $notes)
            <section class="bg-pattern rounded-3xl p-6 sm:p-10 text-white shadow-xl">
                <h2 class="text-xl sm:text-2xl font-extrabold flex items-center gap-3"><i class="fa-solid fa-money-bill-transfer text-gold-400"></i> Cara Pembayaran</h2>
                <div class="grid sm:grid-cols-2 gap-4 mt-6">
                    @if ($callCenter)
                        <div class="bg-white/10 backdrop-blur rounded-2xl p-5 ring-1 ring-white/15">
                            <p class="text-xs font-bold uppercase tracking-wider text-gold-400">Call Center Pembayaran</p>
                            <p class="text-lg font-extrabold mt-1">{{ $callCenter }}</p>
                            <a href="{{ wa_link($callCenter, 'Assalamu\'alaikum, saya wali murid ingin konfirmasi pembayaran.') }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#1ebe5a] text-white text-sm font-bold px-4 py-2 rounded-xl transition hover:-translate-y-0.5"><i class="fa-brands fa-whatsapp text-lg"></i> Hubungi via WhatsApp</a>
                        </div>
                    @endif
                    @if ($bankAccount)
                        <div class="bg-white/10 backdrop-blur rounded-2xl p-5 ring-1 ring-white/15">
                            <p class="text-xs font-bold uppercase tracking-wider text-gold-400">Transfer Bank</p>
                            <p class="text-lg font-extrabold mt-1" id="bank-account">{{ $bankAccount }}</p>
                            <button type="button" onclick="copyBankAccount(this)" data-number="{{ preg_replace('/\D/', '', $bankAccount) }}" class="mt-3 inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-slate-950 text-sm font-bold px-4 py-2 rounded-xl transition hover:-translate-y-0.5"><i class="fa-regular fa-copy"></i> <span>Salin No. Rekening</span></button>
                        </div>
                    @endif
                </div>
                @if ($notes)
                    <div class="mt-5 text-sm text-tosca-100 space-y-2">{{ paragraphs($notes) }}</div>
                @endif
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Langkah 1: cek format NISN di browser (tanpa ke server), lalu tampilkan pop-up kode akses.
        const nisnInput = document.getElementById('nisn');
        const codeModal = document.getElementById('code-modal');
        const codeInput = document.getElementById('access_code');

        function showNisnError(message) {
            const box = document.getElementById('nisn-error');
            box.querySelector('span').textContent = message;
            box.classList.toggle('hidden', !message);
            nisnInput.classList.toggle('border-rose-400', !!message);
        }
        function openCodeModal() {
            const nisn = nisnInput.value.replace(/\D/g, '');
            if (nisn.length !== 10) {
                showNisnError(nisn ? 'NISN harus terdiri dari 10 angka.' : 'Masukkan NISN siswa.');
                nisnInput.focus();
                return;
            }
            showNisnError('');
            document.getElementById('code-modal-nisn').textContent = nisn;
            updateWaLink(nisn);
            codeModal.classList.remove('hidden');
            codeModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => codeInput.focus(), 50);
        }
        // Pesan WhatsApp otomatis berisi NISN yang diketik, agar petugas tahu siswa yang dimaksud.
        function updateWaLink(nisn) {
            const link = document.getElementById('code-wa-link');
            if (!link) return;
            const message = "Assalamu'alaikum, saya wali murid dengan NISN " + nisn + ". Mohon informasi kode akses untuk cek tagihan di website madrasah. Terima kasih.";
            link.href = link.dataset.waBase + '?text=' + encodeURIComponent(message);
        }
        function closeCodeModal() {
            codeModal.classList.add('hidden');
            codeModal.classList.remove('flex');
            document.body.style.overflow = '';
            document.getElementById('code-error').classList.add('hidden');
            codeInput.value = '';
        }
        document.getElementById('bill-form').addEventListener('submit', e => {
            // Enter di kolom NISN: buka pop-up dulu, jangan langsung kirim.
            if (codeModal.classList.contains('hidden')) { e.preventDefault(); openCodeModal(); return; }
            if (codeInput.value.replace(/[^A-Za-z0-9]/g, '').length !== 6) {
                e.preventDefault();
                const box = document.getElementById('code-error');
                box.querySelector('span').textContent = 'Kode akses terdiri dari 6 huruf/angka, contoh: K7X-2MP.';
                box.classList.remove('hidden');
                codeInput.focus();
            }
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && !codeModal.classList.contains('hidden')) closeCodeModal(); });
        // Kode salah / NISN terkunci: pop-up langsung terbuka lagi beserta pesannya.
        if (!codeModal.classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
            updateWaLink(nisnInput.value.replace(/\D/g, ''));
            codeInput.focus();
        }

        function copyBankAccount(btn) {
            const label = btn.querySelector('span');
            navigator.clipboard.writeText(btn.dataset.number).then(() => {
                label.textContent = 'Tersalin!';
                setTimeout(() => label.textContent = 'Salin No. Rekening', 2000);
            });
        }
    </script>
@endpush
