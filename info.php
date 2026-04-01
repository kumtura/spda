<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$levels = DB::table('level_user')->get() ?? [];
if (!$levels) {
    try {
        $levels = DB::table('tb_level_user')->get();
    } catch (\Exception $e) {}
}

if (!$levels) {
    try {
        $levels = DB::table('tb_level')->get();
    } catch (\Exception $e) {}
}

echo "Levels:\n";
foreach ($levels as $l) {
    echo json_encode($l) . "\n";
}

$users = DB::table('users')->select('id_level', DB::raw('count(*) as c'))->groupBy('id_level')->get();
echo "\nUsers by level:\n";
foreach ($users as $u) {
    echo json_encode($u) . "\n";
}
