<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `provinsi_id` VARCHAR(100) NULL");
        DB::statement("ALTER TABLE `users` MODIFY `kabupaten_id` VARCHAR(100) NULL");
        DB::statement("ALTER TABLE `users` MODIFY `kecamatan_id` VARCHAR(100) NULL");
        DB::statement("ALTER TABLE `users` MODIFY `desa_id` VARCHAR(100) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `provinsi_id` VARCHAR(10) NULL");
        DB::statement("ALTER TABLE `users` MODIFY `kabupaten_id` VARCHAR(10) NULL");
        DB::statement("ALTER TABLE `users` MODIFY `kecamatan_id` VARCHAR(15) NULL");
        DB::statement("ALTER TABLE `users` MODIFY `desa_id` VARCHAR(20) NULL");
    }
};
