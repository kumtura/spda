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
            if (!Schema::hasColumn('tb_dana_punia', 'nama_donatur')) {
                $table->string('nama_donatur')->nullable()->after('id_usaha');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'email')) {
                $table->string('email')->nullable()->after('nama_donatur');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'no_wa')) {
                $table->string('no_wa')->nullable()->after('email');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'is_anonymous')) {
                $table->boolean('is_anonymous')->default(0)->after('no_wa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            $columns = ['nama_donatur', 'email', 'no_wa', 'is_anonymous'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tb_dana_punia', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
