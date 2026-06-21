<div class="page-header">
    <div><h1 class="page-title">طلب <?= clean($order['order_number']) ?></h1><p class="page-subtitle"><?= statusBadge($order['status']) ?></p></div>
    <div class="page-actions">
        <a href="<?= url('orders', 'edit', ['id'=>$order['id']]) ?>" class="btn btn-outline"><i class="fas fa-edit"></i> تعديل</a>
    </div>
</div>
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-clipboard-list text-gold mr-1"></i> تفاصيل الطلب</h3></div>
        <div class="card-body">
            <div class="quick-stat"><span class="quick-stat-label">رقم الطلب</span><span class="quick-stat-value"><?= clean($order['order_number']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">العميل</span><a href="<?= url('clients', 'card', ['id'=>$order['client_id']]) ?>" class="quick-stat-value"><?= clean(is_array($order['client']) ? ($order['client']['name'] ?? '-') : '-') ?></a></div>
            <?php if (is_array($order['company'])): ?><div class="quick-stat"><span class="quick-stat-label">الشركة</span><span class="quick-stat-value"><?= clean($order['company']['name_ar'] ?? '') ?></span></div><?php endif; ?>
            <div class="quick-stat"><span class="quick-stat-label">الخدمة</span><span class="quick-stat-value"><?= clean(is_array($order['service']) ? ($order['service']['name'] ?? $order['description']) : $order['description']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">الحالة</span><span class="quick-stat-value"><?= statusBadge($order['status']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">السعر</span><span class="quick-stat-value text-gold"><?= formatMoney($order['price']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">التكلفة</span><span class="quick-stat-value"><?= formatMoney($order['cost']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">الربح</span><span class="quick-stat-value text-success"><?= formatMoney($order['price'] - $order['cost']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">تاريخ البدء</span><span class="quick-stat-value"><?= formatDate($order['start_date']) ?></span></div>
            <div class="quick-stat"><span class="quick-stat-label">تاريخ الاستحقاق</span><span class="quick-stat-value"><?= formatDate($order['due_date']) ?></span></div>
            <?php if (!empty($order['platform_ref'])): ?><div class="quick-stat"><span class="quick-stat-label">مرجع المنصة</span><span class="quick-stat-value"><?= clean($order['platform_ref']) ?></span></div><?php endif; ?>
            <?php if (!empty($order['notes'])): ?><div class="mt-2"><strong class="text-sm">ملاحظات:</strong><p class="text-sm text-muted mt-1"><?= nl2br(clean($order['notes'])) ?></p></div><?php endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-history text-gold mr-1"></i> سجل الحالات</h3></div>
        <div class="card-body">
            <?php if (empty($order['history'])): ?>
                <div class="empty-state"><i class="fas fa-history"></i><p>لا يوجد سجل</p></div>
            <?php else: foreach ($order['history'] as $h): ?>
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--primary-bg);color:var(--gold);"><i class="fas fa-exchange-alt"></i></div>
                    <div class="activity-content">
                        <div class="title"><?= statusBadge($h['old_status'] ?: 'جديد') ?> → <?= statusBadge($h['new_status']) ?></div>
                        <?php if ($h['notes']): ?><div class="desc"><?= clean($h['notes']) ?></div><?php endif; ?>
                        <div class="time"><?= clean($h['user_name'] ?? 'النظام') ?> - <?= formatDateTime($h['created_at']) ?></div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>
