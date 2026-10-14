<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $type = $request->query('type') === 'video' ? 'video' : 'foto';

        return view('admin.galeri.index', [
            'type' => $type,
            'items' => Gallery::where('type', $type)->orderBy('sort_order')->latest('id')->paginate(18)->withQueryString(),
            'counts' => Gallery::selectRaw('type, count(*) as total')->groupBy('type')->pluck('total', 'type'),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.galeri.form', ['item' => new Gallery(['type' => $request->query('type') === 'video' ? 'video' : 'foto'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($data['type'] === 'foto') {
            $data['image'] = $this->storeImage($request, 'image', null, 'galeri');
        }
        Gallery::create($data);

        return redirect()->route('admin.galeri.index', ['type' => $data['type']])->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galeri.form', ['item' => $gallery]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validated($request, $gallery);
        if ($data['type'] === 'foto') {
            $data['image'] = $this->storeImage($request, 'image', $gallery->image, 'galeri');
        } else {
            $this->deleteImage($gallery->image);
            $data['image'] = null;
        }
        $gallery->update($data);

        return redirect()->route('admin.galeri.index', ['type' => $data['type']])->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->deleteImage($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Item galeri berhasil dihapus.');
    }

    private function validated(Request $request, ?Gallery $gallery = null): array
    {
        $type = $request->input('type');
        $needsImage = $type === 'foto' && ! $gallery?->image;

        $data = $request->validate([
            'type' => ['required', Rule::in(['foto', 'video'])],
            'title' => ['required', 'string', 'max:200'],
            'image' => $type === 'foto' ? $this->imageRules($needsImage) : ['nullable'],
            'youtube_url' => [Rule::requiredIf($type === 'video'), 'nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], ['youtube_url.required' => 'Link YouTube wajib diisi untuk video.']);

        $data['youtube_id'] = null;
        if ($type === 'video') {
            $data['youtube_id'] = youtube_id($data['youtube_url']);
            if (! $data['youtube_id']) {
                throw ValidationException::withMessages(['youtube_url' => 'Link YouTube tidak dikenali. Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx']);
            }
        }

        unset($data['image'], $data['youtube_url']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
