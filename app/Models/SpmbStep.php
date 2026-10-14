<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmbStep extends Model
{
    protected $table = 'spmb_steps';

    protected $fillable = ['title', 'description', 'icon', 'badge', 'is_highlight', 'sort_order'];

    protected $casts = ['is_highlight' => 'boolean'];
}
