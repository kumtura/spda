<?php
$host = '127.0.0.1';
$port = '8889';
$db   = 'spda';
$user = 'root';
$pass = 'root'; // Try root/root for MAMP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     $stmt = $pdo->query('SELECT DISTINCT tipe_kategori, market_type FROM tb_kategori_tiket');
     echo json_encode($stmt->fetchAll(), JSON_PRETTY_PRINT);
} catch (\PDOException $e) {
     // Try empty password if root fails
     try {
         $pdo = new PDO($dsn, $user, '', $options);
         $stmt = $pdo->query('SELECT DISTINCT tipe_kategori, market_type FROM tb_kategori_tiket');
         echo json_encode($stmt->fetchAll(), JSON_PRETTY_PRINT);
     } catch (\PDOException $e2) {
         echo "Error: " . $e2->getMessage();
     }
}
