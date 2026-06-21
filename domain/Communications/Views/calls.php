<div class="page-header"><div><h1 class="page-title">سجل المكالمات</h1><p class="page-subtitle"><?= number_format($result['total']) ?> مكالمة</p></div>
    <div class="page-actions"><a href="<?= url('communications','log_call') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> تسجيل مكالمة</a></div></div>
<div class="card">
    <div class="table-header"><form method="GET" action="<?= url('communications','calls') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="communications"><input type="hidden" name="action" value="calls"><input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;"><button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button></form></div>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th>العميل</th><th>النوع</th><th>التاريخ</th><th>المدة</th><th>النتيجة</th><th>الموظف</th><th>الملاحظات</th></tr></thead>
    <tbody><?php if(empty($result['data'])): ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-phone"></i><h3>لا توجد مكالمات</h3></div></td></tr>
    <?php else: foreach($result['data'] as $c): ?><tr>
        <td><a href="<?= url('clients','card',['id'=>$c['client_id']]) ?>"><?= clean($c['client_name']??'-') ?></a></td>
        <td><span class="badge badge-<?= $c['call_type']==='incoming'?'info':'success' ?>"><?= $c['call_type']==='incoming'?'وارد':'صادر' ?></span></td>
        <td class="text-sm"><?= formatDateTime($c['call_date']) ?></td><td><?= $c['duration'] ?> دقيقة</td>
        <td><?= statusBadge($c['result']??'') ?></td><td class="text-sm"><?= clean($c['user_name']??'-') ?></td>
        <td class="text-sm text-muted"><?= clean(truncate($c['notes']??'',50)) ?></td>
    </tr><?php endforeach;endif; ?></tbody></table></div>
</div>
