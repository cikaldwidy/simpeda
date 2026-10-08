<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_anggarans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->unsignedSmallInteger('tahun');
            $table->string('jenis_laporan', 80);
            $table->string('template', 40)->default('infografis');
            $table->text('keterangan')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'tahun']);
        });

        Schema::create('anggaran_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_anggaran_id')->constrained('laporan_anggarans')->cascadeOnDelete();
            $table->string('jenis', 20);
            $table->string('kategori', 120);
            $table->string('uraian');
            $table->decimal('jumlah', 15, 2);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['laporan_anggaran_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran_items');
        Schema::dropIfExists('laporan_anggarans');
    }
};
