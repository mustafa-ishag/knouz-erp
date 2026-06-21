<?php
/**
 * الثوابت العامة للنظام
 */

// حالات طلب الخدمة
define('SERVICE_ORDER_STATUS', [
    'new' => 'جديد',
    'in_progress' => 'جاري التنفيذ',
    'pending_client' => 'بانتظار العميل',
    'pending_government' => 'بانتظار الجهة الحكومية',
    'completed' => 'مكتمل',
    'cancelled' => 'ملغي',
]);

// حالات عرض السعر
define('QUOTATION_STATUS', [
    'draft' => 'مسودة',
    'sent' => 'مرسل',
    'approved' => 'معتمد',
    'rejected' => 'مرفوض',
    'expired' => 'منتهي الصلاحية',
]);

// حالات المطالبة المالية
define('CLAIM_STATUS', [
    'draft' => 'مسودة',
    'sent' => 'مرسلة',
    'due' => 'مستحقة',
    'partially_paid' => 'مدفوعة جزئياً',
    'paid' => 'مدفوعة بالكامل',
    'overdue' => 'متأخرة',
]);

// حالات الفاتورة
define('INVOICE_STATUS', [
    'unpaid' => 'غير مدفوعة',
    'partially_paid' => 'مدفوعة جزئياً',
    'paid' => 'مدفوعة بالكامل',
    'cancelled' => 'ملغاة',
]);

// حالات الفرصة البيعية
define('OPPORTUNITY_STATUS', [
    'new' => 'فرصة جديدة',
    'contacting' => 'جاري التواصل',
    'quote_sent' => 'عرض سعر مرسل',
    'negotiating' => 'تحت التفاوض',
    'contracted' => 'تم التعاقد',
    'lost' => 'فرصة مفقودة',
]);

// أنواع المدفوعات
define('PAYMENT_TYPES', [
    'bank_transfer' => 'تحويل بنكي',
    'cash' => 'نقداً',
    'deposit' => 'عربون',
    'check' => 'شيك',
    'online' => 'دفع إلكتروني',
]);

// نتائج المكالمات
define('CALL_RESULTS', [
    'interested' => 'مهتم',
    'follow_up' => 'يحتاج متابعة',
    'quote_sent' => 'تم إرسال عرض',
    'sold' => 'تم البيع',
    'not_interested' => 'غير مهتم',
    'no_answer' => 'لم يرد',
    'busy' => 'مشغول',
]);

// أنواع المكالمات
define('CALL_TYPES', [
    'incoming' => 'واردة',
    'outgoing' => 'صادرة',
]);

// حالات المهام
define('TASK_STATUS', [
    'pending' => 'قيد الانتظار',
    'in_progress' => 'جاري التنفيذ',
    'completed' => 'مكتمل',
    'cancelled' => 'ملغي',
    'on_hold' => 'معلق',
]);

// أنواع المستندات
define('DOCUMENT_TYPES', [
    'contract' => 'عقد',
    'invoice' => 'فاتورة',
    'quotation' => 'عرض سعر',
    'claim' => 'مطالبة مالية',
    'cr' => 'سجل تجاري',
    'letter' => 'خطاب',
    'certificate' => 'شهادة',
    'other' => 'أخرى',
]);

// المنصات الحكومية
define('GOV_PLATFORMS', [
    'moc' => 'وزارة التجارة',
    'qiwa' => 'قوى',
    'gosi' => 'التأمينات الاجتماعية',
    'mudad' => 'مدد',
    'muqeem' => 'مقيم',
    'zatca' => 'الزكاة والضريبة والجمارك',
    'balady' => 'بلدي',
    'mol' => 'وزارة العمل',
    'absher' => 'أبشر',
    'other' => 'أخرى',
]);

// أدوار المستخدمين
define('USER_ROLES', [
    'admin' => 'مدير النظام',
    'operations_manager' => 'مدير العمليات',
    'sales_manager' => 'مدير المبيعات',
    'accountant' => 'المحاسب',
    'service_employee' => 'موظف الخدمات',
    'client' => 'عميل',
]);

// حالة السداد
define('PAYMENT_STATUS', [
    'paid' => 'مدفوع',
    'unpaid' => 'غير مدفوع',
    'partial' => 'مدفوع جزئياً',
]);

// حالات الموظف
define('EMPLOYEE_STATUS', [
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'on_leave' => 'إجازة',
    'terminated' => 'منتهي',
]);

// أنواع النشاط
define('ACTIVITY_TYPES', [
    'call' => 'مكالمة',
    'whatsapp' => 'واتساب',
    'email' => 'بريد إلكتروني',
    'meeting' => 'اجتماع',
    'note' => 'ملاحظة',
    'service' => 'خدمة',
    'payment' => 'دفعة',
    'quotation' => 'عرض سعر',
    'invoice' => 'فاتورة',
]);
