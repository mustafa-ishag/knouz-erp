<?php
/**
 * إعدادات التطبيق العامة
 * نظام كنوز الإنجاز لإدارة العملاء والخدمات والأعمال
 */

return [
    // معلومات التطبيق
    'name' => 'كنوز الإنجاز',
    'name_en' => 'Knouz Al-Enjaz',
    'version' => '1.0.0',
    'description' => 'نظام إدارة العملاء والخدمات والأعمال',
    
    // إعدادات الشركة
    'company' => [
        'name_ar' => 'كنوز الإنجاز لخدمات الأعمال',
        'name_en' => 'Knouz Al-Enjaz Business Services',
        'cr_number' => '', // رقم السجل التجاري
        'vat_number' => '', // رقم ضريبة القيمة المضافة
        'phone' => '',
        'email' => '',
        'website' => '',
        'address_ar' => 'المملكة العربية السعودية',
        'address_en' => 'Kingdom of Saudi Arabia',
        'city' => '',
        'logo' => 'assets/images/logo.png',
    ],
    
    // إعدادات عامة
    'timezone' => 'Asia/Riyadh',
    'locale' => 'ar',
    'direction' => 'rtl',
    'currency' => 'SAR',
    'currency_symbol' => 'ر.س',
    'date_format' => 'Y-m-d',
    'datetime_format' => 'Y-m-d H:i:s',
    'vat_rate' => 15, // نسبة ضريبة القيمة المضافة
    
    // إعدادات الملفات
    'upload' => [
        'max_size' => 10 * 1024 * 1024, // 10 ميجا
        'allowed_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'webp'],
        'upload_path' => 'uploads/',
    ],
    
    // إعدادات الجلسة
    'session' => [
        'lifetime' => 7200, // ساعتين
        'name' => 'knouz_session',
    ],
    
    // إعدادات التقارير
    'reports' => [
        'items_per_page' => 25,
        'export_formats' => ['pdf', 'excel', 'csv'],
    ],
    
    // إعدادات التنبيهات
    'alerts' => [
        'cr_expiry_days' => 30, // تنبيه قبل انتهاء السجل بـ 30 يوم
        'license_expiry_days' => 30,
        'invoice_due_days' => 7,
        'quotation_validity_days' => 30,
    ],
    
    // مسارات النظام
    'base_path' => dirname(__DIR__),
    'base_url' => '/kn',
    'public_path' => dirname(__DIR__) . '/public',
];
