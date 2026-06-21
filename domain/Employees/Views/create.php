<div class="page-header"><div><h1 class="page-title">إضافة موظف</h1></div><div class="page-actions"><a href="<?= url('employees') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('employees','store') ?>"><?= csrfField() ?>
    <div class="form-row"><div class="form-group"><label class="form-label">الاسم الكامل <span class="required">*</span></label><input type="text" name="full_name" class="form-control" required></div>
    <div class="form-group"><label class="form-label">اسم المستخدم <span class="required">*</span></label><input type="text" name="username" class="form-control" required dir="ltr"></div></div>
    <div class="form-row"><div class="form-group"><label class="form-label">كلمة المرور <span class="required">*</span></label><input type="password" name="password" class="form-control" required dir="ltr"></div>
    <div class="form-group"><label class="form-label">الدور</label><select name="role_id" class="form-control"><?php foreach($roles as $r): ?><option value="<?= $r['id'] ?>"><?= clean($r['name']) ?></option><?php endforeach; ?></select></div></div>
    <div class="form-row"><div class="form-group"><label class="form-label">البريد</label><input type="email" name="email" class="form-control"></div>
    <div class="form-group"><label class="form-label">الهاتف</label><input type="tel" name="phone" class="form-control"></div></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> إضافة</button>
</form></div></div>
