<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Http\Request;
$app->instance('request', Request::create('http://localhost'));

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ObjekWisata;

try {
    $total = ObjekWisata::count();
    $aktif_1 = ObjekWisata::where('aktif', '1')->count();
    $status_aktif = ObjekWisata::where('status', 'aktif')->count();
    $both = ObjekWisata::where('aktif', '1')->where('status', 'aktif')->count();

    echo "Total DB: $total\n";
    echo "Aktif '1': $aktif_1\n";
    echo "Status 'aktif': $status_aktif\n";
    echo "Both (Visible Public): $both\n";

    $all = ObjekWisata::all();
    foreach($all as $o) {
        echo "ID: $o->id_objek_wisata | Nama: $o->nama_objek | Aktif: $o->aktif | Status: $o->status\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
