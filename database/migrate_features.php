<?php
$pdo = new PDO('mysql:host=localhost;dbname=knouz_db;charset=utf8mb4', 'root', '');

// إضافة عمود order_id لجدول المطالبات
try {
    $pdo->exec('ALTER TABLE claims ADD COLUMN order_id INT NULL DEFAULT NULL AFTER quotation_id');
    echo "✅ Added order_id to claims\n";
} catch (Exception $e) {
    echo "⚠ order_id already exists or error: " . $e->getMessage() . "\n";
}

// إضافة عمود url لجدول الخدمات (الميزة #8)
try {
    $pdo->exec('ALTER TABLE services ADD COLUMN url VARCHAR(500) NULL DEFAULT NULL AFTER platform');
    echo "✅ Added url to services\n";
} catch (Exception $e) {
    echo "⚠ url already exists or error: " . $e->getMessage() . "\n";
}

// إضافة أعمدة للمستندات: رقم الرخصة، تاريخ الإصدار (الميزة #9)
try {
    $pdo->exec('ALTER TABLE documents ADD COLUMN license_number VARCHAR(100) NULL DEFAULT NULL AFTER document_type');
    echo "✅ Added license_number to documents\n";
} catch (Exception $e) {
    echo "⚠ license_number: " . $e->getMessage() . "\n";
}
try {
    $pdo->exec('ALTER TABLE documents ADD COLUMN issue_date DATE NULL DEFAULT NULL AFTER license_number');
    echo "✅ Added issue_date to documents\n";
} catch (Exception $e) {
    echo "⚠ issue_date: " . $e->getMessage() . "\n";
}

// إنشاء جدول موظفي الشركات (الميزة #7)
$pdo->exec("CREATE TABLE IF NOT EXISTS company_employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    position VARCHAR(200) NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(200) NULL,
    id_number VARCHAR(20) NULL,
    is_active TINYINT(1) DEFAULT 1,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "✅ Created company_employees table\n";

echo "\n🎉 All migrations completed!";
