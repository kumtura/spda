<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->integer('kapasitas_harian')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->integer('kapasitas_harian')->nullable(false)->change();
        });
    }
};
