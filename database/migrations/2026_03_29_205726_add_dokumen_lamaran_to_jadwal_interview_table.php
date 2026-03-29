<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_jadwal_interview', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_jadwal_interview', 'dokumen_lamaran')) {
                $table->text('dokumen_lamaran')->nullable()->after('alasan_penolakan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_jadwal_interview', function (Blueprint $table) {
            if (Schema::hasColumn('tb_jadwal_interview', 'dokumen_lamaran')) {
                $table->dropColumn('dokumen_lamaran');
            }
        });
    }
};
