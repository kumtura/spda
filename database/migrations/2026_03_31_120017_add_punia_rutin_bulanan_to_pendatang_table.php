<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->decimal('punia_rutin_bulanan', 15, 2)->default(0)->after('alamat_tinggal');
        });
    }

    public function down(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->dropColumn('punia_rutin_bulanan');
        });
    }
};
