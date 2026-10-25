<?php

namespace App\Support\Spp;

use Carbon\Carbon;

/**
 * Mengubah isi file Excel "Data Tagihan Bulanan" (satu sheet per kelas) menjadi data tagihan.
 *
 * Kolom dikenali dari judulnya (2 baris judul), sehingga kolom tagihan yang berbeda
 * antar angkatan (PPDB, Kls 7, Kls 8, SPP, Kekurangan SMT1, ...) otomatis menyesuaikan.
 */
class BillSheetParser
{
    private const MONTHS = [
        'januari' => 1, 'februari' => 2, 'pebruari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
        'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'nopember' => 11, 'desember' => 12,
    ];

    /** Kata yang dibiarkan huruf besar saat merapikan judul kolom. */
    private const KEEP_UPPER = ['PPDB', 'SPP', 'KLS', 'SMT', 'SMT1', 'SMT2', 'OSIS', 'LKS', 'UTS', 'UAS', 'PAS', 'PTS'];

    private array $seenNisn = [];

    /**
     * @param  array<string, array<int, array<int, mixed>>>  $sheets  hasil XlsxReader::read()
     */
    public function parse(array $sheets): array
    {
        $result = ['sheets' => [], 'payment_info' => null];

        foreach ($sheets as $name => $rows) {
            $sheet = $this->parseSheet((string) $name, $rows);
            $result['payment_info'] ??= $sheet['payment_info'];
            unset($sheet['payment_info']);
            $result['sheets'][] = $sheet;
        }

        return $result;
    }

    private function parseSheet(string $name, array $rows): array
    {
        $sheet = [
            'name' => $name, 'skipped' => false, 'skip_reason' => null,
            'class_name' => null, 'homeroom' => null, 'school_year' => null, 'semester' => null,
            'billing_date' => null, 'billing_time' => null, 'columns' => [], 'rows' => [], 'payment_info' => null,
        ];

        if (! array_filter($rows, fn ($cells) => array_filter($cells, fn ($v) => $this->text($v) !== ''))) {
            return ['skipped' => true, 'skip_reason' => 'Sheet kosong'] + $sheet;
        }

        $headerRow = $this->findHeaderRow($rows);
        if ($headerRow === null) {
            return ['skipped' => true, 'skip_reason' => 'Format tidak dikenali (judul kolom NO / NAMA / NISN tidak ditemukan)'] + $sheet;
        }

        $this->parseMeta($sheet, $rows, $headerRow);
        $columns = $this->mapColumns($rows[$headerRow] ?? [], $rows[$headerRow + 1] ?? []);

        if (! isset($columns['nisn'], $columns['name'])) {
            return ['skipped' => true, 'skip_reason' => 'Kolom NAMA atau NISN tidak ditemukan'] + $sheet;
        }

        $sheet['columns'] = array_map(fn ($item) => $item['label'], $columns['items']);

        ksort($rows);
        $footer = [];
        foreach ($rows as $number => $cells) {
            if ($number <= $headerRow + 1) {
                continue;
            }

            $rowText = implode(' ', array_map(fn ($v) => $this->text($v), $cells));
            if ($footer || stripos($rowText, 'call center') !== false || stripos($rowText, 'rek ') !== false) {
                $line = implode('  ·  ', array_filter(array_map(fn ($v) => $this->text($v), $cells), fn ($v) => $v !== ''));
                if ($line !== '') {
                    $footer[] = $line;
                }

                continue;
            }

            $studentName = $this->text($cells[$columns['name']] ?? null);
            $no = $cells[$columns['no']] ?? null;
            if ($studentName === '' || ! is_numeric($no) || $no <= 0) {
                continue; // baris kosong / baris total
            }

            $sheet['rows'][] = $this->parseRow($cells, $columns, $studentName, $sheet, $number);
        }

        $sheet['payment_info'] = $footer ? implode("\n", $footer) : null;

        if (! $sheet['rows']) {
            $sheet['skipped'] = true;
            $sheet['skip_reason'] = 'Tidak ada data siswa';
        }

        return $sheet;
    }

