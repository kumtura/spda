<?php
$pdo = new PDO('mysql:host=localhost;dbname=spda', 'root', '');
$stmt = $pdo->query('DESCRIBE tb_pendatang');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' | ' . $row['Type'] . ' | ' . $row['Null'] . ' | ' . $row['Key'] . PHP_EOL;
}
echo "\n--- tb_data_banjar ---\n";
$stmt = $pdo->query('DESCRIBE tb_data_banjar');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' | ' . $row['Type'] . ' | ' . $row['Null'] . ' | ' . $row['Key'] . PHP_EOL;
}
echo "\n--- Pendatang count per status ---\n";
$stmt = $pdo->query("SELECT status, COUNT(*) as cnt FROM tb_pendatang WHERE aktif='1' GROUP BY status");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['status'] . ': ' . $row['cnt'] . PHP_EOL;
}
