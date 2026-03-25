<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
     $pdo = new PDO("mysql:host=$host", $user, $pass);
     $stmt = $pdo->query('SHOW DATABASES');
     echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN), JSON_PRETTY_PRINT);
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
