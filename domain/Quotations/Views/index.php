<div class="page-header"><div><h1 class="page-title">عروض الأسعار</h1><p class="page-subtitle"><?= number_format($result['total']) ?> عرض</p></div><div class="page-actions"><a href="<?= url('quotations', 'create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء عرض سعر</a></div></div>
<div class="card">
    <div class="table-header"><form method="GET" action="<?= url('quotations') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="quotations"><input type="hidden" name="action" value="index"><input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;">
        <select name="status" class="form-control" style="width:150px;"><option value="">كل الحالات</option><?php foreach(['draft'=>'مسودة','sent'=>'مرسل','approved'=>'معتمد','rejected'=>'مرفوض','expired'=>'منتهي'] as $k=>$v): ?><option value="<?= $k ?>" <?= $status===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button></form></div>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th>الرقم</th><th>العميل</th><th>الشركة</th><th>التاريخ</th><th>الصلاحية</th><th>الإجمالي</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
    <tbody><?php if (empty($result['data'])): ?><tr><td colspan="8"><div class="empty-state"><i class="fas fa-file-invoice"></i><h3>لا توجد عروض أسعار</h3></div></td></tr>
    <?php else: foreach ($result['data'] as $q): ?><tr>
        <td><a href="<?= url('quotations','show',['id'=>$q['id']]) ?>" class="text-bold"><?= clean($q['quotation_number']) ?></a></td>
        <td><?= clean($q['client_name'] ?? '-') ?></td><td><?= clean($q['company_name'] ?? '-') ?></td>
        <td class="text-sm"><?= formatDate($q['quotation_date']) ?></td><td class="text-sm"><?= formatDate($q['validity_date']) ?></td>
        <td class="text-bold text-gold"><?= formatMoney($q['total']) ?></td><td><?= statusBadge($q['status']) ?></td>
        <td><div class="table-actions"><a href="<?= url('quotations','show',['id'=>$q['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-eye text-gold"></i></a><a href="<?= url('quotations','edit',['id'=>$q['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a><button onclick="confirmDelete('<?= url('quotations','delete',['id'=>$q['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button></div></td>
    </tr><?php endforeach; endif; ?></tbody></table></div>
</div>
