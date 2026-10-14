<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrgStructure;
use Illuminate\Http\Request;

class OrgStructureController extends Controller
{
    public function index()
    {
        return view('admin.struktur.index', [
            'items' => OrgStructure::orderBy('level')->orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.struktur.form', ['item' => new OrgStructure(['level' => 2])]);
    }

    public function store(Request $request)
    {
        OrgStructure::create($this->validated($request));

        return redirect()->route('admin.struktur.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(OrgStructure $orgStructure)
    {
        return view('admin.struktur.form', ['item' => $orgStructure]);
    }

    public function update(Request $request, OrgStructure $orgStructure)
    {
        $orgStructure->update($this->validated($request));

        return redirect()->route('admin.struktur.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(OrgStructure $orgStructure)
    {
        $orgStructure->delete();

        return back()->with('success', 'Jabatan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'position' => ['required', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:150'],
            'level' => ['required', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
