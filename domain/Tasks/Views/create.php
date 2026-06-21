<div class="page-header"><div><h1 class="page-title">إضافة مهمة</h1></div><div class="page-actions"><a href="<?= url('tasks') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('tasks','store') ?>"><?= csrfField() ?>
    <div class="form-group"><label class="form-label">العنوان <span class="required">*</span></label><input type="text" name="title" class="form-control" required></div>
    <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="3"></textarea></div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">العميل</label><select name="client_id" class="form-control"><option value="">اختياري</option><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>"><?= clean($c['name']) ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label class="form-label">المسؤول</label><select name="assigned_to" class="form-control"><option value="">اختر</option><?php foreach($employees as $e): ?><option value="<?= $e['id'] ?>"><?= clean($e['full_name']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">الأولوية</label><select name="priority" class="form-control"><option value="medium">متوسطة</option><option value="high">عالية</option><option value="low">منخفضة</option></select></div>
        <div class="form-group"><label class="form-label">الموعد النهائي</label><input type="date" name="due_date" class="form-control"></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
</form></div></div>
