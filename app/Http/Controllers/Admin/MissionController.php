<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index()
    {
        return view('admin.misi.index', ['items' => Mission::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.misi.form', ['item' => new Mission(['sort_order' => Mission::max('sort_order') + 1])]);
    }

    public function store(Request $request)
    {
        Mission::create($this->validated($request));

        return redirect()->route('admin.misi.index')->with('success', 'Misi berhasil ditambahkan.');
    }

    public function edit(Mission $mission)
    {
        return view('admin.misi.form', ['item' => $mission]);
    }

    public function update(Request $request, Mission $mission)
    {
        $mission->update($this->validated($request));

        return redirect()->route('admin.misi.index')->with('success', 'Misi berhasil diperbarui.');
    }

    public function destroy(Mission $mission)
    {
        $mission->delete();

        return back()->with('success', 'Misi berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
