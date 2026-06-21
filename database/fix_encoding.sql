-- =====================================================
-- إصلاح بيانات الترميز + إعادة إدخال البيانات التأسيسية
-- =====================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

USE `knouz_db`;

-- إصلاح بيانات الإعدادات
DELETE FROM `settings`;
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('company_name_ar', 'كنوز الإنجاز لخدمات الأعمال', 'company'),
('company_name_en', 'Knouz Al-Enjaz Business Services', 'company'),
('company_phone', '', 'company'),
('company_email', '', 'company'),
('company_address', '', 'company'),
('company_cr_number', '', 'company'),
('company_vat_number', '', 'company'),
('company_website', '', 'company'),
('company_city', 'الرياض', 'company'),
('vat_rate', '15', 'financial'),
('currency', 'SAR', 'financial'),
('quotation_prefix', 'QT', 'quotation'),
('claim_prefix', 'CL', 'quotation'),
('invoice_prefix', 'INV', 'quotation'),
('payment_prefix', 'PAY', 'quotation'),
('quotation_validity_days', '30', 'quotation'),
('quotation_terms', 'يسري هذا العرض لمدة 30 يوم من تاريخه', 'quotation'),
('cr_expiry_alert_days', '30', 'alerts'),
('invoice_due_alert_days', '7', 'alerts');

-- إصلاح بيانات المستخدم admin
UPDATE `users` SET 
    `full_name` = 'مدير النظام',
    `email` = 'admin@knouz.sa'
WHERE `username` = 'admin';

-- إصلاح/إعادة إدخال الأدوار
UPDATE `roles` SET `name` = 'مدير النظام' WHERE `slug` = 'admin';
UPDATE `roles` SET `name` = 'مدير' WHERE `slug` = 'manager';
UPDATE `roles` SET `name` = 'موظف' WHERE `slug` = 'employee';
UPDATE `roles` SET `name` = 'محاسب' WHERE `slug` = 'accountant';

-- إصلاح تصنيفات الخدمات
UPDATE `service_categories` SET `name` = 'خدمات وزارة التجارة' WHERE `slug` = 'moci';
UPDATE `service_categories` SET `name` = 'خدمات وزارة الموارد البشرية' WHERE `slug` = 'mol';
UPDATE `service_categories` SET `name` = 'خدمات الزكاة والضريبة' WHERE `slug` = 'zatca';
UPDATE `service_categories` SET `name` = 'خدمات التأمينات الاجتماعية' WHERE `slug` = 'gosi';
UPDATE `service_categories` SET `name` = 'خدمات البلديات' WHERE `slug` = 'balady';
UPDATE `service_categories` SET `name` = 'خدمات منصة مقيم' WHERE `slug` = 'muqeem';
UPDATE `service_categories` SET `name` = 'خدمات منصة قوى' WHERE `slug` = 'qiwa';
UPDATE `service_categories` SET `name` = 'خدمات أخرى' WHERE `slug` = 'other';

-- إضافة التصنيفات إذا لم تكن موجودة
INSERT IGNORE INTO `service_categories` (`name`, `slug`, `icon`, `color`, `sort_order`) VALUES
('خدمات وزارة التجارة', 'moci', 'fa-store', '#D4A853', 1),
('خدمات وزارة الموارد البشرية', 'mol', 'fa-users', '#2196F3', 2),
('خدمات الزكاة والضريبة', 'zatca', 'fa-receipt', '#4CAF50', 3),
('خدمات التأمينات الاجتماعية', 'gosi', 'fa-shield-halved', '#FF9800', 4),
('خدمات البلديات', 'balady', 'fa-city', '#9C27B0', 5),
('خدمات منصة مقيم', 'muqeem', 'fa-passport', '#009688', 6),
('خدمات منصة قوى', 'qiwa', 'fa-briefcase', '#795548', 7),
('خدمات أخرى', 'other', 'fa-ellipsis', '#607D8B', 8);

