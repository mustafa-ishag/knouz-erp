<div class="page-header"><div><h1 class="page-title">سجل النشاطات</h1><p class="page-subtitle"><?= number_format($result['total']) ?> نشاط</p></div></div>
<div class="card"><div class="card-body">
    <?php if(empty($result['data'])): ?><div class="empty-state"><i class="fas fa-history"></i><h3>لا توجد نشاطات</h3></div>
    <?php else: foreach($result['data'] as $a): ?>
        <div class="activity-item">
            <div class="activity-icon" style="background:var(--primary-bg);color:var(--gold);"><i class="fas fa-<?= $a['action']==='create'?'plus':($a['action']==='update'?'edit':'trash') ?>"></i></div>
            <div class="activity-content">
                <div class="title"><?= clean($a['description']) ?></div>
                <div class="time"><?= clean($a['user_name']??'النظام') ?> - <?= formatDateTime($a['created_at']) ?> - <?= clean($a['module']) ?></div>
            </div>
        </div>
    <?php endforeach;endif; ?>
</div></div>
