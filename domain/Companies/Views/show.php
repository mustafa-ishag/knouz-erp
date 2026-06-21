<div class="page-header">
    <div><h1 class="page-title"><?= clean($company['name_ar']) ?></h1><p class="page-subtitle">تفاصيل الشركة</p></div>
    <div class="page-actions">
        <a href="<?= url('companies', 'edit', ['id' => $company['id']]) ?>" class="btn btn-outline"><i class="fas fa-edit"></i> تعديل</a>
        <a href="<?= url('orders', 'create', ['client_id' => $company['client_id'], 'company_id' => $company['id']]) ?>" class="btn btn-primary"><i class="fas fa-plus"></i> طلب جديد</a>
    </div>
</div>

<div class="grid-2">
    <!-- بيانات الشركة -->
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-building text-gold mr-1"></i> بيانات الشركة</h3></div>
        <div class="card-body">
            <div class="quick-stat"><span class="quick-stat-label">العميل</span><a href="<?= url('clients', 'card', ['id' => $company['client_id']]) ?>" class="quick-stat-value"><?= clean($company['client']['name'] ?? '-') ?></a></div>
            <div class="quick-stat"><span class="quick-stat-label">الاسم العربي</span><span class="quick-stat-value"><?= clean($company['name_ar']) ?></span></div>
            <?php if ($company['name_en']): ?><div class="quick-stat"><span class="quick-stat-label">الاسم الإنجليزي</span><span class="quick-stat-value" dir="ltr"><?= clean($company['name_en']) ?></span></div><?php endif; ?>
            <div class="quick-stat"><span class="quick-stat-label">السجل التجاري</span><span class="quick-stat-value"><?= clean($company['cr_number'] ?: '-') ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">الرقم الموحد</span><span class="quick-stat-value"><?= clean($company['unified_number'] ?: '-') ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">الرقم المميز</span><span class="quick-stat-value"><?= clean($company['distinctive_number'] ?: '-') ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">رقم قوى</span><span class="quick-stat-value"><?= clean($company['qiwa_number'] ?: '-') ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">النشاط</span><span class="quick-stat-value"><?= clean($company['activity'] ?: '-') ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">المدينة</span><span class="quick-stat-value"><?= clean($company['city'] ?: '-') ?></span></div>
            <?php if ($company['cr_issue_date']): ?><div class="quick-stat"><span class="quick-stat-label">تاريخ إصدار السجل</span><span class="quick-stat-value"><?= formatDate($company['cr_issue_date']) ?></span></div><?php endif; ?>
            <?php if ($company['cr_expiry_date']): ?>
                <div class="quick-stat"><span class="quick-stat-label">انتهاء السجل</span>
                    <span class="badge badge-<?= strtotime($company['cr_expiry_date']) < time() ? 'danger' : (strtotime($company['cr_expiry_date']) < strtotime('+30 days') ? 'warning' : 'success') ?>"><?= formatDate($company['cr_expiry_date']) ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- المستندات -->
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-folder-open text-gold mr-1"></i> المستندات</h3></div>
        <div class="card-body">
            <?php if (empty($company['documents'])): ?>
                <div class="empty-state"><i class="fas fa-folder-open"></i><p>لا توجد مستندات</p></div>
            <?php else: ?>
                <?php foreach ($company['documents'] as $doc): ?>
                    <div class="d-flex align-center gap-2 mb-2" style="padding:8px;background:var(--bg-input);border-radius:8px;">
                        <i class="fas fa-file-pdf" style="font-size:1.5rem;color:var(--danger);"></i>
                        <div class="flex-1">
                            <div class="text-bold text-sm"><?= clean($doc['title']) ?></div>
                            <div class="text-xs text-muted"><?= clean($doc['document_type']) ?> • <?= formatDate($doc['created_at']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- طلبات الخدمات -->
<div class="card mt-2">
    <div class="card-header"><h3><i class="fas fa-clipboard-list text-gold mr-1"></i> طلبات الخدمات</h3></div>
    <div class="card-body">
        <?php if (empty($company['orders'])): ?>
            <div class="empty-state"><i class="fas fa-clipboard-list"></i><h3>لا توجد طلبات</h3></div>
        <?php else: ?>
            <table class="data-table"><thead><tr><th>رقم الطلب</th><th>الخدمة</th><th>الحالة</th><th>المبلغ</th><th>التاريخ</th></tr></thead>
            <tbody>
                <?php foreach ($company['orders'] as $o): ?>
                    <tr>
                        <td><a href="<?= url('orders', 'show', ['id' => $o['id']]) ?>"><?= clean($o['order_number']) ?></a></td>
                        <td><?= clean($o['service_name'] ?? '-') ?></td>
                        <td><?= statusBadge($o['status']) ?></td>
                        <td><?= formatMoney($o['price']) ?></td>
                        <td class="text-muted text-sm"><?= formatDate($o['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody></table>
        <?php endif; ?>
    </div>
</div>
