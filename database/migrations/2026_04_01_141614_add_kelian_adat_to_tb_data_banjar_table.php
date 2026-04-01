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
            $table->string('kelian_adat')->default('-')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_data_banjar', function (Blueprint $table) {
            $table->dropColumn('kelian_adat');
        });
    }
};
