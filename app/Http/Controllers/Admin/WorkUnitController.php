<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WorkUnitController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.unit-kerja.index', ['items' => WorkUnit::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.unit-kerja.form', ['item' => new WorkUnit(['icon' => 'fa-briefcase', 'programs' => []])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->storeImage($request, 'photo', null, 'unit-kerja');
        WorkUnit::create($data);

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit(WorkUnit $workUnit)
    {
        return view('admin.unit-kerja.form', ['item' => $workUnit]);
    }

    public function update(Request $request, WorkUnit $workUnit)
    {
        $data = $this->validated($request, $workUnit);
        $data['photo'] = $this->storeImage($request, 'photo', $workUnit->photo, 'unit-kerja');
        $workUnit->update($data);

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(WorkUnit $workUnit)
    {
        $this->deleteImage($workUnit->photo);
        $workUnit->delete();

        return back()->with('success', 'Unit kerja berhasil dihapus.');
    }

    private function validated(Request $request, ?WorkUnit $unit = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('position'))]);

        $data = $request->validate([
            'position' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'alpha_dash', 'max:60', Rule::unique('work_units', 'slug')->ignore($unit)],
            'field_title' => ['required', 'string', 'max:150'],
            'person_name' => ['required', 'string', 'max:150'],
            'photo' => $this->imageRules(),
            'icon' => ['required', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'description' => ['nullable', 'string', 'max:1000'],
            'programs' => ['nullable', 'array', 'max:12'],
            'programs.*' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], ['slug.unique' => 'Alamat (slug) ini sudah dipakai unit lain.']);

        unset($data['photo']);
        $data['programs'] = array_values(array_filter($data['programs'] ?? [], 'filled'));
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
