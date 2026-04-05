<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_api_tokens')) {
            Schema::create('tb_api_tokens', function (Blueprint $table) {
                $table->id('id_api_token');
                $table->string('name', 100);
                $table->string('token', 64)->unique();
                $table->json('permissions')->nullable();
                $table->string('ip_whitelist', 500)->nullable();
                $table->integer('rate_limit')->default(100);
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->boolean('aktif')->default(true);
                $table->timestamps();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tb_api_logs')) {
            Schema::create('tb_api_logs', function (Blueprint $table) {
                $table->id('id_api_log');
                $table->unsignedBigInteger('id_api_token')->nullable();
                $table->string('endpoint', 255);
                $table->string('method', 10);
                $table->string('ip_address', 45);
                $table->integer('response_code');
                $table->integer('response_time_ms')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('id_api_token')->references('id_api_token')->on('tb_api_tokens')->onDelete('set null');
                $table->index('created_at');
                $table->index('id_api_token');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_api_logs');
        Schema::dropIfExists('tb_api_tokens');
    }
};
