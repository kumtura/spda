<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->date('tinggal_dari')->nullable()->after('alamat_tinggal');
            $table->date('tinggal_sampai')->nullable()->after('tinggal_dari');
            $table->boolean('tinggal_belum_yakin')->default(false)->after('tinggal_sampai');
        });
    }

    public function down(): void
    {
        Schema::table('tb_pendatang', function (Blueprint $table) {
            $table->dropColumn(['tinggal_dari', 'tinggal_sampai', 'tinggal_belum_yakin']);
        });
    }
};
