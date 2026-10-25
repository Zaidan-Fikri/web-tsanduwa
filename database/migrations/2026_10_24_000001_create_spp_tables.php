<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Riwayat upload file Excel tagihan.
        Schema::create('spp_imports', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->unsignedSmallInteger('sheet_count')->default(0);
            $table->unsignedInteger('row_count')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->date('billing_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Tagihan terkini per siswa (satu baris per NISN; upload berikutnya memperbarui).
        Schema::create('spp_bills', function (Blueprint $table) {
            $table->id();
            $table->char('nisn', 10)->unique();
            $table->string('name');
            $table->string('class_name', 10)->index();
            $table->string('homeroom')->nullable();
            $table->json('items');
            $table->unsignedSmallInteger('spp_months')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->string('note')->nullable();
            $table->string('school_year', 20)->nullable();
            $table->string('semester', 10)->nullable();
            $table->date('billing_date')->nullable();
            $table->string('billing_time', 20)->nullable();
            $table->foreignId('spp_import_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_bills');
        Schema::dropIfExists('spp_imports');
    }
};
