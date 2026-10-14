<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        return view('admin.sdm.index', [
            'items' => Staff::query()
                ->when($request->query('q'), fn ($q, $s) => $q->where(fn ($w) => $w
                    ->where('name', 'like', '%'.$s.'%')->orWhere('position', 'like', '%'.$s.'%')))
                ->when($request->query('type'), fn ($q, $t) => $q->where('type', $t))
                ->orderByRaw("CASE type WHEN 'pimpinan' THEN 1 WHEN 'guru' THEN 2 ELSE 3 END")
                ->orderBy('sort_order')->orderBy('name')
                ->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.sdm.form', ['item' => new Staff(['type' => 'guru', 'show_on_home' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->storeImage($request, 'photo', null, 'sdm');
        Staff::create($data);

        return redirect()->route('admin.sdm.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Staff $staff)
    {
        return view('admin.sdm.form', ['item' => $staff]);
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->storeImage($request, 'photo', $staff->photo, 'sdm');
        $staff->update($data);

        return redirect()->route('admin.sdm.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        $this->deleteImage($staff->photo);
        $staff->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(array_keys(Staff::TYPES))],
            'photo' => $this->imageRules(),
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        unset($data['photo']);
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
