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
        Schema::table('tb_data_banjar', function (Blueprint $table) {
            $table->dropColumn('kelian_adat');
            $table->unsignedBigInteger('id_user_kelian')->nullable()->after('alamat_banjar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_data_banjar', function (Blueprint $table) {
            $table->dropColumn('id_user_kelian');
            $table->string('kelian_adat')->nullable()->default('-');
        });
    }
};
