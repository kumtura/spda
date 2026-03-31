<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_punia_pendatang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_acara_punia')->nullable()->after('id_pendatang');
            $table->foreign('id_acara_punia')->references('id_acara_punia')->on('tb_acara_punia')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tb_punia_pendatang', function (Blueprint $table) {
            $table->dropForeign(['id_acara_punia']);
            $table->dropColumn('id_acara_punia');
        });
    }
};
