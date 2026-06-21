<div class="page-header"><div><h1 class="page-title">المدفوعات</h1><p class="page-subtitle"><?= number_format($result['total']) ?> دفعة</p></div><div class="page-actions"><a href="<?= url('payments','create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> تسجيل دفعة</a></div></div>
<div class="card">
    <div class="table-header"><form method="GET" action="<?= url('payments') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="payments"><input type="hidden" name="action" value="index"><input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;"><button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button></form></div>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th>رقم الدفعة</th><th>العميل</th><th>المبلغ</th><th>التاريخ</th><th>طريقة الدفع</th><th>المرجع</th><th></th></tr></thead>
    <tbody><?php if(empty($result['data'])): ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-money-bill-wave"></i><h3>لا توجد مدفوعات</h3></div></td></tr>
    <?php else: foreach($result['data'] as $p): ?><tr>
        <td class="text-bold"><?= clean($p['payment_number']) ?></td><td><?= clean($p['client_name']??'-') ?></td>
        <td class="text-success text-bold"><?= formatMoney($p['amount']) ?></td><td class="text-sm"><?= formatDate($p['payment_date']) ?></td>
        <td><?php $methods=['bank_transfer'=>'تحويل بنكي','cash'=>'نقدي','check'=>'شيك','online'=>'إلكتروني','other'=>'أخرى'];echo $methods[$p['payment_type']]??$p['payment_type']; ?></td>
        <td><?= clean($p['reference_number']??'-') ?></td>
        <td><div class="table-actions"><a href="<?= url('payments','edit',['id'=>$p['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a><button onclick="confirmDelete('<?= url('payments','delete',['id'=>$p['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button></div></td>
    </tr><?php endforeach;endif; ?></tbody></table></div>
</div>
