<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
     $pdo = new PDO("mysql:host=$host", $user, $pass);
     $dbs = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
     foreach ($dbs as $db) {
         if (in_array($db, ['information_schema', 'mysql', 'performance_schema', 'phpmyadmin'])) continue;
         $pdo->exec("USE `$db` ");
         try {
             $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
             $stmt->execute(['frontoffice@hotel.com']);
             $u = $stmt->fetch(PDO::FETCH_ASSOC);
             if ($u) {
                 echo "Found in $db: " . json_encode($u, JSON_PRETTY_PRINT) . "\n";
             }
         } catch (\Exception $e) {
             // Skip if no users table
         }
     }
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
