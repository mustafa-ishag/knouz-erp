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
                <tr>
                    <td><a href="<?= url('orders', 'show', ['id' => $o['id']]) ?>" class="text-bold"><?= clean($o['order_number']) ?></a></td>
                    <td><a href="<?= url('clients', 'card', ['id' => $o['client_id']]) ?>"><?= clean($o['client_name'] ?? '-') ?></a></td>
                    <td><?= clean($o['company_name'] ?? '-') ?></td>
                    <td><?= clean($o['service_name'] ?? '-') ?></td>
                    <td class="text-bold"><?= formatMoney($o['price']) ?></td>
                    <td><?= statusBadge($o['status']) ?></td>
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
