<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=knouz_db;charset=utf8mb4', 'root', '');
$pdo->exec("SET NAMES utf8mb4");
$rows = $pdo->query("SELECT id, name, slug, HEX(name) as hex_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo $r['id'] . ' | ' . $r['name'] . ' | ' . $r['slug'] . ' | HEX: ' . substr($r['hex_name'], 0, 20) . PHP_EOL;
}
