<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add user_id column to tb_usaha
        if (!Schema::hasColumn('tb_usaha', 'user_id')) {
            Schema::table('tb_usaha', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id_usaha');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 2. Migrate existing data
        $businesses = DB::table('tb_usaha')
            ->join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', '=', 'tb_usaha.id_detail_usaha')
            ->select('tb_usaha.*', 'tb_detail_usaha.nama_usaha', 'tb_detail_usaha.email_usaha', 'tb_detail_usaha.no_wa')
            ->get();

        foreach ($businesses as $biz) {
            // Use username if email_usaha is empty, assuming username is email based on previous check
            $email = $biz->email_usaha ?: $biz->username;

            if (!$email) continue;

            // Check if user already exists
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $biz->nama_usaha,
                    'email' => $email,
                    'password' => Hash::make($biz->password ?: 'password123'), // Use legacy password or default
                    'no_wa' => $biz->no_wa,
                    'id_level' => 3, // Level for Unit Usaha
                ]);
            } else {
                // If user exists, update their level to ensure they are Unit Usaha
                $user->update(['id_level' => 3]);
            }

            // Link the business to this user
            DB::table('tb_usaha')->where('id_usaha', $biz->id_usaha)->update(['user_id' => $user->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usaha', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
