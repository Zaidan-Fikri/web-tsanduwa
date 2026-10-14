<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menampilkan gambar yang tersimpan di database.
 */
class MediaController extends Controller
{
    public function show(Request $request, int $id, string $extension): Response
    {
        $meta = Media::query()->select(['id', 'mime', 'extension', 'size', 'checksum', 'updated_at'])->find($id);
        abort_if(! $meta || $meta->extension !== $extension, 404);

        $etag = '"'.$meta->checksum.'"';
        $headers = [
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'ETag' => $etag,
            'Content-Security-Policy' => "default-src 'none'",
        ];

        // Browser sudah punya gambarnya: tidak perlu kirim ulang isinya.
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304, $headers);
        }

        $data = Media::whereKey($id)->value('data');

        return response($data, 200, $headers + [
            'Content-Type' => $meta->mime,
            'Content-Length' => strlen($data),
        ]);
    }
}
