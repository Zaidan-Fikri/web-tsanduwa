<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index()
    {
        return view('admin.program.index', ['items' => Program::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.program.form', ['item' => new Program(['icon' => 'fa-star', 'activities' => [], 'infos' => []])]);
    }

    public function store(Request $request)
    {
        Program::create($this->validated($request));

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('admin.program.form', ['item' => $program]);
    }

    public function update(Request $request, Program $program)
    {
        $program->update($this->validated($request, $program));

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return back()->with('success', 'Program berhasil dihapus.');
    }

    private function validated(Request $request, ?Program $program = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('menu_name'))]);
        $icon = ['nullable', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'];

        $data = $request->validate([
            'menu_name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'alpha_dash', 'max:60', Rule::unique('programs', 'slug')->ignore($program)],
            'title' => ['required', 'string', 'max:150'],
            'icon' => ['required', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'description' => ['nullable', 'string', 'max:1500'],
            'activities' => ['nullable', 'array', 'max:12'],
            'activities.*.icon' => $icon,
            'activities.*.title' => ['nullable', 'string', 'max:100'],
            'activities.*.description' => ['nullable', 'string', 'max:300'],
            'infos' => ['nullable', 'array', 'max:12'],
            'infos.*.icon' => $icon,
            'infos.*.text' => ['nullable', 'string', 'max:150'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], ['slug.unique' => 'Alamat (slug) ini sudah dipakai program lain.']);

        $data['activities'] = collect($data['activities'] ?? [])
            ->filter(fn ($row) => filled($row['title'] ?? null))
            ->map(fn ($row) => [
                'icon' => $row['icon'] ?? 'fa-star',
                'title' => $row['title'],
                'description' => $row['description'] ?? '',
            ])->values()->all();

        $data['infos'] = collect($data['infos'] ?? [])
            ->filter(fn ($row) => filled($row['text'] ?? null))
            ->map(fn ($row) => ['icon' => $row['icon'] ?? 'fa-circle-info', 'text' => $row['text']])
            ->values()->all();

        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
