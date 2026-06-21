<div class="page-header"><div><h1 class="page-title">الفواتير</h1><p class="page-subtitle"><?= number_format($result['total']) ?> فاتورة</p></div><div class="page-actions"><a href="<?= url('invoices','create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء فاتورة</a></div></div>
<div class="card">
    <div class="table-header"><form method="GET" action="<?= url('invoices') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="invoices"><input type="hidden" name="action" value="index"><input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;">
        <select name="status" class="form-control" style="width:150px;"><option value="">الكل</option><?php foreach(['unpaid'=>'غير مسددة','partially_paid'=>'مسددة جزئياً','paid'=>'مسددة','cancelled'=>'ملغية'] as $k=>$v): ?><option value="<?= $k ?>" <?= $status===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button></form></div>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th>الرقم</th><th>العميل</th><th>التاريخ</th><th>الاستحقاق</th><th>الإجمالي</th><th>المدفوع</th><th>المتبقي</th><th>الحالة</th><th></th></tr></thead>
    <tbody><?php if(empty($result['data'])): ?><tr><td colspan="9"><div class="empty-state"><i class="fas fa-file-invoice-dollar"></i><h3>لا توجد فواتير</h3></div></td></tr>
    <?php else: foreach($result['data'] as $i): ?><tr>
        <td><a href="<?= url('invoices','show',['id'=>$i['id']]) ?>" class="text-bold"><?= clean($i['invoice_number']) ?></a></td>
        <td><?= clean($i['client_name']??'-') ?></td><td class="text-sm"><?= formatDate($i['invoice_date']) ?></td><td class="text-sm"><?= formatDate($i['due_date']) ?></td>
        <td class="text-bold"><?= formatMoney($i['total']) ?></td><td class="text-success"><?= formatMoney($i['paid_amount']) ?></td>
        <td class="text-danger text-bold"><?= formatMoney($i['total']-$i['paid_amount']) ?></td><td><?= statusBadge($i['status']) ?></td>
        <td><div class="table-actions"><a href="<?= url('invoices','show',['id'=>$i['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-eye text-gold"></i></a><a href="<?= url('invoices','edit',['id'=>$i['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a></div></td>
    </tr><?php endforeach;endif; ?></tbody></table></div>
</div>
