<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpmbRequirement;
use Illuminate\Http\Request;

class SpmbRequirementController extends Controller
{
    public function index()
    {
        return view('admin.spmb-persyaratan.index', [
            'items' => SpmbRequirement::orderByDesc('is_required')->orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.spmb-persyaratan.form', ['item' => new SpmbRequirement([
            'is_required' => true,
            'sort_order' => SpmbRequirement::max('sort_order') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        SpmbRequirement::create($this->validated($request));

        return redirect()->route('admin.spmb-persyaratan.index')->with('success', 'Persyaratan berhasil ditambahkan.');
    }

    public function edit(SpmbRequirement $spmbRequirement)
    {
        return view('admin.spmb-persyaratan.form', ['item' => $spmbRequirement]);
    }

    public function update(Request $request, SpmbRequirement $spmbRequirement)
    {
        $spmbRequirement->update($this->validated($request));

        return redirect()->route('admin.spmb-persyaratan.index')->with('success', 'Persyaratan berhasil diperbarui.');
    }

    public function destroy(SpmbRequirement $spmbRequirement)
    {
        $spmbRequirement->delete();

        return back()->with('success', 'Persyaratan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['is_required'] = $request->boolean('is_required');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
