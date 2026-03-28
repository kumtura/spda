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
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            $table->string('nama_donatur')->nullable()->after('id_usaha');
            $table->string('email')->nullable()->after('nama_donatur');
            $table->string('no_wa')->nullable()->after('email');
            $table->boolean('is_anonymous')->default(0)->after('no_wa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            $table->dropColumn(['nama_donatur', 'email', 'no_wa', 'is_anonymous']);
        });
    }
};
