<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppImport extends Model
{
    protected $fillable = ['file_name', 'sheet_count', 'row_count', 'total_amount', 'billing_date', 'user_id'];

    protected $casts = ['billing_date' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
