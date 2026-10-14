<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Media;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait HandlesUploads
{
    /** Aturan validasi standar untuk upload gambar (SVG sengaja tidak diizinkan karena bisa berisi script). */
    protected function imageRules(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240',
            'dimensions:max_width=12000,max_height=12000',
        ];
    }

    /**
     * Kompres gambar baru (jika ada), simpan ke database, lalu hapus gambar lama.
     * Mengembalikan path yang disimpan di kolom gambar, mis. "media/12.webp".
     * $folder menentukan ukuran maksimal (lihat ImageOptimizer::MAX_SIZES).
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

        $image = ImageOptimizer::encode($request->file($field), ImageOptimizer::maxSizeFor($folder));

        $limit = Media::maxStorableBytes();
        if (strlen($image['data']) > $limit) {
            throw ValidationException::withMessages([
                $field => 'Gambar masih terlalu besar untuk disimpan di database (maks. '.round($limit / 1048576, 1).' MB setelah dikompres). Gunakan gambar dengan resolusi lebih kecil.',
            ]);
        }

        $media = Media::fromEncoded($image);
        $this->deleteImage($oldPath);

        return $media->path();
    }

    protected function deleteImage(?string $path): void
    {
        if ($id = Media::idFromPath($path)) {
            Media::whereKey($id)->delete();
        } elseif ($path && str_starts_with($path, 'uploads/')) {
            // Gambar lama dari versi sebelumnya yang masih tersimpan sebagai file.
            Storage::disk('uploads')->delete(substr($path, strlen('uploads/')));
        }
    }
}
