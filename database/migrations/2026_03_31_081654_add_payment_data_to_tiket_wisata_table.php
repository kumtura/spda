<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            $table->text('payment_data')->nullable()->after('xendit_id');
        });
    }

    public function down(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            $table->dropColumn('payment_data');
        });
    }
};
