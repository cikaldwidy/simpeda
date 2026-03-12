<?php

use App\Models\SuratPengajuan;
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
        Schema::create('surat_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis_surat', [
                SuratPengajuan::JENIS_DOMISILI,
                SuratPengajuan::JENIS_TIDAK_MAMPU,
                SuratPengajuan::JENIS_KEMATIAN,
                SuratPengajuan::JENIS_KELAHIRAN,
            ]);
            $table->string('nomor_surat')->unique();
            $table->unsignedInteger('nomor_urut');
            $table->unsignedSmallInteger('tahun');
            $table->string('perihal');
            $table->string('keperluan');
            $table->text('catatan')->nullable();
            $table->date('tanggal_surat');
            $table->string('status')->default('diajukan');
            $table->timestamps();

            $table->unique(['tahun', 'nomor_urut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuans');
    }
};
