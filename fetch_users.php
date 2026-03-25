<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$db = $app->make('db');
try {
    $users = $db->select('select id, username, email, name, id_level from users limit 5');
    echo json_encode($users, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
