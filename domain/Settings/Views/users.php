<div class="page-header"><div><h1 class="page-title">إدارة المستخدمين</h1><p class="page-subtitle"><?= count($users) ?> مستخدم</p></div>
    <div class="page-actions"><a href="<?= url('settings','create_user') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مستخدم</a></div></div>
<div class="card"><div class="table-wrapper"><table class="data-table"><thead><tr><th>المستخدم</th><th>الاسم الكامل</th><th>البريد</th><th>الدور</th><th>الحالة</th><th>آخر دخول</th><th></th></tr></thead>
<tbody><?php foreach($users as $u): ?><tr>
    <td class="text-bold"><?= clean($u['username']) ?></td><td><?= clean($u['full_name']) ?></td><td><?= clean($u['email']??'-') ?></td>
    <td><span class="badge badge-primary"><?= clean($u['role_name']??'-') ?></span></td>
    <td><span class="badge badge-<?= $u['is_active']?'success':'secondary' ?>"><?= $u['is_active']?'نشط':'معطل' ?></span></td>
    <td class="text-sm text-muted"><?= !empty($u['last_login_at'])?formatDateTime($u['last_login_at']):'-' ?></td>
    <td><div class="table-actions">
        <a href="<?= url('settings','toggle_user',['id'=>$u['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="<?= $u['is_active']?'تعطيل':'تنشيط' ?>"><i class="fas fa-<?= $u['is_active']?'ban text-danger':'check text-success' ?>"></i></a>
    </div></td>
</tr><?php endforeach; ?></tbody></table></div></div>
