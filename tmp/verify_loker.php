<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Loker;

$loker = Loker::with(['usaha.detail', 'usaha.kategori'])->where('status', 'Buka')->first();

if ($loker) {
    echo "Loker Found:\n";
    echo "Judul: " . $loker->judul . "\n";
    echo "Usaha: " . ($loker->usaha->detail->nama_usaha ?? 'N/A') . "\n";
    echo "Kategori: " . ($loker->usaha->kategori->nama_kategori_usaha ?? 'N/A') . "\n";
    echo "Logo: " . ($loker->usaha->detail->logo ?? 'N/A') . "\n";
} else {
    echo "No open Loker found.\n";
}
