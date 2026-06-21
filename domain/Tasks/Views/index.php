<div class="page-header"><div><h1 class="page-title">المهام</h1><p class="page-subtitle"><?= number_format($result['total']) ?> مهمة</p></div>
    <div class="page-actions"><a href="<?= url('tasks','create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مهمة</a></div></div>
<div class="card">
    <div class="table-header"><form method="GET" action="<?= url('tasks') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="tasks"><input type="hidden" name="action" value="index">
        <select name="status" class="form-control" style="width:150px;" onchange="this.form.submit()"><option value="">كل الحالات</option><?php foreach(['pending'=>'معلقة','in_progress'=>'قيد التنفيذ','completed'=>'مكتملة','cancelled'=>'ملغية'] as $k=>$v): ?><option value="<?= $k ?>" <?= $status===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></form></div>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th>المهمة</th><th>العميل</th><th>المسؤول</th><th>الأولوية</th><th>الموعد</th><th>الحالة</th><th></th></tr></thead>
    <tbody><?php if(empty($result['data'])): ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-tasks"></i><h3>لا توجد مهام</h3></div></td></tr>
    <?php else: foreach($result['data'] as $t):
        $priorityColors=['high'=>'danger','medium'=>'warning','low'=>'info'];
        $isOverdue=$t['due_date']&&strtotime($t['due_date'])<time()&&$t['status']!=='completed';
    ?><tr class="<?= $isOverdue?'row-warning':'' ?>">
        <td class="text-bold"><?= clean($t['title']) ?><?php if($t['description']): ?><br><small class="text-muted"><?= clean(truncate($t['description'],50)) ?></small><?php endif; ?></td>
        <td><?= clean($t['client_name']??'-') ?></td><td class="text-sm"><?= clean($t['assigned_name']??'-') ?></td>
        <td><span class="badge badge-<?= $priorityColors[$t['priority']]??'secondary' ?>"><?php $p=['high'=>'عالية','medium'=>'متوسطة','low'=>'منخفضة'];echo $p[$t['priority']]??$t['priority']; ?></span></td>
        <td class="text-sm <?= $isOverdue?'text-danger text-bold':'' ?>"><?= $t['due_date']?formatDate($t['due_date']):'-' ?></td>
        <td><?= statusBadge($t['status']) ?></td>
        <td><div class="table-actions"><a href="<?= url('tasks','edit',['id'=>$t['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a><button onclick="confirmDelete('<?= url('tasks','delete',['id'=>$t['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button></div></td>
    </tr><?php endforeach;endif; ?></tbody></table></div>
</div>
