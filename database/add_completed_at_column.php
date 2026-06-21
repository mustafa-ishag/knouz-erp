<?php
$pdo = new PDO('mysql:host=localhost;dbname=knouz_db;charset=utf8mb4', 'root', '');
$pdo->exec('ALTER TABLE tasks ADD COLUMN completed_at TIMESTAMP NULL DEFAULT NULL AFTER due_date');
echo "Column completed_at added successfully.";
