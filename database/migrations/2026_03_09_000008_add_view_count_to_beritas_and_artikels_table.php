<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')->default(0)->after('is_published');
        });

        Schema::table('artikels', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')->default(0)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });

        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
};

