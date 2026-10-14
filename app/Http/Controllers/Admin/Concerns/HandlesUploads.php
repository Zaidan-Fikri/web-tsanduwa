<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    /**
     * Sisi terpanjang maksimal (piksel) per folder, disesuaikan dengan ukuran tampil di website.
     * Gambar yang lebih besar otomatis diperkecil lalu dikompres ke WebP.
     */
    protected array $imageMaxSizes = [
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

    /** Aturan validasi standar untuk upload gambar (SVG sengaja tidak diizinkan karena bisa berisi script). */
    protected function imageRules(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240',
            'dimensions:max_width=12000,max_height=12000',
        ];
    }

    /**
     * Simpan gambar baru (jika ada) ke public/uploads/{folder} lalu hapus gambar lama.
     * Mengembalikan path yang disimpan di database, mis. "uploads/berita/abc.webp".
     */
    protected function storeImage(Request $request, string $field, ?string $oldPath, string $folder): ?string
    {
        if ($request->boolean("remove_{$field}") && ! $request->hasFile($field)) {
            $this->deleteImage($oldPath);

            return null;
        }

        if (! $request->hasFile($field)) {
            return $oldPath;
        }

        $stored = ImageOptimizer::store($request->file($field), $folder, $this->imageMaxSizes[$folder] ?? 1600);
        $this->deleteImage($oldPath);

        return 'uploads/'.$stored;
    }

    protected function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/')) {
            Storage::disk('uploads')->delete(substr($path, strlen('uploads/')));
        }
    }
}
