-- =====================================================
-- نظام كنوز الإنجاز - البيانات الأولية
-- =====================================================

USE `knouz_db`;

-- =====================================================
-- الأدوار
-- =====================================================
INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'مدير النظام', 'admin', 'صلاحية كاملة على جميع الوحدات'),
(2, 'مدير العمليات', 'operations_manager', 'متابعة الخدمات والطلبات والموظفين'),
(3, 'مدير المبيعات', 'sales_manager', 'إدارة العملاء والفرص البيعية وعروض الأسعار'),
(4, 'المحاسب', 'accountant', 'إدارة المطالبات والفواتير والمدفوعات'),
(5, 'موظف الخدمات', 'service_employee', 'تنفيذ الخدمات ومتابعة الطلبات'),
(6, 'عميل', 'client', 'صلاحيات محدودة للاطلاع على خدماته');

-- =====================================================
-- الصلاحيات
-- =====================================================
INSERT INTO `permissions` (`name`, `slug`, `module`) VALUES
-- العملاء
('عرض العملاء', 'clients.view', 'clients'),
('إضافة عميل', 'clients.create', 'clients'),
('تعديل عميل', 'clients.edit', 'clients'),
('حذف عميل', 'clients.delete', 'clients'),
('تصدير العملاء', 'clients.export', 'clients'),

-- الشركات
('عرض الشركات', 'companies.view', 'companies'),
('إضافة شركة', 'companies.create', 'companies'),
('تعديل شركة', 'companies.edit', 'companies'),
('حذف شركة', 'companies.delete', 'companies'),
('تصدير الشركات', 'companies.export', 'companies'),

-- الخدمات
('عرض الخدمات', 'services.view', 'services'),
('إضافة خدمة', 'services.create', 'services'),
('تعديل خدمة', 'services.edit', 'services'),
('حذف خدمة', 'services.delete', 'services'),

-- طلبات الخدمات
('عرض الطلبات', 'orders.view', 'orders'),
('إضافة طلب', 'orders.create', 'orders'),
('تعديل طلب', 'orders.edit', 'orders'),
('حذف طلب', 'orders.delete', 'orders'),
('تصدير الطلبات', 'orders.export', 'orders'),

-- عروض الأسعار
('عرض عروض الأسعار', 'quotations.view', 'quotations'),
('إضافة عرض سعر', 'quotations.create', 'quotations'),
('تعديل عرض سعر', 'quotations.edit', 'quotations'),
('حذف عرض سعر', 'quotations.delete', 'quotations'),
('اعتماد عرض سعر', 'quotations.approve', 'quotations'),
('طباعة عرض سعر', 'quotations.print', 'quotations'),
('تصدير عروض الأسعار', 'quotations.export', 'quotations'),

-- المطالبات
('عرض المطالبات', 'claims.view', 'claims'),
('إضافة مطالبة', 'claims.create', 'claims'),
('تعديل مطالبة', 'claims.edit', 'claims'),
('حذف مطالبة', 'claims.delete', 'claims'),
('تصدير المطالبات', 'claims.export', 'claims'),

-- الفواتير
('عرض الفواتير', 'invoices.view', 'invoices'),
('إضافة فاتورة', 'invoices.create', 'invoices'),
('تعديل فاتورة', 'invoices.edit', 'invoices'),
('حذف فاتورة', 'invoices.delete', 'invoices'),
('طباعة فاتورة', 'invoices.print', 'invoices'),
('تصدير الفواتير', 'invoices.export', 'invoices'),

-- المدفوعات
('عرض المدفوعات', 'payments.view', 'payments'),
('إضافة دفعة', 'payments.create', 'payments'),
('تعديل دفعة', 'payments.edit', 'payments'),
('حذف دفعة', 'payments.delete', 'payments'),
('تصدير المدفوعات', 'payments.export', 'payments'),

-- الموظفين
('عرض الموظفين', 'employees.view', 'employees'),
('إضافة موظف', 'employees.create', 'employees'),
('تعديل موظف', 'employees.edit', 'employees'),
('حذف موظف', 'employees.delete', 'employees'),

