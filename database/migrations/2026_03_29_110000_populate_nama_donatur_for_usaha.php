<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Populate nama_donatur for existing unit usaha payments
        DB::statement("
            UPDATE tb_dana_punia 
            SET nama_donatur = (
                SELECT nama_usaha 
                FROM tb_detail_usaha 
                JOIN tb_usaha ON tb_usaha.id_detail_usaha = tb_detail_usaha.id_detail_usaha 
                WHERE tb_usaha.id_usaha = tb_dana_punia.id_usaha 
                LIMIT 1
            )
            WHERE id_usaha IS NOT NULL 
            AND (nama_donatur IS NULL OR nama_donatur = '')
        ");
    }

    public function down(): void
    {
        // No need to revert
    }
};
