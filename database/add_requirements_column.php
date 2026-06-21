<?php
$pdo = new PDO('mysql:host=localhost;dbname=knouz_db;charset=utf8mb4', 'root', '');
$pdo->exec('ALTER TABLE services ADD COLUMN requirements TEXT NULL AFTER description');
echo "Column requirements added successfully.";