-- المهام
('عرض المهام', 'tasks.view', 'tasks'),
('إضافة مهمة', 'tasks.create', 'tasks'),
('تعديل مهمة', 'tasks.edit', 'tasks'),
('حذف مهمة', 'tasks.delete', 'tasks'),

-- التواصل
('عرض التواصل', 'communications.view', 'communications'),
('إضافة تواصل', 'communications.create', 'communications'),

-- المستندات
('عرض المستندات', 'documents.view', 'documents'),
('إضافة مستند', 'documents.create', 'documents'),
('حذف مستند', 'documents.delete', 'documents'),

-- التقارير
('عرض التقارير', 'reports.view', 'reports'),
('تصدير التقارير', 'reports.export', 'reports'),

-- الإعدادات
('عرض الإعدادات', 'settings.view', 'settings'),
('تعديل الإعدادات', 'settings.edit', 'settings'),
('إدارة المستخدمين', 'settings.users', 'settings'),
('عرض سجل المراقبة', 'settings.audit', 'settings'),

-- الفرص البيعية
('عرض الفرص البيعية', 'opportunities.view', 'opportunities'),
('إضافة فرصة', 'opportunities.create', 'opportunities'),
('تعديل فرصة', 'opportunities.edit', 'opportunities'),
('حذف فرصة', 'opportunities.delete', 'opportunities');

-- =====================================================
-- صلاحيات مدير النظام (جميع الصلاحيات)
-- =====================================================
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM permissions;

-- صلاحيات مدير العمليات
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, id FROM permissions WHERE module IN ('clients', 'companies', 'services', 'orders', 'tasks', 'employees', 'communications', 'documents', 'reports');

-- صلاحيات مدير المبيعات
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, id FROM permissions WHERE module IN ('clients', 'companies', 'quotations', 'opportunities', 'communications', 'reports');

-- صلاحيات المحاسب
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 4, id FROM permissions WHERE module IN ('clients', 'claims', 'invoices', 'payments', 'quotations', 'reports');

-- صلاحيات موظف الخدمات
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 5, id FROM permissions WHERE slug IN ('clients.view', 'companies.view', 'services.view', 'orders.view', 'orders.edit', 'tasks.view', 'tasks.edit', 'communications.view', 'communications.create', 'documents.view', 'documents.create');

