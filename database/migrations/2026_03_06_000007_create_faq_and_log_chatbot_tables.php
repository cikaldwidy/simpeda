<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_chatbot', function (Blueprint $table) {
            $table->increments('id_faq');
            $table->string('pertanyaan', 255);
            $table->text('jawaban');
            $table->string('kategori', 50)->default('umum');
            $table->tinyInteger('is_aktif')->default(1);
        });

        Schema::create('log_chatbot', function (Blueprint $table) {
            $table->increments('id_log');
            $table->string('sesi_id', 100);
            $table->text('pertanyaan_user');
            $table->text('jawaban_bot')->nullable();
            $table->dateTime('waktu_interaksi');
            $table->unsignedBigInteger('id_permohonan')->nullable();
            $table->unsignedInteger('id_faq')->nullable();

            $table->foreign('id_faq')->references('id_faq')->on('faq_chatbot')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_chatbot');
        Schema::dropIfExists('faq_chatbot');
    }
};
