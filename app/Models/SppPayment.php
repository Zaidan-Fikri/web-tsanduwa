<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppPayment extends Model
{
    protected $fillable = [
        'spp_bill_id', 'type', 'amount', 'allocations', 'total_before', 'total_after', 'paid_at', 'note', 'user_id',
    ];

    protected $casts = [
        'allocations' => 'array',
        'paid_at' => 'date',
        'amount' => 'integer',
        'total_before' => 'integer',
        'total_after' => 'integer',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(SppBill::class, 'spp_bill_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPayment(): bool
    {
        return $this->type === 'pembayaran';
    }
}
