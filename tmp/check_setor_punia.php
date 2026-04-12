<?php
$host = '127.0.0.1';
$db   = 'spda';
$user = 'root';
$pass = 'root';
$pdo = new PDO("mysql:host=127.0.0.1;port=8889;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Find the actual database name
$dbs = $pdo->query("SHOW DATABASES")->fetchAll();
echo "=== Available databases ===\n";
foreach ($dbs as $d) echo $d['Database'] . "\n";

// Search for the right database
$targetDb = null;
foreach ($dbs as $d) {
    if (stripos($d['Database'], 'spda') !== false || stripos($d['Database'], 'desa') !== false || stripos($d['Database'], 'punia') !== false) {
        $targetDb = $d['Database'];
        break;
    }
}
if (!$targetDb) {
    // Try to find which DB has tb_setor_punia
    foreach ($dbs as $d) {
        if (in_array($d['Database'], ['information_schema','mysql','performance_schema','sys'])) continue;
        $tables = $pdo->query("SHOW TABLES FROM `{$d['Database']}` LIKE 'tb_setor%'")->fetchAll();
        if (count($tables) > 0) {
            $targetDb = $d['Database'];
            break;
        }
    }
}
echo "\nTarget DB: " . ($targetDb ?: 'NOT FOUND') . "\n";
if (!$targetDb) exit;

$pdo->exec("USE `$targetDb`");

echo "=== tb_setor_punia columns ===\n";
$cols = $pdo->query("DESCRIBE tb_setor_punia")->fetchAll();
foreach ($cols as $c) {
    echo $c['Field'] . ' | ' . $c['Type'] . ' | Key:' . $c['Key'] . ' | Null:' . $c['Null'] . "\n";
}

echo "\n=== Foreign keys ===\n";
$fks = $pdo->query("SELECT COLUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME='tb_setor_punia' AND TABLE_SCHEMA='$db' AND REFERENCED_TABLE_NAME IS NOT NULL")->fetchAll();
if (count($fks) == 0) echo "No FK constraints!\n";
foreach ($fks as $fk) echo $fk['COLUMN_NAME'] . ' -> ' . $fk['REFERENCED_TABLE_NAME'] . '.' . $fk['REFERENCED_COLUMN_NAME'] . "\n";

echo "\n=== Row count: " . $pdo->query("SELECT COUNT(*) as cnt FROM tb_setor_punia")->fetch()['cnt'] . " ===\n";

echo "\n=== Related table PK types ===\n";
$tables = [
    ['tb_data_banjar', 'id_data_banjar'],
    ['tb_keuangan', 'id_keuangan'],
    ['users', 'id'],
];
foreach ($tables as [$tbl, $col]) {
    $check = $pdo->query("SELECT COUNT(*) as cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA='$db' AND TABLE_NAME='$tbl'")->fetch();
    if ($check['cnt'] > 0) {
        $info = $pdo->query("SHOW COLUMNS FROM $tbl WHERE Field='$col'")->fetch();
        echo "$tbl.$col: " . $info['Type'] . " | Key:" . $info['Key'] . "\n";
    } else {
        echo "$tbl does NOT exist!\n";
    }
}

echo "\n=== Sample data (first 3) ===\n";
$rows = $pdo->query("SELECT * FROM tb_setor_punia LIMIT 3")->fetchAll();
foreach ($rows as $r) print_r($r);
