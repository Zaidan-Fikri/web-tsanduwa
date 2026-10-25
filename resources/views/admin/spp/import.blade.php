@extends('layouts.admin')

@section('title', 'Upload Tagihan Pembayaran Sekolah')

@section('content')
    <x-admin.page-header title="Upload Tagihan dari Excel" subtitle="Langkah 1 dari 2: pilih file, lalu periksa preview sebelum disimpan." icon="fa-file-excel">
        @if (auth()->user()->canAccess('spp'))
        <a href="{{ route('admin.spp.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-tosca-600 hover:text-tosca-700 transition"><i class="fa-solid fa-list"></i> Data Tagihan</a>
        @endif
    </x-admin.page-header>

    @if ($hasPending)
        <div class="mb-5 p-4 rounded-2xl bg-amber-50 border border-gold-400/50 flex flex-col sm:flex-row sm:items-center gap-3">
            <i class="fa-solid fa-hourglass-half text-gold-600 text-xl"></i>
            <p class="flex-1 text-sm text-slate-700">Ada hasil upload yang <b>belum disimpan</b>. Lanjutkan ke preview atau upload file baru untuk menggantinya.</p>
            <a href="{{ route('admin.spp.preview') }}" class="shrink-0 px-4 py-2 rounded-xl bg-tosca-700 hover:bg-tosca-800 text-white text-sm font-bold transition text-center">Lihat Preview</a>
        </div>
    @endif

    <div class="grid lg:grid-cols-5 gap-6 items-start">
        <form action="{{ route('admin.spp.upload') }}" method="POST" enctype="multipart/form-data" class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-100" id="spp-upload-form">
            @csrf
            <div class="p-6 space-y-5">
                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <p><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <label for="spp-files" class="flex flex-col items-center justify-center gap-3 p-8 rounded-2xl border-2 border-dashed border-tosca-200 bg-tosca-50/50 hover:bg-tosca-50 hover:border-tosca-400 cursor-pointer text-center transition" id="spp-drop">
                    <span class="w-16 h-16 rounded-2xl bg-white text-emerald-600 shadow flex items-center justify-center text-3xl"><i class="fa-solid fa-file-excel"></i></span>
                    <span class="font-bold text-slate-800">Klik untuk memilih file Excel, atau seret file ke sini</span>
                    <span class="text-xs text-slate-500">Format .xlsx · maks. 5 MB per file · boleh sekaligus beberapa file (mis. kelas 7, 8, dan 9)</span>
                    <input id="spp-files" name="files[]" type="file" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple required class="sr-only">
                </label>
                <ul id="spp-file-list" class="space-y-2 text-sm"></ul>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 rounded-b-2xl flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-tosca-700 hover:bg-tosca-800 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition inline-flex items-center gap-2" id="spp-submit">
                    <i class="fa-solid fa-magnifying-glass-chart text-gold-400"></i> Baca File & Lihat Preview
                </button>
            </div>
        </form>

        <!-- Panduan -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="font-extrabold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-circle-question text-tosca-600"></i> Format File</h2>
            <ul class="mt-3 space-y-2.5 text-sm text-slate-600">
                <li class="flex gap-2"><i class="fa-solid fa-check text-tosca-600 mt-1"></i> Gunakan template <b>Data Tagihan Bulanan</b> seperti biasa. Satu sheet = satu kelas (7A, 7B, ...).</li>
                <li class="flex gap-2"><i class="fa-solid fa-check text-tosca-600 mt-1"></i> Wajib ada kolom <b>NO, NAMA, NISN</b> dan <b>JUMLAH TAGIHAN</b>. Kolom tagihan lain (PPDB, Kls 7, SPP, Kekurangan, ...) dikenali otomatis dari judulnya.</li>
                <li class="flex gap-2"><i class="fa-solid fa-check text-tosca-600 mt-1"></i> Tanggal tagihan, wali kelas, dan info call center/rekening di bawah tabel ikut terbaca.</li>
                <li class="flex gap-2"><i class="fa-solid fa-check text-tosca-600 mt-1"></i> Upload ulang akan <b>memperbarui</b> tagihan siswa dengan NISN yang sama.</li>
                <li class="flex gap-2"><i class="fa-solid fa-triangle-exclamation text-gold-500 mt-1"></i> Simpan file dengan Excel sebelum upload agar hasil rumus ikut tersimpan.</li>
            </ul>
        </div>
    </div>

    @if ($imports->isNotEmpty())
        <h2 class="font-extrabold text-slate-900 mt-8 mb-3 flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-tosca-600"></i> Riwayat Upload</h2>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead><tr><th>Waktu</th><th>File</th><th>Jumlah Kelas</th><th>Jumlah Siswa</th><th>Total Tagihan</th><th>Oleh</th></tr></thead>
                    <tbody>
                        @foreach ($imports as $import)
                            <tr>
                                <td class="whitespace-nowrap text-xs text-slate-600">{{ $import->created_at->translatedFormat('d M Y H:i') }}</td>
                                <td class="text-sm text-slate-800 max-w-xs truncate" title="{{ $import->file_name }}">{{ $import->file_name }}</td>
                                <td class="whitespace-nowrap">{{ $import->sheet_count }} kelas</td>
                                <td class="whitespace-nowrap">{{ $import->row_count }} siswa</td>
                                <td class="font-semibold whitespace-nowrap">{{ rupiah($import->total_amount) }}</td>
                                <td class="text-xs text-slate-600">{{ $import->user?->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        (() => {
            const input = document.getElementById('spp-files');
            const list = document.getElementById('spp-file-list');
            const drop = document.getElementById('spp-drop');
            const form = document.getElementById('spp-upload-form');
            const render = () => {
                list.innerHTML = '';
                [...input.files].forEach(f => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 border border-slate-100';
                    li.innerHTML = '<i class="fa-solid fa-file-excel text-emerald-600"></i><span class="flex-1 truncate"></span><span class="text-xs text-slate-500"></span>';
                    li.children[1].textContent = f.name;
                    li.children[2].textContent = (f.size / 1024).toFixed(0) + ' KB';
                    list.appendChild(li);
                });
            };
            input.addEventListener('change', render);
            ['dragover', 'dragenter'].forEach(e => drop.addEventListener(e, ev => { ev.preventDefault(); drop.classList.add('border-tosca-500', 'bg-tosca-50'); }));
            ['dragleave', 'drop'].forEach(e => drop.addEventListener(e, () => drop.classList.remove('border-tosca-500', 'bg-tosca-50')));
            drop.addEventListener('drop', ev => { ev.preventDefault(); input.files = ev.dataTransfer.files; render(); });
            form.addEventListener('submit', () => {
                const btn = document.getElementById('spp-submit');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Membaca file...';
            });
        })();
    </script>
@endpush
