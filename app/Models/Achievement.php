<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['title', 'winner', 'rank', 'level', 'category', 'icon', 'year', 'image', 'is_featured', 'show_on_home'];

    protected $casts = [
        'is_featured' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    public const LEVELS = [
        'internasional' => 'Internasional',
        'nasional' => 'Nasional',
        'provinsi' => 'Provinsi',
        'kabupaten' => 'Kabupaten',
        'kecamatan' => 'Kecamatan',
    ];

    public function levelLabel(): string
    {
        return self::LEVELS[$this->level] ?? ucfirst($this->level);
    }

    /** Warna medali sesuai peringkat (emas / perak / perunggu). */
    public function medalClass(): string
    {
        return match (true) {
            str_contains($this->rank, '1') => 'from-amber-300 via-gold-400 to-gold-600 text-slate-950',
            str_contains($this->rank, '2') => 'from-slate-200 via-slate-300 to-slate-400 text-slate-800',
            str_contains($this->rank, '3') => 'from-orange-300 via-orange-400 to-orange-700 text-white',
            default => 'from-tosca-200 via-tosca-300 to-tosca-500 text-tosca-950',
        };
    }

    public function isFirstPlace(): bool
    {
        return (bool) preg_match('/\b1\b/', $this->rank);
    }
}
