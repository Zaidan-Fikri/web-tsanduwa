<?php

namespace App\Support\Spp;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

/**
 * Pembaca file .xlsx sederhana (tanpa library tambahan).
 * Hanya membaca nilai sel; untuk sel berisi rumus dipakai hasil terakhir yang disimpan Excel.
 */
class XlsxReader
{
    /** Batas ukuran isi file setelah diekstrak, mencegah "zip bomb". */
    private const MAX_UNCOMPRESSED = 50 * 1024 * 1024;

    private const NS_MAIN = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    private const NS_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    /**
     * @return array<string, array<int, array<int, mixed>>> [nama sheet => [nomor baris => [indeks kolom (0=A) => nilai]]]
     */
    public static function read(string $path): array
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('File bukan Excel (.xlsx) yang valid.');
        }

        try {
            $total = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $total += (int) ($zip->statIndex($i)['size'] ?? 0);
            }
            if ($total > self::MAX_UNCOMPRESSED) {
                throw new RuntimeException('Isi file Excel terlalu besar.');
            }

            $workbook = static::xml($zip, 'xl/workbook.xml');
            $rels = static::xml($zip, 'xl/_rels/workbook.xml.rels');
            if (! $workbook || ! $rels) {
                throw new RuntimeException('File bukan Excel (.xlsx) yang valid.');
            }

            $targets = [];
            foreach ($rels->Relationship as $rel) {
                $target = ltrim((string) $rel['Target'], '/');
                $targets[(string) $rel['Id']] = str_starts_with($target, 'xl/') ? $target : 'xl/'.$target;
            }

            $shared = static::sharedStrings($zip);

            $sheets = [];
            $workbook->registerXPathNamespace('m', self::NS_MAIN);
            foreach ($workbook->xpath('//m:sheets/m:sheet') ?: [] as $sheet) {
                $relId = (string) $sheet->attributes(self::NS_REL)['id'];
                $xml = isset($targets[$relId]) ? static::xml($zip, $targets[$relId]) : null;
                $sheets[(string) $sheet['name']] = $xml ? static::rows($xml, $shared) : [];
            }

            return $sheets;
        } finally {
            $zip->close();
        }
    }

    private static function xml(ZipArchive $zip, string $name): ?SimpleXMLElement
    {
        $content = $zip->getFromName($name);
        if ($content === false) {
            return null;
        }

        // LIBXML_NONET: jangan pernah mengambil resource dari jaringan; entitas eksternal tidak di-load (default PHP 8).
        $xml = @simplexml_load_string($content, SimpleXMLElement::class, LIBXML_NONET | LIBXML_COMPACT);

        return $xml === false ? null : $xml;
    }

    /** @return array<int, string> */
    private static function sharedStrings(ZipArchive $zip): array
    {
        $xml = static::xml($zip, 'xl/sharedStrings.xml');
        if (! $xml) {
            return [];
        }

        $strings = [];
        foreach ($xml->si as $si) {
            // Teks biasa (<t>) atau teks berformat (<r><t>..</t></r>...)
            $text = isset($si->t) ? (string) $si->t : '';
            foreach ($si->r as $run) {
                $text .= (string) $run->t;
            }
            $strings[] = $text;
        }

        return $strings;
    }

    private static function rows(SimpleXMLElement $sheet, array $shared): array
    {
        $rows = [];

        foreach ($sheet->sheetData->row as $row) {
            $rowNumber = (int) $row['r'];
            $cells = [];

            foreach ($row->c as $cell) {
                $ref = (string) $cell['r'];
                if (! preg_match('/^([A-Z]+)(\d+)$/', $ref, $m)) {
                    continue;
                }

                $type = (string) $cell['t'];
                $raw = isset($cell->v) ? (string) $cell->v : null;

                $value = match ($type) {
                    's' => $shared[(int) $raw] ?? null,
                    'inlineStr' => (string) ($cell->is->t ?? ''),
                    'str' => $raw,
                    'b' => $raw === '1',
                    'e' => null,
                    default => $raw === null ? null : (is_numeric($raw) ? $raw + 0 : $raw),
                };

                // Sel rumus tanpa hasil tersimpan: tandai agar bisa diperingatkan.
                if ($raw === null && isset($cell->f) && $type !== 'inlineStr') {
                    $value = new FormulaWithoutValue();
                }

                $cells[static::columnIndex($m[1])] = $value;
            }

            $rows[$rowNumber] = $cells;
        }

        return $rows;
    }

    /** "A" => 0, "B" => 1, ..., "AA" => 26 */
    public static function columnIndex(string $letters): int
    {
        $index = 0;
        foreach (str_split($letters) as $char) {
            $index = $index * 26 + (ord($char) - 64);
        }

        return $index - 1;
    }
}
