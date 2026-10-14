<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmbRequirement extends Model
{
    protected $table = 'spmb_requirements';

    protected $fillable = ['name', 'is_required', 'sort_order'];

    protected $casts = ['is_required' => 'boolean'];
}
