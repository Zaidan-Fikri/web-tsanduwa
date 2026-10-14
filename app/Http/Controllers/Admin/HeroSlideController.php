<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.slider.index', [
            'items' => HeroSlide::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.slider.form', ['item' => new HeroSlide(['is_active' => true, 'button_text' => 'Selengkapnya'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image'] = $this->storeImage($request, 'image', null, 'slider');
        HeroSlide::create($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil ditambahkan.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.slider.form', ['item' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $data = $this->validated($request, false);
        $data['image'] = $this->storeImage($request, 'image', $heroSlide->image, 'slider');
        $heroSlide->update($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $this->deleteImage($heroSlide->image);
        $heroSlide->delete();

        return back()->with('success', 'Slide berhasil dihapus.');
    }

    private function validated(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => $this->imageRules($creating),
            'button_text' => ['nullable', 'string', 'max:50'],
            // Hanya link internal (/...) atau http(s) — mencegah "javascript:" dsb.
            'button_url' => ['nullable', 'string', 'max:255', 'regex:#^(https?://\S+|/(?!/)\S*)$#i'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], ['button_url.regex' => 'Link harus diawali "/" (halaman website ini) atau "https://".']);

        unset($data['image']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
