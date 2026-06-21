<?php
$pdo = new PDO('mysql:host=localhost;dbname=knouz_db;charset=utf8mb4', 'root', '');
$pdo->exec("
CREATE TABLE IF NOT EXISTS gov_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(100) NOT NULL COMMENT 'اسم المنصة',
    company_id INT NULL,
    company_name VARCHAR(200) NULL COMMENT 'اسم الشركة يدوي',
    subscription_number VARCHAR(100) NULL COMMENT 'رقم الاشتراك',
    start_date DATE NULL COMMENT 'تاريخ البداية',
    end_date DATE NULL COMMENT 'تاريخ الانتهاء',
    cost DECIMAL(10,2) DEFAULT 0 COMMENT 'تكلفة الاشتراك',
    username VARCHAR(100) NULL COMMENT 'اسم المستخدم في المنصة',
    password_hint VARCHAR(200) NULL COMMENT 'تلميح كلمة المرور',
    notes TEXT NULL,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo 'Table created successfully';
