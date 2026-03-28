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
        Schema::table('tb_sumbangan_sukarela', function (Blueprint $table) {
            // Using change() to alter existing column type
            $table->string('metode', 255)->default('0')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_sumbangan_sukarela', function (Blueprint $table) {
            // Reverting back to original type (but it loses string data if reverted)
            $table->integer('metode')->default(0)->change();
        });
    }
};
