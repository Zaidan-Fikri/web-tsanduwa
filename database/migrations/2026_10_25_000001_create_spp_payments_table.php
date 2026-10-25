<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat pembayaran (cicilan) & koreksi tagihan yang dicatat admin.
     */
    public function up(): void
    {
        Schema::create('spp_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spp_bill_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('pembayaran'); // pembayaran | koreksi
            $table->unsignedBigInteger('amount')->default(0);   // nominal dibayar (pembayaran)
            $table->json('allocations')->nullable();             // [{label, amount}] dibayar per jenis tagihan
            $table->unsignedBigInteger('total_before')->default(0);
            $table->unsignedBigInteger('total_after')->default(0);
            $table->date('paid_at');
            $table->string('note', 200)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_payments');
    }
};
