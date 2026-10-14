<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $fillable = ['name', 'category', 'icon', 'description', 'schedule', 'image', 'show_on_home', 'sort_order'];

    protected $casts = ['show_on_home' => 'boolean'];

    /** Kategori => [label, ikon filter] */
    public const CATEGORIES = [
        'agama' => ['Keagamaan & Seni', 'fa-mosque'],
        'olahraga' => ['Olahraga & Bela Diri', 'fa-person-running'],
        'sosial' => ['Kepemimpinan & Sosial', 'fa-people-group'],
    ];

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category][0] ?? ucfirst($this->category);
    }
}
