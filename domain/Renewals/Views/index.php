<?php
/**
 * مركز التجديدات - رصد كل ما يحتاج تجديداً قبل انتهائه
 */

// دالة مساعدة لتصنيف الأيام
function renewalBadge(int $days): string {
    if ($days < 0) return '<span class="badge badge-danger" style="animation:pulse 1.5s infinite;">منتهي منذ ' . abs($days) . ' يوم</span>';
    if ($days === 0) return '<span class="badge badge-danger" style="animation:pulse 1.5s infinite;">ينتهي اليوم!</span>';
    if ($days <= 7) return '<span class="badge badge-danger">باقي ' . $days . ' أيام</span>';
    if ($days <= 30) return '<span class="badge badge-warning">باقي ' . $days . ' يوم</span>';
    return '<span class="badge badge-success">باقي ' . $days . ' يوم</span>';
}

function renewalIcon(int $days): string {
    if ($days < 0) return '<i class="fas fa-exclamation-circle text-danger"></i>';
    if ($days <= 7) return '<i class="fas fa-exclamation-triangle text-danger"></i>';
    if ($days <= 30) return '<i class="fas fa-clock text-warning"></i>';
    return '<i class="fas fa-check-circle text-success"></i>';
}
?>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}
.renewal-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
.renewal-stat-card { background: var(--bg-card); border-radius: 12px; padding: 20px; text-align: center; border: 1px solid var(--border); transition: all 0.3s; position: relative; overflow: hidden; }
.renewal-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
.renewal-stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
.renewal-stat-card.expired::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
.renewal-stat-card.critical::before { background: linear-gradient(90deg, #f97316, #ea580c); }
.renewal-stat-card.warning::before { background: linear-gradient(90deg, #eab308, #ca8a04); }
.renewal-stat-card.upcoming::before { background: linear-gradient(90deg, #22c55e, #16a34a); }
.renewal-stat-card.total::before { background: linear-gradient(90deg, var(--gold), #b8860b); }
.renewal-stat-number { font-size: 2.2rem; font-weight: 800; margin: 8px 0; }
.renewal-stat-card.expired .renewal-stat-number { color: #ef4444; }
.renewal-stat-card.critical .renewal-stat-number { color: #f97316; }
.renewal-stat-card.warning .renewal-stat-number { color: #eab308; }
.renewal-stat-card.upcoming .renewal-stat-number { color: #22c55e; }
.renewal-stat-card.total .renewal-stat-number { color: var(--gold); }
.renewal-stat-label { font-size: 0.85rem; color: var(--text-muted); }
.renewal-stat-icon { font-size: 1.3rem; margin-bottom: 4px; }

.filter-bar { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-bottom: 24px; background: var(--bg-card); padding: 16px; border-radius: 12px; border: 1px solid var(--border); }
.filter-bar label { font-size: 0.9rem; font-weight: 600; white-space: nowrap; }
.filter-btn { padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-input); color: var(--text); cursor: pointer; transition: all 0.2s; font-size: 0.85rem; }
.filter-btn:hover, .filter-btn.active { background: var(--gold); color: #000; border-color: var(--gold); font-weight: 600; }

.section-card { background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); margin-bottom: 20px; overflow: hidden; }
.section-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; cursor: pointer; user-select: none; transition: background 0.2s; }
.section-header:hover { background: var(--bg-input); }
.section-header h3 { margin: 0; font-size: 1rem; display: flex; align-items: center; gap: 10px; }
.section-header .count-badge { background: var(--bg-input); padding: 3px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
.section-header .count-badge.has-items { background: rgba(234,179,8,0.15); color: var(--gold); }
.section-header .count-badge.danger { background: rgba(239,68,68,0.15); color: #ef4444; }
.section-body { padding: 0; }
.section-chevron { transition: transform 0.3s; color: var(--text-muted); }
.section-chevron.open { transform: rotate(180deg); }

.renewal-row { display: grid; grid-template-columns: 40px 1fr 150px 130px 120px; align-items: center; padding: 12px 20px; border-top: 1px solid var(--border); transition: background 0.15s; gap: 12px; }
.renewal-row:hover { background: var(--bg-input); }
.renewal-row .status-dot { width: 10px; height: 10px; border-radius: 50%; }
.renewal-row .status-dot.expired { background: #ef4444; box-shadow: 0 0 8px rgba(239,68,68,0.5); }
.renewal-row .status-dot.critical { background: #f97316; box-shadow: 0 0 8px rgba(249,115,22,0.5); }
.renewal-row .status-dot.warning { background: #eab308; }
.renewal-row .status-dot.ok { background: #22c55e; }

.empty-section { padding: 32px; text-align: center; color: var(--text-muted); font-size: 0.9rem; }
.empty-section i { font-size: 2rem; margin-bottom: 10px; display: block; opacity: 0.4; }

@media (max-width: 768px) {
    .renewal-row { grid-template-columns: 1fr; gap: 6px; }
    .renewal-stats { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-sync-alt text-gold"></i> مركز التجديدات</h1>
        <p class="page-subtitle">رصد وتتبع كل ما يحتاج تجديداً أو متابعة قبل انتهائه</p>
    </div>
</div>

<!-- إحصائيات -->
<div class="renewal-stats">
    <div class="renewal-stat-card expired">
        <div class="renewal-stat-icon"><i class="fas fa-times-circle text-danger"></i></div>
        <div class="renewal-stat-number"><?= $stats['expired'] ?></div>
        <div class="renewal-stat-label">منتهي الصلاحية</div>
    </div>
    <div class="renewal-stat-card critical">
        <div class="renewal-stat-icon"><i class="fas fa-exclamation-triangle" style="color:#f97316;"></i></div>
        <div class="renewal-stat-number"><?= $stats['critical'] ?></div>
        <div class="renewal-stat-label">حرج (أقل من 7 أيام)</div>
    </div>
    <div class="renewal-stat-card warning">
        <div class="renewal-stat-icon"><i class="fas fa-clock" style="color:#eab308;"></i></div>
        <div class="renewal-stat-number"><?= $stats['warning'] ?></div>
        <div class="renewal-stat-label">تحذير (8-30 يوم)</div>
    </div>
    <div class="renewal-stat-card upcoming">
        <div class="renewal-stat-icon"><i class="fas fa-check-circle text-success"></i></div>
        <div class="renewal-stat-number"><?= $stats['upcoming'] ?></div>
        <div class="renewal-stat-label">قادم (31+ يوم)</div>
    </div>
    <div class="renewal-stat-card total">
        <div class="renewal-stat-icon"><i class="fas fa-layer-group text-gold"></i></div>
        <div class="renewal-stat-number"><?= $stats['total'] ?></div>
        <div class="renewal-stat-label">إجمالي البنود</div>
    </div>
</div>

<!-- فلتر -->
<div class="filter-bar">
    <label><i class="fas fa-filter"></i> عرض خلال:</label>
    <?php foreach([7=>'7 أيام', 15=>'15 يوم', 30=>'30 يوم', 60=>'60 يوم', 90=>'90 يوم', 180=>'6 أشهر', 365=>'سنة'] as $d=>$label): ?>
    <a href="<?= url('renewals','index',['days'=>$d]) ?>" class="filter-btn <?= $filterDays==$d?'active':'' ?>"><?= $label ?></a>
    <?php endforeach; ?>
</div>

<!-- 1. السجلات التجارية -->
<?php $hasDanger = count(array_filter($companies, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('companies')">
        <h3><i class="fas fa-building text-gold"></i> السجلات التجارية
            <span class="count-badge <?= $hasDanger?'danger':(!empty($companies)?'has-items':'') ?>"><?= count($companies) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($companies)?'open':'' ?>" id="chevron-companies"></i>
    </div>
    <div class="section-body" id="section-companies" style="<?= empty($companies)?'display:none':'' ?>">
        <?php if(empty($companies)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد سجلات تجارية تحتاج تجديداً في الفترة المحددة</div>
        <?php else: foreach($companies as $co):
            $dotClass = $co['days_remaining']<0?'expired':($co['days_remaining']<=7?'critical':($co['days_remaining']<=30?'warning':'ok'));
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <a href="<?= url('companies','show',['id'=>$co['id']]) ?>" class="text-bold"><?= clean($co['name_ar']) ?></a>
                <div class="text-sm text-muted"><?= clean($co['client_name']??'') ?> • سجل: <?= clean($co['cr_number']??'-') ?></div>
            </div>
            <div class="text-sm"><?= formatDate($co['cr_expiry_date']) ?></div>
            <div><?= renewalBadge($co['days_remaining']) ?></div>
            <div><a href="<?= url('companies','edit',['id'=>$co['id']]) ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> تجديد</a></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- 2. مستندات الشركات -->
<?php $hasDanger = count(array_filter($documents, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('documents')">
        <h3><i class="fas fa-file-alt" style="color:#8b5cf6;"></i> مستندات الشركات
            <span class="count-badge <?= $hasDanger?'danger':(!empty($documents)?'has-items':'') ?>"><?= count($documents) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($documents)?'open':'' ?>" id="chevron-documents"></i>
    </div>
    <div class="section-body" id="section-documents" style="<?= empty($documents)?'display:none':'' ?>">
        <?php if(empty($documents)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد مستندات تحتاج تجديداً</div>
        <?php else: foreach($documents as $doc):
            $dotClass = $doc['days_remaining']<0?'expired':($doc['days_remaining']<=7?'critical':($doc['days_remaining']<=30?'warning':'ok'));
            $typeNames = ['cr'=>'سجل تجاري','license'=>'رخصة','certificate'=>'شهادة','contract'=>'عقد','insurance'=>'تأمين','other'=>'أخرى'];
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <span class="text-bold"><?= clean($doc['title']) ?></span>
                <div class="text-sm text-muted"><?= clean($doc['company_name']??'') ?> • <?= $typeNames[$doc['document_type']]??$doc['document_type'] ?></div>
            </div>
            <div class="text-sm"><?= formatDate($doc['expiry_date']) ?></div>
            <div><?= renewalBadge($doc['days_remaining']) ?></div>
            <div><a href="<?= url('documents','upload') ?>" class="btn btn-outline btn-sm"><i class="fas fa-upload"></i> رفع جديد</a></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- 3. عروض الأسعار -->
<?php $hasDanger = count(array_filter($quotations, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('quotations')">
        <h3><i class="fas fa-file-invoice" style="color:#06b6d4;"></i> عروض الأسعار (قاربت على الانتهاء)
            <span class="count-badge <?= $hasDanger?'danger':(!empty($quotations)?'has-items':'') ?>"><?= count($quotations) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($quotations)?'open':'' ?>" id="chevron-quotations"></i>
    </div>
    <div class="section-body" id="section-quotations" style="<?= empty($quotations)?'display:none':'' ?>">
        <?php if(empty($quotations)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد عروض أسعار منتهية أو قاربت</div>
        <?php else: foreach($quotations as $q):
            $dotClass = $q['days_remaining']<0?'expired':($q['days_remaining']<=7?'critical':($q['days_remaining']<=30?'warning':'ok'));
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <a href="<?= url('quotations','show',['id'=>$q['id']]) ?>" class="text-bold"><?= clean($q['quotation_number']) ?></a>
                <div class="text-sm text-muted"><?= clean($q['client_name']??'') ?> • <?= formatMoney($q['total']) ?></div>
            </div>
            <div class="text-sm"><?= formatDate($q['validity_date']) ?></div>
            <div><?= renewalBadge($q['days_remaining']) ?></div>
            <div><a href="<?= url('quotations','edit',['id'=>$q['id']]) ?>" class="btn btn-outline btn-sm"><i class="fas fa-redo"></i> تمديد</a></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- 4. الفواتير المستحقة -->
<?php $hasDanger = count(array_filter($invoices, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('invoices')">
        <h3><i class="fas fa-file-invoice-dollar text-success"></i> الفواتير المستحقة
            <span class="count-badge <?= $hasDanger?'danger':(!empty($invoices)?'has-items':'') ?>"><?= count($invoices) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($invoices)?'open':'' ?>" id="chevron-invoices"></i>
    </div>
    <div class="section-body" id="section-invoices" style="<?= empty($invoices)?'display:none':'' ?>">
        <?php if(empty($invoices)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد فواتير مستحقة في الفترة المحددة</div>
        <?php else: foreach($invoices as $inv):
            $dotClass = $inv['days_remaining']<0?'expired':($inv['days_remaining']<=7?'critical':($inv['days_remaining']<=30?'warning':'ok'));
            $remaining = $inv['total'] - $inv['paid_amount'];
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <a href="<?= url('invoices','show',['id'=>$inv['id']]) ?>" class="text-bold"><?= clean($inv['invoice_number']) ?></a>
                <div class="text-sm text-muted"><?= clean($inv['client_name']??'') ?> • المتبقي: <strong class="text-danger"><?= formatMoney($remaining) ?></strong></div>
            </div>
            <div class="text-sm"><?= formatDate($inv['due_date']) ?></div>
            <div><?= renewalBadge($inv['days_remaining']) ?></div>
            <div><a href="<?= url('payments','create',['invoice_id'=>$inv['id']]) ?>" class="btn btn-outline btn-sm"><i class="fas fa-money-bill"></i> دفع</a></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- 5. المطالبات المستحقة -->
<?php $hasDanger = count(array_filter($claims, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('claims')">
        <h3><i class="fas fa-hand-holding-dollar" style="color:#ec4899;"></i> المطالبات المستحقة
            <span class="count-badge <?= $hasDanger?'danger':(!empty($claims)?'has-items':'') ?>"><?= count($claims) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($claims)?'open':'' ?>" id="chevron-claims"></i>
    </div>
    <div class="section-body" id="section-claims" style="<?= empty($claims)?'display:none':'' ?>">
        <?php if(empty($claims)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد مطالبات مستحقة</div>
        <?php else: foreach($claims as $cl):
            $dotClass = $cl['days_remaining']<0?'expired':($cl['days_remaining']<=7?'critical':($cl['days_remaining']<=30?'warning':'ok'));
            $remaining = $cl['total'] - $cl['paid_amount'];
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <a href="<?= url('claims','show',['id'=>$cl['id']]) ?>" class="text-bold"><?= clean($cl['claim_number']) ?></a>
                <div class="text-sm text-muted"><?= clean($cl['client_name']??'') ?> • المتبقي: <strong class="text-danger"><?= formatMoney($remaining) ?></strong></div>
            </div>
            <div class="text-sm"><?= formatDate($cl['due_date']) ?></div>
            <div><?= renewalBadge($cl['days_remaining']) ?></div>
            <div><a href="<?= url('payments','create',['claim_id'=>$cl['id']]) ?>" class="btn btn-outline btn-sm"><i class="fas fa-money-bill"></i> دفع</a></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- 6. أوامر الخدمة -->
<?php $hasDanger = count(array_filter($orders, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('orders')">
        <h3><i class="fas fa-clipboard-list" style="color:#f59e0b;"></i> أوامر الخدمة المستحقة
            <span class="count-badge <?= $hasDanger?'danger':(!empty($orders)?'has-items':'') ?>"><?= count($orders) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($orders)?'open':'' ?>" id="chevron-orders"></i>
    </div>
    <div class="section-body" id="section-orders" style="<?= empty($orders)?'display:none':'' ?>">
        <?php if(empty($orders)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد أوامر خدمة مستحقة</div>
        <?php else: foreach($orders as $o):
            $dotClass = $o['days_remaining']<0?'expired':($o['days_remaining']<=7?'critical':($o['days_remaining']<=30?'warning':'ok'));
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <a href="<?= url('orders','show',['id'=>$o['id']]) ?>" class="text-bold"><?= clean($o['order_number']) ?></a>
                <div class="text-sm text-muted"><?= clean($o['company_name']??$o['client_name']??'') ?> • <?= clean($o['service_name']??'') ?></div>
            </div>
            <div class="text-sm"><?= formatDate($o['due_date']) ?></div>
            <div><?= renewalBadge($o['days_remaining']) ?></div>
            <div><a href="<?= url('orders','show',['id'=>$o['id']]) ?>" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> عرض</a></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- 7. المهام -->
<?php $hasDanger = count(array_filter($tasks, fn($i)=>$i['days_remaining']<=7)); ?>
<div class="section-card">
    <div class="section-header" onclick="toggleSection('tasks')">
        <h3><i class="fas fa-tasks" style="color:#10b981;"></i> المهام المستحقة
            <span class="count-badge <?= $hasDanger?'danger':(!empty($tasks)?'has-items':'') ?>"><?= count($tasks) ?></span>
        </h3>
        <i class="fas fa-chevron-down section-chevron <?= !empty($tasks)?'open':'' ?>" id="chevron-tasks"></i>
    </div>
    <div class="section-body" id="section-tasks" style="<?= empty($tasks)?'display:none':'' ?>">
        <?php if(empty($tasks)): ?>
            <div class="empty-section"><i class="fas fa-check-double"></i>لا توجد مهام مستحقة</div>
        <?php else: foreach($tasks as $t):
            $dotClass = $t['days_remaining']<0?'expired':($t['days_remaining']<=7?'critical':($t['days_remaining']<=30?'warning':'ok'));
            $priorities = ['low'=>'منخفض','medium'=>'متوسط','high'=>'عالي','urgent'=>'عاجل'];
        ?>
        <div class="renewal-row">
            <div><div class="status-dot <?= $dotClass ?>"></div></div>
            <div>
                <span class="text-bold"><?= clean($t['title']) ?></span>
                <div class="text-sm text-muted"><?= clean($t['assigned_to_name']??'غير محدد') ?> • الأولوية: <?= $priorities[$t['priority']]??$t['priority'] ?></div>
            </div>
            <div class="text-sm"><?= formatDate($t['due_date']) ?></div>
            <div><?= renewalBadge($t['days_remaining']) ?></div>
            <div><?= statusBadge($t['status']) ?></div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<script>
function toggleSection(name) {
    const body = document.getElementById('section-' + name);
    const chevron = document.getElementById('chevron-' + name);
    if (body.style.display === 'none') {
        body.style.display = '';
        chevron.classList.add('open');
    } else {
        body.style.display = 'none';
        chevron.classList.remove('open');
    }
}
</script>
