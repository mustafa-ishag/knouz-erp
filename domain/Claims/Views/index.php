<div class="page-header"><div><h1 class="page-title">المطالبات المالية</h1><p class="page-subtitle"><?= number_format($result['total']) ?> مطالبة</p></div><div class="page-actions"><a href="<?= url('claims','create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء مطالبة</a></div></div>
<div class="card">
    <div class="table-header"><form method="GET" action="<?= url('claims') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="claims"><input type="hidden" name="action" value="index"><input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;">
        <select name="status" class="form-control" style="width:150px;"><option value="">الكل</option><?php foreach(['draft'=>'مسودة','sent'=>'مرسلة','due'=>'مستحقة','overdue'=>'متأخرة','partially_paid'=>'مسددة جزئياً','paid'=>'مسددة','cancelled'=>'ملغية'] as $k=>$v): ?><option value="<?= $k ?>" <?= $status===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button></form></div>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th>الرقم</th><th>العميل</th><th>التاريخ</th><th>الاستحقاق</th><th>الإجمالي</th><th>المدفوع</th><th>المتبقي</th><th>الحالة</th><th></th></tr></thead>
    <tbody><?php if(empty($result['data'])): ?><tr><td colspan="9"><div class="empty-state"><i class="fas fa-hand-holding-dollar"></i><h3>لا توجد مطالبات</h3></div></td></tr>
    <?php else: foreach($result['data'] as $c): ?><tr>
        <td><a href="<?= url('claims','show',['id'=>$c['id']]) ?>" class="text-bold"><?= clean($c['claim_number']) ?></a></td>
        <td><?= clean($c['client_name']??'-') ?></td><td class="text-sm"><?= formatDate($c['created_at']) ?></td><td class="text-sm"><?= formatDate($c['due_date']) ?></td>
        <td class="text-bold"><?= formatMoney($c['total']) ?></td><td class="text-success"><?= formatMoney($c['paid_amount']) ?></td>
        <td class="text-danger text-bold"><?= formatMoney($c['total']-$c['paid_amount']) ?></td><td><?= statusBadge($c['status']) ?></td>
        <td><div class="table-actions"><a href="<?= url('claims','show',['id'=>$c['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-eye text-gold"></i></a><a href="<?= url('claims','edit',['id'=>$c['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a></div></td>
    </tr><?php endforeach;endif; ?></tbody></table></div>
</div>
