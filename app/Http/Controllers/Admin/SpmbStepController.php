<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpmbStep;
use Illuminate\Http\Request;

class SpmbStepController extends Controller
{
    public function index()
    {
        return view('admin.spmb-prosedur.index', ['items' => SpmbStep::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.spmb-prosedur.form', ['item' => new SpmbStep([
            'icon' => 'fa-file-invoice',
            'sort_order' => SpmbStep::max('sort_order') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        SpmbStep::create($this->validated($request));

        return redirect()->route('admin.spmb-prosedur.index')->with('success', 'Langkah berhasil ditambahkan.');
    }

    public function edit(SpmbStep $spmbStep)
    {
        return view('admin.spmb-prosedur.form', ['item' => $spmbStep]);
    }

    public function update(Request $request, SpmbStep $spmbStep)
    {
        $spmbStep->update($this->validated($request));

        return redirect()->route('admin.spmb-prosedur.index')->with('success', 'Langkah berhasil diperbarui.');
    }

    public function destroy(SpmbStep $spmbStep)
    {
        $spmbStep->delete();

        return back()->with('success', 'Langkah berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['required', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'badge' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['is_highlight'] = $request->boolean('is_highlight');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
