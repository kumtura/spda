<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nama_objek');
        });

        // Generate slugs for existing records
        $objects = \App\Models\ObjekWisata::all();
        foreach ($objects as $obj) {
            $baseSlug = Str::slug($obj->nama_objek);
            $slug = $baseSlug;
            $counter = 1;
            while (\App\Models\ObjekWisata::where('slug', $slug)->where('id_objek_wisata', '!=', $obj->id_objek_wisata)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $obj->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
