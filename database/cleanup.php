<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=knouz_db;charset=utf8mb4', 'root', '');

// حذف التصنيفات التالفة
$cats = $pdo->query("SELECT id, name, HEX(name) as h FROM service_categories")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cats as $c) {
    if (strpos($c['h'], '3F3F') !== false) {
        $pdo->exec("DELETE FROM service_categories WHERE id=" . $c['id']);
        echo "Deleted bad category ID: " . $c['id'] . "\n";
    }
}

// حذف الصلاحيات التالفة  
$perms = $pdo->query("SELECT id, name, HEX(name) as h FROM permissions")->fetchAll(PDO::FETCH_ASSOC);
foreach ($perms as $p) {
    if (strpos($p['h'], '3F3F') !== false) {
        $pdo->exec("DELETE FROM role_permissions WHERE permission_id=" . $p['id']);
        $pdo->exec("DELETE FROM permissions WHERE id=" . $p['id']);
        echo "Deleted bad permission ID: " . $p['id'] . "\n";
    }
}

// حذف الخدمات التالفة
$svcs = $pdo->query("SELECT id, HEX(name) as h FROM services")->fetchAll(PDO::FETCH_ASSOC);
foreach ($svcs as $s) {
    if (strpos($s['h'], '3F3F') !== false) {
        $pdo->exec("DELETE FROM services WHERE id=" . $s['id']);
        echo "Deleted bad service ID: " . $s['id'] . "\n";
    }
}

echo "\nCleaning done!\n";

// تقرير نهائي
echo "Categories: " . $pdo->query("SELECT COUNT(*) FROM service_categories")->fetchColumn() . "\n";
echo "Services: " . $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn() . "\n";
echo "Permissions: " . $pdo->query("SELECT COUNT(*) FROM permissions")->fetchColumn() . "\n";
