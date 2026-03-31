<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to string
        DB::statement("ALTER TABLE tb_tiket_wisata MODIFY metode_pembayaran VARCHAR(50) NULL");
    }

    public function down(): void
    {
        // Revert back to enum
        DB::statement("ALTER TABLE tb_tiket_wisata MODIFY metode_pembayaran ENUM('xendit', 'transfer_manual', 'cash') NULL");
    }
};