-- إضافة خدمات نموذجية
INSERT IGNORE INTO `services` (`category_id`, `name`, `description`, `platform`, `execution_days`, `default_price`, `default_cost`, `is_active`, `sort_order`) VALUES
((SELECT id FROM service_categories WHERE slug='moci'), 'إصدار سجل تجاري', 'إصدار سجل تجاري رئيسي أو فرعي', 'وزارة التجارة', 3, 500.00, 200.00, 1, 1),
((SELECT id FROM service_categories WHERE slug='moci'), 'تجديد سجل تجاري', 'تجديد السجل التجاري', 'وزارة التجارة', 2, 400.00, 200.00, 1, 2),
((SELECT id FROM service_categories WHERE slug='moci'), 'تعديل سجل تجاري', 'تعديل بيانات السجل التجاري', 'وزارة التجارة', 2, 300.00, 100.00, 1, 3),
((SELECT id FROM service_categories WHERE slug='mol'), 'إصدار رخصة عمل', 'إصدار رخصة عمل للعامل', 'وزارة الموارد البشرية', 1, 300.00, 100.00, 1, 4),
((SELECT id FROM service_categories WHERE slug='mol'), 'نقل كفالة', 'نقل كفالة عامل', 'وزارة الموارد البشرية', 5, 800.00, 300.00, 1, 5),
((SELECT id FROM service_categories WHERE slug='mol'), 'تعديل مهنة', 'تعديل المسمى الوظيفي', 'وزارة الموارد البشرية', 3, 250.00, 100.00, 1, 6),
((SELECT id FROM service_categories WHERE slug='zatca'), 'تسجيل ضريبي', 'التسجيل في ضريبة القيمة المضافة', 'هيئة الزكاة', 3, 500.00, 150.00, 1, 7),
((SELECT id FROM service_categories WHERE slug='zatca'), 'إقرار ضريبي', 'تقديم الإقرار الضريبي الدوري', 'هيئة الزكاة', 2, 600.00, 200.00, 1, 8),
((SELECT id FROM service_categories WHERE slug='gosi'), 'تسجيل منشأة', 'تسجيل منشأة في التأمينات', 'التأمينات الاجتماعية', 3, 400.00, 150.00, 1, 9),
((SELECT id FROM service_categories WHERE slug='gosi'), 'تسجيل موظف', 'تسجيل موظف في التأمينات', 'التأمينات الاجتماعية', 1, 200.00, 50.00, 1, 10),
((SELECT id FROM service_categories WHERE slug='qiwa'), 'توثيق عقد عمل', 'توثيق عقد العمل في منصة قوى', 'قوى', 2, 300.00, 100.00, 1, 11),
((SELECT id FROM service_categories WHERE slug='muqeem'), 'تجديد إقامة', 'تجديد إقامة عامل', 'مقيم', 2, 350.00, 150.00, 1, 12);

