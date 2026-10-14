<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.agenda.index', [
            'items' => Agenda::query()
                ->when($request->query('q'), fn ($q, $s) => $q->where('title', 'like', '%'.$s.'%'))
                ->orderByDesc('start_date')->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.agenda.form', ['item' => new Agenda(['category' => 'kegiatan'])]);
    }

    public function store(Request $request)
    {
        Agenda::create($this->validated($request));

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        return view('admin.agenda.form', ['item' => $agenda]);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $agenda->update($this->validated($request));

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return back()->with('success', 'Agenda berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'category' => ['required', Rule::in(array_keys(Agenda::CATEGORIES))],
        ], ['end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.']);
    }
}
