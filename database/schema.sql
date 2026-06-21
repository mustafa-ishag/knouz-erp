-- =====================================================
-- نظام كنوز الإنجاز - هيكل قاعدة البيانات
-- Knouz Al-Enjaz ERP & CRM Database Schema
-- =====================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS `knouz_db` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `knouz_db`;

-- =====================================================
-- 1. جدول الأدوار (Roles)
-- =====================================================
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL COMMENT 'اسم الدور',
    `slug` VARCHAR(50) NOT NULL UNIQUE COMMENT 'المعرف الفريد',
    `description` TEXT NULL COMMENT 'الوصف',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. جدول الصلاحيات (Permissions)
-- =====================================================
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL COMMENT 'اسم الصلاحية',
    `slug` VARCHAR(100) NOT NULL UNIQUE COMMENT 'المعرف الفريد',
    `module` VARCHAR(50) NOT NULL COMMENT 'الوحدة',
    `description` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. جدول ربط الأدوار بالصلاحيات
-- =====================================================
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    UNIQUE KEY `unique_role_permission` (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. جدول المستخدمين (Users)
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(150) NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(150) NOT NULL COMMENT 'الاسم الكامل',
    `phone` VARCHAR(20) NULL,
    `role_id` INT NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `avatar` VARCHAR(255) NULL,
    `last_login_at` DATETIME NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `failed_login_attempts` INT DEFAULT 0,
    `last_failed_login` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`),
    INDEX `idx_role` (`role_id`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. جدول العملاء (Clients)
-- =====================================================
CREATE TABLE IF NOT EXISTS `clients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'رقم العميل',
    `name` VARCHAR(200) NOT NULL COMMENT 'الاسم',
    `phone` VARCHAR(20) NULL COMMENT 'الجوال',
    `phone2` VARCHAR(20) NULL COMMENT 'رقم جوال إضافي',
    `email` VARCHAR(150) NULL COMMENT 'البريد الإلكتروني',
    `city` VARCHAR(100) NULL COMMENT 'المدينة',
    `address` TEXT NULL COMMENT 'العنوان',
    `id_number` VARCHAR(20) NULL COMMENT 'رقم الهوية',
    `notes` TEXT NULL COMMENT 'الملاحظات',
    `source` VARCHAR(50) NULL COMMENT 'مصدر العميل',
    `assigned_to` INT NULL COMMENT 'الموظف المسؤول',
    `last_contact_date` DATE NULL COMMENT 'آخر تواصل',
    `user_id` INT NULL COMMENT 'حساب المستخدم المرتبط',
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client_number` (`client_number`),
    INDEX `idx_name` (`name`),
    INDEX `idx_phone` (`phone`),
    INDEX `idx_city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. جدول الشركات (Companies)
-- =====================================================
CREATE TABLE IF NOT EXISTS `companies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL COMMENT 'العميل المالك',
    `name_ar` VARCHAR(200) NOT NULL COMMENT 'الاسم العربي',
    `name_en` VARCHAR(200) NULL COMMENT 'الاسم الإنجليزي',
    `cr_number` VARCHAR(20) NULL COMMENT 'رقم السجل التجاري',
    `unified_number` VARCHAR(20) NULL COMMENT 'الرقم الموحد',
    `distinctive_number` VARCHAR(20) NULL COMMENT 'الرقم المميز',
    `qiwa_number` VARCHAR(20) NULL COMMENT 'رقم المنشأة في قوى',
    `activity` VARCHAR(200) NULL COMMENT 'النشاط',
    `city` VARCHAR(100) NULL COMMENT 'المدينة',
    `address` TEXT NULL COMMENT 'العنوان',
    `email` VARCHAR(150) NULL,
    `phone` VARCHAR(20) NULL,
    `cr_issue_date` DATE NULL COMMENT 'تاريخ إصدار السجل',
    `cr_expiry_date` DATE NULL COMMENT 'تاريخ انتهاء السجل',
    `notes` TEXT NULL,
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_cr_number` (`cr_number`),
    INDEX `idx_cr_expiry` (`cr_expiry_date`),
    INDEX `idx_name_ar` (`name_ar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. جدول مستندات الشركات (Company Documents)
-- =====================================================
CREATE TABLE IF NOT EXISTS `company_documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_id` INT NOT NULL,
    `document_type` VARCHAR(50) NOT NULL COMMENT 'نوع المستند',
    `title` VARCHAR(200) NOT NULL COMMENT 'عنوان المستند',
    `file_path` VARCHAR(500) NOT NULL COMMENT 'مسار الملف',
    `file_name` VARCHAR(200) NOT NULL COMMENT 'اسم الملف',
    `file_size` INT NULL COMMENT 'حجم الملف',
    `issue_date` DATE NULL COMMENT 'تاريخ الإصدار',
    `expiry_date` DATE NULL COMMENT 'تاريخ الانتهاء',
    `notes` TEXT NULL,
    `uploaded_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_company` (`company_id`),
    INDEX `idx_type` (`document_type`),
    INDEX `idx_expiry` (`expiry_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 8. جدول تصنيفات الخدمات (Service Categories)
-- =====================================================
CREATE TABLE IF NOT EXISTS `service_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL COMMENT 'اسم التصنيف',
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `icon` VARCHAR(50) NULL COMMENT 'الأيقونة',
    `color` VARCHAR(20) NULL COMMENT 'اللون',
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 9. جدول الخدمات (Services)
-- =====================================================
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL COMMENT 'التصنيف',
    `name` VARCHAR(200) NOT NULL COMMENT 'اسم الخدمة',
    `description` TEXT NULL COMMENT 'الوصف',
    `platform` VARCHAR(50) NULL COMMENT 'المنصة الحكومية',
    `execution_days` INT NULL COMMENT 'مدة التنفيذ (أيام)',
    `default_price` DECIMAL(12,2) DEFAULT 0 COMMENT 'سعر البيع الافتراضي',
    `default_cost` DECIMAL(12,2) DEFAULT 0 COMMENT 'التكلفة الافتراضية',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`category_id`) REFERENCES `service_categories`(`id`),
    INDEX `idx_category` (`category_id`),
    INDEX `idx_platform` (`platform`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 10. جدول الموظفين (Employees)
-- =====================================================
CREATE TABLE IF NOT EXISTS `employees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'الرقم الوظيفي',
    `name` VARCHAR(150) NOT NULL COMMENT 'الاسم',
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(150) NULL,
    `job_title` VARCHAR(100) NULL COMMENT 'المسمى الوظيفي',
    `department` VARCHAR(100) NULL COMMENT 'القسم',
    `status` ENUM('active','inactive','on_leave','terminated') DEFAULT 'active',
    `user_id` INT NULL COMMENT 'حساب المستخدم المرتبط',
    `hire_date` DATE NULL COMMENT 'تاريخ التوظيف',
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_employee_number` (`employee_number`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 11. جدول طلبات الخدمات (Service Orders)
-- =====================================================
CREATE TABLE IF NOT EXISTS `service_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'رقم الطلب',
    `client_id` INT NOT NULL,
    `company_id` INT NULL,
    `service_id` INT NOT NULL,
    `assigned_to` INT NULL COMMENT 'الموظف المسؤول',
    `status` VARCHAR(30) DEFAULT 'new',
    `platform_ref` VARCHAR(100) NULL COMMENT 'مرجع المنصة',
    `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
    `description` TEXT NULL COMMENT 'الوصف التفصيلي',
    `price` DECIMAL(12,2) DEFAULT 0 COMMENT 'مبلغ البيع',
    `cost` DECIMAL(12,2) DEFAULT 0 COMMENT 'التكلفة',
    `start_date` DATE NULL,
    `due_date` DATE NULL,
    `completed_date` DATE NULL,
    `notes` TEXT NULL,
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`),
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_company` (`company_id`),
    INDEX `idx_service` (`service_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_assigned` (`assigned_to`),
    INDEX `idx_dates` (`start_date`, `due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 11.5. جدول سجل حالات الطلبات (Order Status History)
-- =====================================================
CREATE TABLE IF NOT EXISTS `order_status_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `old_status` VARCHAR(30) NULL,
    `new_status` VARCHAR(30) NOT NULL,
    `notes` TEXT NULL,
    `changed_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `service_orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 12. جدول الخدمات التاريخية (Historical Services)
-- =====================================================
CREATE TABLE IF NOT EXISTS `historical_services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `company_id` INT NULL,
    `service_date` DATE NOT NULL COMMENT 'تاريخ الخدمة',
    `platform` VARCHAR(50) NULL COMMENT 'المنصة',
    `service_name` VARCHAR(200) NOT NULL COMMENT 'اسم الخدمة',
    `description` TEXT NULL COMMENT 'الوصف التفصيلي',
    `executed_by` INT NULL COMMENT 'الموظف المنفذ',
    `execution_days` INT NULL COMMENT 'مدة التنفيذ',
    `sale_amount` DECIMAL(12,2) DEFAULT 0 COMMENT 'مبلغ البيع',
    `cost_amount` DECIMAL(12,2) DEFAULT 0 COMMENT 'التكلفة',
    `profit` DECIMAL(12,2) DEFAULT 0 COMMENT 'الربح',
    `payment_status` VARCHAR(20) DEFAULT 'paid',
    `notes` TEXT NULL,
    `attachments` TEXT NULL COMMENT 'المرفقات (JSON)',
    `imported_from` VARCHAR(50) NULL COMMENT 'مصدر الاستيراد',
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`executed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_date` (`service_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 13. جدول الفرص البيعية (Sales Opportunities)
-- =====================================================
CREATE TABLE IF NOT EXISTS `sales_opportunities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL COMMENT 'عنوان الفرصة',
    `client_id` INT NOT NULL,
    `company_id` INT NULL,
    `status` VARCHAR(30) DEFAULT 'new',
    `expected_amount` DECIMAL(12,2) DEFAULT 0 COMMENT 'المبلغ المتوقع',
    `probability` INT DEFAULT 50 COMMENT 'نسبة الاحتمال',
    `assigned_to` INT NULL,
    `expected_close_date` DATE NULL COMMENT 'تاريخ الإغلاق المتوقع',
    `description` TEXT NULL,
    `lost_reason` TEXT NULL COMMENT 'سبب الخسارة',
    `notes` TEXT NULL,
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_assigned` (`assigned_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 14. جدول عروض الأسعار (Quotations)
-- =====================================================
CREATE TABLE IF NOT EXISTS `quotations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quotation_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'رقم العرض',
    `client_id` INT NOT NULL,
    `company_id` INT NULL,
    `opportunity_id` INT NULL COMMENT 'الفرصة البيعية',
    `quotation_date` DATE NOT NULL COMMENT 'تاريخ العرض',
    `validity_date` DATE NOT NULL COMMENT 'تاريخ الصلاحية',
    `status` VARCHAR(20) DEFAULT 'draft',
    `subtotal` DECIMAL(12,2) DEFAULT 0 COMMENT 'المجموع الفرعي',
    `discount` DECIMAL(12,2) DEFAULT 0 COMMENT 'الخصم',
    `vat_rate` DECIMAL(5,2) DEFAULT 15 COMMENT 'نسبة الضريبة',
    `vat_amount` DECIMAL(12,2) DEFAULT 0 COMMENT 'مبلغ الضريبة',
    `total` DECIMAL(12,2) DEFAULT 0 COMMENT 'الإجمالي',
    `payment_terms` TEXT NULL COMMENT 'شروط الدفع',
    `notes` TEXT NULL COMMENT 'الملاحظات',
    `terms_conditions` TEXT NULL COMMENT 'الشروط والأحكام',
    `language` ENUM('ar','en') DEFAULT 'ar' COMMENT 'لغة العرض',
    `sent_at` DATETIME NULL,
    `approved_at` DATETIME NULL,
    `rejected_at` DATETIME NULL,
    `claimed_amount` DECIMAL(12,2) DEFAULT 0 COMMENT 'المبلغ المطالب به',
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`opportunity_id`) REFERENCES `sales_opportunities`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_number` (`quotation_number`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_date` (`quotation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 15. جدول بنود عروض الأسعار (Quotation Items)
-- =====================================================
CREATE TABLE IF NOT EXISTS `quotation_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quotation_id` INT NOT NULL,
    `service_id` INT NULL,
    `description` VARCHAR(500) NOT NULL COMMENT 'الوصف',
    `quantity` INT DEFAULT 1,
    `unit_price` DECIMAL(12,2) DEFAULT 0 COMMENT 'سعر الوحدة',
    `total` DECIMAL(12,2) DEFAULT 0 COMMENT 'الإجمالي',
    `sort_order` INT DEFAULT 0,
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE SET NULL,
    INDEX `idx_quotation` (`quotation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 16. جدول المطالبات المالية (Claims)
-- =====================================================
CREATE TABLE IF NOT EXISTS `claims` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `claim_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'رقم المطالبة',
    `quotation_id` INT NOT NULL COMMENT 'عرض السعر',
    `client_id` INT NOT NULL,
    `company_id` INT NULL,
    `claim_percentage` DECIMAL(5,2) DEFAULT 100 COMMENT 'نسبة المطالبة',
    `subtotal` DECIMAL(12,2) DEFAULT 0,
    `vat_rate` DECIMAL(5,2) DEFAULT 15,
    `vat_amount` DECIMAL(12,2) DEFAULT 0,
    `total` DECIMAL(12,2) DEFAULT 0,
    `status` VARCHAR(20) DEFAULT 'draft',
    `due_date` DATE NULL COMMENT 'تاريخ الاستحقاق',
    `paid_amount` DECIMAL(12,2) DEFAULT 0 COMMENT 'المبلغ المدفوع',
    `notes` TEXT NULL,
    `sent_at` DATETIME NULL,
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_number` (`claim_number`),
    INDEX `idx_quotation` (`quotation_id`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 17. جدول الفواتير (Invoices)
-- =====================================================
CREATE TABLE IF NOT EXISTS `invoices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `invoice_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'رقم الفاتورة',
    `client_id` INT NOT NULL,
    `company_id` INT NULL,
    `claim_id` INT NULL COMMENT 'المطالبة',
    `quotation_id` INT NULL,
    `invoice_date` DATE NOT NULL COMMENT 'تاريخ الإصدار',
    `due_date` DATE NULL COMMENT 'تاريخ الاستحقاق',
    `subtotal` DECIMAL(12,2) DEFAULT 0,
    `discount` DECIMAL(12,2) DEFAULT 0,
    `vat_rate` DECIMAL(5,2) DEFAULT 15,
    `vat_amount` DECIMAL(12,2) DEFAULT 0,
    `total` DECIMAL(12,2) DEFAULT 0,
    `paid_amount` DECIMAL(12,2) DEFAULT 0,
    `status` VARCHAR(20) DEFAULT 'unpaid',
    `notes` TEXT NULL,
    `qr_code` TEXT NULL COMMENT 'QR Code data',
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`claim_id`) REFERENCES `claims`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_number` (`invoice_number`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_date` (`invoice_date`),
    INDEX `idx_due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 18. جدول بنود الفواتير (Invoice Items)
-- =====================================================
CREATE TABLE IF NOT EXISTS `invoice_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `invoice_id` INT NOT NULL,
    `service_id` INT NULL,
    `description` VARCHAR(500) NOT NULL,
    `quantity` INT DEFAULT 1,
    `unit_price` DECIMAL(12,2) DEFAULT 0,
    `total` DECIMAL(12,2) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE SET NULL,
    INDEX `idx_invoice` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 19. جدول المدفوعات (Payments)
-- =====================================================
CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `payment_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'رقم السند',
    `client_id` INT NOT NULL,
    `invoice_id` INT NULL,
    `claim_id` INT NULL,
    `amount` DECIMAL(12,2) NOT NULL COMMENT 'المبلغ',
    `payment_type` VARCHAR(30) NOT NULL COMMENT 'نوع الدفع',
    `payment_date` DATE NOT NULL COMMENT 'تاريخ الدفع',
    `reference_number` VARCHAR(100) NULL COMMENT 'رقم المرجع',
    `bank_name` VARCHAR(100) NULL COMMENT 'اسم البنك',
    `notes` TEXT NULL,
    `attachment` VARCHAR(500) NULL COMMENT 'مرفق إيصال',
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`claim_id`) REFERENCES `claims`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_invoice` (`invoice_id`),
    INDEX `idx_date` (`payment_date`),
    INDEX `idx_type` (`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 20. جدول المكالمات (Calls)
-- =====================================================
CREATE TABLE IF NOT EXISTS `calls` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `user_id` INT NOT NULL COMMENT 'الموظف',
    `call_type` ENUM('incoming','outgoing') NOT NULL COMMENT 'نوع المكالمة',
    `call_date` DATETIME NOT NULL COMMENT 'تاريخ المكالمة',
    `duration` INT NULL COMMENT 'المدة بالدقائق',
    `result` VARCHAR(30) NULL COMMENT 'نتيجة المكالمة',
    `notes` TEXT NULL COMMENT 'ملاحظات المكالمة',
    `follow_up_date` DATE NULL COMMENT 'تاريخ المتابعة',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_date` (`call_date`),
    INDEX `idx_result` (`result`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 21. جدول رسائل الواتساب (WhatsApp Messages)
-- =====================================================
CREATE TABLE IF NOT EXISTS `whatsapp_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `direction` ENUM('sent','received') NOT NULL,
    `message` TEXT NOT NULL COMMENT 'نص الرسالة',
    `message_type` VARCHAR(30) DEFAULT 'text' COMMENT 'نوع الرسالة',
    `related_type` VARCHAR(30) NULL COMMENT 'النوع المرتبط',
    `related_id` INT NULL COMMENT 'المعرف المرتبط',
    `sent_at` DATETIME NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_date` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 22. جدول البريد الإلكتروني (Emails)
-- =====================================================
CREATE TABLE IF NOT EXISTS `emails` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `direction` ENUM('sent','received') NOT NULL,
    `subject` VARCHAR(500) NOT NULL COMMENT 'الموضوع',
    `body` TEXT NOT NULL COMMENT 'المحتوى',
    `to_email` VARCHAR(150) NULL,
    `from_email` VARCHAR(150) NULL,
    `attachments` TEXT NULL COMMENT 'المرفقات (JSON)',
    `sent_at` DATETIME NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_date` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 23. جدول المهام (Tasks)
-- =====================================================
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL COMMENT 'عنوان المهمة',
    `description` TEXT NULL,
    `service_order_id` INT NULL COMMENT 'طلب الخدمة',
    `assigned_to` INT NULL COMMENT 'الموظف المسؤول',
    `client_id` INT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
    `start_date` DATE NULL,
    `due_date` DATE NULL,
    `completed_date` DATE NULL,
    `progress` INT DEFAULT 0 COMMENT 'نسبة الإنجاز',
    `notes` TEXT NULL,
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`service_order_id`) REFERENCES `service_orders`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_status` (`status`),
    INDEX `idx_assigned` (`assigned_to`),
    INDEX `idx_due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 24. جدول المستندات (Documents)
-- =====================================================
CREATE TABLE IF NOT EXISTS `documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL COMMENT 'عنوان المستند',
    `document_type` VARCHAR(30) NOT NULL COMMENT 'نوع المستند',
    `file_path` VARCHAR(500) NOT NULL,
    `file_name` VARCHAR(200) NOT NULL,
    `file_size` INT NULL,
    `file_type` VARCHAR(20) NULL,
    `client_id` INT NULL,
    `company_id` INT NULL,
    `related_type` VARCHAR(30) NULL COMMENT 'النوع المرتبط',
    `related_id` INT NULL,
    `notes` TEXT NULL,
    `uploaded_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_type` (`document_type`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_company` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 25. جدول الإشعارات (Notifications)
-- =====================================================
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `type` ENUM('info','success','warning','danger') DEFAULT 'info',
    `link` VARCHAR(500) NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_read` (`is_read`),
    INDEX `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 26. جدول سجل المراقبة (Audit Log)
-- =====================================================
CREATE TABLE IF NOT EXISTS `audit_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(50) NOT NULL COMMENT 'الإجراء',
    `module` VARCHAR(50) NOT NULL COMMENT 'الوحدة',
    `record_id` INT NULL COMMENT 'معرف السجل',
    `details` TEXT NULL COMMENT 'التفاصيل',
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_module` (`module`),
    INDEX `idx_action` (`action`),
    INDEX `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 27. جدول الإعدادات (Settings)
-- =====================================================
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NULL,
    `setting_group` VARCHAR(50) DEFAULT 'general',
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 28. جدول سجل النشاط (Activity Log)
-- =====================================================
CREATE TABLE IF NOT EXISTS `activity_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT NULL,
    `user_id` INT NULL,
    `activity_type` VARCHAR(30) NOT NULL COMMENT 'نوع النشاط',
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `related_type` VARCHAR(30) NULL,
    `related_id` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE CASCADE,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_type` (`activity_type`),
    INDEX `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
