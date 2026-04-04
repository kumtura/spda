<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_detail_usaha', function (Blueprint $table) {
            $table->integer('jumlah_tk_total')->nullable()->after('google_maps');
            $table->integer('jumlah_tk_bali')->nullable()->after('jumlah_tk_total');
            $table->integer('jumlah_tk_lokal')->nullable()->after('jumlah_tk_bali');
        });
    }

    public function down(): void
    {
        Schema::table('tb_detail_usaha', function (Blueprint $table) {
            $table->dropColumn(['jumlah_tk_total', 'jumlah_tk_bali', 'jumlah_tk_lokal']);
        });
    }
};
