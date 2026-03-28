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
            $table->string('xendit_id')->nullable()->after('metode');
            $table->string('status_pembayaran')->default('pending')->after('xendit_id');
            $table->json('payment_data')->nullable()->after('status_pembayaran');
        });

        Schema::table('tb_sumbangan_sukarela', function (Blueprint $table) {
            $table->string('xendit_id')->nullable()->after('metode');
            $table->string('status_pembayaran')->default('pending')->after('xendit_id');
            $table->json('payment_data')->nullable()->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            $table->dropColumn(['xendit_id', 'status_pembayaran', 'payment_data']);
        });

        Schema::table('tb_sumbangan_sukarela', function (Blueprint $table) {
            $table->dropColumn(['xendit_id', 'status_pembayaran', 'payment_data']);
        });
    }
};
