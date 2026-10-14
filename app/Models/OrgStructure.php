<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgStructure extends Model
{
    protected $table = 'org_structures';

    protected $fillable = ['position', 'name', 'level', 'sort_order'];
}
