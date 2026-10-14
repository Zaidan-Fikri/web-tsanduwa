<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    protected $table = 'work_units';

    protected $fillable = ['slug', 'position', 'field_title', 'person_name', 'photo', 'icon', 'description', 'programs', 'sort_order'];

    protected $casts = ['programs' => 'array'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
