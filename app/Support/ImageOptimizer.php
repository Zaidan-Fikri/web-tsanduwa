<?php

namespace App\Support;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Kompres gambar upload: diperkecil sesuai kebutuhan tampilan, diubah ke WebP,
 * dan metadata EXIF (termasuk lokasi GPS dari foto HP) ikut terbuang.
 * Jika GD tidak tersedia atau gagal, isi file asli yang dipakai.
 */
class ImageOptimizer
{
    private const QUALITY = 80;

    private const EXTENSIONS = ['image/webp' => 'webp', 'image/png' => 'png', 'image/jpeg' => 'jpg'];

    /**
     * Sisi terpanjang maksimal (piksel) per menu, disesuaikan dengan ukuran tampil di website.
     */
    public const MAX_SIZES = [
        'slider' => 1920,
        'pengaturan' => 1920,   // termasuk brosur SPMB agar tulisannya tetap terbaca
        'galeri' => 1600,
        'berita' => 1280,
        'fasilitas' => 1280,
        'prestasi' => 1000,
        'unit-kerja' => 900,
        'ekskul' => 800,
        'sdm' => 800,
    ];

    public static function maxSizeFor(string $folder): int
    {
        return self::MAX_SIZES[$folder] ?? 1600;
    }

    /**
     * Kompres gambar upload dan kembalikan hasilnya (isi file + info) untuk disimpan.
     * Jika GD tidak tersedia atau kompres tidak membantu, isi file asli yang dikembalikan.
     *
     * @return array{data: string, mime: string, extension: string, width: ?int, height: ?int}
     */
    public static function encode(UploadedFile $file, int $maxSize): array
    {
        return static::encodeFile($file->getRealPath(), $maxSize);
    }

    /**
     * Sama seperti encode(), untuk file gambar di disk (dipakai saat memindahkan gambar lama ke database).
     *
     * @return array{data: string, mime: string, extension: string, width: ?int, height: ?int}
     */
    public static function encodeFile(string $path, int $maxSize): array
    {
        $optimized = null;

        if (function_exists('imagecreatefromstring')) {
            try {
                $optimized = static::optimize($path, $maxSize);
            } catch (Throwable $e) {
                Log::warning('Kompres gambar gagal, memakai file asli', ['error' => $e->getMessage()]);
            }
        }

        $data = $optimized[0] ?? file_get_contents($path);
        $info = @getimagesizefromstring($data) ?: [null, null, 'mime' => null];
        $mime = $info['mime'] ?? 'application/octet-stream';

        return [
            'data' => $data,
            'mime' => $mime,
            'extension' => self::EXTENSIONS[$mime] ?? 'bin',
            'width' => $info[0],
            'height' => $info[1],
        ];
    }

    /**
     * @return array{0: string, 1: string}|null [isi file, ekstensi]
     */
    private static function optimize(string $sourcePath, int $maxSize): ?array
    {
        $info = @getimagesize($sourcePath);
        if (! $info || ! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return null;
        }

        [$width, $height, $type] = $info;

        // Foto 48 MP butuh memori besar saat dibuka; naikkan batas sementara.
        if ($width * $height > 20_000_000) {
            @ini_set('memory_limit', '512M');
        }

        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
        };

        if (! $image instanceof GdImage) {
            return null;
        }

        if ($type === IMAGETYPE_JPEG) {
            $image = static::fixOrientation($image, $sourcePath);
        }

        $wasResized = max($width, $height) > $maxSize;
        $image = static::resize($image, $maxSize);
        $hasAlpha = $type !== IMAGETYPE_JPEG;

        ob_start();
        if (function_exists('imagewebp')) {
            imagesavealpha($image, $hasAlpha);
            imagewebp($image, null, self::QUALITY);
            $extension = 'webp';
        } elseif ($hasAlpha) {
            imagesavealpha($image, true);
            imagepng($image, null, 8);
            $extension = 'png';
        } else {
            imageinterlace($image, true);
            imagejpeg($image, null, 82);
            $extension = 'jpg';
        }
        $binary = ob_get_clean();
        imagedestroy($image);

        // Gambar asli sudah kecil & teroptimasi: hasil kompres tidak boleh lebih besar.
        if ($binary === '' || (! $wasResized && strlen($binary) >= filesize($sourcePath))) {
            return null;
        }

        return [$binary, $extension];
    }

    /** Kecilkan agar sisi terpanjang <= $maxSize (tidak pernah memperbesar). */
    private static function resize(GdImage $image, int $maxSize): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = $maxSize / max($width, $height);

        if ($scale >= 1) {
            return $image;
        }

        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }

    /** Foto HP sering tersimpan miring dengan info rotasi di EXIF; putar sesuai aslinya. */
    private static function fixOrientation(GdImage $image, string $path): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = (int) (@exif_read_data($path)['Orientation'] ?? 1);

        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => null,
        };

        if ($rotated instanceof GdImage) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }
}
