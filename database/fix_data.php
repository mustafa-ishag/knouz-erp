<?php
/**
 * إصلاح الترميز وإدخال البيانات التأسيسية
 */

// اتصال UTF8MB4 صحيح
$pdo = new PDO(
    'mysql:host=127.0.0.1;port=3306;dbname=knouz_db;charset=utf8mb4',
    'root',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ]
);

echo "Connected with UTF8MB4\n";

// === إصلاح الإعدادات ===
$pdo->exec("DELETE FROM settings");
$settings = [
    ['company_name_ar', 'كنوز الإنجاز لخدمات الأعمال', 'company'],
    ['company_name_en', 'Knouz Al-Enjaz Business Services', 'company'],
    ['company_phone', '', 'company'],
    ['company_email', '', 'company'],
    ['company_address', '', 'company'],
    ['company_cr_number', '', 'company'],
    ['company_vat_number', '', 'company'],
    ['company_website', '', 'company'],
    ['company_city', 'الرياض', 'company'],
    ['vat_rate', '15', 'financial'],
    ['currency', 'SAR', 'financial'],
    ['quotation_prefix', 'QT', 'quotation'],
    ['claim_prefix', 'CL', 'quotation'],
    ['invoice_prefix', 'INV', 'quotation'],
    ['payment_prefix', 'PAY', 'quotation'],
    ['quotation_validity_days', '30', 'quotation'],
    ['quotation_terms', 'يسري هذا العرض لمدة 30 يوم من تاريخه', 'quotation'],
    ['cr_expiry_alert_days', '30', 'alerts'],
    ['invoice_due_alert_days', '7', 'alerts'],
];

$stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, setting_group) VALUES (?, ?, ?)");
foreach ($settings as $s) {
    $stmt->execute($s);
}
echo "Settings fixed: " . count($settings) . " rows\n";

// === إصلاح المستخدم admin ===
$pdo->exec("UPDATE users SET full_name = 'مدير النظام', email = 'admin@knouz.sa' WHERE username = 'admin'");
echo "Admin user fixed\n";

// === إصلاح الأدوار ===
$roles = [
    ['admin', 'مدير النظام'],
    ['manager', 'مدير'],
    ['employee', 'موظف'],
    ['accountant', 'محاسب'],
];
$stmt = $pdo->prepare("UPDATE roles SET name = ? WHERE slug = ?");
foreach ($roles as [$slug, $name]) {
    $stmt->execute([$name, $slug]);
}
echo "Roles fixed\n";

// === إصلاح تصنيفات الخدمات ===
$categories = [
    ['moci', 'خدمات وزارة التجارة', 'fa-store', '#D4A853', 1],
    ['mol', 'خدمات وزارة الموارد البشرية', 'fa-users', '#2196F3', 2],
    ['zatca', 'خدمات الزكاة والضريبة', 'fa-receipt', '#4CAF50', 3],
    ['gosi', 'خدمات التأمينات الاجتماعية', 'fa-shield-halved', '#FF9800', 4],
    ['balady', 'خدمات البلديات', 'fa-city', '#9C27B0', 5],
    ['muqeem', 'خدمات منصة مقيم', 'fa-passport', '#009688', 6],
    ['qiwa', 'خدمات منصة قوى', 'fa-briefcase', '#795548', 7],
    ['other', 'خدمات أخرى', 'fa-ellipsis', '#607D8B', 8],
];

$stmt = $pdo->prepare("UPDATE service_categories SET name = ?, icon = ?, color = ?, sort_order = ? WHERE slug = ?");
$insertStmt = $pdo->prepare("INSERT IGNORE INTO service_categories (name, slug, icon, color, sort_order) VALUES (?, ?, ?, ?, ?)");
foreach ($categories as [$slug, $name, $icon, $color, $order]) {
    $updated = $stmt->execute([$name, $icon, $color, $order, $slug]);
    if ($stmt->rowCount() == 0) {
        $insertStmt->execute([$name, $slug, $icon, $color, $order]);
    }
}
echo "Categories fixed\n";

