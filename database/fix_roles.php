<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=knouz_db;charset=utf8mb4', 'root', '');
$pdo->exec("SET NAMES utf8mb4");

$roles = [
    ['slug' => 'admin', 'name' => 'مدير النظام', 'description' => 'صلاحيات كاملة للوصول لجميع الوظائف'],
    ['slug' => 'operations_manager', 'name' => 'مدير العمليات', 'description' => 'إدارة العمليات التشغيلية والخدمات'],
    ['slug' => 'sales_manager', 'name' => 'مدير المبيعات', 'description' => 'إدارة العملاء والعروض وعروض الأسعار'],
    ['slug' => 'accountant', 'name' => 'محاسب', 'description' => 'إدارة الفواتير والمدفوعات والتقارير المالية'],
    ['slug' => 'service_employee', 'name' => 'موظف الخدمات', 'description' => 'تنفيذ أوامر الخدمة والمتابعة'],
    ['slug' => 'client', 'name' => 'عميل', 'description' => 'الاطلاع على حالة الطلبات والفواتير'],
];

$stmt = $pdo->prepare("UPDATE roles SET name = ?, description = ? WHERE slug = ?");

foreach ($roles as $role) {
    $stmt->execute([$role['name'], $role['description'], $role['slug']]);
    echo "Updated: {$role['slug']}\n";
}

// Verify
$rows = $pdo->query("SELECT id, name, slug FROM roles")->fetchAll(PDO::FETCH_ASSOC);
echo "\n=== Verification ===\n";
foreach ($rows as $r) {
    echo "{$r['id']} | {$r['name']} | {$r['slug']}\n";
}
echo "\nDone!\n";