    /**
     * Ambil nomor call center & rekening dari footer, mis.
     * "1. · 082232400303 (Bu Itaul) · Rek BCA · 7205002715 (Itaul Khusnah)".
     *
     * @return array{call_center: ?string, bank_account: ?string}
     */
    public static function detectPaymentInfo(?string $footer): array
    {
        $info = ['call_center' => null, 'bank_account' => null];
        if (! $footer) {
            return $info;
        }

        $bankPos = null;
        if (preg_match('/Rek(?:ening)?\.?\s+([A-Za-z]{2,10})/i', $footer, $bank, PREG_OFFSET_CAPTURE)) {
            $bankPos = $bank[0][1];
            $rest = substr($footer, $bankPos + strlen($bank[0][0]));
            if (preg_match('/(\d[\d .-]{5,25}\d)\s*(?:\(([^)]+)\))?/', $rest, $acc)) {
                $info['bank_account'] = strtoupper($bank[1][0]).' '.preg_replace('/\D/', '', $acc[1])
                    .(! empty($acc[2]) ? ' a.n. '.trim($acc[2]) : '');
            }
        }

        // Nomor HP sebelum keterangan rekening.
        $phonePart = $bankPos !== null ? substr($footer, 0, $bankPos) : $footer;
        if (preg_match('/(0\d{9,13})\s*(?:\(([^)]+)\))?/', preg_replace('/(?<=\d)[ .-](?=\d)/', '', $phonePart), $phone)) {
            $number = implode('-', str_split($phone[1], 4));
            $info['call_center'] = $number.(! empty($phone[2]) ? ' ('.trim($phone[2]).')' : '');
        }

        return $info;
    }

    private function findHeaderRow(array $rows): ?int
    {
        foreach ($rows as $number => $cells) {
            if ($number > 30) {
                break;
            }

            $texts = array_map(fn ($v) => strtoupper($this->text($v)), $cells);
            if (in_array('NO', $texts, true) && in_array('NAMA', $texts, true)) {
                return $number;
            }
        }

        return null;
    }

    private function parseMeta(array &$sheet, array $rows, int $headerRow): void
    {
        foreach ($rows as $number => $cells) {
            if ($number >= $headerRow) {
                break;
            }

            $texts = array_values(array_filter(array_map(fn ($v) => $this->text($v), $cells), fn ($v) => $v !== ''));
            $line = implode(' ', $texts);

            if (preg_match('/TAHUN\s+(\d{4})\s*[-\/]\s*(\d{4})(?:\s+SEMESTER\s+(\w+))?/i', $line, $m)) {
                $sheet['school_year'] = $m[1].'/'.$m[2];
                $sheet['semester'] = $m[3] ?? null;
            }

            if (preg_match('/Tanggal\s+(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/i', $line, $m) && isset(self::MONTHS[strtolower($m[2])])) {
                $sheet['billing_date'] = Carbon::create((int) $m[3], self::MONTHS[strtolower($m[2])], (int) $m[1])->toDateString();
            }

            if (stripos($line, 'Jam') !== false && preg_match('/(\d{1,2}[.:]\d{2}(?:\s*WI[BT]A?)?)/i', $line, $m)) {
                $sheet['billing_time'] = strtoupper(str_replace(':', '.', $m[1]));
            }

            if (preg_match('/KELA\s*S\s*:\s*(\d+)\s*-?\s*([A-Z])/i', $line, $m)) {
                $sheet['class_name'] = $m[1].strtoupper($m[2]);
            }

            foreach ($texts as $i => $text) {
                if (preg_match('/^Walas\b/i', $text)) {
                    $value = trim(preg_replace('/^Walas\s*/i', '', $text));
                    if (in_array($value, ['', ':'], true)) {
                        $value = $texts[$i + 1] ?? '';
                    }
                    $sheet['homeroom'] = trim(ltrim($value, ": \t")) ?: null;
                }
            }
        }
    }

    /**
     * Gabungkan 2 baris judul, lalu tentukan arti tiap kolom.
     */
    private function mapColumns(array $top, array $bottom): array
    {
        $columns = ['items' => []];
        $indexes = array_unique(array_merge(array_keys($top), array_keys($bottom)));
        sort($indexes);

        foreach ($indexes as $i) {
            $label = trim(preg_replace('/\s+/', ' ', $this->text($top[$i] ?? null).' '.$this->text($bottom[$i] ?? null)));
            $upper = strtoupper($label);

            match (true) {
                $label === '' => null,
                $upper === 'NO' => $columns['no'] ??= $i,
                $upper === 'NAMA' => $columns['name'] ??= $i,
                in_array($upper, ['KLS', 'KELAS'], true) => $columns['class'] ??= $i,
                str_contains($upper, 'NISN') => $columns['nisn'] ??= $i,
                str_starts_with($upper, 'JML') => $columns['months'] ??= $i,
                str_contains($upper, 'JUMLAH') => $columns['total'] ??= $i,
                str_contains($upper, 'KETERANGAN') => $columns['note'] ??= $i,
                default => $columns['items'][$i] = ['label' => $this->prettyLabel($label), 'is_spp' => str_contains($upper, 'SPP')],
            };
        }

        // Kolom tagihan hanya yang berada setelah NISN dan sebelum JUMLAH.
        $columns['items'] = array_filter($columns['items'], fn ($_, $i) => $i > ($columns['nisn'] ?? -1)
            && $i < ($columns['total'] ?? PHP_INT_MAX), ARRAY_FILTER_USE_BOTH);
        $columns['no'] ??= 0;

        return $columns;
    }

