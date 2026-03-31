<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_agenda', function (Blueprint $table) {
            $table->string('waktu_selesai_data')->nullable()->after('waktu_agenda');
            $table->string('status_selesai')->default('fixed')->after('waktu_selesai_data'); // 'fixed' or 'selesai'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_agenda', function (Blueprint $table) {
            $table->dropColumn(['waktu_selesai_data', 'status_selesai']);
        });
    }
};
