<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=knouz_db;charset=utf8mb4', 'root', '');
$r = $pdo->query("SELECT setting_value FROM settings WHERE setting_key='company_name_ar'")->fetch();
echo "Company: " . $r['setting_value'] . "\n";
$r = $pdo->query("SELECT full_name FROM users WHERE username='admin'")->fetch();
echo "Admin: " . $r['full_name'] . "\n";
$r = $pdo->query("SELECT name FROM roles WHERE slug='admin'")->fetch();
echo "Role: " . $r['name'] . "\n";
$rows = $pdo->query("SELECT name FROM service_categories ORDER BY sort_order")->fetchAll();
foreach ($rows as $row) echo "Cat: " . $row['name'] . "\n";
$rows = $pdo->query("SELECT name FROM services LIMIT 5")->fetchAll();
foreach ($rows as $row) echo "Svc: " . $row['name'] . "\n";
echo "Total services: " . $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn() . "\n";
echo "Total permissions: " . $pdo->query("SELECT COUNT(*) FROM permissions")->fetchColumn() . "\n";
echo "Admin perms: " . $pdo->query("SELECT COUNT(*) FROM role_permissions WHERE role_id = (SELECT id FROM roles WHERE slug='admin')")->fetchColumn() . "\n";
