<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExtracurricularController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        return view('admin.ekskul.index', [
            'items' => Extracurricular::query()
                ->when($request->query('category'), fn ($q, $c) => $q->where('category', $c))
                ->orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.ekskul.form', ['item' => new Extracurricular(['category' => 'agama', 'icon' => 'fa-star', 'show_on_home' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request, 'image', null, 'ekskul');
        Extracurricular::create($data);

        return redirect()->route('admin.ekskul.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function edit(Extracurricular $extracurricular)
    {
        return view('admin.ekskul.form', ['item' => $extracurricular]);
    }

    public function update(Request $request, Extracurricular $extracurricular)
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request, 'image', $extracurricular->image, 'ekskul');
        $extracurricular->update($data);

        return redirect()->route('admin.ekskul.index')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular)
    {
        $this->deleteImage($extracurricular->image);
        $extracurricular->delete();

        return back()->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', Rule::in(array_keys(Extracurricular::CATEGORIES))],
            'icon' => ['required', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'description' => ['nullable', 'string', 'max:500'],
            'schedule' => ['nullable', 'string', 'max:100'],
            'image' => $this->imageRules(),
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        unset($data['image']);
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