    private function parseRow(array $cells, array $columns, string $studentName, array $sheet, int $rowNumber): array
    {
        $errors = [];
        $warnings = [];

        [$nisn, $nisnError] = $this->normalizeNisn($cells[$columns['nisn']] ?? null);
        if ($nisnError) {
            $errors[] = $nisnError;
        } elseif (isset($this->seenNisn[$nisn])) {
            $errors[] = 'NISN sama dengan '.$this->seenNisn[$nisn];
        } else {
            $this->seenNisn[$nisn] = $studentName.' (sheet '.$sheet['name'].')';
        }

        $months = isset($columns['months']) ? $this->amount($cells[$columns['months']] ?? null) : null;

        $items = [];
        foreach ($columns['items'] as $i => $column) {
            $amount = $this->amount($cells[$i] ?? null);
            if ($amount === null) {
                $errors[] = 'Nilai kolom "'.$column['label'].'" belum dihitung Excel. Buka file di Excel lalu simpan ulang.';
                $amount = 0;
            }
            $items[] = ['label' => $column['label'], 'amount' => $amount] + ($column['is_spp'] && $months ? ['months' => $months] : []);
        }

        $sum = array_sum(array_column($items, 'amount'));
        $total = isset($columns['total']) ? $this->amount($cells[$columns['total']] ?? null) : $sum;
        if ($total === null) {
            $errors[] = 'Nilai JUMLAH TAGIHAN belum dihitung Excel. Buka file di Excel lalu simpan ulang.';
            $total = $sum;
        } elseif ($total !== $sum) {
            $warnings[] = 'Jumlah tagihan ('.number_format($total, 0, ',', '.').') berbeda dengan total rincian ('.number_format($sum, 0, ',', '.').'). Dipakai nilai JUMLAH dari file.';
        }

        $className = isset($columns['class']) ? strtoupper(str_replace([' ', '-'], '', $this->text($cells[$columns['class']] ?? null))) : '';

        return [
            'row' => $rowNumber,
            'no' => (int) $cells[$columns['no']],
            'nisn' => $nisn,
            'name' => $studentName,
            'class_name' => $className ?: ($sheet['class_name'] ?? $sheet['name']),
            'items' => $items,
            'spp_months' => (int) ($months ?? 0),
            'total' => $total,
            'note' => isset($columns['note']) ? ($this->text($cells[$columns['note']] ?? null) ?: null) : null,
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /** @return array{0: ?string, 1: ?string} [nisn, pesan error] */
    private function normalizeNisn(mixed $value): array
    {
        if ($value === null || $value === '' || $value instanceof FormulaWithoutValue) {
            return [null, 'NISN kosong'];
        }

        if (is_int($value) || is_float($value)) {
            // Excel menyimpan NISN sebagai angka, sehingga nol di depan hilang (0123... => 123...).
            $digits = str_pad((string) (int) round($value), 10, '0', STR_PAD_LEFT);
        } else {
            $digits = preg_replace('/\D+/', '', (string) $value);
        }

        if (! preg_match('/^\d{10}$/', $digits)) {
            return [$digits ?: null, 'NISN tidak valid ('.$this->text($value).'), harus 10 digit'];
        }

        return [$digits, null];
    }

    /** Angka rupiah dari sel; null bila sel rumus belum dihitung. */
    private function amount(mixed $value): ?int
    {
        if ($value instanceof FormulaWithoutValue) {
            return null;
        }
        if (is_int($value) || is_float($value)) {
            return (int) round($value);
        }

        $digits = preg_replace('/[^\d-]/', '', (string) $value);

        return $digits === '' || $digits === '-' ? 0 : (int) $digits;
    }

    private function text(mixed $value): string
    {
        if ($value === null || $value instanceof FormulaWithoutValue) {
            return '';
        }
        if (is_float($value) && floor($value) === $value) {
            $value = (int) $value;
        }

        return trim(preg_replace('/\s+/u', ' ', (string) $value));
    }

    /** "TAGIHAN PPDB" => "Tagihan PPDB", "TAG.SPP s/ Oktober" => "Tagihan SPP s/ Oktober" */
    private function prettyLabel(string $label): string
    {
        $label = preg_replace('/^TAG\.\s*/i', 'TAGIHAN ', $label);

        return implode(' ', array_map(function ($word) {
            return ctype_upper(preg_replace('/[^A-Za-z]/', '', $word)) && ! in_array($word, self::KEEP_UPPER, true) && strlen($word) > 2
                ? ucfirst(strtolower($word))
                : $word;
        }, explode(' ', $label)));
    }
}
