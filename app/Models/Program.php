<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';

    protected $fillable = ['slug', 'menu_name', 'title', 'icon', 'description', 'activities', 'infos', 'sort_order'];

    protected $casts = ['activities' => 'array', 'infos' => 'array'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
