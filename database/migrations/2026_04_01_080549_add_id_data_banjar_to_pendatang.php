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
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->integer('id_data_banjar')->nullable()->after('alamat_tinggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->dropColumn('id_data_banjar');
        });
    }
};
