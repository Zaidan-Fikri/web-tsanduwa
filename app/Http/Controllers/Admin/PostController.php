<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        return view('admin.berita.index', [
            'items' => Post::query()
                ->when($request->query('q'), fn ($q, $s) => $q->where('title', 'like', '%'.$s.'%'))
                ->when($request->query('category'), fn ($q, $c) => $q->where('category', $c))
                ->latest('published_at')->latest('id')
                ->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.berita.form', ['item' => new Post([
            'category' => 'kegiatan',
            'published_at' => today(),
            'is_published' => true,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Post::uniqueSlug($data['title']);
        $data['image'] = $this->storeImage($request, 'image', null, 'berita');
        Post::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diterbitkan.');
    }

    public function edit(Post $post)
    {
        return view('admin.berita.form', ['item' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request);
        if ($post->title !== $data['title']) {
            $data['slug'] = Post::uniqueSlug($data['title'], $post->id);
        }
        $data['image'] = $this->storeImage($request, 'image', $post->image, 'berita');
        $post->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $this->deleteImage($post->image);
        $post->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(array_keys(Post::CATEGORIES))],
            'published_at' => ['required', 'date'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string', 'max:50000'],
            'image' => $this->imageRules(),
        ]);

        unset($data['image']);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
