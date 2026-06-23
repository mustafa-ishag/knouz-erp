<?php
$pdo = new PDO('mysql:host=localhost;dbname=knouz_db;charset=utf8mb4', 'root', '');
try {
    $pdo->exec('ALTER TABLE company_documents ADD COLUMN notes TEXT NULL DEFAULT NULL AFTER expiry_date');
    echo "OK: notes added\n";
} catch (Exception $e) {
    echo "Skip: " . $e->getMessage() . "\n";
}
