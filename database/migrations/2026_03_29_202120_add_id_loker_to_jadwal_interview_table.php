<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_jadwal_interview', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_jadwal_interview', 'id_loker')) {
                $table->unsignedBigInteger('id_loker')->nullable()->after('id_usaha');
                $table->foreign('id_loker')->references('id_loker')->on('tb_loker')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_jadwal_interview', function (Blueprint $table) {
            if (Schema::hasColumn('tb_jadwal_interview', 'id_loker')) {
                $table->dropForeign(['id_loker']);
                $table->dropColumn('id_loker');
            }
        });
    }
};
