<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('tb_alokasi_punia', 'foto')) {
    Schema::table('tb_alokasi_punia', function (Blueprint $table) {
        $table->json('foto')->nullable();
    });
    echo "Column 'foto' created successfully.\n";
} else {
    echo "Column 'foto' already exists.\n";
}
