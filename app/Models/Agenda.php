<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = ['title', 'start_date', 'end_date', 'category'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /** Kategori => [label, kelas badge tanggal, kelas badge label] */
    public const CATEGORIES = [
        'ujian' => ['Ujian', 'bg-rose-50 text-rose-700', 'bg-rose-100 text-rose-700'],
        'semester' => ['Semester', 'bg-tosca-50 text-tosca-700', 'bg-tosca-100 text-tosca-700'],
        'libur' => ['Libur', 'bg-amber-50 text-gold-600', 'bg-amber-100 text-gold-600'],
        'kegiatan' => ['Kegiatan', 'bg-sky-50 text-sky-700', 'bg-sky-100 text-sky-700'],
    ];

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category][0] ?? ucfirst($this->category);
    }

    public function dateClass(): string
    {
        return self::CATEGORIES[$this->category][1] ?? self::CATEGORIES['kegiatan'][1];
    }

    public function badgeClass(): string
    {
        return self::CATEGORIES[$this->category][2] ?? self::CATEGORIES['kegiatan'][2];
    }

    /**
     * Teks tanggal besar dan kecil pada kartu agenda, mis. ["30 Nov", "– 9 Des"] atau ["18", "Des"].
     */
    public function dateParts(): array
    {
        $start = $this->start_date->locale('id');

        if (! $this->end_date || $this->end_date->isSameDay($this->start_date)) {
            return [$start->translatedFormat('j'), $start->translatedFormat('M')];
        }

        return [$start->translatedFormat('j M'), '– '.$this->end_date->locale('id')->translatedFormat('j M')];
    }
}
