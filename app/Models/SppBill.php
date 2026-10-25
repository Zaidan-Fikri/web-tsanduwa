<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SppBill extends Model
{
    protected $fillable = [
        'nisn', 'access_code', 'name', 'class_name', 'homeroom', 'items', 'spp_months', 'total', 'note',
        'school_year', 'semester', 'billing_date', 'billing_time', 'spp_import_id',
    ];

    protected $casts = [
        'items' => 'array',
        'billing_date' => 'date',
        'total' => 'integer',
    ];

    public function sppImport(): BelongsTo
    {
        return $this->belongsTo(SppImport::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SppPayment::class)->latest('paid_at')->latest('id');
    }

    /**
     * Pembayaran yang dicatat SETELAH data Excel terakhir diupload.
     * Pembayaran sebelumnya dianggap sudah tercakup dalam angka di file Excel terbaru.
     */
    public function paymentsSinceImport()
    {
        return $this->payments()->where('type', 'pembayaran')
            ->when($this->sppImport, fn ($q) => $q->where('created_at', '>=', $this->sppImport->created_at));
    }

    /** Kode akses untuk ditampilkan, mis. "K7X-2MP". */
    public function accessCodeLabel(): string
    {
        return \App\Support\Spp\AccessCode::format($this->access_code);
    }

    public function isPaidOff(): bool
    {
        return $this->total <= 0;
    }

    /** Hanya komponen yang nilainya lebih dari 0 (untuk ditampilkan ke wali murid). */
    public function outstandingItems(): array
    {
        return array_values(array_filter($this->items ?? [], fn ($item) => ($item['amount'] ?? 0) > 0));
    }

    public function billingDateLabel(): ?string
    {
        if (! $this->billing_date) {
            return null;
        }

        return $this->billing_date->translatedFormat('d F Y').($this->billing_time ? ', '.$this->billing_time : '');
    }
}
