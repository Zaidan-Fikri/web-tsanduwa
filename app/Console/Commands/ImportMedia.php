<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\Setting;
use App\Support\ImageOptimizer;
use App\Support\SettingGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Memindahkan gambar konten yang masih berupa file (public/img, public/uploads) atau
 * link luar (Unsplash, dll.) ke tabel media, sekaligus dikompres. Aman dijalankan berulang:
 * gambar yang sudah di database ("media/...") dilewati.
 */
class ImportMedia extends Command
{
    protected $signature = 'media:import {--dry-run : Hanya tampilkan apa yang akan dipindahkan}';

    protected $description = 'Pindahkan & kompres gambar konten (file / link luar) ke database';

    /** tabel => [kolom gambar, ukuran (lihat ImageOptimizer::MAX_SIZES)] */
    private const TABLES = [
        'hero_slides' => ['image', 'slider'],
        'facilities' => ['image', 'fasilitas'],
        'work_units' => ['photo', 'unit-kerja'],
        'staff' => ['photo', 'sdm'],
        'extracurriculars' => ['image', 'ekskul'],
        'posts' => ['image', 'berita'],
        'achievements' => ['image', 'prestasi'],
        'galleries' => ['image', 'galeri'],
    ];

    private int $imported = 0;

    private int $failed = 0;

    public function handle(): int
    {
        foreach (self::TABLES as $table => [$column, $folder]) {
            $rows = DB::table($table)->whereNotNull($column)->where($column, '!=', '')
                ->where($column, 'not like', 'media/%')->get(['id', $column]);

            foreach ($rows as $row) {
                $newPath = $this->import($row->{$column}, $folder, "{$table}#{$row->id}");
                if ($newPath) {
                    DB::table($table)->where('id', $row->id)->update([$column => $newPath]);
                }
            }
        }

        // Gambar di menu Pengaturan (foto kepala madrasah, brosur SPMB, sampul video, dll.)
        $settings = [];
        foreach (SettingGroups::all() as $group) {
            foreach ($group['fields'] as $key => $field) {
                $value = setting($key);
                if ($field['type'] === 'image' && $value && ! Media::idFromPath($value)) {
                    if ($newPath = $this->import($value, 'pengaturan', "setting {$key}")) {
                        $settings[$key] = $newPath;
                    }
                }
            }
        }
        if ($settings) {
            Setting::setMany($settings);
        }

        $verb = $this->option('dry-run') ? 'akan dipindahkan' : 'dipindahkan ke database';
        $this->info("Selesai: {$this->imported} gambar {$verb}, {$this->failed} gagal.");

        return $this->failed ? self::FAILURE : self::SUCCESS;
    }

    /** Kembalikan path media baru, atau null jika dilewati/gagal. */
    private function import(string $source, string $folder, string $label): ?string
    {
        if ($this->option('dry-run')) {
            $this->line("  - {$label}: {$source}");
            $this->imported++;

            return null;
        }

        $temp = null;

        try {
            if (preg_match('#^https?://#i', $source)) {
                $response = Http::timeout(30)->retry(2, 1000)->get($source);
                if (! $response->successful() || ! str_starts_with((string) $response->header('Content-Type'), 'image/')) {
                    throw new \RuntimeException('gagal mengunduh (HTTP '.$response->status().')');
                }
                $temp = tempnam(sys_get_temp_dir(), 'img');
                file_put_contents($temp, $response->body());
                $path = $temp;
            } else {
                $path = public_path(ltrim($source, '/'));
                if (! is_file($path)) {
                    throw new \RuntimeException('file tidak ditemukan');
                }
            }

            if (! @getimagesize($path)) {
                throw new \RuntimeException('bukan file gambar');
            }

            $image = ImageOptimizer::encodeFile($path, ImageOptimizer::maxSizeFor($folder));
            if (strlen($image['data']) > Media::maxStorableBytes()) {
                throw new \RuntimeException('terlalu besar untuk max_allowed_packet MySQL');
            }

            $media = Media::fromEncoded($image);

            // File upload lama dari panel admin tidak diperlukan lagi.
            if (str_starts_with($source, 'uploads/')) {
                @unlink($path);
            }

            $this->line(sprintf('  <info>✓</info> %s: %s → %s (%s KB)', $label, $source, $media->path(), round($media->size / 1024)));
            $this->imported++;

            return $media->path();
        } catch (Throwable $e) {
            $this->line("  <error>✗</error> {$label}: {$source} — {$e->getMessage()}");
            $this->failed++;

            return null;
        } finally {
            if ($temp) {
                @unlink($temp);
            }
        }
    }
}
