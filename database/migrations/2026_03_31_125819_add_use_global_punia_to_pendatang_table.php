<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->boolean('use_global_punia')->default(true)->after('punia_rutin_bulanan');
        });
    }

    public function down(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->dropColumn('use_global_punia');
        });
    }
};
