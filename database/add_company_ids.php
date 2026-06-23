<?php
$pdo = new PDO('mysql:host=localhost;dbname=knouz_db;charset=utf8mb4', 'root', '');
try {
    $pdo->exec('ALTER TABLE claims ADD COLUMN company_ids TEXT NULL DEFAULT NULL AFTER company_id');
    echo "OK: company_ids added\n";
} catch (Exception $e) {
    echo "Skip: " . $e->getMessage() . "\n";
}
