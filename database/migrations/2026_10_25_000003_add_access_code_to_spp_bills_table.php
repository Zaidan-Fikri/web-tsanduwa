<?php

use App\Support\Spp\AccessCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kode akses per siswa untuk cek tagihan (NISN + kode akses).
     */
    public function up(): void
    {
        Schema::table('spp_bills', function (Blueprint $table) {
            $table->char('access_code', 6)->nullable()->unique()->after('nisn');
        });

        // Siswa yang sudah ada langsung dibuatkan kode.
        $taken = [];
        DB::table('spp_bills')->whereNull('access_code')->orderBy('id')->select('id')
            ->chunkById(200, function ($rows) use (&$taken) {
                foreach ($rows as $row) {
                    DB::table('spp_bills')->where('id', $row->id)->update(['access_code' => AccessCode::generate($taken)]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('spp_bills', function (Blueprint $table) {
            $table->dropUnique(['access_code']);
            $table->dropColumn('access_code');
        });
    }
};
