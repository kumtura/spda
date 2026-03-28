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
        Schema::create('tb_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name')->default('xendit');
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->text('webhook_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_payment_settings');
    }
};
