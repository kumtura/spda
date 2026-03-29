<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_jadwal_interview', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_jadwal_interview', 'status_interview')) {
                $table->string('status_interview', 1)->default('0')->after('status_diterima')->comment('0=pending, 1=interview');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_jadwal_interview', function (Blueprint $table) {
            if (Schema::hasColumn('tb_jadwal_interview', 'status_interview')) {
                $table->dropColumn('status_interview');
            }
        });
    }
};
