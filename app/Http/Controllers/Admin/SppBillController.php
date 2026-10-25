<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SppBill;
use App\Models\SppImport;
use App\Support\Spp\AccessCode;
use App\Support\Spp\BillSheetParser;
use App\Support\Spp\XlsxReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class SppBillController extends Controller
{
    /** Hasil baca Excel disimpan sementara di sini sampai admin menekan "Simpan". */
    private const PREVIEW_DIR = 'spp-imports';

    private const SESSION_KEY = 'spp_import_token';

    public function index(Request $request)
    {
        $query = SppBill::query()
            ->when(trim((string) $request->query('q')), function ($q, $s) {
                $digits = preg_replace('/\D/', '', $s);

                // Cari di nama; cari di NISN hanya jika yang diketik mengandung angka
                // (tanpa ini, "muhammad" -> NISN LIKE '%%' -> semua siswa ikut tampil).
                return $q->where(fn ($w) => $w->where('name', 'like', '%'.$s.'%')
                    ->when($digits !== '', fn ($w) => $w->orWhere('nisn', 'like', '%'.$digits.'%')));
            })
            ->when($request->query('kelas'), fn ($q, $c) => $q->where('class_name', $c))
            ->when($request->query('status') === 'tagihan', fn ($q) => $q->where('total', '>', 0))
            ->when($request->query('status') === 'lunas', fn ($q) => $q->where('total', '<=', 0));

        return view('admin.spp.index', [
            'items' => (clone $query)->orderBy('class_name')->orderBy('name')->paginate(25)->withQueryString(),
            'classes' => SppBill::query()->distinct()->orderBy('class_name')->pluck('class_name'),
            'stats' => [
                'students' => SppBill::count(),
                'with_bill' => SppBill::where('total', '>', 0)->count(),
                'total' => (int) SppBill::sum('total'),
                'filtered_total' => (int) (clone $query)->sum('total'),
            ] + $this->componentTotals(),
            'lastImport' => SppImport::with('user')->latest()->first(),
        ]);
    }

    public function importForm()
    {
        return view('admin.spp.import', [
            'imports' => SppImport::with('user')->latest()->take(10)->get(),
            'hasPending' => $this->loadPreview() !== null,
        ]);
    }

    /** Langkah 1: upload & baca file, lalu tampilkan preview (belum disimpan). */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:5'],
            'files.*' => ['required', 'file', 'max:5120', 'extensions:xlsx'],
        ], [
            'files.required' => 'Pilih minimal satu file Excel.',
            'files.*.extensions' => 'File harus berformat Excel .xlsx (bukan .xls / .csv).',
            'files.*.max' => 'Ukuran file Excel maksimal 5 MB.',
        ]);

        $parser = new BillSheetParser(); // satu parser agar NISN ganda antar-file ikut terdeteksi
        $sheets = [];
        $footer = null;

        foreach ($request->file('files') as $fileIndex => $file) {
            try {
                $parsed = $parser->parse(XlsxReader::read($file->getRealPath()));
            } catch (Throwable $e) {
                throw ValidationException::withMessages([
                    'files' => '"'.$file->getClientOriginalName().'" tidak bisa dibaca: '.$e->getMessage(),
                ]);
            }

            $footer ??= $parsed['payment_info'];
            foreach ($parsed['sheets'] as $sheet) {
                $sheet['key'] = $fileIndex.':'.$sheet['name'];
                $sheet['file'] = Str::limit($file->getClientOriginalName(), 120, '');
                $sheets[] = $sheet;
            }
        }

        if (! collect($sheets)->where('skipped', false)->count()) {
            throw ValidationException::withMessages(['files' => 'Tidak ada data siswa yang bisa dibaca. Pastikan memakai template Data Tagihan Bulanan.']);
        }

        $this->discardPreview();
        $token = Str::random(40);
        Storage::disk('local')->put(self::PREVIEW_DIR.'/'.$token.'.json', json_encode([
            'files' => collect($request->file('files'))->map(fn ($f) => Str::limit($f->getClientOriginalName(), 120, ''))->all(),
            'sheets' => $sheets,
            'payment' => BillSheetParser::detectPaymentInfo($footer),
            'user_id' => $request->user()->id,
        ]));
        $request->session()->put(self::SESSION_KEY, $token);
        $this->cleanupOldPreviews();

        return redirect()->route('admin.spp.preview');
    }

    /** Langkah 2: preview isi file sebelum disimpan. */
    public function preview()
    {
        $data = $this->loadPreview();
        if (! $data) {
            return redirect()->route('admin.spp.import')->with('error', 'Preview sudah kedaluwarsa. Silakan upload ulang file Excel.');
        }

        $rows = collect($data['sheets'])->where('skipped', false)->flatMap(fn ($s) => $s['rows']);
        $existing = SppBill::whereIn('nisn', $rows->pluck('nisn')->filter()->all())->pluck('total', 'nisn');

        return view('admin.spp.preview', [
            'data' => $data,
            'existing' => $existing,
            'summary' => [
                'sheets' => collect($data['sheets'])->where('skipped', false)->count(),
                'students' => $rows->count(),
                'valid' => $rows->filter(fn ($r) => ! $r['errors'])->count(),
                'errors' => $rows->filter(fn ($r) => (bool) $r['errors'])->count(),
                'warnings' => $rows->filter(fn ($r) => (bool) $r['warnings'])->count(),
                'total' => $rows->filter(fn ($r) => ! $r['errors'])->sum('total'),
                'updated' => $existing->count(),
            ],
            'currentPayment' => ['call_center' => setting('spp_call_center'), 'bank_account' => setting('spp_bank_account')],
        ]);
    }

    /** Langkah 3: simpan sheet yang dipilih ke database. */
    public function commit(Request $request)
    {
        $data = $this->loadPreview();
        if (! $data) {
            return redirect()->route('admin.spp.import')->with('error', 'Preview sudah kedaluwarsa. Silakan upload ulang file Excel.');
        }

        $selected = $request->validate([
            'sheets' => ['required', 'array', 'min:1'],
            'sheets.*' => ['string', 'max:150'],
        ], ['sheets.required' => 'Pilih minimal satu sheet/kelas untuk disimpan.'])['sheets'];

        $sheets = collect($data['sheets'])->where('skipped', false)->whereIn('key', $selected);
        $rows = $sheets->flatMap(fn ($sheet) => collect($sheet['rows'])
            ->filter(fn ($row) => ! $row['errors'])
            ->map(fn ($row) => [$row, $sheet]));

        if ($rows->isEmpty()) {
            return back()->with('error', 'Tidak ada baris valid untuk disimpan.');
        }

        $import = DB::transaction(function () use ($data, $sheets, $rows, $request) {
            $import = SppImport::create([
                'file_name' => Str::limit(implode(', ', $data['files']), 250),
                'sheet_count' => $sheets->count(),
                'row_count' => $rows->count(),
                'total_amount' => $rows->sum(fn ($pair) => $pair[0]['total']),
                'billing_date' => $sheets->pluck('billing_date')->filter()->max(),
                'user_id' => $request->user()->id,
            ]);

            $now = now();

            // Kode akses: siswa lama tetap memakai kodenya, siswa baru dibuatkan kode unik.
            $existingCodes = SppBill::whereIn('nisn', $rows->pluck('0.nisn')->all())->pluck('access_code', 'nisn');
            $taken = array_fill_keys(SppBill::whereNotNull('access_code')->pluck('access_code')->all(), true);
            $codeFor = fn (string $nisn) => $existingCodes[$nisn] ?? AccessCode::generate($taken);

            $records = $rows->map(fn ($pair) => [
                'nisn' => $pair[0]['nisn'],
                'access_code' => $codeFor($pair[0]['nisn']),
                'name' => Str::limit($pair[0]['name'], 250, ''),
                'class_name' => Str::limit($pair[0]['class_name'], 10, ''),
                'homeroom' => $pair[1]['homeroom'] ? Str::limit($pair[1]['homeroom'], 250, '') : null,
                'items' => json_encode($pair[0]['items']),
                'spp_months' => $pair[0]['spp_months'],
                'total' => max(0, $pair[0]['total']),
                'note' => $pair[0]['note'] ? Str::limit($pair[0]['note'], 250, '') : null,
                'school_year' => $pair[1]['school_year'],
                'semester' => $pair[1]['semester'],
                'billing_date' => $pair[1]['billing_date'],
                'billing_time' => $pair[1]['billing_time'],
                'spp_import_id' => $import->id,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            foreach (array_chunk($records, 200) as $chunk) {
                SppBill::upsert($chunk, ['nisn'], [
                    'access_code', 'name', 'class_name', 'homeroom', 'items', 'spp_months', 'total', 'note',
                    'school_year', 'semester', 'billing_date', 'billing_time', 'spp_import_id', 'updated_at',
                ]);
            }

            return $import;
        });

        if ($request->boolean('update_payment_info')) {
            Setting::setMany(array_filter([
                'spp_call_center' => $data['payment']['call_center'] ?? null,
                'spp_bank_account' => $data['payment']['bank_account'] ?? null,
            ]));
        }

        $this->discardPreview();

        return redirect()->route($request->user()->canAccess('spp') ? 'admin.spp.index' : 'admin.spp.import')
            ->with('success', "Berhasil menyimpan {$import->row_count} tagihan siswa dari {$import->sheet_count} kelas.");
    }

    /** Halaman cetak slip kode akses per kelas, untuk dibagikan wali kelas ke wali murid. */
    public function codes(Request $request)
    {
        $classes = SppBill::query()->distinct()->orderBy('class_name')->pluck('class_name');
        $class = $request->query('kelas');

        return view('admin.spp.codes', [
            'classes' => $classes,
            'class' => $class,
            'bills' => $class
                ? SppBill::where('class_name', $class)->orderBy('name')->get(['id', 'nisn', 'access_code', 'name', 'class_name', 'homeroom'])
                : collect(),
        ]);
    }

    public function cancel()
    {
        $this->discardPreview();

        return redirect()->route('admin.spp.import')->with('success', 'Import dibatalkan. Tidak ada data yang disimpan.');
    }

    public function destroy(SppBill $sppBill)
    {
        $sppBill->delete();

        return back()->with('success', 'Data tagihan '.$sppBill->name.' dihapus.');
    }

    public function destroyClass(string $className)
    {
        $count = SppBill::where('class_name', $className)->delete();

        return redirect()->route('admin.spp.index')->with('success', "{$count} data tagihan kelas {$className} dihapus.");
    }

    /**
     * Total per jenis tagihan, dihitung dari rincian (kolom Excel) yang judulnya
     * mengandung "PPDB" atau "SPP" (mis. "Tagihan PPDB", "Tagihan SPP s/ Oktober").
     */
    private function componentTotals(): array
    {
        $totals = ['ppdb' => 0, 'spp' => 0];

        foreach (SppBill::query()->select('items')->cursor() as $bill) {
            foreach ($bill->items ?? [] as $item) {
                $label = strtoupper($item['label'] ?? '');
                if (str_contains($label, 'PPDB')) {
                    $totals['ppdb'] += (int) ($item['amount'] ?? 0);
                } elseif (str_contains($label, 'SPP')) {
                    $totals['spp'] += (int) ($item['amount'] ?? 0);
                }
            }
        }

        return $totals;
    }

    private function loadPreview(): ?array
    {
        $token = session(self::SESSION_KEY);
        if (! $token || ! preg_match('/^[A-Za-z0-9]{40}$/', $token)) {
            return null;
        }

        $json = Storage::disk('local')->get(self::PREVIEW_DIR.'/'.$token.'.json');

        return $json ? json_decode($json, true) : null;
    }

    private function discardPreview(): void
    {
        if ($token = session()->pull(self::SESSION_KEY)) {
            Storage::disk('local')->delete(self::PREVIEW_DIR.'/'.basename($token).'.json');
        }
    }

    /** Hapus preview yang ditinggalkan (lebih dari 1 hari). */
    private function cleanupOldPreviews(): void
    {
        foreach (Storage::disk('local')->files(self::PREVIEW_DIR) as $file) {
            if (Storage::disk('local')->lastModified($file) < now()->subDay()->getTimestamp()) {
                Storage::disk('local')->delete($file);
            }
        }
    }
}
