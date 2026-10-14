<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Gambar yang tersimpan di database. Kolom gambar di tabel lain menyimpan path
 * "media/{id}.{ext}", yang dilayani oleh MediaController.
 */
class Media extends Model
{
    protected $fillable = ['mime', 'extension', 'size', 'width', 'height', 'checksum', 'data'];

    /** Jangan pernah ikut ter-serialize (isi file bisa ratusan KB). */
    protected $hidden = ['data'];

    /**
     * Simpan hasil ImageOptimizer::encode()/encodeFile() ke database.
     */
    public static function fromEncoded(array $image): self
    {
        return static::create([
            'mime' => $image['mime'],
            'extension' => $image['extension'],
            'size' => strlen($image['data']),
            'width' => $image['width'],
            'height' => $image['height'],
            'checksum' => md5($image['data']),
            'data' => $image['data'],
        ]);
    }

    public function path(): string
    {
        return 'media/'.$this->id.'.'.$this->extension;
    }

    /** Ambil id dari path "media/12.webp"; null jika bukan path media. */
    public static function idFromPath(?string $path): ?int
    {
        return $path && preg_match('#^media/(\d+)\.[a-z0-9]+$#', $path, $m) ? (int) $m[1] : null;
    }

    /**
     * Ukuran file terbesar yang bisa dikirim ke MySQL dalam satu query (max_allowed_packet),
     * dikurangi sedikit ruang untuk bagian query lainnya.
     */
    public static function maxStorableBytes(): int
    {
        static $limit = null;

        if ($limit === null) {
            $packet = in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)
                ? (int) DB::selectOne('SELECT @@max_allowed_packet AS size')->size
                : 64 * 1048576;
            $limit = max(0, $packet - 64 * 1024);
        }

        return $limit;
    }
}
