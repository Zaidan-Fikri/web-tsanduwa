<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = ['name', 'position', 'type', 'photo', 'show_on_home', 'sort_order'];

    protected $casts = ['show_on_home' => 'boolean'];

    /** Jenis => [label, kelas badge, kelas latar foto] */
    public const TYPES = [
        'pimpinan' => ['Pimpinan', 'bg-gold-500 text-slate-950', 'bg-gradient-to-br from-tosca-400 via-tosca-600 to-tosca-800'],
        'guru' => ['Guru', 'bg-tosca-700 text-white', 'bg-tosca-100'],
        'tendik' => ['Tendik', 'bg-slate-700 text-white', 'bg-tosca-100'],
    ];

    public function typeLabel(): string
    {
        return self::TYPES[$this->type][0] ?? ucfirst($this->type);
    }

    public function badgeClass(): string
    {
        return self::TYPES[$this->type][1] ?? self::TYPES['guru'][1];
    }

    public function photoBgClass(): string
    {
        return self::TYPES[$this->type][2] ?? self::TYPES['guru'][2];
    }
}
