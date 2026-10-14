<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AchievementController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        return view('admin.prestasi.index', [
            'items' => Achievement::query()
                ->when($request->query('q'), fn ($q, $s) => $q->where(fn ($w) => $w
                    ->where('title', 'like', '%'.$s.'%')->orWhere('winner', 'like', '%'.$s.'%')))
                ->when($request->query('level'), fn ($q, $l) => $q->where('level', $l))
                ->latest('year')->latest('id')
                ->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.prestasi.form', ['item' => new Achievement([
            'rank' => 'Juara 1', 'level' => 'kabupaten', 'icon' => 'fa-trophy', 'year' => now()->year,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request, 'image', null, 'prestasi');
        $achievement = Achievement::create($data);
        $this->keepSingleFeatured($achievement);

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.prestasi.form', ['item' => $achievement]);
    }

    public function update(Request $request, Achievement $achievement)
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request, 'image', $achievement->image, 'prestasi');
        $achievement->update($data);
        $this->keepSingleFeatured($achievement);

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement)
    {
        $this->deleteImage($achievement->image);
        $achievement->delete();

        return back()->with('success', 'Prestasi berhasil dihapus.');
    }

    /** Hanya satu prestasi yang tampil sebagai "Prestasi Unggulan". */
    private function keepSingleFeatured(Achievement $achievement): void
    {
        if ($achievement->is_featured) {
            Achievement::whereKeyNot($achievement->id)->where('is_featured', true)->update(['is_featured' => false]);
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'winner' => ['required', 'string', 'max:150'],
            'rank' => ['required', 'string', 'max:50'],
            'level' => ['required', Rule::in(array_keys(Achievement::LEVELS))],
            'category' => ['required', 'string', 'max:50'],
            'icon' => ['required', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'year' => ['required', 'integer', 'min:1980', 'max:'.(now()->year + 1)],
            'image' => $this->imageRules(),
        ]);

        unset($data['image']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['show_on_home'] = $request->boolean('show_on_home');

        return $data;
    }
}
