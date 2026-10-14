<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = ['type', 'title', 'image', 'youtube_id', 'sort_order'];
}
