<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
     $pdo = new PDO("mysql:host=$host", $user, $pass);
     $dbs = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
     $result = [];
     foreach ($dbs as $db) {
         if (in_array($db, ['information_schema', 'mysql', 'performance_schema', 'phpmyadmin'])) continue;
         $pdo->exec("USE `$db` ");
         $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
         if (!empty($tables)) {
             $result[$db] = $tables;
         }
     }
     echo json_encode($result, JSON_PRETTY_PRINT);
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
