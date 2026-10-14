<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.fasilitas.index', ['items' => Facility::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.fasilitas.form', ['item' => new Facility()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image'] = $this->storeImage($request, 'image', null, 'fasilitas');
        Facility::create($data);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        return view('admin.fasilitas.form', ['item' => $facility]);
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $this->validated($request, false);
        $data['image'] = $this->storeImage($request, 'image', $facility->image, 'fasilitas');
        $facility->update($data);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        $this->deleteImage($facility->image);
        $facility->delete();

        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }

    private function validated(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => $this->imageRules($creating),
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        unset($data['image']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