// === إصلاح/إضافة الخدمات ===
$catIds = [];
foreach ($pdo->query("SELECT id, slug FROM service_categories")->fetchAll(PDO::FETCH_ASSOC) as $c) {
    $catIds[$c['slug']] = $c['id'];
}

// حذف الخدمات القديمة ذات الترميز الخاطئ
$existingServices = $pdo->query("SELECT id, HEX(name) as h FROM services")->fetchAll(PDO::FETCH_ASSOC);
foreach ($existingServices as $s) {
    if (strpos($s['h'], '3F3F') !== false) {
        $pdo->exec("DELETE FROM services WHERE id = " . $s['id']);
    }
}

$services = [
    ['moci', 'إصدار سجل تجاري', 'إصدار سجل تجاري رئيسي أو فرعي', 'وزارة التجارة', 3, 500, 200],
    ['moci', 'تجديد سجل تجاري', 'تجديد السجل التجاري', 'وزارة التجارة', 2, 400, 200],
    ['moci', 'تعديل سجل تجاري', 'تعديل بيانات السجل التجاري', 'وزارة التجارة', 2, 300, 100],
    ['mol', 'إصدار رخصة عمل', 'إصدار رخصة عمل للعامل', 'وزارة الموارد البشرية', 1, 300, 100],
    ['mol', 'نقل كفالة', 'نقل كفالة عامل', 'وزارة الموارد البشرية', 5, 800, 300],
    ['mol', 'تعديل مهنة', 'تعديل المسمى الوظيفي للعامل', 'وزارة الموارد البشرية', 3, 250, 100],
    ['zatca', 'تسجيل ضريبي', 'التسجيل في ضريبة القيمة المضافة', 'هيئة الزكاة', 3, 500, 150],
    ['zatca', 'إقرار ضريبي', 'تقديم الإقرار الضريبي الدوري', 'هيئة الزكاة', 2, 600, 200],
    ['gosi', 'تسجيل منشأة', 'تسجيل منشأة في التأمينات الاجتماعية', 'التأمينات الاجتماعية', 3, 400, 150],
    ['gosi', 'تسجيل موظف', 'تسجيل موظف جديد في التأمينات', 'التأمينات الاجتماعية', 1, 200, 50],
    ['qiwa', 'توثيق عقد عمل', 'توثيق عقد العمل في منصة قوى', 'قوى', 2, 300, 100],
    ['muqeem', 'تجديد إقامة', 'تجديد إقامة عامل', 'مقيم', 2, 350, 150],
];

$stmt = $pdo->prepare("INSERT INTO services (category_id, name, description, platform, execution_days, default_price, default_cost, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)");
$order = 1;
foreach ($services as [$catSlug, $name, $desc, $platform, $days, $price, $cost]) {
    if (isset($catIds[$catSlug])) {
        try {
            $stmt->execute([$catIds[$catSlug], $name, $desc, $platform, $days, $price, $cost, $order++]);
        } catch (PDOException $e) {
            // skip duplicates
        }
    }
}
echo "Services added\n";

