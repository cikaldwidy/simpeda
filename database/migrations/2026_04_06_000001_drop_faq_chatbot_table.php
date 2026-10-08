<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('log_chatbot') && Schema::hasColumn('log_chatbot', 'id_faq')) {
            Schema::table('log_chatbot', function (Blueprint $table) {
                $table->dropForeign(['id_faq']);
                $table->dropColumn('id_faq');
            });
        }

        Schema::dropIfExists('faq_chatbot');
    }

    public function down(): void
    {
        if (! Schema::hasTable('faq_chatbot')) {
            Schema::create('faq_chatbot', function (Blueprint $table) {
                $table->increments('id_faq');
                $table->string('pertanyaan', 255);
                $table->text('jawaban');
                $table->string('kategori', 50)->default('umum');
                $table->tinyInteger('is_aktif')->default(1);
            });
        }

        if (Schema::hasTable('log_chatbot') && ! Schema::hasColumn('log_chatbot', 'id_faq')) {
            Schema::table('log_chatbot', function (Blueprint $table) {
                $table->unsignedInteger('id_faq')->nullable();
                $table->foreign('id_faq')->references('id_faq')->on('faq_chatbot')->nullOnDelete();
            });
        }
    }
};
