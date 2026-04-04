<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            $table->string('nama_pengunjung')->nullable()->after('id_objek_wisata');
            $table->string('no_wa')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            $table->dropColumn(['nama_pengunjung', 'no_wa']);
        });
    }
};