// === إضافة الصلاحيات ===
$permissions = [
    ['عرض العملاء', 'clients.view', 'clients'],
    ['إضافة عميل', 'clients.create', 'clients'],
    ['تعديل عميل', 'clients.edit', 'clients'],
    ['حذف عميل', 'clients.delete', 'clients'],
    ['عرض الشركات', 'companies.view', 'companies'],
    ['إضافة شركة', 'companies.create', 'companies'],
    ['تعديل شركة', 'companies.edit', 'companies'],
    ['حذف شركة', 'companies.delete', 'companies'],
    ['عرض الخدمات', 'services.view', 'services'],
    ['إضافة خدمة', 'services.create', 'services'],
    ['تعديل خدمة', 'services.edit', 'services'],
    ['حذف خدمة', 'services.delete', 'services'],
    ['عرض الطلبات', 'orders.view', 'orders'],
    ['إضافة طلب', 'orders.create', 'orders'],
    ['تعديل طلب', 'orders.edit', 'orders'],
    ['حذف طلب', 'orders.delete', 'orders'],
    ['عرض العروض', 'quotations.view', 'quotations'],
    ['إنشاء عرض', 'quotations.create', 'quotations'],
    ['تعديل عرض', 'quotations.edit', 'quotations'],
    ['حذف عرض', 'quotations.delete', 'quotations'],
    ['اعتماد عرض', 'quotations.approve', 'quotations'],
    ['طباعة عرض', 'quotations.print', 'quotations'],
    ['عرض المطالبات', 'claims.view', 'claims'],
    ['إنشاء مطالبة', 'claims.create', 'claims'],
    ['تعديل مطالبة', 'claims.edit', 'claims'],
    ['حذف مطالبة', 'claims.delete', 'claims'],
    ['عرض الفواتير', 'invoices.view', 'invoices'],
    ['إنشاء فاتورة', 'invoices.create', 'invoices'],
    ['تعديل فاتورة', 'invoices.edit', 'invoices'],
    ['حذف فاتورة', 'invoices.delete', 'invoices'],
    ['طباعة فاتورة', 'invoices.print', 'invoices'],
    ['عرض المدفوعات', 'payments.view', 'payments'],
    ['إضافة دفعة', 'payments.create', 'payments'],
    ['تعديل دفعة', 'payments.edit', 'payments'],
    ['حذف دفعة', 'payments.delete', 'payments'],
    ['عرض الفرص', 'opportunities.view', 'opportunities'],
    ['إضافة فرصة', 'opportunities.create', 'opportunities'],
    ['تعديل فرصة', 'opportunities.edit', 'opportunities'],
    ['حذف فرصة', 'opportunities.delete', 'opportunities'],
    ['عرض التواصل', 'communications.view', 'communications'],
    ['إضافة تواصل', 'communications.create', 'communications'],
    ['عرض المهام', 'tasks.view', 'tasks'],
    ['إضافة مهمة', 'tasks.create', 'tasks'],
    ['تعديل مهمة', 'tasks.edit', 'tasks'],
    ['حذف مهمة', 'tasks.delete', 'tasks'],
    ['عرض الموظفين', 'employees.view', 'employees'],
    ['إضافة موظف', 'employees.create', 'employees'],
    ['تعديل موظف', 'employees.edit', 'employees'],
    ['حذف موظف', 'employees.delete', 'employees'],
    ['عرض المستندات', 'documents.view', 'documents'],
    ['إضافة مستند', 'documents.create', 'documents'],
    ['حذف مستند', 'documents.delete', 'documents'],
    ['عرض التقارير', 'reports.view', 'reports'],
    ['عرض الإعدادات', 'settings.view', 'settings'],
    ['تعديل الإعدادات', 'settings.edit', 'settings'],
    ['إدارة المستخدمين', 'settings.users', 'settings'],
    ['سجل المراقبة', 'settings.audit', 'settings'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO permissions (name, slug, module) VALUES (?, ?, ?)");
foreach ($permissions as $p) {
    $stmt->execute($p);
}
echo "Permissions added: " . count($permissions) . "\n";

// === منح الصلاحيات للأدوار ===
$adminRoleId = $pdo->query("SELECT id FROM roles WHERE slug = 'admin'")->fetchColumn();
if ($adminRoleId) {
    $permIds = $pdo->query("SELECT id FROM permissions")->fetchAll(PDO::FETCH_COLUMN);
    $stmt = $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
    foreach ($permIds as $pid) {
        $stmt->execute([$adminRoleId, $pid]);
    }
    echo "Admin permissions granted\n";
}

echo "\n=== Done! All data fixed with correct UTF8MB4 encoding ===\n";
