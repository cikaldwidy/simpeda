<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE surat_pengajuans
            MODIFY jenis_surat ENUM('domisili', 'tidak_mampu', 'kematian', 'kelahiran') NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE surat_pengajuans
            MODIFY jenis_surat ENUM('domisili', 'tidak_mampu') NOT NULL
        ");
    }
};
