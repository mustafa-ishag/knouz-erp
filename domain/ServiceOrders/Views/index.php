<div class="page-header">
    <div><h1 class="page-title">طلبات الخدمات</h1><p class="page-subtitle"><?= number_format($result['total']) ?> طلب</p></div>
    <div class="page-actions"><a href="<?= url('orders', 'create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> طلب جديد</a></div>
</div>
<div class="card">
    <div class="table-header">
        <form method="GET" action="<?= url('orders') ?>" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="hidden" name="module" value="orders"><input type="hidden" name="action" value="index">
            <input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;">
            <select name="status" class="form-control" style="width:150px;">
                <option value="">كل الحالات</option>
                <?php foreach(['new'=>'جديد','in_progress'=>'قيد التنفيذ','pending'=>'معلق','completed'=>'مكتمل','cancelled'=>'ملغي'] as $k=>$v): ?>
                    <option value="<?= $k ?>" <?= $status===$k?'selected':'' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button>
            <?php if ($search || $status): ?><a href="<?= url('orders') ?>" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
        </form>
    </div>
    <div class="table-wrapper">
        <table class="data-table"><thead><tr><th>رقم الطلب</th><th>العميل</th><th>الشركة</th><th>الخدمة</th><th>المبلغ</th><th>الحالة</th><th>الموظف</th><th>التاريخ</th><th>الإجراءات</th></tr></thead>
        <tbody>
            <?php if (empty($result['data'])): ?>
                <tr><td colspan="9"><div class="empty-state"><i class="fas fa-clipboard-list"></i><h3>لا توجد طلبات</h3></div></td></tr>
            <?php else: foreach ($result['data'] as $o): ?>
                <tr id="order-row-<?= $o['id'] ?>">
                    <td><a href="<?= url('orders', 'show', ['id' => $o['id']]) ?>" class="text-bold"><?= clean($o['order_number']) ?></a></td>
                    <td><a href="<?= url('clients', 'card', ['id' => $o['client_id']]) ?>"><?= clean($o['client_name'] ?? '-') ?></a></td>
                    <td><?= clean($o['company_name'] ?? '-') ?></td>
                    <td><?= clean($o['service_name'] ?? '-') ?></td>
                    <td class="text-bold"><?= formatMoney($o['price']) ?></td>
                    <td>
                        <select class="status-select status-<?= $o['status'] ?>" 
                                onchange="updateOrderStatus(<?= $o['id'] ?>, this.value, this)"
                                data-original="<?= $o['status'] ?>">
                            <?php foreach(['new'=>'جديد','in_progress'=>'قيد التنفيذ','pending_client'=>'بانتظار العميل','pending_government'=>'بانتظار الجهة','completed'=>'مكتمل','cancelled'=>'ملغي'] as $sk=>$sv): ?>
                                <option value="<?= $sk ?>" <?= $o['status']===$sk?'selected':'' ?>><?= $sv ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><?= clean($o['assigned_name'] ?? '-') ?></td>
                    <td class="text-muted text-sm"><?= formatDate($o['created_at']) ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= url('orders', 'show', ['id' => $o['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-eye text-gold"></i></a>
                            <a href="<?= url('orders', 'edit', ['id' => $o['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a>
                            <button onclick="confirmDelete('<?= url('orders', 'delete', ['id' => $o['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody></table>
    </div>
    <?php if ($result['total_pages'] > 1): ?><div class="pagination"><?php for ($i = 1; $i <= $result['total_pages']; $i++): ?><a href="<?= url('orders', 'index', ['page'=>$i,'search'=>$search,'status'=>$status]) ?>" class="<?= $i===$result['page']?'active':'' ?>"><?= $i ?></a><?php endfor; ?></div><?php endif; ?>
</div>

<style>
.status-select {
    padding: 4px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
    border: none; cursor: pointer; font-family: var(--font-family);
    appearance: none; -webkit-appearance: none; text-align: center;
    min-width: 120px; transition: all 0.3s ease;
}
.status-select:focus { outline: none; box-shadow: 0 0 0 3px rgba(23,108,180,0.2); }
.status-select.status-new { background: #e3f2fd; color: #1565c0; }
.status-select.status-in_progress { background: #fff3e0; color: #e65100; }
.status-select.status-pending_client, .status-select.status-pending_government, .status-select.status-pending { background: #fce4ec; color: #c62828; }
.status-select.status-completed { background: #e8f5e9; color: #2e7d32; }
.status-select.status-cancelled { background: #f5f5f5; color: #757575; }
.status-select.updating { opacity: 0.5; pointer-events: none; }
</style>

<script>
function updateOrderStatus(orderId, newStatus, selectEl) {
    const originalStatus = selectEl.dataset.original;
    if (newStatus === originalStatus) return;
    
    selectEl.classList.add('updating');
    
    fetch(BASE_URL + '/?module=orders&action=update_status', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
        body: `id=${orderId}&status=${newStatus}&csrf_token=${document.querySelector('meta[name="csrf-token"]')?.content || ''}`
    })
    .then(r => r.json())
    .then(data => {
        selectEl.classList.remove('updating');
        if (data.success) {
            // تحديث الكلاس واللون
            selectEl.className = 'status-select status-' + newStatus;
            selectEl.dataset.original = newStatus;
            
            // إشعار نجاح
            showToast(data.message || 'تم تحديث الحالة بنجاح', 'success');
            
            // إذا تم التحويل لمطالبة، إظهار إشعار إضافي
            if (data.claim_created) {
                setTimeout(() => showToast('تم إنشاء مطالبة مالية تلقائياً رقم: ' + data.claim_number, 'info'), 1000);
            }
        } else {
            selectEl.value = originalStatus;
            showToast(data.message || 'حدث خطأ', 'danger');
        }
    })
    .catch(() => {
        selectEl.classList.remove('updating');
        selectEl.value = originalStatus;
        showToast('حدث خطأ في الاتصال', 'danger');
    });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-' + type;
    toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);z-index:9999;min-width:300px;animation:dropdownFadeIn 0.3s ease;';
    toast.innerHTML = '<i class="fas fa-' + (type==='success'?'check-circle':type==='info'?'info-circle':'exclamation-circle') + '"></i><span>' + message + '</span>';
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>

