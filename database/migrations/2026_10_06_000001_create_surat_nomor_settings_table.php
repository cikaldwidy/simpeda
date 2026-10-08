<?php

use App\Models\SuratNomorSetting;
use App\Models\SuratPengajuan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_nomor_settings', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat')->unique();
            $table->string('kode_klasifikasi', 50);
            $table->string('kode_wilayah', 50);
            $table->timestamps();
        });

        foreach (array_keys(SuratPengajuan::jenisOptions()) as $jenisSurat) {
            SuratNomorSetting::create(SuratNomorSetting::defaultsFor($jenisSurat));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_nomor_settings');
    }
};
