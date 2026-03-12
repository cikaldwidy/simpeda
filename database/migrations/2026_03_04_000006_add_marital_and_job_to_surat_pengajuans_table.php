<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_pengajuans', function (Blueprint $table) {
            $table->string('status_perkawinan', 50)->nullable()->after('catatan');
            $table->string('pekerjaan', 100)->nullable()->after('status_perkawinan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_pengajuans', function (Blueprint $table) {
            $table->dropColumn(['status_perkawinan', 'pekerjaan']);
        });
    }
};
