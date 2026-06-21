<?php
$currentModule = $_GET['module'] ?? 'dashboard';
$rbac = new RBAC();
?>
<!-- القائمة الجانبية -->
<aside class="sidebar">
    <!-- شعار النظام -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">ك</div>
        <div class="sidebar-brand-text">
            <h1>كنوز الإنجاز</h1>
            <span>نظام إدارة الأعمال</span>
        </div>
    </div>
    
    <!-- القائمة -->
    <nav class="sidebar-nav">
        <!-- الرئيسية -->
        <div class="nav-section">
            <div class="nav-section-title">الرئيسية</div>
            <a href="<?= url('dashboard') ?>" class="nav-item <?= activeMenu('dashboard') ?>">
                <i class="fas fa-home"></i>
                <span class="nav-text">لوحة التحكم</span>
            </a>
        </div>
        
        <!-- إدارة العملاء -->
        <?php if ($rbac->hasAnyPermission(['clients.view', 'companies.view'])): ?>
        <div class="nav-section">
            <div class="nav-section-title">العملاء</div>
            <?php if ($rbac->hasPermission('clients.view')): ?>
            <a href="<?= url('clients') ?>" class="nav-item <?= activeMenu('clients') ?>">
                <i class="fas fa-users"></i>
                <span class="nav-text">إدارة العملاء</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('companies.view')): ?>
            <a href="<?= url('companies') ?>" class="nav-item <?= activeMenu('companies') ?>">
                <i class="fas fa-building"></i>
                <span class="nav-text">إدارة الشركات</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('communications.view')): ?>
            <a href="<?= url('communications', 'calls') ?>" class="nav-item <?= activeMenu('communications') ?>">
                <i class="fas fa-comments"></i>
                <span class="nav-text">التواصل</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- المبيعات -->
        <?php if ($rbac->hasAnyPermission(['opportunities.view', 'quotations.view'])): ?>
        <div class="nav-section">
            <div class="nav-section-title">المبيعات</div>
            <?php if ($rbac->hasPermission('opportunities.view')): ?>
            <a href="<?= url('opportunities') ?>" class="nav-item <?= activeMenu('opportunities') ?>">
                <i class="fas fa-chart-line"></i>
                <span class="nav-text">الفرص البيعية</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('quotations.view')): ?>
            <a href="<?= url('quotations') ?>" class="nav-item <?= activeMenu('quotations') ?>">
                <i class="fas fa-file-invoice"></i>
                <span class="nav-text">عروض الأسعار</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- العمليات -->
        <?php if ($rbac->hasAnyPermission(['services.view', 'orders.view', 'tasks.view'])): ?>
        <div class="nav-section">
            <div class="nav-section-title">العمليات</div>
            <?php if ($rbac->hasPermission('services.view')): ?>
            <a href="<?= url('services') ?>" class="nav-item <?= activeMenu('services') ?>">
                <i class="fas fa-cogs"></i>
                <span class="nav-text">مكتبة الخدمات</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('orders.view')): ?>
            <a href="<?= url('orders') ?>" class="nav-item <?= activeMenu('orders') ?>">
                <i class="fas fa-clipboard-list"></i>
                <span class="nav-text">طلبات الخدمات</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('tasks.view')): ?>
            <a href="<?= url('tasks') ?>" class="nav-item <?= activeMenu('tasks') ?>">
                <i class="fas fa-tasks"></i>
                <span class="nav-text">المهام</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- المالية -->
        <?php if ($rbac->hasAnyPermission(['claims.view', 'invoices.view', 'payments.view'])): ?>
        <div class="nav-section">
            <div class="nav-section-title">المالية</div>
            <?php if ($rbac->hasPermission('claims.view')): ?>
            <a href="<?= url('claims') ?>" class="nav-item <?= activeMenu('claims') ?>">
                <i class="fas fa-hand-holding-dollar"></i>
                <span class="nav-text">المطالبات المالية</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('invoices.view')): ?>
            <a href="<?= url('invoices') ?>" class="nav-item <?= activeMenu('invoices') ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span class="nav-text">الفواتير</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('payments.view')): ?>
            <a href="<?= url('payments') ?>" class="nav-item <?= activeMenu('payments') ?>">
                <i class="fas fa-money-bill-wave"></i>
                <span class="nav-text">المدفوعات</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- الموارد -->
        <?php if ($rbac->hasAnyPermission(['employees.view', 'documents.view'])): ?>
        <div class="nav-section">
            <div class="nav-section-title">الموارد</div>
            <?php if ($rbac->hasPermission('employees.view')): ?>
            <a href="<?= url('employees') ?>" class="nav-item <?= activeMenu('employees') ?>">
                <i class="fas fa-user-tie"></i>
                <span class="nav-text">الموظفين</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('documents.view')): ?>
            <a href="<?= url('documents') ?>" class="nav-item <?= activeMenu('documents') ?>">
                <i class="fas fa-folder-open"></i>
                <span class="nav-text">المستندات</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- التقارير والإعدادات -->
        <div class="nav-section">
            <div class="nav-section-title">النظام</div>
            <a href="<?= url('renewals') ?>" class="nav-item <?= activeMenu('renewals') ?>">
                <i class="fas fa-sync-alt"></i>
                <span class="nav-text">مركز التجديدات</span>
            </a>
            <?php if ($rbac->hasPermission('reports.view')): ?>
            <a href="<?= url('reports') ?>" class="nav-item <?= activeMenu('reports') ?>">
                <i class="fas fa-chart-bar"></i>
                <span class="nav-text">التقارير</span>
            </a>
            <?php endif; ?>
            <?php if ($rbac->hasPermission('settings.view')): ?>
            <a href="<?= url('settings') ?>" class="nav-item <?= activeMenu('settings') ?>">
                <i class="fas fa-gear"></i>
                <span class="nav-text">الإعدادات</span>
            </a>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- زر طي القائمة -->
    <div class="sidebar-toggle">
        <button onclick="toggleSidebar()" title="طي/توسيع القائمة">
            <i class="fas fa-right-left"></i>
        </button>
    </div>
</aside>
