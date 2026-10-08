<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('surat_pengajuans')
            ->where('status', '!=', 'disetujui')
            ->update([
                'nomor_surat' => null,
                'nomor_urut' => null,
            ]);
    }

    public function down(): void
    {
        //
    }
};
