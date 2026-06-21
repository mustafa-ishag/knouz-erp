<?php
/**
 * نقطة الدخول الرئيسية - نظام كنوز الإنجاز
 * Knouz Al-Enjaz ERP & CRM
 */

// تعيين المنطقة الزمنية
date_default_timezone_set('Asia/Riyadh');

// بدء الجلسة
session_start();

// تعريف المسار الأساسي
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('BASE_URL', '/kn/public');

// تحميل الملفات الأساسية
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Helpers.php';
require_once BASE_PATH . '/core/AuditLog.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/RBAC.php';
require_once BASE_PATH . '/core/Validator.php';
require_once BASE_PATH . '/core/FileUploader.php';
require_once BASE_PATH . '/core/Notification.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Router.php';

// Autoloader بسيط للـ Domain
spl_autoload_register(function ($className) {
    // البحث في domain
    $domains = [
        'Auth', 'Dashboard', 'Clients', 'Companies', 'Services',
        'ServiceOrders', 'SalesOpportunities', 'Quotations', 'Claims',
        'Invoices', 'Payments', 'Communications', 'Tasks', 'Employees',
        'Documents', 'Notifications', 'Reports', 'Settings', 'Renewals'
    ];
    
    foreach ($domains as $domain) {
        $paths = [
            BASE_PATH . "/domain/{$domain}/Controllers/{$className}.php",
            BASE_PATH . "/domain/{$domain}/Models/{$className}.php",
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
});

// توليد CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// إنشاء Router وتسجيل المسارات
$router = Router::getInstance();

// مسارات المصادقة
$router->register('auth', 'login', 'AuthController', 'login');
$router->register('auth', 'do_login', 'AuthController', 'doLogin');
$router->register('auth', 'logout', 'AuthController', 'logout');
$router->register('auth', 'change_password', 'AuthController', 'changePassword');

// لوحة التحكم
$router->register('dashboard', 'index', 'DashboardController', 'index');
$router->register('dashboard', 'stats', 'DashboardController', 'stats');

// العملاء
$router->register('clients', 'index', 'ClientController', 'index', ['clients.view']);
$router->register('clients', 'create', 'ClientController', 'create', ['clients.create']);
$router->register('clients', 'store', 'ClientController', 'store', ['clients.create']);
$router->register('clients', 'edit', 'ClientController', 'edit', ['clients.edit']);
$router->register('clients', 'update', 'ClientController', 'update', ['clients.edit']);
$router->register('clients', 'delete', 'ClientController', 'delete', ['clients.delete']);
$router->register('clients', 'show', 'ClientController', 'show', ['clients.view']);
$router->register('clients', 'card', 'ClientController', 'card', ['clients.view']);

// الشركات
$router->register('companies', 'index', 'CompanyController', 'index', ['companies.view']);
$router->register('companies', 'create', 'CompanyController', 'create', ['companies.create']);
$router->register('companies', 'store', 'CompanyController', 'store', ['companies.create']);
$router->register('companies', 'edit', 'CompanyController', 'edit', ['companies.edit']);
$router->register('companies', 'update', 'CompanyController', 'update', ['companies.edit']);
$router->register('companies', 'delete', 'CompanyController', 'delete', ['companies.delete']);
$router->register('companies', 'show', 'CompanyController', 'show', ['companies.view']);

// الخدمات
$router->register('services', 'index', 'ServiceController', 'index', ['services.view']);
$router->register('services', 'create', 'ServiceController', 'create', ['services.create']);
$router->register('services', 'store', 'ServiceController', 'store', ['services.create']);
$router->register('services', 'edit', 'ServiceController', 'edit', ['services.edit']);
$router->register('services', 'update', 'ServiceController', 'update', ['services.edit']);
$router->register('services', 'delete', 'ServiceController', 'delete', ['services.delete']);

// طلبات الخدمات
$router->register('orders', 'index', 'ServiceOrderController', 'index', ['orders.view']);
$router->register('orders', 'create', 'ServiceOrderController', 'create', ['orders.create']);
$router->register('orders', 'store', 'ServiceOrderController', 'store', ['orders.create']);
$router->register('orders', 'edit', 'ServiceOrderController', 'edit', ['orders.edit']);
$router->register('orders', 'update', 'ServiceOrderController', 'update', ['orders.edit']);
$router->register('orders', 'delete', 'ServiceOrderController', 'delete', ['orders.delete']);
$router->register('orders', 'show', 'ServiceOrderController', 'show', ['orders.view']);
$router->register('orders', 'history', 'ServiceOrderController', 'history', ['orders.view']);
$router->register('orders', 'import', 'ServiceOrderController', 'import', ['orders.create']);

// الفرص البيعية
$router->register('opportunities', 'index', 'SalesOpportunityController', 'index', ['opportunities.view']);
$router->register('opportunities', 'create', 'SalesOpportunityController', 'create', ['opportunities.create']);
$router->register('opportunities', 'store', 'SalesOpportunityController', 'store', ['opportunities.create']);
$router->register('opportunities', 'edit', 'SalesOpportunityController', 'edit', ['opportunities.edit']);
$router->register('opportunities', 'update', 'SalesOpportunityController', 'update', ['opportunities.edit']);
$router->register('opportunities', 'delete', 'SalesOpportunityController', 'delete', ['opportunities.delete']);

// عروض الأسعار
$router->register('quotations', 'index', 'QuotationController', 'index', ['quotations.view']);
$router->register('quotations', 'create', 'QuotationController', 'create', ['quotations.create']);
$router->register('quotations', 'store', 'QuotationController', 'store', ['quotations.create']);
$router->register('quotations', 'edit', 'QuotationController', 'edit', ['quotations.edit']);
$router->register('quotations', 'update', 'QuotationController', 'update', ['quotations.edit']);
$router->register('quotations', 'delete', 'QuotationController', 'delete', ['quotations.delete']);
$router->register('quotations', 'show', 'QuotationController', 'show', ['quotations.view']);
$router->register('quotations', 'print', 'QuotationController', 'printQuotation', ['quotations.print']);
$router->register('quotations', 'approve', 'QuotationController', 'approve', ['quotations.approve']);
$router->register('quotations', 'to_claim', 'QuotationController', 'toClaim', ['claims.create']);

// المطالبات
$router->register('claims', 'index', 'ClaimController', 'index', ['claims.view']);
$router->register('claims', 'create', 'ClaimController', 'create', ['claims.create']);
$router->register('claims', 'store', 'ClaimController', 'store', ['claims.create']);
$router->register('claims', 'edit', 'ClaimController', 'edit', ['claims.edit']);
$router->register('claims', 'update', 'ClaimController', 'update', ['claims.edit']);
$router->register('claims', 'delete', 'ClaimController', 'delete', ['claims.delete']);
$router->register('claims', 'show', 'ClaimController', 'show', ['claims.view']);
$router->register('claims', 'print', 'ClaimController', 'printClaim', ['claims.view']);

// الفواتير
$router->register('invoices', 'index', 'InvoiceController', 'index', ['invoices.view']);
$router->register('invoices', 'create', 'InvoiceController', 'create', ['invoices.create']);
$router->register('invoices', 'store', 'InvoiceController', 'store', ['invoices.create']);
$router->register('invoices', 'edit', 'InvoiceController', 'edit', ['invoices.edit']);
$router->register('invoices', 'update', 'InvoiceController', 'update', ['invoices.edit']);
$router->register('invoices', 'delete', 'InvoiceController', 'delete', ['invoices.delete']);
$router->register('invoices', 'show', 'InvoiceController', 'show', ['invoices.view']);
$router->register('invoices', 'print', 'InvoiceController', 'printInvoice', ['invoices.print']);

// المدفوعات
$router->register('payments', 'index', 'PaymentController', 'index', ['payments.view']);
$router->register('payments', 'create', 'PaymentController', 'create', ['payments.create']);
$router->register('payments', 'store', 'PaymentController', 'store', ['payments.create']);
$router->register('payments', 'edit', 'PaymentController', 'edit', ['payments.edit']);
$router->register('payments', 'update', 'PaymentController', 'update', ['payments.edit']);
$router->register('payments', 'delete', 'PaymentController', 'delete', ['payments.delete']);

// التواصل
$router->register('communications', 'calls', 'CommunicationController', 'calls', ['communications.view']);
$router->register('communications', 'log_call', 'CommunicationController', 'logCall', ['communications.create']);
$router->register('communications', 'store_call', 'CommunicationController', 'storeCall', ['communications.create']);
$router->register('communications', 'activities', 'CommunicationController', 'activities', ['communications.view']);

// المهام
$router->register('tasks', 'index', 'TaskController', 'index', ['tasks.view']);
$router->register('tasks', 'create', 'TaskController', 'create', ['tasks.create']);
$router->register('tasks', 'store', 'TaskController', 'store', ['tasks.create']);
$router->register('tasks', 'edit', 'TaskController', 'edit', ['tasks.edit']);
$router->register('tasks', 'update', 'TaskController', 'update', ['tasks.edit']);
$router->register('tasks', 'delete', 'TaskController', 'delete', ['tasks.delete']);

// الموظفين
$router->register('employees', 'index', 'EmployeeController', 'index', ['employees.view']);
$router->register('employees', 'create', 'EmployeeController', 'create', ['employees.create']);
$router->register('employees', 'store', 'EmployeeController', 'store', ['employees.create']);
$router->register('employees', 'edit', 'EmployeeController', 'edit', ['employees.edit']);
$router->register('employees', 'update', 'EmployeeController', 'update', ['employees.edit']);
$router->register('employees', 'delete', 'EmployeeController', 'delete', ['employees.delete']);
$router->register('employees', 'show', 'EmployeeController', 'show', ['employees.view']);

// المستندات
$router->register('documents', 'index', 'DocumentController', 'index', ['documents.view']);
$router->register('documents', 'upload', 'DocumentController', 'upload', ['documents.create']);
$router->register('documents', 'store', 'DocumentController', 'store', ['documents.create']);
$router->register('documents', 'delete', 'DocumentController', 'deleteDoc', ['documents.delete']);
$router->register('documents', 'download', 'DocumentController', 'download', ['documents.view']);
$router->register('documents', 'preview', 'DocumentController', 'preview', ['documents.view']);

// الإشعارات
$router->register('notifications', 'index', 'NotificationController', 'index');
$router->register('notifications', 'read', 'NotificationController', 'markRead');
$router->register('notifications', 'read_all', 'NotificationController', 'markAllRead');
$router->register('notifications', 'get', 'NotificationController', 'getNotifications');

// التقارير
$router->register('reports', 'index', 'ReportController', 'index', ['reports.view']);
$router->register('reports', 'revenue', 'ReportController', 'revenue', ['reports.view']);
$router->register('reports', 'clients', 'ReportController', 'clients', ['reports.view']);
$router->register('reports', 'financial', 'ReportController', 'financial', ['reports.view']);
$router->register('reports', 'services', 'ReportController', 'services', ['reports.view']);

// الإعدادات
$router->register('settings', 'index', 'SettingsController', 'index', ['settings.view']);
$router->register('settings', 'update', 'SettingsController', 'update', ['settings.edit']);
$router->register('settings', 'users', 'SettingsController', 'users', ['settings.users']);
$router->register('settings', 'create_user', 'SettingsController', 'createUser', ['settings.users']);
$router->register('settings', 'store_user', 'SettingsController', 'storeUser', ['settings.users']);
$router->register('settings', 'toggle_user', 'SettingsController', 'toggleUser', ['settings.users']);

// مركز التجديدات
$router->register('renewals', 'index', 'RenewalController', 'index');

// API - بيانات الشركات حسب العميل
$router->register('companies', 'by_client', 'CompanyController', 'byClient');

// تنفيذ الطلب
$router->dispatch();