-- إضافة صلاحيات أساسية
INSERT IGNORE INTO `permissions` (`name`, `slug`, `module`) VALUES
-- العملاء
('عرض العملاء', 'clients.view', 'clients'),
('إضافة عميل', 'clients.create', 'clients'),
('تعديل عميل', 'clients.edit', 'clients'),
('حذف عميل', 'clients.delete', 'clients'),
-- الشركات
('عرض الشركات', 'companies.view', 'companies'),
('إضافة شركة', 'companies.create', 'companies'),
('تعديل شركة', 'companies.edit', 'companies'),
('حذف شركة', 'companies.delete', 'companies'),
-- الخدمات
('عرض الخدمات', 'services.view', 'services'),
('إضافة خدمة', 'services.create', 'services'),
('تعديل خدمة', 'services.edit', 'services'),
('حذف خدمة', 'services.delete', 'services'),
-- الطلبات
('عرض الطلبات', 'orders.view', 'orders'),
('إضافة طلب', 'orders.create', 'orders'),
('تعديل طلب', 'orders.edit', 'orders'),
('حذف طلب', 'orders.delete', 'orders'),
-- عروض الأسعار
('عرض العروض', 'quotations.view', 'quotations'),
('إنشاء عرض', 'quotations.create', 'quotations'),
('تعديل عرض', 'quotations.edit', 'quotations'),
('حذف عرض', 'quotations.delete', 'quotations'),
('اعتماد عرض', 'quotations.approve', 'quotations'),
('طباعة عرض', 'quotations.print', 'quotations'),
-- المطالبات
('عرض المطالبات', 'claims.view', 'claims'),
('إنشاء مطالبة', 'claims.create', 'claims'),
('تعديل مطالبة', 'claims.edit', 'claims'),
('حذف مطالبة', 'claims.delete', 'claims'),
-- الفواتير
('عرض الفواتير', 'invoices.view', 'invoices'),
('إنشاء فاتورة', 'invoices.create', 'invoices'),
('تعديل فاتورة', 'invoices.edit', 'invoices'),
('حذف فاتورة', 'invoices.delete', 'invoices'),
('طباعة فاتورة', 'invoices.print', 'invoices'),
-- المدفوعات
('عرض المدفوعات', 'payments.view', 'payments'),
('إضافة دفعة', 'payments.create', 'payments'),
('تعديل دفعة', 'payments.edit', 'payments'),
('حذف دفعة', 'payments.delete', 'payments'),
-- الفرص البيعية
('عرض الفرص', 'opportunities.view', 'opportunities'),
('إضافة فرصة', 'opportunities.create', 'opportunities'),
('تعديل فرصة', 'opportunities.edit', 'opportunities'),
('حذف فرصة', 'opportunities.delete', 'opportunities'),
-- التواصل
('عرض التواصل', 'communications.view', 'communications'),
('إضافة تواصل', 'communications.create', 'communications'),
-- المهام
('عرض المهام', 'tasks.view', 'tasks'),
('إضافة مهمة', 'tasks.create', 'tasks'),
('تعديل مهمة', 'tasks.edit', 'tasks'),
('حذف مهمة', 'tasks.delete', 'tasks'),
-- الموظفين
('عرض الموظفين', 'employees.view', 'employees'),
('إضافة موظف', 'employees.create', 'employees'),
('تعديل موظف', 'employees.edit', 'employees'),
('حذف موظف', 'employees.delete', 'employees'),
-- المستندات
('عرض المستندات', 'documents.view', 'documents'),
('إضافة مستند', 'documents.create', 'documents'),
('حذف مستند', 'documents.delete', 'documents'),
-- التقارير
('عرض التقارير', 'reports.view', 'reports'),
-- الإعدادات
('عرض الإعدادات', 'settings.view', 'settings'),
('تعديل الإعدادات', 'settings.edit', 'settings'),
('إدارة المستخدمين', 'settings.users', 'settings'),
('سجل المراقبة', 'settings.audit', 'settings');

-- منح جميع الصلاحيات لدور المدير (admin)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT (SELECT id FROM roles WHERE slug = 'admin'), id FROM permissions;

-- منح صلاحيات المدير
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT (SELECT id FROM roles WHERE slug = 'manager'), id FROM permissions
WHERE slug NOT IN ('settings.edit', 'settings.users', 'settings.audit');

-- منح صلاحيات الموظف
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT (SELECT id FROM roles WHERE slug = 'employee'), id FROM permissions
WHERE slug IN ('clients.view','companies.view','services.view','orders.view','orders.create','orders.edit',
'quotations.view','quotations.create','quotations.edit','quotations.print',
'claims.view','invoices.view','payments.view',
'tasks.view','tasks.create','tasks.edit',
'communications.view','communications.create',
'documents.view','documents.create');

-- منح صلاحيات المحاسب
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT (SELECT id FROM roles WHERE slug = 'accountant'), id FROM permissions
WHERE slug IN ('clients.view','companies.view','services.view','orders.view',
'quotations.view','quotations.print',
'claims.view','claims.create','claims.edit',
'invoices.view','invoices.create','invoices.edit','invoices.print',
'payments.view','payments.create','payments.edit',
'reports.view','documents.view');
