<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            // Drop columns that are no longer needed
            $table->dropColumn(['nama_pengunjung', 'no_telp', 'jumlah_tiket']);
            
            // Make email nullable (optional)
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            $table->string('nama_pengunjung')->nullable();
            $table->string('no_telp')->nullable();
            $table->integer('jumlah_tiket')->default(1);
        });
    }
};
