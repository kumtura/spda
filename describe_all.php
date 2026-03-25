<?php
$host = '127.0.0.1';
$db   = 'spda';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     $stmt = $pdo->query('SHOW TABLES');
     $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
     $result = [];
     foreach ($tables as $table) {
         $stmt2 = $pdo->query("DESCRIBE `$table` ");
         $result[$table] = $stmt2->fetchAll();
     }
     echo json_encode($result, JSON_PRETTY_PRINT);
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
