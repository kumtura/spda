<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_tenaga_kerja', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_tenaga_kerja', 'no_telp')) {
                $table->string('no_telp', 20)->nullable()->after('no_wa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_tenaga_kerja', function (Blueprint $table) {
            if (Schema::hasColumn('tb_tenaga_kerja', 'no_telp')) {
                $table->dropColumn('no_telp');
            }
        });
    }
};
