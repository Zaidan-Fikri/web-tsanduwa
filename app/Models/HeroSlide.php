<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $table = 'hero_slides';

    protected $fillable = ['title', 'description', 'image', 'button_text', 'button_url', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
