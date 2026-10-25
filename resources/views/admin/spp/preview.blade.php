@extends('layouts.admin')

@section('title', 'Preview Tagihan Pembayaran Sekolah')

@php
    $payment = $data['payment'] ?? [];
    $paymentChanged = ($payment['call_center'] ?? null) || ($payment['bank_account'] ?? null);
@endphp

@section('content')
    <x-admin.page-header title="Preview Data Tagihan" subtitle="Langkah 2 dari 2: periksa data di bawah. Data BELUM tersimpan sebelum Anda menekan Simpan." icon="fa-magnifying-glass-chart" />

    <!-- Ringkasan -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['Kelas (sheet)', $summary['sheets'], 'fa-layer-group', 'text-tosca-700 bg-tosca-50'],
            ['Siswa siap disimpan', $summary['valid'].' / '.$summary['students'], 'fa-user-check', 'text-emerald-700 bg-emerald-50'],
            ['Perlu diperiksa', $summary['errors'].' error · '.$summary['warnings'].' peringatan', 'fa-triangle-exclamation', $summary['errors'] ? 'text-rose-700 bg-rose-50' : 'text-slate-600 bg-slate-100'],
            ['Total tagihan', rupiah($summary['total']), 'fa-money-bill-wave', 'text-gold-600 bg-amber-50'],
        ] as [$label, $value, $icon, $color])
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <span class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center"><i class="fa-solid {{ $icon }}"></i></span>
                <div class="text-lg font-extrabold text-slate-900 mt-3 leading-tight">{{ $value }}</div>
                <div class="text-xs font-semibold text-slate-500">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <p class="text-sm text-slate-600 mb-5"><i class="fa-solid fa-file-excel text-emerald-600 mr-1"></i> File: <b>{{ implode(', ', $data['files']) }}</b>
        @if ($summary['updated']) · <span class="text-tosca-700 font-semibold">{{ $summary['updated'] }} siswa sudah ada di database dan akan diperbarui.</span> @endif
    </p>

    <form action="{{ route('admin.spp.commit') }}" method="POST" id="spp-commit-form">
        @csrf

        @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}</div>
        @endif

        <!-- Info pembayaran dari footer -->
        @if ($paymentChanged)
            <label class="mb-6 flex items-start gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer has-[:checked]:border-tosca-300 has-[:checked]:bg-tosca-50/60 transition">
                <input type="checkbox" name="update_payment_info" value="1" @checked(! setting('spp_call_center') || ! setting('spp_bank_account')) class="mt-1 w-5 h-5 rounded border-slate-300 text-tosca-700 focus:ring-tosca-600">
                <span class="flex-1">
                    <span class="block font-bold text-slate-900">Perbarui Info Pembayaran di website dengan data dari file</span>
                    <span class="grid sm:grid-cols-2 gap-3 mt-2 text-sm">
                        <span><span class="text-xs text-slate-500 block">Call center</span><b>{{ $payment['call_center'] ?? '-' }}</b>
                            @if ($currentPayment['call_center'] && $currentPayment['call_center'] !== ($payment['call_center'] ?? null)) <span class="block text-xs text-slate-400">saat ini: {{ $currentPayment['call_center'] }}</span> @endif</span>
                        <span><span class="text-xs text-slate-500 block">Rekening</span><b>{{ $payment['bank_account'] ?? '-' }}</b>
                            @if ($currentPayment['bank_account'] && $currentPayment['bank_account'] !== ($payment['bank_account'] ?? null)) <span class="block text-xs text-slate-400">saat ini: {{ $currentPayment['bank_account'] }}</span> @endif</span>
                    </span>
                </span>
            </label>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
            <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-layer-group text-tosca-600"></i> Data per Kelas</h2>
            <div class="flex gap-2 text-xs font-bold">
                <button type="button" onclick="toggleAllSheets(true)" class="px-3 py-1.5 rounded-lg bg-tosca-50 text-tosca-700 hover:bg-tosca-100 transition">Pilih semua</button>
                <button type="button" onclick="toggleAllSheets(false)" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition">Kosongkan</button>
            </div>
        </div>

        <div class="space-y-3">
            @foreach ($data['sheets'] as $sheet)
                @php
                    $rowsWithError = collect($sheet['rows'])->filter(fn ($r) => $r['errors'])->count();
                    $rowsWithWarning = collect($sheet['rows'])->filter(fn ($r) => $r['warnings'])->count();
                    $validRows = count($sheet['rows']) - $rowsWithError;
                @endphp

                @if ($sheet['skipped'])
                    <div class="flex items-center gap-3 px-5 py-3 rounded-2xl bg-slate-100 text-sm text-slate-500">
                        <i class="fa-solid fa-ban"></i> Sheet <b>{{ $sheet['name'] }}</b> <span class="text-xs">({{ $sheet['file'] }})</span> dilewati — {{ $sheet['skip_reason'] }}
                    </div>
                    @continue
                @endif

                <details class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" @if ($rowsWithError) open @endif>
                    <summary class="flex flex-wrap items-center gap-3 px-5 py-4 cursor-pointer list-none hover:bg-slate-50/70">
                        <input type="checkbox" name="sheets[]" value="{{ $sheet['key'] }}" @checked($validRows > 0) @disabled($validRows === 0) onclick="event.stopPropagation()" class="sheet-check w-5 h-5 rounded border-slate-300 text-tosca-700 focus:ring-tosca-600">
                        <span class="w-11 h-11 rounded-xl bg-tosca-700 text-gold-400 font-extrabold flex items-center justify-center">{{ $sheet['class_name'] ?? $sheet['name'] }}</span>
                        <span class="flex-1 min-w-[12rem]">
                            <span class="block font-bold text-slate-900">Kelas {{ $sheet['class_name'] ?? $sheet['name'] }} <span class="font-normal text-xs text-slate-500">· sheet {{ $sheet['name'] }} · {{ $sheet['file'] }}</span></span>
                            <span class="block text-xs text-slate-500">
                                Wali kelas: {{ $sheet['homeroom'] ?? '-' }}
                                @if ($sheet['billing_date']) · Per {{ \Carbon\Carbon::parse($sheet['billing_date'])->translatedFormat('d F Y') }} {{ $sheet['billing_time'] }} @endif
                                @if ($sheet['school_year']) · TA {{ $sheet['school_year'] }} @endif
                            </span>
                        </span>
                        <span class="flex flex-wrap items-center gap-2 text-xs font-bold">
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">{{ count($sheet['rows']) }} siswa</span>
                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-gold-600">{{ rupiah(collect($sheet['rows'])->filter(fn ($r) => ! $r['errors'])->sum('total')) }}</span>
                            @if ($rowsWithError)<span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700">{{ $rowsWithError }} error</span>@endif
                            @if ($rowsWithWarning)<span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">{{ $rowsWithWarning }} peringatan</span>@endif
                            <i class="fa-solid fa-chevron-down text-slate-400 transition group-open:rotate-180 ml-1"></i>
                        </span>
                    </summary>

                    <div class="overflow-x-auto border-t border-slate-100">
                        <table class="admin-table w-full">
                            <thead>
                                <tr>
                                    <th>No</th><th>Nama</th><th>NISN</th>
                                    @foreach ($sheet['columns'] as $column)<th class="text-right">{{ $column }}</th>@endforeach
                                    <th class="text-right">Jumlah</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sheet['rows'] as $row)
                                    <tr class="{{ $row['errors'] ? 'bg-rose-50/70' : '' }}">
                                        <td class="text-slate-500">{{ $row['no'] }}</td>
                                        <td class="font-semibold text-slate-800 whitespace-nowrap">{{ $row['name'] }}</td>
                                        <td class="font-mono text-xs">{{ $row['nisn'] ?? '—' }}</td>
                                        @foreach ($row['items'] as $item)
                                            <td class="text-right whitespace-nowrap {{ $item['amount'] > 0 ? 'text-slate-800' : 'text-slate-300' }}">
                                                {{ number_format($item['amount'], 0, ',', '.') }}
                                                @if (! empty($item['months']))<span class="block text-[10px] text-slate-400">{{ $item['months'] }} bln</span>@endif
                                            </td>
                                        @endforeach
                                        <td class="text-right font-bold whitespace-nowrap {{ $row['total'] > 0 ? 'text-tosca-800' : 'text-emerald-600' }}">{{ $row['total'] > 0 ? number_format($row['total'], 0, ',', '.') : 'Lunas' }}</td>
                                        <td class="text-xs min-w-[11rem]">
                                            @foreach ($row['errors'] as $message)
                                                <span class="block text-rose-700 font-semibold"><i class="fa-solid fa-circle-xmark mr-1"></i>{{ $message }} — tidak disimpan</span>
                                            @endforeach
                                            @foreach ($row['warnings'] as $message)
                                                <span class="block text-amber-700"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $message }}</span>
                                            @endforeach
                                            @unless ($row['errors'])
                                                @if ($row['nisn'] && $existing->has($row['nisn']))
                                                    <span class="inline-block px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 font-bold">Diperbarui</span>
                                                    @if ((int) $existing[$row['nisn']] !== $row['total'])<span class="block text-slate-400">sebelumnya {{ number_format($existing[$row['nisn']], 0, ',', '.') }}</span>@endif
                                                @else
                                                    <span class="inline-block px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold">Baru</span>
                                                @endif
                                            @endunless
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            @endforeach
        </div>

        <!-- Aksi -->
        <div class="sticky bottom-0 mt-6 -mx-4 sm:mx-0 px-4 sm:px-6 py-4 bg-white/95 backdrop-blur border-t sm:border border-slate-100 sm:rounded-2xl shadow-lg flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
            <p class="text-xs text-slate-500"><i class="fa-solid fa-circle-info text-tosca-600 mr-1"></i>Hanya kelas yang dicentang & baris tanpa error yang disimpan.</p>
            <div class="flex flex-col-reverse sm:flex-row gap-2">
                <button type="submit" form="spp-cancel-form" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-gold-400"></i> Simpan <span id="selected-count"></span>
                </button>
            </div>
        </div>
    </form>

    <form action="{{ route('admin.spp.cancel') }}" method="POST" id="spp-cancel-form" data-confirm="Batalkan import? Hasil baca file ini akan dibuang.">@csrf</form>
@endsection

@push('scripts')
    <script>
        function updateSelectedCount() {
            const n = document.querySelectorAll('.sheet-check:checked').length;
            document.getElementById('selected-count').textContent = '(' + n + ' kelas)';
        }
        function toggleAllSheets(on) {
            document.querySelectorAll('.sheet-check:not(:disabled)').forEach(c => c.checked = on);
            updateSelectedCount();
        }
        document.querySelectorAll('.sheet-check').forEach(c => c.addEventListener('change', updateSelectedCount));
        updateSelectedCount();
        document.getElementById('spp-commit-form').addEventListener('submit', e => {
            if (e.submitter && e.submitter.getAttribute('form') === 'spp-cancel-form') return;
            if (!document.querySelectorAll('.sheet-check:checked').length) {
                e.preventDefault();
                alert('Pilih minimal satu kelas untuk disimpan.');
            }
        });
    </script>
@endpush
