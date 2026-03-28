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
        Schema::create('tb_payment_channels', function (Blueprint $table) {
            $table->id('id_payment_channel');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['VA', 'EWALLET', 'QRIS']);
            $table->string('icon_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_payment_channels');
    }
};
