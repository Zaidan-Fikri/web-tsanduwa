<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gambar yang diupload dari panel admin disimpan di tabel ini (bukan di folder).
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('mime', 50);
            $table->string('extension', 10);
            $table->unsignedInteger('size');
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->char('checksum', 32);
            $table->timestamps();
        });

        // BLOB biasa di MySQL hanya 64 KB, jadi pakai LONGBLOB.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `media` ADD `data` LONGBLOB NOT NULL');
        } else {
            Schema::table('media', fn (Blueprint $table) => $table->binary('data'));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