-- =====================================================
-- المستخدم الافتراضي (مدير النظام)
-- كلمة المرور: admin123
-- =====================================================
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `phone`, `role_id`, `is_active`) VALUES
('admin', 'admin@knouz.sa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'مدير النظام', '0500000000', 1, 1);

-- =====================================================
-- تصنيفات الخدمات
-- =====================================================
INSERT INTO `service_categories` (`name`, `slug`, `icon`, `color`, `sort_order`) VALUES
('وزارة التجارة', 'moc', 'fa-building-columns', '#2196F3', 1),
('منصة قوى', 'qiwa', 'fa-users-gear', '#4CAF50', 2),
('التأمينات الاجتماعية', 'gosi', 'fa-shield-halved', '#FF9800', 3),
('مدد', 'mudad', 'fa-money-bill-wave', '#9C27B0', 4),
('مقيم', 'muqeem', 'fa-passport', '#F44336', 5),
('الزكاة والضريبة والجمارك', 'zatca', 'fa-file-invoice-dollar', '#607D8B', 6),
('بلدي', 'balady', 'fa-city', '#795548', 7),
('خدمات أخرى', 'other', 'fa-cogs', '#9E9E9E', 8);

-- =====================================================
-- الخدمات الافتراضية
-- =====================================================
INSERT INTO `services` (`category_id`, `name`, `description`, `platform`, `execution_days`, `default_price`, `default_cost`, `is_active`, `sort_order`) VALUES
-- وزارة التجارة
(1, 'إصدار سجل تجاري', 'إصدار سجل تجاري جديد', 'moc', 3, 1500.00, 500.00, 1, 1),
(1, 'تجديد سجل تجاري', 'تجديد سجل تجاري قائم', 'moc', 2, 1000.00, 400.00, 1, 2),
(1, 'تعديل سجل تجاري', 'تعديل بيانات السجل التجاري', 'moc', 3, 800.00, 300.00, 1, 3),
(1, 'شطب سجل تجاري', 'شطب سجل تجاري', 'moc', 5, 500.00, 200.00, 1, 4),
(1, 'حجز اسم تجاري', 'حجز اسم تجاري جديد', 'moc', 1, 300.00, 100.00, 1, 5),

-- منصة قوى
(2, 'إصدار رخصة عمل', 'إصدار رخصة عمل جديدة', 'qiwa', 2, 500.00, 100.00, 1, 1),
(2, 'نقل خدمات', 'نقل خدمات عامل', 'qiwa', 3, 800.00, 200.00, 1, 2),
(2, 'تجديد عقد عمل', 'تجديد عقد عمل عامل', 'qiwa', 2, 400.00, 100.00, 1, 3),
(2, 'إصدار تأشيرة', 'إصدار تأشيرة استقدام', 'qiwa', 5, 1500.00, 500.00, 1, 4),

-- التأمينات
(3, 'إضافة مشترك', 'تسجيل مشترك جديد في التأمينات', 'gosi', 1, 300.00, 50.00, 1, 1),
(3, 'استبعاد مشترك', 'استبعاد مشترك من التأمينات', 'gosi', 1, 200.00, 50.00, 1, 2),

-- مدد
(4, 'رفع ملف حماية الأجور', 'رفع ملف حماية الأجور الشهري', 'mudad', 1, 500.00, 100.00, 1, 1),

-- مقيم
(5, 'إصدار إقامة', 'إصدار إقامة جديدة', 'muqeem', 3, 800.00, 200.00, 1, 1),
(5, 'تجديد إقامة', 'تجديد إقامة', 'muqeem', 2, 600.00, 200.00, 1, 2),

-- الزكاة والضريبة
(6, 'تسجيل ضريبة القيمة المضافة', 'تسجيل في ضريبة القيمة المضافة', 'zatca', 3, 1000.00, 200.00, 1, 1),
(6, 'تقديم إقرار ضريبي', 'تقديم الإقرار الضريبي الدوري', 'zatca', 2, 800.00, 200.00, 1, 2),

-- بلدي
(7, 'إصدار رخصة بلدية', 'إصدار رخصة بلدية جديدة', 'balady', 5, 1500.00, 500.00, 1, 1),
(7, 'تجديد رخصة بلدية', 'تجديد رخصة بلدية', 'balady', 3, 1000.00, 300.00, 1, 2);

-- =====================================================
-- الإعدادات الافتراضية
-- =====================================================
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('company_name_ar', 'كنوز الإنجاز لخدمات الأعمال', 'company'),
('company_name_en', 'Knouz Al-Enjaz Business Services', 'company'),
('company_phone', '', 'company'),
('company_email', '', 'company'),
('company_address', 'المملكة العربية السعودية', 'company'),
('company_cr_number', '', 'company'),
('company_vat_number', '', 'company'),
('company_website', '', 'company'),
('vat_rate', '15', 'financial'),
('currency', 'SAR', 'financial'),
('quotation_validity_days', '30', 'financial'),
('invoice_due_days', '30', 'financial'),
('quotation_terms', 'الأسعار لا تشمل ضريبة القيمة المضافة\nالعرض ساري لمدة 30 يوم من تاريخه\nيتم السداد خلال 30 يوم من تاريخ الفاتورة', 'templates'),
('quotation_prefix', 'QT', 'numbering'),
('claim_prefix', 'CL', 'numbering'),
('invoice_prefix', 'INV', 'numbering'),
('payment_prefix', 'PAY', 'numbering'),
('order_prefix', 'SO', 'numbering'),
('client_prefix', 'CLI', 'numbering');
