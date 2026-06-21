<div class="page-header"><div><h1 class="page-title">تعديل الموظف</h1></div><div class="page-actions"><a href="<?= url('employees') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('employees','update') ?>"><?= csrfField() ?><input type="hidden" name="id" value="<?= $user['id'] ?>">
    <div class="form-row"><div class="form-group"><label class="form-label">الاسم الكامل</label><input type="text" name="full_name" class="form-control" required value="<?= clean($user['full_name']) ?>"></div>
    <div class="form-group"><label class="form-label">اسم المستخدم</label><input type="text" class="form-control" value="<?= clean($user['username']) ?>" disabled></div></div>
    <div class="form-row"><div class="form-group"><label class="form-label">كلمة مرور جديدة</label><input type="password" name="password" class="form-control" dir="ltr" placeholder="اتركها فارغة لعدم التغيير"></div>
    <div class="form-group"><label class="form-label">الدور</label><select name="role_id" class="form-control"><?php foreach($roles as $r): ?><option value="<?= $r['id'] ?>" <?= $user['role_id']==$r['id']?'selected':'' ?>><?= clean($r['name']) ?></option><?php endforeach; ?></select></div></div>
    <div class="form-row"><div class="form-group"><label class="form-label">البريد</label><input type="email" name="email" class="form-control" value="<?= clean($user['email']) ?>"></div>
    <div class="form-group"><label class="form-label">الهاتف</label><input type="tel" name="phone" class="form-control" value="<?= clean($user['phone']) ?>"></div></div>
    <div class="form-check mb-2"><input type="checkbox" name="is_active" value="1" <?= $user['is_active']?'checked':'' ?> id="is_active"><label for="is_active">حساب نشط</label></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
</form></div></div>
