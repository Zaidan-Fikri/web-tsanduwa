@extends('layouts.admin')

@section('title', 'Edit Tagihan '.$bill->name)

@php
    $items = $bill->items ?? [];
    $inputClass = 'w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-right font-semibold focus:ring-2 focus:ring-tosca-600 focus:outline-none';
@endphp

@section('content')
    <x-admin.page-header title="Edit Tagihan & Catat Pembayaran" subtitle="Catat pembayaran wali murid (boleh dicicil) atau koreksi data tagihan." icon="fa-pen-to-square">
        <a href="{{ route('admin.spp.index', ['kelas' => $bill->class_name]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-arrow-left"></i> Kembali ke Kelas {{ $bill->class_name }}</a>
    </x-admin.page-header>

    <!-- Identitas siswa -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-tosca-500 via-tosca-700 to-tosca-950 text-white p-6 mb-6 shadow-lg">
        <div class="absolute inset-0 bg-[radial-gradient(#5eead4_1px,transparent_1px)] [background-size:18px_18px] opacity-25"></div>
        <div class="relative flex flex-col md:flex-row md:items-center gap-5">
            <span class="w-14 h-14 shrink-0 rounded-2xl bg-gold-500 text-slate-950 flex items-center justify-center text-2xl"><i class="fa-solid fa-user-graduate"></i></span>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl sm:text-2xl font-extrabold leading-tight">{{ $bill->name }}</h2>
                <p class="text-tosca-100 text-sm mt-1">NISN {{ $bill->nisn }} · Kelas {{ $bill->class_name }} @if ($bill->homeroom) · Wali kelas {{ $bill->homeroom }} @endif</p>
                @if ($bill->billingDateLabel())
                    <p class="text-tosca-200 text-xs mt-1"><i class="fa-regular fa-clock mr-1"></i>Data Excel per {{ $bill->billingDateLabel() }}</p>
                @endif
            </div>
            <div class="md:text-right">
                <p class="text-xs font-bold uppercase tracking-wider text-gold-400">Sisa Tagihan</p>
                <p class="text-3xl font-extrabold">{{ $bill->isPaidOff() ? 'LUNAS' : rupiah($bill->total) }}</p>
            </div>
        </div>
    </div>

    <!-- Kode akses wali murid -->
    <div class="mb-6 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center gap-4">
        <span class="w-11 h-11 shrink-0 rounded-xl bg-amber-50 text-gold-600 flex items-center justify-center text-lg"><i class="fa-solid fa-key"></i></span>
        <div class="flex-1">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Kode Akses Wali Murid</p>
            <p class="font-mono text-2xl font-extrabold tracking-[0.25em] text-slate-900">{{ $bill->accessCodeLabel() }}</p>
            <p class="text-xs text-slate-500">Dipakai bersama NISN di menu Informasi → Pembayaran. Buat ulang bila kode tersebar ke orang lain.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.spp.codes', ['kelas' => $bill->class_name]) }}" target="_blank" rel="noopener" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-print mr-1"></i> Cetak Kelas {{ $bill->class_name }}</a>
            <form action="{{ route('admin.spp.reset-code', $bill) }}" method="POST" data-confirm-title="Buat ulang kode akses?" data-confirm-button="Ya, Buat Ulang" data-confirm="Buat kode akses baru untuk {{ $bill->name }}? Kode lama langsung tidak berlaku dan wali murid perlu diberi kode baru.">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition"><i class="fa-solid fa-rotate mr-1"></i> Buat Ulang Kode</button>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
            @foreach ($errors->all() as $error)
                <p><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid lg:grid-cols-5 gap-6 items-start">
        <div class="lg:col-span-3 space-y-6">
            <!-- Catat pembayaran -->
            <form action="{{ route('admin.spp.pay', $bill) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100" id="pay-form">
                @csrf
                <div class="px-6 pt-6 flex flex-wrap items-center justify-between gap-3">
                    <h3 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-hand-holding-dollar text-tosca-600"></i> Catat Pembayaran</h3>
                    @unless ($bill->isPaidOff())
                        <button type="button" onclick="payAll()" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-tosca-50 text-tosca-700 hover:bg-tosca-100 transition"><i class="fa-solid fa-check-double mr-1"></i> Lunasi semua</button>
                    @endunless
                </div>

                @if ($bill->isPaidOff())
                    <div class="m-6 p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-3xl"></i>
                        <p class="font-extrabold text-emerald-800 mt-2">Tagihan siswa ini sudah lunas.</p>
                        <p class="text-xs text-emerald-700 mt-1">Jika ada tambahan tagihan, gunakan "Koreksi Data" di bawah atau upload Excel terbaru.</p>
                    </div>
                @else
                    <div class="p-6 space-y-3">
                        <p class="text-xs text-slate-500">Isi nominal yang dibayar pada jenis tagihan yang sesuai. Boleh sebagian (cicilan).</p>
                        <div class="rounded-xl border border-slate-100 divide-y divide-slate-100">
                            @foreach ($items as $i => $item)
                                @continue(($item['amount'] ?? 0) <= 0)
                                <div class="grid grid-cols-12 items-center gap-3 px-4 py-3">
                                    <div class="col-span-12 sm:col-span-5">
                                        <p class="font-semibold text-slate-800 text-sm">{{ $item['label'] }}@if (! empty($item['months'])) <span class="text-xs font-normal text-slate-500">({{ $item['months'] }} bln)</span>@endif</p>
                                        <p class="text-xs text-slate-500">Sisa {{ rupiah($item['amount']) }}</p>
                                    </div>
                                    <div class="col-span-8 sm:col-span-5 relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                        <input type="text" inputmode="numeric" name="amounts[{{ $i }}]" value="{{ old('amounts.'.$i) ? number_format((int) old('amounts.'.$i), 0, ',', '.') : '' }}" placeholder="0"
                                            data-pay-input data-max="{{ (int) $item['amount'] }}" class="{{ $inputClass }} pl-9 {{ $errors->has('amounts.'.$i) ? 'border-rose-400 bg-rose-50/40' : '' }}">
                                    </div>
                                    <button type="button" onclick="payItem(this)" class="col-span-4 sm:col-span-2 px-2 py-2 rounded-lg text-xs font-bold text-tosca-700 bg-tosca-50 hover:bg-tosca-100 transition">Lunasi</button>
                                </div>
                            @endforeach
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3 pt-2">
                            <x-admin.input name="paid_at" label="Tanggal Bayar" type="date" :value="today()->toDateString()" :max="today()->toDateString()" required />
                            <x-admin.input name="pay_note" label="Keterangan (opsional)" placeholder="Contoh: Tunai / Transfer BCA" list="pay-notes" maxlength="200" />
                            <datalist id="pay-notes"><option value="Tunai"><option value="Transfer BCA"><option value="Transfer bank lain"></datalist>
                        </div>

                        <div class="grid grid-cols-2 gap-3 p-4 rounded-xl bg-gradient-to-r from-amber-50 to-tosca-50">
                            <div><p class="text-xs text-slate-500">Dibayar sekarang</p><p class="text-lg font-extrabold text-tosca-800" id="pay-total">Rp 0</p></div>
                            <div class="text-right"><p class="text-xs text-slate-500">Sisa setelah dibayar</p><p class="text-lg font-extrabold text-slate-900" id="pay-remaining" data-total="{{ (int) $bill->total }}">{{ rupiah($bill->total) }}</p></div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center gap-2"><i class="fa-solid fa-floppy-disk text-gold-400"></i> Simpan Pembayaran</button>
                    </div>
                @endif
            </form>

            <!-- Koreksi data -->
            <details class="group bg-white rounded-2xl shadow-sm border border-slate-100" @if ($errors->hasAny(['name', 'class_name', 'items.*'])) open @endif>
                <summary class="px-6 py-4 cursor-pointer list-none flex items-center justify-between gap-3 hover:bg-slate-50/70 rounded-2xl">
                    <span>
                        <span class="font-extrabold text-slate-900"><i class="fa-solid fa-pen-ruler text-gold-500 mr-2"></i>Koreksi Data</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Untuk memperbaiki salah ketik nama, kelas, atau nominal tagihan (bukan untuk mencatat pembayaran).</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-slate-400 transition group-open:rotate-180"></i>
                </summary>
                <form action="{{ route('admin.spp.update', $bill) }}" method="POST" class="border-t border-slate-100">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div class="grid sm:grid-cols-3 gap-4">
                            <x-admin.input name="name" label="Nama Siswa" :value="$bill->name" required class="sm:col-span-2" />
                            <x-admin.input name="class_name" label="Kelas" :value="$bill->class_name" required maxlength="10" />
                        </div>
                        <div class="rounded-xl border border-slate-100 divide-y divide-slate-100">
                            @foreach ($items as $i => $item)
                                <div class="grid grid-cols-12 items-center gap-3 px-4 py-3">
                                    <p class="col-span-12 sm:col-span-6 text-sm font-semibold text-slate-700">{{ $item['label'] }}</p>
                                    <div class="col-span-12 sm:col-span-6 relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                        <input type="text" inputmode="numeric" name="items[{{ $i }}]" value="{{ number_format((int) old('items.'.$i, $item['amount']), 0, ',', '.') }}" data-money class="{{ $inputClass }} pl-9">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <x-admin.input name="note" label="Keterangan Tagihan (opsional)" :value="$bill->note" maxlength="200" />
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-gold-500 hover:bg-gold-600 text-slate-900 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center gap-2"><i class="fa-solid fa-floppy-disk"></i> Simpan Koreksi</button>
                    </div>
                </form>
            </details>
        </div>

        <!-- Riwayat -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-tosca-600"></i> Riwayat Pembayaran</h3>
            @if ($importedAt)
                <p class="text-xs text-slate-500 mt-1">Upload Excel terakhir untuk siswa ini: {{ $importedAt->translatedFormat('d M Y H:i') }}</p>
            @endif

            <div class="mt-4 space-y-3">
                @forelse ($bill->payments as $payment)
                    @php($beforeImport = $importedAt && $payment->created_at->lt($importedAt))
                    <div class="p-4 rounded-xl border {{ $payment->isPayment() ? 'border-tosca-100 bg-tosca-50/40' : 'border-amber-100 bg-amber-50/50' }} {{ $beforeImport ? 'opacity-70' : '' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                @if ($payment->isPayment())
                                    <p class="font-extrabold text-tosca-800">{{ rupiah($payment->amount) }}</p>
                                @else
                                    <p class="font-extrabold text-gold-600">Koreksi data</p>
                                @endif
                                <p class="text-xs text-slate-500">{{ $payment->paid_at->translatedFormat('d M Y') }} @if ($payment->note) · {{ $payment->note }} @endif</p>
                            </div>
                            @if ($payment->isPayment() && ! $beforeImport)
                                <form action="{{ route('admin.spp.payment.cancel', [$bill, $payment]) }}" method="POST" data-confirm="Batalkan pembayaran {{ rupiah($payment->amount) }}? Nominalnya akan dikembalikan ke tagihan.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Batalkan pembayaran" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-600 bg-white border border-rose-100 hover:bg-rose-50 transition"><i class="fa-solid fa-rotate-left"></i> Batalkan</button>
                                </form>
                            @endif
                        </div>
                        @if ($payment->isPayment())
                            <ul class="mt-2 text-xs text-slate-600 space-y-0.5">
                                @foreach ($payment->allocations ?? [] as $allocation)
                                    <li>• {{ $allocation['label'] }}: <b>{{ number_format($allocation['amount'], 0, ',', '.') }}</b></li>
                                @endforeach
                            </ul>
                        @endif
                        <p class="text-[11px] text-slate-400 mt-2">
                            Sisa {{ rupiah($payment->total_before) }} → <b>{{ rupiah($payment->total_after) }}</b> · dicatat {{ $payment->user?->name ?? 'admin' }}, {{ $payment->created_at->translatedFormat('d M Y H:i') }}
                        </p>
                        @if ($beforeImport)
                            <p class="text-[11px] text-slate-500 mt-1"><i class="fa-solid fa-circle-info mr-1"></i>Sudah tercakup di data Excel terbaru.</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-6">Belum ada pembayaran yang dicatat.</p>
                @endforelse
            </div>

            <p class="text-[11px] text-slate-400 mt-4 leading-relaxed"><i class="fa-solid fa-triangle-exclamation text-gold-500 mr-1"></i>Saat upload Excel tagihan terbaru, sisa tagihan siswa ini akan mengikuti angka di file Excel. Pastikan pembayaran yang dicatat di sini juga sudah masuk ke Excel bendahara.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const fmt = n => n.toLocaleString('id-ID');
            const num = v => parseInt(String(v).replace(/\D/g, '') || '0', 10);

            // Format ribuan saat mengetik (150000 -> 150.000)
            document.querySelectorAll('[data-pay-input], [data-money]').forEach(input => {
                input.addEventListener('input', () => {
                    const n = num(input.value);
                    input.value = input.value.trim() === '' ? '' : fmt(n);
                    recalc();
                });
            });

            const inputs = [...document.querySelectorAll('[data-pay-input]')];
            const remaining = document.getElementById('pay-remaining');

            function recalc() {
                if (!remaining) return;
                let paid = 0;
                inputs.forEach(i => {
                    const v = num(i.value);
                    const over = v > num(i.dataset.max);
                    i.classList.toggle('border-rose-400', over);
                    i.classList.toggle('bg-rose-50', over);
                    paid += v;
                });
                const left = Math.max(0, num(remaining.dataset.total) - paid);
                document.getElementById('pay-total').textContent = 'Rp ' + fmt(paid);
                remaining.textContent = left === 0 ? 'LUNAS' : 'Rp ' + fmt(left);
                remaining.classList.toggle('text-emerald-600', left === 0);
            }

            window.payItem = btn => {
                const input = btn.parentElement.querySelector('[data-pay-input]');
                input.value = fmt(num(input.dataset.max));
                recalc();
            };
            window.payAll = () => { inputs.forEach(i => i.value = fmt(num(i.dataset.max))); recalc(); };

            const form = document.getElementById('pay-form');
            if (form && inputs.length) {
                form.addEventListener('submit', e => {
                    const over = inputs.find(i => num(i.value) > num(i.dataset.max));
                    if (over) { e.preventDefault(); alert('Ada nominal pembayaran yang melebihi sisa tagihannya.'); over.focus(); return; }
                    if (!inputs.some(i => num(i.value) > 0)) { e.preventDefault(); alert('Isi nominal pembayaran terlebih dahulu.'); }
                });
            }
            recalc();
        })();
    </script>
@endpush
