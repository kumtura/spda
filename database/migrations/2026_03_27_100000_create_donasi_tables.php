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
        // Tables already exist in database, this is a placeholder migration
        // tb_kategori_donasi, tb_program_donasi, tb_sumbangan_sukarela already created
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing - tables should not be dropped
    }
};
