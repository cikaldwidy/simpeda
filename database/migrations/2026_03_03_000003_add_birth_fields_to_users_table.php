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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->after('no_hp');
            $table->unsignedTinyInteger('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->unsignedTinyInteger('bulan_lahir')->nullable()->after('tanggal_lahir');
            $table->unsignedSmallInteger('tahun_lahir')->nullable()->after('bulan_lahir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'bulan_lahir',
                'tahun_lahir',
            ]);
        });
    }
};
