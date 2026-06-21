-- =====================================================
-- تحديثات الـ schema لمطابقة كود PHP
-- يتم تشغيله بعد schema.sql الأساسي
-- =====================================================

USE `knouz_db`;

-- إضافة أعمدة مفقودة في quotations
ALTER TABLE `quotations` 
    ADD COLUMN IF NOT EXISTS `valid_until` DATE NULL AFTER `quotation_date`,
    ADD COLUMN IF NOT EXISTS `terms` TEXT NULL AFTER `notes`;

-- تحديث quotations: نسخ البيانات القديمة إن وجدت
UPDATE `quotations` SET `valid_until` = `validity_date` WHERE `valid_until` IS NULL AND `validity_date` IS NOT NULL;
UPDATE `quotations` SET `terms` = `terms_conditions` WHERE `terms` IS NULL AND `terms_conditions` IS NOT NULL;

-- إضافة أعمدة مفقودة في claims
ALTER TABLE `claims` 
    ADD COLUMN IF NOT EXISTS `claim_date` DATE NULL AFTER `company_id`,
    ADD COLUMN IF NOT EXISTS `discount` DECIMAL(12,2) DEFAULT 0 AFTER `vat_amount`,
    MODIFY COLUMN `quotation_id` INT NULL COMMENT 'عرض السعر (اختياري)';

-- إضافة أعمدة بنود المطالبات
CREATE TABLE IF NOT EXISTS `claim_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `claim_id` INT NOT NULL,
    `service_id` INT NULL,
    `description` VARCHAR(500) NOT NULL COMMENT 'الوصف',
    `quantity` INT DEFAULT 1,
    `unit_price` DECIMAL(12,2) DEFAULT 0,
    `total` DECIMAL(12,2) DEFAULT 0,
    FOREIGN KEY (`claim_id`) REFERENCES `claims`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE SET NULL,
    INDEX `idx_claim` (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة عمود payment_method في payments (بدل payment_type)
ALTER TABLE `payments` 
    ADD COLUMN IF NOT EXISTS `payment_method` VARCHAR(30) NULL AFTER `payment_date`;

-- نسخ بيانات payment_type إلى payment_method
UPDATE `payments` SET `payment_method` = `payment_type` WHERE `payment_method` IS NULL AND `payment_type` IS NOT NULL;

-- إضافة أعمدة مفقودة في sales_opportunities
ALTER TABLE `sales_opportunities`
    ADD COLUMN IF NOT EXISTS `value` DECIMAL(12,2) DEFAULT 0 AFTER `title`,
    ADD COLUMN IF NOT EXISTS `stage` VARCHAR(30) DEFAULT 'lead' AFTER `value`;

-- نسخ البيانات إذا كانت موجودة
UPDATE `sales_opportunities` SET `value` = `expected_amount` WHERE `value` = 0 AND `expected_amount` > 0;
UPDATE `sales_opportunities` SET `stage` = `status` WHERE `stage` = 'lead' AND `status` != 'new';

-- تحديث جدول المهام ليشير assigned_to لـ users بدل employees
ALTER TABLE `tasks` 
    DROP FOREIGN KEY IF EXISTS `tasks_ibfk_2`,
    ADD COLUMN IF NOT EXISTS `completed_at` DATETIME NULL AFTER `completed_date`;

-- إضافة عمود display_name في roles
ALTER TABLE `roles` 
    ADD COLUMN IF NOT EXISTS `display_name` VARCHAR(100) NULL AFTER `name`;

-- إضافة عمود last_login في users
ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `last_login` DATETIME NULL AFTER `last_login_at`;

-- نسخ last_login_at إلى last_login
UPDATE `users` SET `last_login` = `last_login_at` WHERE `last_login` IS NULL AND `last_login_at` IS NOT NULL;

-- إضافة بيانات الأدوار الافتراضية
INSERT IGNORE INTO `roles` (`id`, `name`, `display_name`, `slug`, `description`) VALUES
(1, 'admin', 'مدير النظام', 'admin', 'صلاحيات كاملة للنظام'),
(2, 'manager', 'مدير', 'manager', 'صلاحيات الإدارة'),
(3, 'employee', 'موظف', 'employee', 'صلاحيات الموظف'),
(4, 'accountant', 'محاسب', 'accountant', 'صلاحيات مالية');

-- إضافة الإعدادات الافتراضية
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('company_name_ar', 'كنوز الإنجاز', 'company'),
('company_name_en', 'Knouz Al-Enjaz', 'company'),
('company_phone', '', 'company'),
('company_email', '', 'company'),
('company_city', 'الرياض', 'company'),
('company_address', '', 'company'),
('company_cr', '', 'company'),
('company_vat_number', '', 'company'),
('vat_rate', '15', 'financial'),
('currency', 'SAR', 'financial'),
('quotation_validity_days', '30', 'quotation'),
('quotation_prefix', 'QT', 'quotation'),
('quotation_terms', '', 'quotation'),
('cr_expiry_alert_days', '30', 'alerts'),
('invoice_due_alert_days', '7', 'alerts');

-- إضافة عمود created_by في company_documents
ALTER TABLE `company_documents` 
    ADD COLUMN IF NOT EXISTS `created_by` INT NULL AFTER `uploaded_by`;
