<div class="page-header"><div><h1 class="page-title">الموظفون</h1><p class="page-subtitle"><?= count($users) ?> موظف</p></div>
    <div class="page-actions"><a href="<?= url('employees','create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة موظف</a></div></div>
<div class="card"><div class="table-wrapper"><table class="data-table"><thead><tr><th>الاسم</th><th>المستخدم</th><th>البريد</th><th>الهاتف</th><th>الدور</th><th>الحالة</th><th></th></tr></thead>
<tbody><?php foreach($users as $u): ?><tr>
    <td class="text-bold"><?= clean($u['full_name']) ?></td><td class="text-sm" dir="ltr"><?= clean($u['username']) ?></td><td class="text-sm"><?= clean($u['email']??'-') ?></td><td class="text-sm"><?= formatPhone($u['phone']??'-') ?></td>
    <td><span class="badge badge-primary"><?= clean($u['role_name']??'-') ?></span></td>
    <td><span class="badge badge-<?= $u['is_active']?'success':'secondary' ?>"><?= $u['is_active']?'نشط':'معطل' ?></span></td>
    <td><div class="table-actions"><a href="<?= url('employees','edit',['id'=>$u['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a></div></td>
</tr><?php endforeach; ?></tbody></table></div></div>
