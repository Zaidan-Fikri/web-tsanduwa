<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'category', 'image', 'excerpt', 'content', 'published_at', 'is_published'];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    /** Kategori => [label, kelas badge] */
    public const CATEGORIES = [
        'pengumuman' => ['Pengumuman', 'bg-tosca-100 text-tosca-800'],
        'prestasi' => ['Prestasi', 'bg-amber-100 text-amber-800'],
        'kegiatan' => ['Kegiatan', 'bg-emerald-100 text-emerald-800'],
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->whereDate('published_at', '<=', now());
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category][0] ?? ucfirst($this->category);
    }

    public function badgeClass(): string
    {
        return self::CATEGORIES[$this->category][1] ?? self::CATEGORIES['kegiatan'][1];
    }

    public function dateLabel(): string
    {
        return $this->published_at->locale('id')->translatedFormat('d F Y');
    }

    public function summary(int $limit = 160): string
    {
        return $this->excerpt ?: Str::limit(preg_replace('/\s+/', ' ', $this->content), $limit);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
